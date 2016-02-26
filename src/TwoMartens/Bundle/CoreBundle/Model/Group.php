<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\Group as FOSGroup;

/**
 * Represents a group.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class Group extends FOSGroup
{
    /**
     * the role compatible name
     * @var string
     */
    protected $roleName;

    /**
     * the public name of the group
     * @var string
     */
    protected $name;

    /**
     * True, if this group is essential
     * @var bool
     */
    protected $isEssential;

    /**
     * True, if the group can have zero users.
     * @var bool
     */
    protected $canBeEmpty;

    /**
     * the option category for frontend user options
     * @var OptionCategory
     */
    protected $frontendUserCategory;

    /**
     * the option category for frontend mod options
     * @var OptionCategory
     */
    protected $frontendModCategory;

    /**
     * the option category for ACP options
     * @var OptionCategory
     */
    protected $acpCategory;

    /**
     * the list of users
     * @var Collection
     */
    protected $users;

    /**
     * Initializes the Group object.
     *
     * @param int            $id                   the ID
     * @param string         $roleName             only letters and underscore
     * @param string         $publicName           publicly visible name
     * @param bool           $isEssential          true, if the group is essential
     * @param bool           $canBeEmpty           true, if the group may be empty
     * @param string[]       $roles                array of roles granted by this group
     * @param OptionCategory $frontendUserCategory the frontend user category
     * @param OptionCategory $frontendModCategory  the frontend mod category
     * @param OptionCategory $acpCategory          the ACP category
     */
    public function __construct(
        $id,
        $roleName,
        $publicName,
        $isEssential,
        $canBeEmpty,
        array $roles,
        OptionCategory $frontendUserCategory,
        OptionCategory $frontendModCategory,
        OptionCategory $acpCategory
    ) {
        parent::__construct($publicName, $roles);
        $this->id = $id;
        $this->roleName = strtoupper($roleName);
        $this->isEssential = $isEssential;
        $this->canBeEmpty = $canBeEmpty;
        $this->frontendUserCategory = $frontendUserCategory;
        $this->frontendModCategory = $frontendModCategory;
        $this->acpCategory = $acpCategory;
        $this->users = new ArrayCollection();
    }

    /**
     * Returns the role compatible name of the group.
     *
     * The role compatible name will be used for the group role.
     * For example the role name is ADMIN. The resulting group
     * role will be ROLE_ADMIN.
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * Sets the role name.
     *
     * The role name may only contain letters and underscores. It is
     * in upper case.
     *
     * @param string $roleName
     */
    public function setRoleName($roleName)
    {
        $this->roleName = strtoupper($roleName);
    }

    /**
     * Returns the public name.
     *
     * The public name is the visible name of the group. This can also
     * be an identifier, which is translated to its representation.
     *
     * @return string
     */
    public function getPublicName()
    {
        return $this->name;
    }

    /**
     * Sets the public name.
     *
     * @param string $name
     */
    public function setPublicName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns true, if the group is essential.
     *
     * An essential group is required for the functionality
     * of the web platform and cannot be deleted.
     *
     * @return bool
     */
    public function isEssential()
    {
        return $this->isEssential;
    }

    /**
     * Returns true, if the group can be empty.
     *
     * If a group MUST not be empty, the last user of it cannot
     * leave it (via whatever way). This is useful for admin
     * groups, since the admin should not be able to exclude
     * himself from the ACP.
     *
     * @return bool
     */
    public function canBeEmpty()
    {
        return $this->canBeEmpty;
    }

    /**
     * Returns the frontend user category.
     *
     * @return OptionCategory
     */
    public function getFrontendUserCategory()
    {
        return $this->frontendUserCategory;
    }

    /**
     * Sets the frontend user category.
     *
     * @param OptionCategory $category
     */
    public function setFrontendUserCategory(OptionCategory $category)
    {
        $this->frontendUserCategory = $category;
    }

    /**
     * Returns the frontend mod category.
     *
     * @return OptionCategory
     */
    public function getFrontendModCategory()
    {
        return $this->frontendModCategory;
    }

    /**
     * Sets the frontend mod category.
     *
     * @param OptionCategory $category
     */
    public function setFrontendModCategory(OptionCategory $category)
    {
        $this->frontendModCategory = $category;
    }

    /**
     * Returns the ACP category.
     *
     * @return OptionCategory
     */
    public function getACPCategory()
    {
        return $this->acpCategory;
    }

    /**
     * Sets the ACP category.
     *
     * @param OptionCategory $category
     */
    public function setACPCategory(OptionCategory $category)
    {
        $this->acpCategory = $category;
    }

    /**
     * Returns the users.
     *
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Sets the users.
     *
     * @param Collection $users
     */
    public function setUsers(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * Adds given user to group.
     *
     * @param User $user
     */
    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }

    /**
     * Removes given user from group.
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
    }
}
