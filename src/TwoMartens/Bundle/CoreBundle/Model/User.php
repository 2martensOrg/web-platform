<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as FOSUser;

/**
 * Represents a user.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class User extends FOSUser
{
    /**
     * the salt for the password
     * @var string|null
     */
    protected $salt;

    /**
     * Initializes the User object.
     */
    public function __construct()
    {
        // saves salt into user object
        // if bcrypt is used (default), salt should be null
        parent::__construct();

        $this->salt = null;
        // our users are always in groups
        $this->groups = new ArrayCollection();
    }
}
