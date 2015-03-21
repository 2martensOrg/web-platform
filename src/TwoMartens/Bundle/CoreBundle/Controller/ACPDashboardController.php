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
        return $this->render(
            'TwoMartensCoreBundle:ACPDashboard:index.html.twig',
            array(
                'area' => array(
                    'showBreadcrumbs' => false,
                    'title' => 'Dashboard'
                ),
                'siteTitle' => 'CoreBundle Test Site',
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
