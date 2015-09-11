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

/**
 * Manages the routes for the group system.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ACPGroupController extends AbstractACPController
{
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
            'success' => false
        ];
        parent::assignVariables();
    }
}
