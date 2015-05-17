<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Option;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use TwoMartens\Bundle\CoreBundle\Model\Option\Option;
use TwoMartens\Bundle\CoreBundle\Model\Option\OptionCategory;
use TwoMartens\Bundle\CoreBundle\Util\ConfigUtil;

/**
 * Provides core option functionality.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OptionService implements OptionServiceInterface
{
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
     * the options in YAML format
     * @var array
     */
    private $optionsYAML;

    /**
     * Initializes the service.
     *
     * @param Finder     $finder     Must be configured to read ONE config file
     * @param Parser     $parser     A YAML parser
     * @param Dumper     $dumper     A YAML dumper
     * @param Filesystem $filesystem A filesystem
     */
    public function __construct(Finder $finder, Parser $parser, Dumper $dumper, Filesystem $filesystem)
    {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->dumper = $dumper;
        $this->filesystem = $filesystem;

        $this->optionsYAML = $this->readConfig();
        if ($this->optionsYAML === null) {
            throw new \LogicException('The given finder isn\'t properly configured.');
        }
    }

    /**
     * Writes the options to the config file.
     */
    public function __destruct()
    {
        $this->writeConfig();
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return ConfigUtil::convertToOptions($this->optionsYAML);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(OptionCategory $options)
    {
        $this->optionsYAML = ConfigUtil::convertToArray($options);
    }

    /**
     * {@inheritdoc}
     */
    public function get($optionCategory, $optionName)
    {
        $optionValue = $this->optionsYAML[$optionCategory][$optionName];

        return ConfigUtil::convertToOption($optionName, $optionValue);
    }

    /**
     * {@inheritdoc}
     */
    public function set($optionCategory, $optionName, Option $value)
    {
        $this->optionsYAML[$optionCategory][$optionName] = $value->getValue();
    }

    /**
     * Reads the config file configured in the finder and returns the
     * YAML parsed content for it.
     *
     * @return array|null
     */
    private function readConfig()
    {
        $configContents = null;
        foreach ($this->finder as $configFile) {
            /** @var SplFileInfo $configFile */
            $configContents = $this->parser->parse($configFile->getContents());
        }

        return $configContents;
    }

    /**
     * Dumps the options into the config file.
     *
     * ATTENTION: This method DOES NOT perform any sanitize actions. It
     * overwrites the config file with the options entirely.
     */
    private function writeConfig()
    {
        $dumpedData = $this->dumper->dump($this->optionsYAML, 2);
        foreach ($this->finder as $configFile) {
            /** @var SplFileInfo $configFile */
            $this->filesystem->dumpFile($configFile->getRealPath(), $dumpedData);
        }
    }
}
