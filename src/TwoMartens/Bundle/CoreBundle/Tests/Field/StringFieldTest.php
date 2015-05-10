<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Field;

use TwoMartens\Bundle\CoreBundle\Field\StringField;

/**
 * Tests the StringField class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class StringFieldTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $stringField = new StringField('42');
        $this->assertEquals('42', $stringField->getValue());
        $this->assertEquals('string', $stringField->getType());

        try {
            new StringField(32);
            $this->assertTrue(false, 'An InvalidArgumentException should have been thrown');
        } catch (\InvalidArgumentException $iae) {
            $this->assertTrue(true, 'The expected InvalidArgumentException was thrown');
        }
    }

    public function testSet()
    {
        $fieldString = new StringField('alea');
        $this->assertEquals('alea', $fieldString->getValue());
        $this->assertEquals('string', $fieldString->getType());
        $fieldString->setValue('rome');
        $this->assertEquals('rome', $fieldString->getValue());

        try {
            $fieldString->setValue(42);
            $this->assertTrue(false, 'An InvalidArgumentException should have been thrown');
        } catch (\InvalidArgumentException $iae) {
            $this->assertTrue(true, 'The expected InvalidArgumentException was thrown');
        }
    }
}
