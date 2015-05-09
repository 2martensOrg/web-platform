<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Field;

use TwoMartens\Bundle\CoreBundle\Field\IntegerField;

/**
 * Tests the IntegerField class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class IntegerFieldTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $integerField = new IntegerField(42);
        $this->assertEquals(42, $integerField->getValue());
        $this->assertEquals('int', $integerField->getType());

        try {
            new IntegerField('32');
            $this->assertTrue(false, 'An InvalidArgumentException should have been thrown');
        } catch (\InvalidArgumentException $iae) {
            $this->assertTrue(true, 'The expected InvalidArgumentException was thrown');
        }
    }

    public function testSet()
    {
        $fieldInteger = new IntegerField(-1);
        $this->assertEquals(-1, $fieldInteger->getValue());
        $this->assertEquals('int', $fieldInteger->getType());
        $fieldInteger->setValue(42);
        $this->assertEquals(42, $fieldInteger->getValue());

        try {
            $fieldInteger->setValue('44');
            $this->assertTrue(false, 'An InvalidArgumentException should have been thrown');
        } catch (\InvalidArgumentException $iae) {
            $this->assertTrue(true, 'The expected InvalidArgumentException was thrown');
        }
    }
}
