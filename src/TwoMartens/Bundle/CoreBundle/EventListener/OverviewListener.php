<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\EventListener;

use TwoMartens\Bundle\CoreBundle\Event\OverviewEvent;

/**
 * The listener adds the 2WP Core Bundle links to the overview pages.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OverviewListener
{
    /**
     * Adds entries to the system overview.
     *
     * @param OverviewEvent $event
     */
    public function onSystem(OverviewEvent $event)
    {
        $event->addCategory('acp.system.configuration', 'TwoMartensCoreBundle');
        $event->addEntry(
            'acp.system.configuration',
            'acp.options',
            'TwoMartensCoreBundle',
            'acp.system.options',
            ''
        );
    }

    /**
     * Adds entries to the user overview.
     *
     * @param OverviewEvent $event
     */
    public function onUser(OverviewEvent $event)
    {
        $event->addCategory('acp.user.group', 'TwoMartensCoreBundle');
        $event->addEntry(
            'acp.user.group',
            'acp.group.add',
            'TwoMartensCoreBundle',
            'acp.user.group.add',
            'ROLE_ACP_TWOMARTENS.CORE_GROUP_ADD'
        );
        $event->addEntry(
            'acp.user.group',
            'acp.group.list',
            'TwoMartensCoreBundle',
            'acp.user.group.list',
            'ROLE_ACP_TWOMARTENS.CORE_GROUP_LIST'
        );
    }
}
