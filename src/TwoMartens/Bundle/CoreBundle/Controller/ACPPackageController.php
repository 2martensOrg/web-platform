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
 * Manages the routes for the package system.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class ACPPackageController extends AbstractACPController
{
    /**
     * the current action.
     *
     * @var string
     */
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->action = '';
    }

    /**
     * Shows the package list.
     *
     * @return Response
     */
    public function listAction()
    {
        $this->action = 'list';

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:Package');
        $packages = $repository->findAll();

        $this->assignVariables();
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.package.list', [], 'TwoMartensCoreBundle');
        $this->templateVariables['packages'] = $packages;

        return $this->render(
            'TwoMartensCoreBundle:ACPPackage:list.html.twig',
            $this->templateVariables
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function setBreadcrumbs()
    {
        $systemBreadcrumb = new Breadcrumb(
            'acp.system',
            $this->get('translator')->trans('acp.breadcrumb.system', [], 'TwoMartensCoreBundle')
        );
        $active = new Breadcrumb(
            'acp.system.package.'.$this->action,
            $this->get('translator')->trans('acp.breadcrumb.package.'.$this->action, [], 'TwoMartensCoreBundle')
        );
        $active->activate();
        $this->breadcrumbs = [
            $systemBreadcrumb,
            $active,
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
                'active' => 'system',
            ],
            'success' => false,
        ];
        parent::assignVariables();
    }
}
