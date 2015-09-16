<?php
/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Group;

use TwoMartens\Bundle\CoreBundle\Model\Option;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;
use TwoMartens\Bundle\CoreBundle\Model\User;

/**
 * Interface for the GroupService.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
interface GroupServiceInterface
{
    /**
     * Returns the options for the group with the given role name.
     *
     * @param $groupRoleName
     *
     * @api
     *
     * @return OptionCategory
     */
    public function getOptionsFor($groupRoleName);

    /**
     * Sets the options for the group with the given name to the given value.
     *
     * @param string         $groupRoleName
     * @param OptionCategory $options
     *
     * @api
     *
     * @return void
     */
    public function setOptionsFor($groupRoleName, OptionCategory $options);

    /**
     * Returns the Option for given group, category and option name.
     *
     * @param string $groupRoleName
     * @param string $superCategory acp, mod, frontend
     * @param string $category
     * @param string $optionName
     *
     * @api
     *
     * @return Option|null null if there is no valid value
     */
    public function get($groupRoleName, $superCategory, $category, $optionName);

    /**
     * Sets the given option for given group and category.
     * 
     * @param string $groupRoleName
     * @param string $superCategory acp, mod, frontend
     * @param string $category
     * @param Option $value
     *
     * @api
     *
     * @return void
     */
    public function set($groupRoleName, $superCategory, $category, Option $value);

    /**
     * Returns the effective value of the given option for the given user.
     *
     * @param User   $user
     * @param string $superCategory acp, mod, frontend
     * @param string $category
     * @param string $optionName
     *
     * @api
     *
     * @return Option|null null if there is no valid value
     */
    public function getEffective(User $user, $superCategory, $category, $optionName);

    /**
     * Commits the changes to the file system.
     *
     * @api
     *
     * @return void
     */
    public function commitChanges();

    /**
     * Removes the options for the given group.
     *
     * @param string $groupRoleName
     *
     * @api
     *
     * @return void
     */
    public function removeOptionsFor($groupRoleName);
}
