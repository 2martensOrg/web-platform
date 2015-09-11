<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use TwoMartens\Bundle\CoreBundle\Model\Breadcrumb;
use TwoMartens\Bundle\CoreBundle\Model\Group;

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

    public function __construct()
    {
        parent::__construct();
        $this->success = false;
        $this->error = false;
        $this->errorMessage = '';
    }

    /**
     * Shows a group list.
     *
     * @return Response
     */
    public function listAction()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:Group');
        $groups = $repository->findAll();

        $this->assignVariables();
        $this->templateVariables['groups'] = $groups;

        return $this->render(
            'TwoMartensCoreBundle:ACPGroup:list.html.twig',
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
        // TODO add role validation

        if (!$this->error) {
            $objectManager->remove($group);
            $objectManager->flush();
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
        $groupListBreadcrumb = new Breadcrumb(
            'acp.user.group.list',
            $this->get('translator')->trans('acp.breadcrumb.user.group.list', [], 'TwoMartensCoreBundle')
        );
        $groupListBreadcrumb->activate();
        $this->breadcrumbs = [
            $userBreadcrumb,
            $groupListBreadcrumb
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function assignVariables()
    {
        $this->templateVariables = [
            'area' => [
                'showBreadcrumbs' => true,
                'title' => $this->get('translator')->trans('acp.group.list', [], 'TwoMartensCoreBundle')
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
}
