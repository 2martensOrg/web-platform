<?php
/*
 * This file is part of the 2martens Web Platform.
 *
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Model;

use TwoMartens\Bundle\CoreBundle\Model\Breadcrumb;

/**
 * Tests the Breadcrumb class.
 *
 * @author Jim Martens <github@2martens.de>
 */
class BreadcrumbTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $breadcrumb = new Breadcrumb('acp.home', 'Dashboard');
        $this->assertFalse($breadcrumb->isActive());
        $this->assertEquals('acp.home', $breadcrumb->getPath());
        $this->assertEquals('Dashboard', $breadcrumb->getTitle());

        // even the empty string should work
        $breadcrumb = new Breadcrumb('', '');
        $this->assertFalse($breadcrumb->isActive());
        $this->assertEquals('', $breadcrumb->getPath());
        $this->assertEquals('', $breadcrumb->getTitle());

        // leading and trailing spaces should not make a difference
        $breadcrumb = new Breadcrumb('     133 7    ', '    1337       ');
        $this->assertFalse($breadcrumb->isActive());
        $this->assertEquals('133 7', $breadcrumb->getPath());
        $this->assertEquals('1337', $breadcrumb->getTitle());

        // the strings should be taken as is (expect for leading/trailing spaces); no operations should be performed
        $breadcrumb = new Breadcrumb('     133+7    ', '    1337       ');
        $this->assertFalse($breadcrumb->isActive());
        $this->assertEquals('133+7', $breadcrumb->getPath());
        $this->assertEquals('1337', $breadcrumb->getTitle());
    }

    public function testActivate()
    {
        $breadcrumb = new Breadcrumb('acp.home', 'Dashboard');
        $this->assertFalse($breadcrumb->isActive());
        $breadcrumb->activate();
        $this->assertTrue($breadcrumb->isActive());
        $breadcrumb->deactivate();
        $this->assertFalse($breadcrumb->isActive());
    }

    public function testSet()
    {
        $breadcrumb = new Breadcrumb('acp.schwachsinn', 'Bastille');
        $breadcrumb->setTitle('Alea-Iacta-Est');
        $this->assertEquals('Alea-Iacta-Est', $breadcrumb->getTitle());
        $breadcrumb->setPath('Harz');
        $this->assertEquals('Harz', $breadcrumb->getPath());
    }
}
