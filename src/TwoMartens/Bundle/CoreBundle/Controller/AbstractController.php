<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Empowers ROLE_SUPER_ADMIN to do everything.
 *
 * Extends the denyAccessUnlessGranted method to check
 * if either provided role or ROLE_SUPER_ADMIN is granted.
 * If both are not granted it throws an exception.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
abstract class AbstractController extends Controller
{
    /**
     * {@inheritdoc}
     */
    protected function denyAccessUnlessGranted($attributes, $object = null, $message = 'Access Denied.')
    {
        if (!$this->isGranted($attributes, $object) && !$this->isGranted('ROLE_SUPER_ADMIN', $object)) {
            throw $this->createAccessDeniedException($message);
        }
    }
}
