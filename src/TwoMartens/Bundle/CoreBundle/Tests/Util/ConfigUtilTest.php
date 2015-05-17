<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Util;

use Symfony\Component\Yaml\Yaml;
use TwoMartens\Bundle\CoreBundle\Model\Option\Option;
use TwoMartens\Bundle\CoreBundle\Model\Option\OptionCategory;
use TwoMartens\Bundle\CoreBundle\Util\ConfigUtil;

/**
 * Tests the ConfigUtil.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ConfigUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the convertToOptions method.
     */
    public function testConvertToOptions()
    {
        $path = __DIR__.'/config/config.yml';
        $contents = file_get_contents($path);
        $yamlProcessed = Yaml::parse($contents);
        $output = ConfigUtil::convertToOptions($yamlProcessed);

        $expectedOutput = new OptionCategory();
        // imports category
        $importsCategory = new OptionCategory('imports');
        $subOptions = [
            new Option('resource', 'string', 'parameters.yml'),
            new Option('resource', 'string', 'security.yml'),
            new Option('resource', 'string', '@TwoMartensCoreBundle/Resources/config/services.yml')
        ];
        $importsCategory->setOptions($subOptions);

        // test category
        $testCategory = new OptionCategory('test');
        $subOptions = [
            new Option('jacob', 'null', null),
            new Option('hans', 'array', ['achim', 'jacob', 'manfred'])
        ];
        $testCategory->setOptions($subOptions);
        $manfredCategory = new OptionCategory('michael');
        $subOptions = [
            new Option('security', 'boolean', true)
        ];
        $manfredCategory->setOptions($subOptions);
        $testCategory->setCategories([$manfredCategory]);

        $expectedOutput->setCategories([$importsCategory, $testCategory]);

        $this->assertEquals($expectedOutput, $output);
    }

    /**
     * Tests the convertToOption method.
     */
    public function testConvertToOption()
    {
        $optionName = 'testOption';
        $optionValue = true;

        $option = ConfigUtil::convertToOption($optionName, $optionValue);
        $this->assertEquals('testOption', $option->getName());
        $this->assertTrue($option->getValue());
        $this->assertEquals('boolean', $option->getType());
    }

    /**
     * Tests the convertToArray method.
     */
    public function testConvertToArray()
    {
        $optionCategory = new OptionCategory();
        $mainCategory = new OptionCategory('twomartens.core');
        $options = [
            new Option('one', 'string', 'hello'),
            new Option('two', 'boolean', false),
            new Option('three', 'array', [1, 2, 3]),
        ];
        $mainCategory->setOptions($options);
        $optionCategory->setCategories([$mainCategory]);

        $yamlData = ConfigUtil::convertToArray($optionCategory);
        $expectedYamlData = [
            'twomartens.core' => [
                'one' => 'hello',
                'two' => false,
                'three' => [1, 2, 3]
            ]
        ];

        $this->assertEquals($expectedYamlData, $yamlData);
    }
}
