<?php

/*
 * This file is part of the 2martens Web Platform.
 *
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Manages the routes for the ACP dashboard.
 *
 * @author Jim Martens <github@2martens.de>
 */
class ACPDashboardController extends AbstractACPController
{
    /**
     * Shows the dashboard/landing site of the ACP.
     *
     * @return Response
     */
    public function indexAction()
    {
        $this->assignVariables();

        return $this->render(
            'TwoMartensCoreBundle:ACPDashboard:index.html.twig',
            $this->templateVariables
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function setBreadcrumbs()
    {
        // no breadcrumbs needed on ACP Dashboard
    }

    /**
     * {@inheritdoc}
     */
    protected function assignVariables()
    {
        $this->templateVariables = array(
            'area' => array(
                'showBreadcrumbs' => false,
                'title' => $this->get('translator')->trans('acp.dashboard', array(), 'TwoMartensCoreBundle')
            ),
            'siteTitle' => $this->get('translator')->trans(
                'acp.siteTitle',
                array('globalTitle' => 'CoreBundle Test'),
                'TwoMartensCoreBundle'
            ),
            'navigation' => array(
                'active' => 'home'
            ),
        );
        parent::assignVariables();
    }
}
