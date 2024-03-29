<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use TwoMartens\Bundle\CoreBundle\Form\Type\GroupType;
use TwoMartens\Bundle\CoreBundle\Group\GroupServiceInterface;
use TwoMartens\Bundle\CoreBundle\Model\Breadcrumb;
use TwoMartens\Bundle\CoreBundle\Model\Group;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;

/**
 * Manages the routes for the group system.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ACPGroupController extends AbstractACPController
{
    /**
     * saves success state
     * @var boolean
     */
    private $success;

    /**
     * saves error state
     * @var boolean
     */
    private $error;

    /**
     * saves error message
     * @var string
     */
    private $errorMessage;

    /**
     * the current action
     * @var string
     */
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->success = false;
        $this->error = false;
        $this->errorMessage = '';
        $this->action = '';
    }

    /**
     * Shows a group list.
     *
     * @return Response
     */
    public function listAction()
    {
        $this->action = 'list';

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:Group');
        $groups = $repository->findAll();

        $this->assignVariables();
        $this->templateVariables['groups'] = $groups;
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.group.list', [], 'TwoMartensCoreBundle');

        return $this->render(
            'TwoMartensCoreBundle:ACPGroup:list.html.twig',
            $this->templateVariables
        );
    }

    /**
     * Shows the group add form.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $this->action = 'add';

        $this->denyAccessUnlessGranted('ROLE_ACP_TWOMARTENS.CORE_GROUP_ADD');

        /** @var GroupServiceInterface $groupService */
        $groupService = $this->get('twomartens.core.group');
        // default is no real group but contains the default option values
        $options = $groupService->getOptionsFor('DEFAULT');
        $categories = $options->getCategories();
        $sortedCategories = [];

        foreach ($categories as $category) {
            $sortedCategories[$category->getName()] = $category;
        }

        $group = new Group(
            null, // no id known yet
            '', // no role name yet
            '', // no public name yet
            false, // all new groups created through ACP are non-essential
            true, // all new groups created through ACP can be empty
            [], // no known roles yet
            $sortedCategories['frontend'],
            $sortedCategories['mod'],
            $sortedCategories['acp']
        );

        $form = $this->createForm(
            GroupType::class,
            $group,
            [
                'validation_groups' => ['Registration', 'NewGroup'],
                'isEditForm' => false
            ]
        );

        $form->handleRequest($request);
        $this->assignVariables();

        if ($form->isValid()) {
            /** @var OptionCategory[] $categories */
            $categories = [
                $group->getACPCategory(),
                $group->getFrontendModCategory(),
                $group->getFrontendUserCategory()
            ];
            $roles = [];
            foreach ($categories as $category) {
                $newRoles = $this->updateOptions($category, $form);
                $roles = array_merge($roles, $newRoles);
            }
            // add group role
            $roles[] = 'ROLE_' . $group->getRoleName();
            $group->setRoles($roles);

            /** @var ObjectManager $objectManager */
            $objectManager = $this->get('twomartens.core.db_manager');
            $objectManager->persist($group);
            $groupService->commitChanges();
            $objectManager->flush();

            return $this->listAction();
        }

        $this->templateVariables['form'] = $form->createView();
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.group.add', [], 'TwoMartensCoreBundle');

        return $this->render(
            'TwoMartensCoreBundle:ACPGroup:add.html.twig',
            $this->templateVariables
        );
    }

    /**
     * Shows the group edit form.
     *
     * @param Request $request
     * @param string  $rolename
     *
     * @return Response
     */
    public function editAction(Request $request, $rolename)
    {
        $this->action = 'edit';

        $this->denyAccessUnlessGranted('ROLE_ACP_TWOMARTENS.CORE_GROUP_EDIT');

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:Group');
        /** @var Group $group */
        $group = $repository->findOneBy(['roleName' => $rolename]);
        $form = $this->createForm(
            GroupType::class,
            $group
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var OptionCategory[] $categories */
            $categories = [
                $group->getACPCategory(),
                $group->getFrontendModCategory(),
                $group->getFrontendUserCategory()
            ];
            $roles = [];
            foreach ($categories as $category) {
                $newRoles = $this->updateOptions($category, $form);
                $roles = array_merge($roles, $newRoles);
            }
            // add group role
            $roles[] = 'ROLE_' . $group->getRoleName();
            $group->setRoles($roles);

            $objectManager->flush();
            /** @var GroupServiceInterface $groupService */
            $groupService = $this->get('twomartens.core.group');
            $groupService->commitChanges();

            // reauthenticate token to update roles
            /** @var TokenInterface $token */
            $token = $this->container->get('security.token_storage')->getToken();
            $token->setAuthenticated(false);
            $this->success = true;
        }

        $this->assignVariables();
        $this->templateVariables['form'] = $form->createView();
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.group.edit', [], 'TwoMartensCoreBundle');

        return $this->render(
            'TwoMartensCoreBundle:ACPGroup:edit.html.twig',
            $this->templateVariables
        );
    }

    /**
     * Deletes the group identified by the role name.
     *
     * @param string $rolename
     *
     * @return Response
     */
    public function deleteAction($rolename)
    {
        $this->denyAccessUnlessGranted('ROLE_ACP_TWOMARTENS.CORE_GROUP_DELETE');

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:Group');
        /** @var Group $group */
        $group = $repository->findOneBy(['roleName' => $rolename]);

        // perform validation - to be sure
        if ($group->isEssential()) {
            $this->error = true;
            $this->errorMessage = $this->get('translator')->trans(
                'acp.group.delete.error.essential',
                [
                    'name' => $group->getPublicName()
                ],
                'TwoMartensCoreBundle'
            );
        }

        if (!$this->error) {
            /** @var GroupServiceInterface $groupService */
            $groupService = $this->get('twomartens.core.group');
            $objectManager->remove($group);
            $groupService->commitChanges();
            $objectManager->flush();

            // reauthenticate token to update roles
            /** @var TokenInterface $token */
            $token = $this->container->get('security.token_storage')->getToken();
            $token->setAuthenticated(false);
            $this->success = true;
        }

        return $this->listAction();
    }

    /**
     * {@inheritdoc}
     */
    protected function setBreadcrumbs()
    {
        $userBreadcrumb = new Breadcrumb(
            'acp.user',
            $this->get('translator')->trans('acp.breadcrumb.user', [], 'TwoMartensCoreBundle')
        );
        $activeBreadcrumb = new Breadcrumb(
            'acp.user.group.'.$this->action,
            $this->get('translator')->trans(
                'acp.breadcrumb.user.group.'.$this->action,
                [],
                'TwoMartensCoreBundle'
            )
        );
        $activeBreadcrumb->activate();
        $this->breadcrumbs = [
            $userBreadcrumb,
            $activeBreadcrumb
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function assignVariables()
    {
        $this->templateVariables = [
            'area' => [
                'showBreadcrumbs' => true
            ],
            'siteTitle' => $this->get('translator')->trans(
                'acp.siteTitle',
                ['globalTitle' => 'CoreBundle Test'],
                'TwoMartensCoreBundle'
            ),
            'navigation' => [
                'active' => 'user'
            ],
            'success' => $this->success,
            'error' => $this->error,
            'errorMessage' => $this->errorMessage
        ];
        parent::assignVariables();
    }

    /**
     * Updates the options of the given category and returns the roles.
     *
     * @param OptionCategory $category
     * @param Form           $form
     *
     * @return string[]
     */
    private function updateOptions(OptionCategory $category, Form $form)
    {
        $categories = $category->getCategories();
        $superCategoryName = $category->getName();
        $roles = [];
        foreach ($categories as $category) {
            $categoryName = $category->getName();
            $options = $category->getOptions();

            foreach ($options as $option) {
                $optionName = $option->getName();
                $optionType = $option->getType();
                $fieldName = $superCategoryName . '_' .
                    str_replace('.', '_', $categoryName) .
                    '_' . $optionName;
                if (!$form->has($fieldName)) {
                    // should be the case only for checkbox
                    $fieldValue = false;
                } else {
                    $fieldValue = $form->get($fieldName);
                }
                settype($fieldValue, $optionType);
                if ($optionType == 'boolean' && $fieldValue) {
                    $roles[] = 'ROLE_' .
                        strtoupper($superCategoryName) . '_' .
                        strtoupper($categoryName) . '_' .
                        strtoupper($optionName);
                }
                $option->setValue($fieldValue);
            }
        }

        return $roles;
    }
}
