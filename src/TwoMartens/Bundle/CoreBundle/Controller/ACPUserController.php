<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use TwoMartens\Bundle\CoreBundle\Form\Type\UserType;
use TwoMartens\Bundle\CoreBundle\Model\Breadcrumb;
use TwoMartens\Bundle\CoreBundle\Model\Group;
use TwoMartens\Bundle\CoreBundle\Model\User;

/**
 * Manages the routes for the user system.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ACPUserController extends AbstractACPController
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
     * Shows a user list.
     *
     * @return Response
     */
    public function listAction()
    {
        $this->action = 'list';

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:User');
        $users = $repository->findAll();

        $this->assignVariables();
        $this->templateVariables['users'] = $users;
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.user.list', [], 'TwoMartensCoreBundle');

        return $this->render(
            'TwoMartensCoreBundle:ACPUser:list.html.twig',
            $this->templateVariables
        );
    }

    /**
     * Shows the user add form.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $this->action = 'add';

        $this->denyAccessUnlessGranted('ROLE_ACP_TWOMARTENS.CORE_USER_ADD');

        /** @var UserManager $userManager */
        $userManager = $this->get('fos_user.user_manager');
        /** @var User $user */
        $user = $userManager->createUser();

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repositoryGroup = $objectManager->getRepository('TwoMartensCoreBundle:Group');
        /** @var Collection $groups */
        $groups = new ArrayCollection($repositoryGroup->findAll());
        $isAddMode = true;

        $form = $this->createForm(
            new UserType($groups, $isAddMode),
            $user
        );

        $form->handleRequest($request);
        $this->assignVariables();

        if ($form->isValid()) {
            $this->updateGroupAssignment($form, $user);

            // updates the canonical fields, the password and flushes the changes
            $userManager->updateUser($user);

            return $this->listAction();
        }

        $this->templateVariables['form'] = $form->createView();
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.user.add', [], 'TwoMartensCoreBundle');

        return $this->render(
            'TwoMartensCoreBundle:ACPUser:add.html.twig',
            $this->templateVariables
        );
    }

    /**
     * Shows the user edit form.
     *
     * @param Request $request
     * @param string  $username
     *
     * @return Response
     */
    public function editAction(Request $request, $username)
    {
        $this->action = 'edit';

        $this->denyAccessUnlessGranted('ROLE_ACP_TWOMARTENS.CORE_USER_EDIT');

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repositoryUser = $objectManager->getRepository('TwoMartensCoreBundle:User');
        $repositoryGroup = $objectManager->getRepository('TwoMartensCoreBundle:Group');
        /** @var User $user */
        $user = $repositoryUser->findOneBy(['usernameCanonical' => $username]);
        /** @var Collection $groups */
        $groups = new ArrayCollection($repositoryGroup->findAll());
        $isAddMode = false;
        $form = $this->createForm(
            new UserType($groups, $isAddMode),
            $user
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->updateGroupAssignment($form, $user);

            // updates the canonical fields, the password and flushes the changes
            /** @var UserManager $userManager */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            // reauthenticate token to update roles
            /** @var TokenInterface $token */
            $token = $this->container->get('security.token_storage')->getToken();
            $token->setAuthenticated(false);
            $this->success = true;
        }

        $this->assignVariables();
        $this->templateVariables['form'] = $form->createView();
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.user.edit', [], 'TwoMartensCoreBundle');

        return $this->render(
            'TwoMartensCoreBundle:ACPUser:edit.html.twig',
            $this->templateVariables
        );
    }

    /**
     * Deletes the user identified by the username.
     *
     * @param string $username
     *
     * @return Response
     */
    public function deleteAction($username)
    {
        $this->denyAccessUnlessGranted('ROLE_ACP_TWOMARTENS.CORE_USER_DELETE');

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:User');
        /** @var User $user */
        $user = $repository->findOneBy(['usernameCanonical' => $username]);
        /** @var User $loggedInUser */
        $loggedInUser = $this->getUser();

        if ($user->getUsernameCanonical() == $loggedInUser->getUsernameCanonical()) {
            throw $this->createAccessDeniedException('You cannot delete yourself!');
        }

        if (!$this->error) {
            /** @var UserManager $userManager */
            $userManager = $this->get('for_user.user_manager');
            $userManager->deleteUser($user);
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
            'acp.user.user.'.$this->action,
            $this->get('translator')->trans(
                'acp.breadcrumb.user.user.'.$this->action,
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
     * Updates the group assignment of given user.
     *
     * @param Form $form
     * @param User $user
     */
    private function updateGroupAssignment(Form $form, User $user)
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:Group');
        /** @var Collection $groups */
        $groups = new ArrayCollection($repository->findAll());
        $submittedGroups = $form->get('groups')->getData();
        foreach ($groups as $group) {
            /** @var Group $group */
            if (in_array($group->getRoleName(), $submittedGroups)) {
                $user->addGroup($group);
                $group->addUser($user);
            } else {
                // don't remove user from group if he is last user and group
                // must not be empty
                if (!$group->canBeEmpty() && $group->getUsers()->count() <= 1) {
                    continue;
                }
                $user->removeGroup($group);
                $group->removeUser($user);
            }
        }
    }
}
