<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Model;

/**
 * Represents an OptionCategory.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OptionCategory
{
    /**
     * sub categories
     * @var OptionCategory[]
     */
    private $categories;

    /**
     * embedded options
     * @var Option[]
     */
    private $options;

    /**
     * name of the category
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name = '')
    {
        $this->name = $name;
        $this->categories = [];
        $this->options = [];
    }

    /**
     * @return OptionCategory[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param OptionCategory[] $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Option[] $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
