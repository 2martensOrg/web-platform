<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Model;

/**
 * Represents a package.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class Package
{
    /**
     * the package name
     *
     * @var string
     */
    private $name;

    /**
     * the package description
     *
     * @var string
     */
    private $description;

    /**
     * the version string in SemVer format
     *
     * @var string
     */
    private $version;

    /**
     * the author of the package
     *
     * @var string
     */
    private $author;

    /**
     * the website for this package
     *
     * @var string
     */
    private $website;

    /**
     * the unique composer name
     *
     * @var string
     */
    private $composerName;

    /**
     * Initializes the Package object.
     *
     * @param string $composerName
     */
    public function __construct($composerName)
    {
        $this->name         = '';
        $this->description  = '';
        $this->version      = '';
        $this->author       = '';
        $this->website      = '';
        $this->composerName = $composerName;
    }

    /**
     * Returns the name of the package.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the package.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the package description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the package description.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the package version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the package version.
     *
     * Must be compatible with semantic versioning.
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Returns the package author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the package author.
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Returns the package website.
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Sets the package website.
     *
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * Returns the composer name.
     *
     * This is the name used by Composer.
     *
     * @return string
     */
    public function getComposerName()
    {
        return $this->composerName;
    }

    /**
     * Sets the composer name.
     *
     * @param string $composerName
     */
    public function setComposerName($composerName)
    {
        $this->composerName = $composerName;
    }
}
