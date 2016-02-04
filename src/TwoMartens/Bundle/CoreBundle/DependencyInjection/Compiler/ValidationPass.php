<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers the additional validators according to the storage.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class ValidationPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('twomartens.core.storage')) {
            return;
        }

        $storage = $container->getParameter('twomartens.core.storage');

        if ('custom' === $storage) {
            return;
        }

        $validationFile = __DIR__ . '/../../Resources/config/storage-validation/' . $storage . '.xml';

        if ($container->hasDefinition('validator.builder')) {
            // Symfony 2.5+
            $container->getDefinition('validator.builder')
                ->addMethodCall('addXmlMapping', array($validationFile));

            return;
        }
    }
}
