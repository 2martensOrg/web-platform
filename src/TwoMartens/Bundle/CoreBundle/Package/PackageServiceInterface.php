<?php
/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Package;

/**
 * Defines the API of the Package Service.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
interface PackageServiceInterface
{
    /**
     * Installs a package by using Composer.
     *
     * @param string $composerName
     * @param string $versionCondition
     *
     * @api
     *
     * @return void
     */
    public function installPackage($composerName, $versionCondition);
}
