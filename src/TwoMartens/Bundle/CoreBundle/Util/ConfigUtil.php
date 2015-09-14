<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Util;

use TwoMartens\Bundle\CoreBundle\Model\Option;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;

/**
 * Provides utility methods for config-related things.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ConfigUtil
{
    /**
     * Converts an array of config values into their option representation.
     *
     * @param array $configData
     *
     * @return OptionCategory
     */
    public static function convertToOptions($configData)
    {
        $returnData = new OptionCategory();
        $categories = [];

        foreach ($configData as $category => $values) {
            $optionCategory = new OptionCategory($category);
            $subCategory = self::convertValues($category, $values);
            $subOptions = $subCategory->getOptions();
            $subCategories = $subCategory->getCategories();
            $optionCategory->setOptions($subOptions);
            $optionCategory->setCategories($subCategories);
            $categories[] = $optionCategory;
        }
        $returnData->setCategories($categories);

        return $returnData;
    }

    /**
     * Converts the given name and value into an Option.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return Option
     */
    public static function convertToOption($name, $value)
    {
        $type = str_replace('double', 'float', gettype($value));
        $type = str_replace('NULL', 'null', $type);
        $option = new Option(
            0,
            $name,
            $type,
            $value
        );

        return $option;
    }

    /**
     * Converts a top level category into the YAML representation.
     *
     * @param OptionCategory $optionCategory
     *
     * @return array
     */
    public static function convertToArray(OptionCategory $optionCategory)
    {
        $result = [];
        $categories = $optionCategory->getCategories();

        foreach ($categories as $category) {
            $options = $category->getOptions();
            $_categories = $category->getCategories();

            $optionsArray = self::getOptionsArray($options);

            foreach ($_categories as $_category) {
                $_options = $_category->getOptions();
                $_optionsArray = self::getOptionsArray($_options);
                $optionsArray[$_category->getName()] = $_optionsArray;
            }

            $result[$category->getName()] = $optionsArray;
        }

        return $result;
    }

    /**
     * Returns an array of options.
     *
     * @param Option[] $options
     *
     * @return array
     */
    private static function getOptionsArray($options)
    {
        $optionsArray = [];
        foreach ($options as $option) {
            $optionsArray[$option->getName()] = $option->getValue();
        }

        return $optionsArray;
    }

    /**
     * Converts the given values into options.
     *
     * @param string $key
     * @param array  $values
     *
     * @return OptionCategory
     */
    private static function convertValues($key, $values)
    {
        $returnCategory = new OptionCategory();

        if (self::isAssoc($values)) {
            $options = [];
            $categories = [];
            foreach ($values as $_key => $value) {
                // case: value = [...]
                if (is_array($value)) {
                    // recursive call
                    $subCategory = self::convertValues($_key, $value);
                    $subOptions = $subCategory->getOptions();
                    $subCategories = $subCategory->getCategories();

                    // case: value = [a, b, c]
                    if (count($subOptions) == 1 && empty($subCategories) && !self::isAssoc($value)) {
                        $options[] = $subOptions[0];
                    // case: value = [[a: b], [c: d, e: f]] || [a: b, c: d, e: [...]]
                    } else {
                        $category = new OptionCategory($_key);
                        $category->setOptions($subOptions);
                        $category->setCategories($subCategories);
                        $categories[] = $category;
                    }
                // case: value = b
                } else {
                    $type = str_replace('double', 'float', gettype($value));
                    $type = str_replace('NULL', 'null', $type);
                    $options[] = new Option(
                        0,
                        $_key,
                        $type,
                        $value
                    );
                }
            }
            $returnCategory->setOptions($options);
            $returnCategory->setCategories($categories);
        } else {
            $options = [];
            $_values = [];
            foreach ($values as $_key => $value) {
                if (is_array($value)) {
                    $subCategory = self::convertValues($_key, $value);
                    $subOptions = $subCategory->getOptions();
                    $subCategories = $subCategory->getCategories();
                    // case: value = [a: b]
                    if (count($subOptions) == 1 && empty($subCategories)) {
                        $option = $subOptions[0];
                        $options[] = $option;
                    // case: value = [a: b, d: e]
                    } elseif (count($subOptions) > 1) {
                        $option = new Option(0, '', 'array');
                        $optionValues = [];
                        foreach ($subOptions as $subOption) {
                            $optionValues[$subOption->getName()] = $subOption->getValue();
                        }
                        $option->setValue($optionValues);
                        $options[] = $option;
                    }
                } else {
                    $_values[] = $value;
                }
            }
            // case: values = [a, b, c, d]
            if (!empty($_values)) {
                $options[] = new Option(0, $key, 'array', $_values);
            }
            $returnCategory->setOptions($options);
        }

        return $returnCategory;
    }

    /**
     * Checks, if the array is associative or not.
     *
     * @param array $array
     *
     * @return bool
     */
    private static function isAssoc($array)
    {
        return (bool) count(array_filter(array_keys($array), 'is_string'));
    }
}
