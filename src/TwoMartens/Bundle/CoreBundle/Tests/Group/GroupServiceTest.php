<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Group;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;
use TwoMartens\Bundle\CoreBundle\Event\GroupOptionTypeEvent;
use TwoMartens\Bundle\CoreBundle\Group\GroupService;
use TwoMartens\Bundle\CoreBundle\Group\GroupServiceInterface;
use TwoMartens\Bundle\CoreBundle\Group\Option\BooleanOptionType;
use TwoMartens\Bundle\CoreBundle\Model\Option;
use TwoMartens\Bundle\CoreBundle\Model\User;
use TwoMartens\Bundle\CoreBundle\Util\ConfigUtil;

/**
 * Tests the GroupService.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class GroupServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GroupServiceInterface
     */
    private $groupService;

    /**
     * the dummy parser
     * @var Parser
     */
    private $dummyParser;

    /**
     * the dummy dumper
     * @var Dumper
     */
    private $dummyDumper;

    /**
     * the dummy filesystem
     * @var Filesystem
     */
    private $dummyFilesystem;

    /**
     * the yaml parsed data
     * @var array
     */
    private $yamlData;

    /**
     * the option data
     * @var array
     */
    private $optionData;

    /**
     * Prepares test environment.
     */
    public function setUp()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/config/');
        $finder->name('*.yml');
        $this->dummyParser = $this->getMock('Symfony\Component\Yaml\Parser');
        $this->dummyDumper = $this->getMock('Symfony\Component\Yaml\Dumper');
        $this->dummyFilesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');

        $dummyDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dummyDispatcher->expects($this->once())
            ->method('dispatch')
            ->willReturnCallback(function ($name, $event) {
                /** @var GroupOptionTypeEvent $event */
                $optionTypes = [
                    'acp' => [
                        'twomartens.core' => [
                            'access' => new BooleanOptionType()
                        ]
                    ]
                ];
                $event->addOptions($optionTypes);
            });


        $this->yamlData = [];
        $this->optionData = [];
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $basename = $file->getBasename('.yml');
            $this->yamlData[$basename] = Yaml::parse($file->getContents());
            $this->optionData[$basename] = ConfigUtil::convertToOptions($this->yamlData[$basename]);
        }
        $this->dummyParser->expects($this->any())
            ->method('parse')
            ->willReturnCallback(function ($content) {
                return Yaml::parse($content);
            });

        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dummyDispatcher */
        $this->groupService = new GroupService(
            $finder,
            $this->dummyParser,
            $this->dummyDumper,
            $this->dummyFilesystem,
            $dummyDispatcher
        );
    }

    /**
     * Tests the ability to set and then get options.
     */
    public function testGetOptions()
    {
        $this->groupService->setOptionsFor('ADMIN', $this->optionData['admin']);
        $this->groupService->setOptionsFor('USER', $this->optionData['user']);
        $options = $this->groupService->getOptionsFor('ADMIN');
        $this->assertEquals($this->optionData['admin'], $options);

        // setting; category: acp, option: access
        $this->assertTrue(
            $this->groupService->get(
                'ADMIN',
                'acp',
                'twomartens.core',
                'access'
            )->getValue()
        );

        // the various option types are tested separately
        // here we should only test the core functionality of getEffective
        $user = $this->getMock('\TwoMartens\Bundle\CoreBundle\Model\User');
        $user->expects($this->any())
            ->method('getGroupNames')
            ->willReturn(['ADMIN', 'USER']);
        /** @var User $user */
        $option = $this->groupService->getEffective(
            $user,
            'acp',
            'twomartens.core',
            'access'
        );
        $this->assertTrue($option->getValue());

        $option->setValue(false);
        $this->groupService->set('ADMIN', 'acp', 'twomartens.core', $option);
        $this->assertFalse($this->groupService->getEffective(
            $user,
            'acp',
            'twomartens.core',
            'access'
        )->getValue());

        $option = $this->groupService->getEffective(
            $user,
            'acp',
            'twomartens.core',
            'carpot'
        );
        $this->assertNull($option);
    }

    /**
     * Tests that the two set methods leave a consistent state.
     */
    public function testConsistency()
    {
        $this->groupService->setOptionsFor('ADMIN', $this->optionData['admin']);
        $this->groupService->setOptionsFor('USER', $this->optionData['user']);

        // check single get method
        $option = $this->groupService->get('ADMIN', 'acp', 'twomartens.core', 'access');
        $this->assertTrue($option->getValue());
        // change via single change
        $newOption = new Option(0, 'access', 'boolean', false);
        $this->groupService->set('ADMIN', 'acp', 'twomartens.core', $newOption);
        // check complete get
        $options = $this->groupService->getOptionsFor('ADMIN');
        $categories = $options->getCategories();
        foreach ($categories as $category) {
            if ($category->getName() != 'acp') {
                continue;
            }

            $_options = $category->getOptions();
            foreach ($_options as $option) {
                if ($option->getName() != 'access') {
                    continue;
                }

                $this->assertFalse($option->getValue());
                break 2;
            }
        }
    }

    /**
     * Tests that options can be removed.
     */
    public function testRemoval()
    {
        $this->groupService->setOptionsFor('ADMIN', $this->optionData['admin']);
        $options = $this->groupService->getOptionsFor('ADMIN');
        $this->assertEquals($this->optionData['admin'], $options);

        $this->groupService->removeOptionsFor('ADMIN');
        try {
            $this->groupService->getOptionsFor('ADMIN');
            $this->fail();
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }
}
