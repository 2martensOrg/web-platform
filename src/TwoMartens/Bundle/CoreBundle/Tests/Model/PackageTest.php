<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Model;

use TwoMartens\Bundle\CoreBundle\Model\Package;

/**
 * Tests the Package class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class PackageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the constructor.
     */
    public function testConstructor()
    {
        $package = new Package('2martens/web-platform');
        $this->assertEquals('2martens/web-platform', $package->getComposerName());
        $this->assertEmpty($package->getName());
        $this->assertEmpty($package->getDescription());
        $this->assertEmpty($package->getVersion());
        $this->assertEmpty($package->getWebsite());
        $this->assertEmpty($package->getAuthor());

        $package = new Package('');
        $this->assertEquals('', $package->getComposerName());
    }

    /**
     * Tests the setters.
     */
    public function testSetters()
    {
        $package = new Package('2martens/web-platform');
        // test name
        $package->setName('');
        $this->assertEquals('', $package->getName());
        $package->setName('2WP Platform');
        $this->assertEquals('2WP Platform', $package->getName());
        // test description
        $package->setDescription('');
        $this->assertEquals('', $package->getDescription());
        $package->setDescription('The 2WP Platform delivers important features all in one');
        $this->assertEquals('The 2WP Platform delivers important features all in one', $package->getDescription());
        // test version
        $package->setVersion('');
        $this->assertEquals('', $package->getVersion());
        $package->setVersion('1.0.0');
        $this->assertEquals('1.0.0', $package->getVersion());
        // test website
        $package->setWebsite('');
        $this->assertEquals('', $package->getWebsite());
        $package->setWebsite('https://2martens.de');
        $this->assertEquals('https://2martens.de', $package->getWebsite());
        // test author
        $package->setAuthor('');
        $this->assertEquals('', $package->getAuthor());
        $package->setAuthor('Jim Martens');
        $this->assertEquals('Jim Martens', $package->getAuthor());
    }
}
