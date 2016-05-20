<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Package;

use Symfony\Component\Process\ProcessBuilder;
use TwoMartens\Bundle\CoreBundle\Model\Package;

/**
 * Implementation for the PackageServiceInterface.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class PackageService implements PackageServiceInterface
{
    /**
     * the process builder
     *
     * @var ProcessBuilder
     */
    private $processBuilder;

    /**
     * the vendor directory
     *
     * @var string
     */
    private $vendorDirectory;

    /**
     * Initializes this service.
     *
     * @param ProcessBuilder $processBuilder
     * @param string         $directory      The directory of the composer.json
     */
    public function __construct(ProcessBuilder $processBuilder, $directory)
    {
        $this->processBuilder = $processBuilder;
        $this->processBuilder->setPrefix('composer');
        $this->processBuilder->setWorkingDirectory(realpath($directory));
        $this->processBuilder->setTimeout(null);
        $this->vendorDirectory = realpath($directory).'/vendor/';
    }

    /**
     * {@inheritdoc}
     */
    public function installPackage(Package $package)
    {
        $this->processBuilder->setArguments([
            'require',
            '--prefer-dist',
            $package->getComposerName().':'.$package->getVersion()
        ]);

        $process = $this->processBuilder->getProcess();
        $process->mustRun();

        $package->setName($package->getComposerName());
        $composerJSON = new ComposerJSON($this->vendorDirectory.$package->getComposerName().'/');
        $package->setAuthor($composerJSON->getPrimaryAuthor());
        $package->setDescription($composerJSON->getDescription());
        $package->setWebsite($composerJSON->getWebsite());
    }
}
