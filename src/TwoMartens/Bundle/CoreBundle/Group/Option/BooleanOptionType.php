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
 * Implements the OptionTypeInterface for the type boolean.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class BooleanOptionType implements OptionTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBestValue($optionA, $optionB)
    {
        if ($optionA === null) {
            return $optionB;
        }
        if ($optionB === null) {
            return $optionA;
        }

        $valueA = $optionA->getValue();
        $valueB = $optionB->getValue();
        $result = $valueA || $valueB;
        // prevents reference issues
        $resultObj = new Option(
            $optionA->getId(),
            $optionA->getName(),
            $optionA->getType(),
            $result
        );

        return $resultObj;
    }
}
