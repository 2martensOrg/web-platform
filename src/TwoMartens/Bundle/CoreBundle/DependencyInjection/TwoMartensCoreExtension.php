<?php
/*
 * This file is part of the 2martens Web Platform.
 *
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Container extension for TwoMartensCoreBundle.
 *
 * @author Jim Martens <github@2martens.de>
 */
class TwoMartensCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');

        // TODO replace with semantic configuration
        $config = [
            'db_driver' => 'mongodb'
        ];

        if ('custom' !== $config['db_driver']) {
            $loader->load(sprintf('%s.yml', $config['db_driver']));
            $container->setParameter('twomartens.core.backend_type_' . $config['db_driver'], true);
        }

        switch ($config['db_driver']) {
            case 'orm':
                $container->getDefinition('twomartens.core.group_listener')->addTag('doctrine.event_subscriber', [
                    'priority' => 10
                ]);
                break;

            case 'mongodb':
                $container->getDefinition('twomartens.core.group_listener')->addTag('doctrine_mongodb.odm.event_subscriber', [
                    'priority' => 10
                ]);
                break;

            case 'couchdb':
                $container->getDefinition('twomartens.core.group_listener')->addTag('doctrine_couchdb.event_subscriber', [
                    'priority' => 10
                ]);
                break;

            case 'propel':
                break;

            default:
                break;
        }
    }
}
