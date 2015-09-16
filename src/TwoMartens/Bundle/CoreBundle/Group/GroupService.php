<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Group;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use TwoMartens\Bundle\CoreBundle\Event\GroupOptionTypeEvent;
use TwoMartens\Bundle\CoreBundle\Group\Option\OptionTypeInterface;
use TwoMartens\Bundle\CoreBundle\Model\Option;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;
use TwoMartens\Bundle\CoreBundle\Model\User;
use TwoMartens\Bundle\CoreBundle\Util\ConfigUtil;

/**
 * Implementation for the GroupServiceInterface.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class GroupService implements GroupServiceInterface
{
    /**
     * OptionCategory mapped to group
     * @var OptionCategory[]
     */
    private $optionData;

    /**
     * options mapped to group and category
     * @var Option[][]
     */
    private $options;

    /**
     * option types mapped to category and option name
     * @var array
     */
    private $optionTypes;

    /**
     * the finder
     * @var Finder
     */
    private $finder;

    /**
     * the YAML parser
     * @var Parser
     */
    private $parser;

    /**
     * the YAML dumper
     * @var Dumper
     */
    private $dumper;

    /**
     * the filesystem
     * @var Filesystem
     */
    private $filesystem;

    /**
     * list of the filenames which have to be removed
     * @var string[]
     */
    private $toBeRemoved;

    /**
     * Constructor.
     *
     * @param Finder                   $finder
     * @param Parser                   $parser
     * @param Dumper                   $dumper
     * @param Filesystem               $filesystem
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        Finder $finder,
        Parser $parser,
        Dumper $dumper,
        Filesystem $filesystem,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->dumper = $dumper;
        $this->filesystem = $filesystem;
        $this->dispatcher = $dispatcher;
        $this->optionData = [];
        $this->options = [];
        $this->optionTypes = [];
        $this->toBeRemoved = [];

        // fill option types
        $event = new GroupOptionTypeEvent();
        $dispatcher->dispatch(
            'twomartens.core.group_service.init',
            $event
        );
        $this->optionTypes = $event->getOptionTypes();

        $this->parseConfig();
    }

    /**
     * {@inheritdoc}
     */
    public function commitChanges()
    {
        $this->writeConfig();
    }

    /**
     * {@inheritdoc}
     */
    public function removeOptionsFor($groupRoleName)
    {
        if (!isset($this->optionData[$groupRoleName])
         || !isset($this->options[$groupRoleName])) {
            throw new \LogicException('The given group role name doesn\'t exist.');
        }
        unset($this->options[$groupRoleName]);
        unset($this->optionData[$groupRoleName]);
        $this->toBeRemoved[] = $groupRoleName;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsFor($groupRoleName)
    {
        return $this->optionData[$groupRoleName];
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionsFor($groupRoleName, OptionCategory $options)
    {
        $this->optionData[$groupRoleName] = $options;

        // updating options
        $categories = $options->getCategories();
        foreach ($categories as $category) {
            $name = $category->getName();
            if (!isset($this->options[$groupRoleName][$name])) {
                $this->options[$groupRoleName][$name] = [];
            }

            $_options = $category->getOptions();
            foreach ($_options as $_option) {
                $this->options[$groupRoleName][$name][$_option->getName()] = $_option;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($groupRoleName, $category, $optionName)
    {
        if (!isset($this->options[$groupRoleName][$category][$optionName])) {
            return null;
        }

        return $this->options[$groupRoleName][$category][$optionName];
    }

    /**
     * {@inheritdoc}
     */
    public function set($groupRoleName, $category, Option $value)
    {
        $this->options[$groupRoleName][$category][$value->getName()] = $value;

        $categories = $this->optionData[$groupRoleName]->getCategories();
        foreach ($categories as $_category) {
            if ($_category->getName() != $category) {
                continue;
            }

            $_options = $_category->getOptions();
            foreach ($_options as $option) {
                if ($option->getName() != $value->getName()) {
                    continue;
                }

                $option->setValue($value->getValue());
                break 2;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEffective(User $user, $category, $optionName)
    {
        $groupNames = $user->getGroupNames();
        /** @var Option[] $options */
        $options = [];
        foreach ($groupNames as $name) {
            $options[$name] = $this->get($name, $category, $optionName);
        }

        $returnValue = null;
        foreach ($options as $name => $option) {
            if (!isset($this->optionTypes[$category][$optionName])) {
                // if this happens, somebody somewhere screwed up
                // we must return null, as there is no way to simply
                // ignore this
                $returnValue = null;
                break;
            }
            /** @var OptionTypeInterface $type */
            $type = $this->optionTypes[$category][$optionName];
            $returnValue = $type->getBestValue(
                $returnValue,
                $option
            );
        }

        return $returnValue;
    }

    /**
     * Parses the config files.
     */
    private function parseConfig()
    {
        foreach ($this->finder as $configFile) {
            /** @var SplFileInfo $configFile */
            $configYAML = $this->parser->parse($configFile->getContents());
            $configOptions = ConfigUtil::convertToOptions($configYAML);
            $this->setOptionsFor(strtoupper($configFile->getBasename('.yml')), $configOptions);
        }
    }

    /**
     * Writes the config files.
     *
     * ATTENTION: This method DOES NOT perform any sanitize actions. It
     * overwrites the config files with the options entirely.
     */
    private function writeConfig()
    {
        // updates existing files and adds new files if necessary
        $path = null;
        $roleNames = array_keys($this->optionData);
        $baseNames = [];
        foreach ($this->finder as $file) {
            /** @var SplFileInfo $file */
            if ($path === null) {
                $path = $file->getPath();
                break;
            }
        }
        foreach ($roleNames as $roleName) {
            $baseNames[] = strtolower($roleName);
        }
        foreach ($baseNames as $basename) {
            $category = $this->optionData[strtoupper($basename)];
            $yaml = ConfigUtil::convertToArray($category);
            $dumpReady = $this->dumper->dump($yaml, 3);
            $writePath = $path . '/' . $basename . '.yml';
            $this->filesystem->dumpFile($writePath, $dumpReady);
        }
        foreach ($this->toBeRemoved as $toRemove) {
            $basename = strtolower($toRemove). '.yml';
            $deletePath = $path . '/' . $basename;
            $this->filesystem->remove($deletePath);
        }
        $this->toBeRemoved = [];
    }
}
