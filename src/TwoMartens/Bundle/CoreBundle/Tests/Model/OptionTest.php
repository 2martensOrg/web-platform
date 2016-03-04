<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Model;

use TwoMartens\Bundle\CoreBundle\Model\Option;

/**
 * Tests the Option class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the constructor
     */
    public function testConstruct()
    {
        $option = new Option(0, 'test', 'string', 'hallo');
        $this->assertEquals('test', $option->getName());
        $this->assertEquals('string', $option->getType());
        $this->assertEquals('hallo', $option->getValue());
    }

    /**
     * Tests setting values.
     */
    public function testSet()
    {
        $option = new Option();
        // set name
        $option->setName('hero');
        $this->assertEquals('hero', $option->getName());
        $option->setName('');
        $this->assertEquals('', $option->getName());
        $option->setName('-123');
        $this->assertEquals('-123', $option->getName());

        // set type
        $option->setType('string');
        $this->assertEquals('string', $option->getType());
        $option->setType('boolean');
        $this->assertEquals('boolean', $option->getType());
        $option->setType('select');
        $this->assertEquals('select', $option->getType());

        // set value
        $option->setValue(123);
        $this->assertEquals(123, $option->getValue());
        $option->setValue(true);
        $this->assertTrue($option->getValue());
        $option->setValue('');
        $this->assertEquals('', $option->getValue());
    }
}
