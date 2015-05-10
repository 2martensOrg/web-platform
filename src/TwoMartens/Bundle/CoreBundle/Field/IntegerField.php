<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Field;

/**
 * Represents an integer field.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class IntegerField extends AbstractField
{
    /**
     * Initializes the IntegerField.
     *
     * @param int $value
     */
    public function __construct($value)
    {
        parent::__construct($value, 'int');
    }

    /**
     * Returns the value of this field.
     *
     * @return int
     */
    public function getValue()
    {
        return parent::getValue();
    }

    /**
     * Sets the value of the field.
     *
     * @param int $value
     *
     * @throws \InvalidArgumentException if the value is not an integer
     */
    public function setValue($value)
    {
        parent::setValue($value);
    }
}
