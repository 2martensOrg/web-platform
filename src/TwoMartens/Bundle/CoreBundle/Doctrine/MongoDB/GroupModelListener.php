<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Doctrine\MongoDB;

use Doctrine\ODM\MongoDB\Events;
use TwoMartens\Bundle\CoreBundle\Doctrine\AbstractGroupModelListener;

/**
 * Database specific listener to register the events.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class GroupModelListener extends AbstractGroupModelListener
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
            Events::prePersist,
            Events::preRemove
        ];
    }
}
