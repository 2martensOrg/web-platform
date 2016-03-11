<?php
/*
 * This file is part of the 2martens Web Platform.
 *
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Twig;

/**
 * Extends Twig with 2martens Web Platform needs.
 *
 * @author Jim Martens <github@2martens.de>
 */
class TwoMartensCoreBundleExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'twomartens_core_extension';
    }

    /**
     * {@inheritdoc}
     *
     * @return array Global variables needed for 2WP.
     */
    public function getGlobals()
    {
        return array(
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
        );
    }
}
