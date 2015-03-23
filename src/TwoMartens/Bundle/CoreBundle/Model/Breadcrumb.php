<?php
/*
 * This file is part of the 2martens Web Platform.
 *
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Model;

/**
 * Represents a breadcrumb.
 *
 * @author Jim Martens <github@2martens.de>
 */
class Breadcrumb
{
    /**
     * Route for the breadcrumb.
     * @var string
     */
    private $path;

    /**
     * True, if active.
     * @var boolean
     */
    private $active;

    /**
     * Title of the breadcrumb.
     * @var string
     */
    private $title;

    /**
     * Constructor.
     *
     * The breadcrumb is deactivated after creation.
     *
     * @param string $path
     * @param string $title
     */
    public function __construct($path, $title)
    {
        $this->path = trim($path);
        $this->title = trim($title);
        $this->active = false;
    }

    /**
     * Returns true, if this breadcrumb is active.
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the title to the parameter.
     *
     * @param string $newTitle
     */
    public function setTitle($newTitle)
    {
        $this->title = trim($newTitle);
    }

    /**
     * Sets the path to the parameter.
     *
     * @param string $newPath
     */
    public function setPath($newPath)
    {
        $this->path = trim($newPath);
    }

    /**
     * Activate the breadcrumb.
     */
    public function activate()
    {
        $this->active = true;
    }

    /**
     * Deactivates the breadcrumb.
     */
    public function deactivate()
    {
        $this->active = false;
    }
}
