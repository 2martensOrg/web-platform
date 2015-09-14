<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\EventListener;

/**
 * Abstract class for group option listeners.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
abstract class AbstractGroupOptionListener extends AbstractOptionListener
{
    /**
     * {@inheritdoc}
     */
    protected function getLabelPrefix()
    {
        return 'acp.group.options';
    }
}
