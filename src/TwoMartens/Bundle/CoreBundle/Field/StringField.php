<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Field;

/**
 * Represents an string field.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class StringField extends AbstractField
{
    /**
     * Initializes the StringField.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        parent::__construct($value, 'string');
    }

    /**
     * Returns the value of this field.
     *
     * @return string
     */
    public function getValue()
    {
        return parent::getValue();
    }

    /**
     * Sets the value of the field.
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException if the value is not a string
     */
    public function setValue($value)
    {
        parent::setValue($value);
    }
}
