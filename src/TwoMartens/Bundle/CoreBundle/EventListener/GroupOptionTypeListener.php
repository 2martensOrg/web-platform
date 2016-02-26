<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\EventListener;

use TwoMartens\Bundle\CoreBundle\Event\GroupOptionTypeEvent;
use TwoMartens\Bundle\CoreBundle\Group\Option\BooleanOptionType;

/**
 * Registers option types for the group options of this bundle.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class GroupOptionTypeListener
{
    /**
     * Called during initialization of the Group service.
     *
     * @param GroupOptionTypeEvent $event
     */
    public function onGroupServiceInit(GroupOptionTypeEvent $event)
    {
        $optionTypes = [
            'acp' => [
                'twomartens.core' => [
                    'access' => new BooleanOptionType(),
                    'group_add' => new BooleanOptionType(),
                    'group_list' => new BooleanOptionType(),
                    'group_edit' => new BooleanOptionType(),
                    'group_delete' => new BooleanOptionType(),
                    'user_add' => new BooleanOptionType(),
                    'user_list' => new BooleanOptionType(),
                    'user_edit' => new BooleanOptionType(),
                    'user_delete' => new BooleanOptionType()
                ]
            ]
        ];
        $event->addOptions($optionTypes);
    }
}
