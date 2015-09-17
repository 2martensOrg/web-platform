<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event object used for overview pages.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OverviewEvent extends Event
{
    /**
     * list of entries
     * @var array
     */
    private $entries;

    /**
     * list of categories
     * @var array
     */
    private $categories;

    /**
     * Initializes the event.
     */
    public function __construct()
    {
        $this->entries = [];
        $this->categories = [];
    }

    /**
     * Returns list of entries.
     *
     * The list contains arrays, each of which has three keys:
     * 'entryName', 'translationDomain', 'route'
     *
     * @return array[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * Returns the category info.
     *
     * Keys are category names and values are translation domains.
     *
     * @return string[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Adds an entry.
     *
     * @param string $categoryName
     * @param string $entryName
     * @param string $translationDomain
     * @param string $route
     */
    public function addEntry($categoryName, $entryName, $translationDomain, $route)
    {
        if (!isset($this->entries[$categoryName])) {
            $this->addCategory($categoryName, $translationDomain);
        }
        $this->entries[$categoryName][] = [
            'entryName' => $entryName,
            'translationDomain' => $translationDomain,
            'route' => $route
        ];
    }

    /**
     * Adds a category.
     *
     * @param string $categoryName
     * @param string $translationDomain
     */
    public function addCategory($categoryName, $translationDomain)
    {
        if (!isset($this->entries[$categoryName])) {
            $this->entries[$categoryName] = [];
        }
        if (!isset($this->categories[$categoryName])) {
            $this->categories[$categoryName] = $translationDomain;
        }
    }
}
