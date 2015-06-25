<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Model\User;

use TwoMartens\Bundle\CoreBundle\Model\User\User;

/**
 * Tests the User class.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the constructor
     */
    public function testConstruct()
    {
        $user = new User();
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertFalse($user->isEnabled());
        $this->assertTrue($user->isAccountNonExpired());
        $this->assertTrue($user->isAccountNonLocked());
        $this->assertTrue($user->isCredentialsNonExpired());
    }

    /**
     * Tests the status change methods.
     */
    public function testStatusChange()
    {
        $user = new User();
        // test enabled
        $this->assertFalse($user->isEnabled());
        $user->setEnabled(true);
        $this->assertTrue($user->isEnabled());
        $user->setEnabled(false);
        $this->assertFalse($user->isEnabled());

        // test account expired
        $this->assertTrue($user->isAccountNonExpired());
        $user->setExpired(true);
        $this->assertFalse($user->isAccountNonExpired());

        // test account locked
        $this->assertTrue($user->isAccountNonLocked());
        $user->setLocked(true);
        $this->assertFalse($user->isAccountNonLocked());
        $user->setLocked(false);
        $this->assertTrue($user->isAccountNonLocked());

        // test credentials expired
        $this->assertTrue($user->isCredentialsNonExpired());
        $user->setCredentialsExpired(true);
        $this->assertFalse($user->isCredentialsNonExpired());
    }

    /**
     * Tests the setters.
     */
    public function testSetters()
    {
        $user = new User();
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        // set username
        $user->setUsername('Joanne');
        $this->assertEquals('Joanne', $user->getUsername());
        // set pw and salt
        $user->setPassword('hostile');
        $this->assertEquals('hostile', $user->getPassword());
        $this->assertEquals(null, $user->getSalt());
        // set email
        $user->setEmail('test2@localhost');
        $this->assertEquals('test2@localhost', $user->getEmail());
        // set roles
        $roles = [
            'ROLE_ACP_ACCESS',
            'ROLE_USER',
        ];
        $user->setRoles($roles);
        $this->assertEquals($roles, $user->getRoles());
    }
}
