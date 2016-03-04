<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Group\Option;

use TwoMartens\Bundle\CoreBundle\Group\Option\BooleanOptionType;
use TwoMartens\Bundle\CoreBundle\Group\Option\OptionTypeInterface;
use TwoMartens\Bundle\CoreBundle\Model\Option;

/**
 * Tests the BooleanOptionType class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class BooleanOptionTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * the option type
     * @var OptionTypeInterface
     */
    private $optionType;

    /**
     * contains test options
     * @var Option[]
     */
    private $options;

    /**
     * Prepares the environment.
     */
    public function setUp()
    {
        $this->optionType = new BooleanOptionType();
        $this->options = [
            'true' => new Option(0, 'test', 'boolean', true),
            'false' => new Option(0, 'test', 'boolean', false),
        ];
    }

    /**
     * Tests the functionality.
     */
    public function testFunctionality()
    {
        $nullLeftTrue = $this->optionType->getBestValue(
            null,
            $this->options['true']
        );
        $nullLeftFalse = $this->optionType->getBestValue(
            null,
            $this->options['false']
        );
        $nullRightTrue = $this->optionType->getBestValue(
            $this->options['true'],
            null
        );
        $nullRightFalse = $this->optionType->getBestValue(
            $this->options['false'],
            null
        );
        $nullBoth = $this->optionType->getBestValue(null, null);
        $this->assertTrue($nullLeftTrue->getValue());
        $this->assertFalse($nullLeftFalse->getValue());
        $this->assertTrue($nullRightTrue->getValue());
        $this->assertFalse($nullRightFalse->getValue());
        $this->assertNull($nullBoth);

        $trueLeft = $this->optionType->getBestValue(
            $this->options['true'],
            $this->options['false']
        );
        $trueBoth = $this->optionType->getBestValue(
            $this->options['true'],
            $this->options['true']
        );
        $trueRight = $this->optionType->getBestValue(
            $this->options['false'],
            $this->options['true']
        );
        $this->assertTrue(
            $trueLeft->getValue() &&
            $trueBoth->getValue() &&
            $trueRight->getValue()
        );

        $falseBoth = $this->optionType->getBestValue(
            $this->options['false'],
            $this->options['false']
        );
        $this->assertFalse($falseBoth->getValue());
    }
}
