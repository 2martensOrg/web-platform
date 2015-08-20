<?php
/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Option;

use TwoMartens\Bundle\CoreBundle\Model\Option;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;

/**
 * Defines the API of the OptionService.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
interface OptionServiceInterface
{
    /**
     * Returns the top level option category.
     *
     * @api
     *
     * @return OptionCategory
     */
    public function getOptions();

    /**
     * Sets the options.
     *
     * @param OptionCategory $options
     *
     * @api
     *
     * @return void
     */
    public function setOptions(OptionCategory $options);

    /**
     * Returns the option belonging to the given option category and name.
     *
     * @param string $optionCategory
     * @param string $optionName
     *
     * @api
     *
     * @return Option
     */
    public function get($optionCategory, $optionName);

    /**
     * Sets the option with the given name in the given category to the given value.
     *
     * @param string $optionCategory
     * @param string $optionName
     * @param Option $value
     *
     * @api
     *
     * @return void
     */
    public function set($optionCategory, $optionName, Option $value);
}
