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
        $package = new Package(
            0,
            '2WP Platform',
            'One platform to rule them all',
            '1.0.0',
            'Jim Martens',
            '2martens.de',
            '2martens/web-platform'
        );
        $this->assertEquals(0, $package->getId());
        $this->assertEquals('2WP Platform', $package->getName());
        $this->assertEquals('One platform to rule them all', $package->getDescription());
        $this->assertEquals('1.0.0', $package->getVersion());
        $this->assertEquals('Jim Martens', $package->getAuthor());
        $this->assertEquals('2martens.de', $package->getWebsite());
        $this->assertEquals('2martens/web-platform', $package->getComposerName());

        $package = new Package(
            0,
            '',
            '',
            '',
            '',
            '',
            ''
        );
        $this->assertEquals(0, $package->getId());
        $this->assertEquals('', $package->getName());
        $this->assertEquals('', $package->getDescription());
        $this->assertEquals('', $package->getVersion());
        $this->assertEquals('', $package->getAuthor());
        $this->assertEquals('', $package->getWebsite());
        $this->assertEquals('', $package->getComposerName());

        $package = new Package(
            '123',
            '',
            '',
            '',
            '',
            '',
            ''
        );
        $this->assertEquals('123', $package->getId());
        $this->assertEquals('', $package->getName());
        $this->assertEquals('', $package->getDescription());
        $this->assertEquals('', $package->getVersion());
        $this->assertEquals('', $package->getAuthor());
        $this->assertEquals('', $package->getWebsite());
        $this->assertEquals('', $package->getComposerName());
    }

    /**
     * Tests the setters.
     */
    public function testSetters()
    {
        $package = new Package(
            0,
            '2WP Platform',
            'One platform to rule them all',
            '1.0.0',
            'Jim Martens',
            '2martens.de',
            '2martens/web-platform'
        );
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
        // test composer name
        $package->setComposerName('');
        $this->assertEquals('', $package->getComposerName());
        $package->setComposerName('symfony/symfony');
        $this->assertEquals('symfony/symfony', $package->getComposerName());
    }
}
