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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Manages the routes for the ACP dashboard.
 *
 * @author Jim Martens <github@2martens.de>
 */
class ACPDashboardController extends Controller
{
    /**
     * Shows the dashboard/landing site of the ACP.
     */
    public function indexAction()
    {
        // TODO replace dependencies with proper system
        return $this->render(
            'TwoMartensCoreBundle:ACPDashboard:index.html.twig',
            array(
                'area' => array(
                    'showBreadcrumbs' => false, // the proper breadcrumbs system has to be developed first
                    'title' => $this->get('translator')->trans('acp.dashboard', array(), 'TwoMartensCoreBundle')
                ),
                'siteTitle' => $this->get('translator')->trans('acp.siteTitle', array('globalTitle' => 'CoreBundle Test'), 'TwoMartensCoreBundle'),
                'navigation' => array(
                    'active' => 'home'
                ),
                'dependencies' => array(
                    'bootstrap' => array(
                        'version' => '3.3.2'
                    ),
                    'jquery' => array(
                        'version' => '2.1.1'
                    ),
                    'fontawesome' => array(
                        'version' => '4.3.0'
                    )
                )
            )
        );
    }
}
