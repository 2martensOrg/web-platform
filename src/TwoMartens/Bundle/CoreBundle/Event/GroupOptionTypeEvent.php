<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use TwoMartens\Bundle\CoreBundle\Group\Option\OptionTypeInterface;

/**
 * Event object used in the GroupService.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class GroupOptionTypeEvent extends Event
{
    /**
     * @var OptionTypeInterface[][]
     */
    private $optionTypes;

    /**
     * Initializes the event.
     */
    public function __construct()
    {
        $this->optionTypes = [];
    }

    /**
     * Updates the optionTypes with given options.
     *
     * @param OptionTypeInterface[][] $options
     */
    public function addOptions(array $options)
    {
        $this->optionTypes = array_merge_recursive($this->optionTypes, $options);
    }

    /**
     * Returns the option types.
     *
     * @return OptionTypeInterface[][]
     */
    public function getOptionTypes()
    {
        return $this->optionTypes;
    }
}
