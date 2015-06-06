<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Model\Option;

/**
 * Represents an option.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class Option
{
    /**
     * the id of the option
     * @var int
     */
    private $id;

    /**
     * the name of the option
     * @var string
     */
    private $name;

    /**
     * the type of the option
     * @var string
     */
    private $type;

    /**
     * the value of the option
     * @var mixed
     */
    private $value;

    /**
     * Initializes the Option.
     *
     * @param string $name
     * @param string $type
     * @param mixed  $value
     */
    public function __construct($name = '', $type = '', $value = null)
    {
        $this->id = 0;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Returns the ID of the option.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the name of the option.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the option.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the type of the option.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type of the option.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the value of the option.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the option.
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
