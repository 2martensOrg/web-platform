<?php
/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Package;

use TwoMartens\Bundle\CoreBundle\Model\Package;

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
     * The package object is modified in the process
     * and the name, description, author and website
     * are filled if such data is available.
     *
     * @param Package $package
     *
     * @api
     *
     * @return void
     */
    public function installPackage(Package $package);
}
