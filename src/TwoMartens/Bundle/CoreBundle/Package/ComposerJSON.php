<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Package;

/**
 * Represents a composer JSON file.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class ComposerJSON
{
    /**
     * the file name for the composer json file
     *
     * @var string
     */
    private $composerFileName;

    /**
     * the composer name of a package
     *
     * @var string
     */
    private $name;

    /**
     * the description of a package
     *
     * @var string
     */
    private $description;

    /**
     * the list of authors
     *
     * @var string[]
     */
    private $authors;

    /**
     * the website of the package
     *
     * @var string
     */
    private $website;

    /**
     * the license of the package
     * @var string
     */
    private $license;

    /**
     * ComposerJSON constructor.
     *
     * @param string $composerJSONDirectory
     */
    public function __construct($composerJSONDirectory)
    {
        $this->composerFileName = $composerJSONDirectory.'composer.json';
        $this->name = $this->description = $this->website = $this->license = '';
        $this->authors = [];
        $this->parseFile();
    }

    /**
     * Returns the composer name.
     *
     * @return string
     */
    public function getComposerName()
    {
        return $this->name;
    }

    /**
     * Returns the primary author.
     *
     * @return string
     */
    public function getPrimaryAuthor()
    {
        return $this->authors[0];
    }

    /**
     * Returns the description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the website.
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Returns the license.
     *
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Parses the composer JSON file.
     */
    private function parseFile()
    {
        $decodedJSON = json_decode(file_get_contents($this->composerFileName), true);
        $this->name = $decodedJSON['name'] ?: '';
        $this->description = $decodedJSON['description'] ?: '';
        $this->license = $decodedJSON['license'] ?: '';
        $this->website = $decodedJSON['homepage'] ?: '';
        foreach ($decodedJSON['authors'] as $author) {
            $this->authors[] = $author['name'];
        }
    }
}
