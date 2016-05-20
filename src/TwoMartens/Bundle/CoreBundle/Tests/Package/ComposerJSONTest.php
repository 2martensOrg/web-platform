<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Package;

use TwoMartens\Bundle\CoreBundle\Package\ComposerJSON;

/**
 * Tests the ComposerJSON file.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class ComposerJSONTest extends \PHPUnit_Framework_TestCase
{
    /**
     * the used ComposerJSON object
     *
     * @var ComposerJSON
     */
    private $composerJSON;

    /**
     * Sets the test environment up.
     */
    public function setUp()
    {
        $this->composerJSON = new ComposerJSON(__DIR__.'/');
    }

    /**
     * Tests the parsing.
     */
    public function testParsing()
    {
        $this->assertEquals('2martens/web-platform', $this->composerJSON->getComposerName());
        $this->assertEquals('The 2martens Web Platform', $this->composerJSON->getDescription());
        $this->assertEquals('https://github.com/2martens/web-platform', $this->composerJSON->getWebsite());
        $this->assertEquals('MIT', $this->composerJSON->getLicense());
        $this->assertEquals('Jim Martens <github@2martens.de>', $this->composerJSON->getPrimaryAuthor());
    }
}
