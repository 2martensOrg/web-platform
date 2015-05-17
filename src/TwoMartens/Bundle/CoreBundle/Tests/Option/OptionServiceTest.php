<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Option;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;
use TwoMartens\Bundle\CoreBundle\Model\Option\OptionCategory;
use TwoMartens\Bundle\CoreBundle\Option\OptionService;
use TwoMartens\Bundle\CoreBundle\Option\OptionServiceInterface;
use TwoMartens\Bundle\CoreBundle\Util\ConfigUtil;

/**
 * Tests the OptionService.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OptionServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * the service
     * @var OptionServiceInterface
     */
    private $optionService;

    /**
     * the dummy parser
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dummyParser;

    /**
     * the dummy dumper
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dummyDumper;

    /**
     * the dummy filesystem
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dummyFilesystem;

    /**
     * the yaml parsed data
     * @var array
     */
    private $yamlData;

    /**
     * the option data
     * @var OptionCategory
     */
    private $optionData;

    /**
     * Sets the test environment up.
     */
    public function setUp()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/config/');
        $finder->name('config.yml');
        $this->dummyParser = $this->getMock('Symfony\Component\Yaml\Parser');
        $this->dummyDumper = $this->getMock('Symfony\Component\Yaml\Dumper');
        $this->dummyFilesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');

        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $this->yamlData = Yaml::parse($file->getContents());
            $this->optionData = ConfigUtil::convertToOptions($this->yamlData);
            $this->dummyParser->expects($this->once())->method('parse')
                ->willReturn($this->yamlData);
            $this->dummyDumper->expects($this->any())->method('dump')
                ->willReturn($file->getContents());
            $this->dummyFilesystem->expects($this->any())->method('dumpFile')
                ->with($file->getRealPath(), $file->getContents());
        }

        $this->optionService = new OptionService($finder, $this->dummyParser, $this->dummyDumper, $this->dummyFilesystem);
    }

    /**
     * Tests the state after the construct method has finished.
     */
    public function testConstruct()
    {
        $options = $this->optionService->getOptions();
        $categories = $options->getCategories();
        /** @var OptionCategory $firstCategory */
        $firstCategory = $categories[0];
        $this->assertEquals('twomartens.core', $firstCategory->getName());
        $options = $firstCategory->getOptions();

        $this->assertEquals('optionOne', $options[0]->getName());
        $this->assertEquals('boolean', $options[0]->getType());
        $this->assertEquals('optionTwo', $options[1]->getName());
        $this->assertEquals('string', $options[1]->getType());
        $this->assertEquals('optionThree', $options[2]->getName());
        $this->assertEquals('array', $options[2]->getType());
    }

    /**
     * Tests the get method.
     */
    public function testGet()
    {
        $optionValue = $this->optionService->get('twomartens.core', 'optionOne');
        $this->assertEquals('optionOne', $optionValue->getName());
        $this->assertEquals('boolean', $optionValue->getType());
        $this->assertTrue($optionValue->getValue());

        $optionValue = $this->optionService->get('twomartens.core', 'optionTwo');
        $this->assertEquals('optionTwo', $optionValue->getName());
        $this->assertEquals('string', $optionValue->getType());
        $this->assertEquals('Sascha', $optionValue->getValue());

        $optionValue = $this->optionService->get('twomartens.core', 'optionThree');
        $this->assertEquals('optionThree', $optionValue->getName());
        $this->assertEquals('array', $optionValue->getType());
        $this->assertEquals([1, 2, 3], $optionValue->getValue());
    }

    /**
     * Tests the set method.
     */
    public function testSet()
    {
        $optionValue = $this->optionService->get('twomartens.core', 'optionOne');
        $optionValue->setValue(false);
        $this->optionService->set('twomartens.core', 'optionOne', $optionValue);

        $firstOption = $this->optionService->get('twomartens.core', 'optionOne');
        $this->assertFalse($firstOption->getValue());
        $this->assertEquals('boolean', $firstOption->getType());
    }

    /**
     * Tests the getOptions method.
     */
    public function testSetOptions()
    {
        $superCategory = $this->optionService->getOptions();
        $categories = $superCategory->getCategories();

        $firstCategory = $categories[0];
        $options = $firstCategory->getOptions();
        $firstOption = $options[0];
        $firstOption->setValue(false);

        $this->optionService->setOptions($superCategory);
        $testOption = $this->optionService->get('twomartens.core', 'optionOne');
        $this->assertFalse($testOption->getValue());
    }
}
