<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Model;

use Doctrine\Common\Collections\ArrayCollection;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;
use TwoMartens\Bundle\CoreBundle\Model\Group;

/**
 * Tests the Group class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class GroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the constructor.
     */
    public function testConstructor()
    {
        $frontendUserCategory = new OptionCategory('twomartens.core.group.category.frontendUser');
        $frontendModCategory = new OptionCategory('twomartens.core.group.category.frontendMod');
        $acpCategory = new OptionCategory('twomartens.core.group.category.acp');
        $group = new Group(
            0,
            'ADMIN',
            'twomartens.core.group.admin',
            true,
            false,
            [],
            $frontendUserCategory,
            $frontendModCategory,
            $acpCategory
        );

        $this->assertEquals(0, $group->getId());
        $this->assertEquals('ADMIN', $group->getRoleName());
        $this->assertEquals('twomartens.core.group.admin', $group->getPublicName());
        $this->assertTrue($group->isEssential());
        $this->assertFalse($group->canBeEmpty());
        $this->assertEquals($frontendUserCategory, $group->getFrontendUserCategory());
        $this->assertEquals($frontendModCategory, $group->getFrontendModCategory());
        $this->assertEquals($acpCategory, $group->getACPCategory());
        $this->assertTrue($group->getUsers()->isEmpty());
    }

    /**
     * Tests the setters.
     */
    public function testSetters()
    {
        $frontendUserCategory = new OptionCategory('twomartens.core.group.category.frontendUser');
        $frontendModCategory = new OptionCategory('twomartens.core.group.category.frontendMod');
        $acpCategory = new OptionCategory('twomartens.core.group.category.acp');
        $group = new Group(
            0,
            'ADMIN',
            'twomartens.core.group.admin',
            true,
            false,
            [],
            $frontendUserCategory,
            $frontendModCategory,
            $acpCategory
        );
        // test public name
        $group->setPublicName('Administrators');
        $this->assertEquals('Administrators', $group->getPublicName());
        // test role name
        $group->setRoleName('SUPER_ADMIN');
        $this->assertEquals('SUPER_ADMIN', $group->getRoleName());
        // test users
        $user = $this->getMock('\TwoMartens\Bundle\CoreBundle\Model\User');
        $users = new ArrayCollection([$user]);
        $group->setUsers($users);
        $this->assertEquals($users, $group->getUsers());
    }
}
