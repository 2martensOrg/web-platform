<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles security actions for the ACP.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ACPSecurityController extends Controller
{
    /**
     * Displays the login.
     *
     * @return Response
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'TwoMartensCoreBundle:ACPSecurity:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'siteTitle'     => $this->get('translator')->trans('acp.login.title', array(), 'TwoMartensCoreBundle'),
                'login'         => [
                    'button' => $this->get('translator')->trans('acp.login.button', array(), 'TwoMartensCoreBundle'),
                    'title'  => $this->get('translator')->trans('acp.login.title', array(), 'TwoMartensCoreBundle')
                ],
            )
        );
    }

    /**
     * This action serves as a required placeholder.
     *
     * The real action happens in the Security system which handles
     * this action.
     */
    public function loginCheckAction()
    {
    }
}
