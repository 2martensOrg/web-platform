<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Model\Field;

/**
 * Abstract class for field models.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
abstract class AbstractField
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $type;

    /**
     * Initializes the field.
     *
     * @param mixed  $value Must be of the given type
     * @param string $type  Must be a valid type of PHP (primitive or FQCN)
     *
     * @throws \InvalidArgumentException if $value doesn't match $type
     */
    public function __construct($value, $type)
    {
        if ($type == 'integer') {
            $type = 'int';
        }

        $validValue = $this->checkType($value, $type);

        if ($validValue) {
            $this->type = $type;
            $this->value = $value;
        } else {
            throw new \InvalidArgumentException("The given value doesn't match the given type");
        }
    }

    /**
     * Returns the value of this field.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the field.
     *
     * @param mixed $value Must be of the type of the field
     *
     * @throws \InvalidArgumentException if the value doesn't match the type of this field
     */
    public function setValue($value)
    {
        if ($this->checkType($value, $this->type)) {
            $this->value = $value;
        } else {
            throw new \InvalidArgumentException("The given value doesn't match the type of this field");
        }
    }

    /**
     * Returns the type of the field.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Checks if the given value matches the given type.
     *
     * @param mixed  $value
     * @param string $type
     *
     * @return bool
     */
    private function checkType($value, $type)
    {
        $checkMethod = 'is_' . trim($type);
        $validValue = false;
        if (function_exists($checkMethod)) {
            if ($checkMethod($value)) {
                $validValue = true;
            }
        } else {
            if (is_a($value, $type)) {
                $validValue = true;
            }
        }

        return $validValue;
    }
}
