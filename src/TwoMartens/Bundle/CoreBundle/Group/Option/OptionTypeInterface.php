<?php
/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Group\Option;

use TwoMartens\Bundle\CoreBundle\Model\Option;

/**
 * Interface for option types.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
interface OptionTypeInterface
{
    /**
     * Returns the best value of the two.
     *
     * @param Option|null $valueA
     * @param Option|null $valueB
     *
     * @api
     *
     * @return Option
     */
    public function getBestValue($valueA, $valueB);
}
