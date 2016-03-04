<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Model;

use TwoMartens\Bundle\CoreBundle\Model\Option;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;

/**
 * Tests the OptionCategory class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OptionCategoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the constructor
     */
    public function testConstruct()
    {
        $optionCategory = new OptionCategory('test');
        $this->assertEquals('test', $optionCategory->getName());
        $this->assertEquals([], $optionCategory->getOptions());
        $this->assertEquals([], $optionCategory->getCategories());
    }

    /**
     * Tests setting values.
     */
    public function testSet()
    {
        $optionCategory = new OptionCategory();

        // set category name
        $optionCategory->setName('hansolo');
        $this->assertEquals('hansolo', $optionCategory->getName());
        $optionCategory->setName('');
        $this->assertTrue(empty($optionCategory->getName()));
        $optionCategory->setName('123');
        $this->assertTrue('123' === $optionCategory->getName());

        // set options
        $options = [
            new Option(),
            new Option(0, 'testV', 'string', 'elephant'),
            new Option(0, 'samba', 'array', [1, 2, 3])
        ];
        $optionCategory->setOptions($options);
        $this->assertEquals($options, $optionCategory->getOptions());

        // set categories
        $categories = [
            new OptionCategory('testv1'),
            new OptionCategory('testv0')
        ];
        $optionCategory->setCategories($categories);
        $this->assertEquals($categories, $optionCategory->getCategories());
    }
}
