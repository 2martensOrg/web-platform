<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Field;

use TwoMartens\Bundle\CoreBundle\Field\DefaultField;

/**
 * Tests the AbstractField class with help of DefaultField class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        // int and integer should be equivalent
        // the type should be followed strictly
        $fieldInteger = new DefaultField(0, 'int');
        $integerValue = $fieldInteger->getValue();
        $this->assertEquals(0, $integerValue);
        $this->assertEquals('int', $fieldInteger->getType());
        $this->assertFalse($integerValue === '0');

        $fieldInteger = new DefaultField(-1, 'int');
        $this->assertEquals(-1, $fieldInteger->getValue());
        $this->assertEquals('int', $fieldInteger->getType());

        $fieldInteger = new DefaultField(1, 'integer');
        $this->assertEquals(1, $fieldInteger->getValue());
        $this->assertEquals('int', $fieldInteger->getType());

        $fieldString = new DefaultField('', 'string');
        $this->assertEquals('', $fieldString->getValue());
        $this->assertEquals('string', $fieldString->getType());

        $fieldString = new DefaultField('123678', 'string');
        $stringValue = $fieldString->getValue();
        $this->assertEquals('123678', $stringValue);
        $this->assertEquals('string', $fieldString->getType());
        $this->assertFalse($stringValue === 123678);

        $fieldString = new DefaultField('aNaLPHabet', 'string');
        $this->assertEquals('aNaLPHabet', $fieldString->getValue());
        $this->assertEquals('string', $fieldString->getType());

        try {
            new DefaultField('42', 'int');
            $this->assertTrue(false, 'An InvalidArgumentException should have been thrown');
        } catch (\InvalidArgumentException $iae) {
            $this->assertTrue(true, 'The expected InvalidArgumentException was thrown');
        }

        // test class type
        $className = '\TwoMartens\Bundle\CoreBundle\Field\AbstractField';
        $classField = new DefaultField($fieldInteger, $className);
        $classValue = $classField->getValue();
        $this->assertEquals(1, $classValue->getValue());
        $this->assertEquals($className, $classField->getType());
    }

    public function testSet()
    {
        $fieldInteger = new DefaultField(-1, 'int');
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
