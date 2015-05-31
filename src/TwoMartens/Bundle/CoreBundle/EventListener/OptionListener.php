<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\EventListener;

use Symfony\Component\Form\ChoiceList\ChoiceListInterface;

/**
 * This listener adds the form fields for the Core bundle options.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OptionListener extends AbstractOptionListener
{
    /**
     * Maps option name to form field type.
     * @var string[]
     */
    private $fieldMap;

    /**
     * Maps option name to multiple setting.
     * @var boolean[]
     */
    private $multipleMap;

    /**
     * Maps option name to choice list.
     * @var ChoiceListInterface[]
     */
    private $choicesMap;

    /**
     * Initializes the event listener.
     */
    public function __construct()
    {
        // TODO add real options as they are determined
        $this->fieldMap = [
            'testOption' => 'checkbox'
        ];
        $this->multipleMap = [];
        $this->choicesMap = [];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCategoryName()
    {
        return 'twomartens.core';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFieldMap()
    {
        return $this->fieldMap;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMultipleMap()
    {
        return $this->multipleMap;
    }

    /**
     * {@inheritdoc}
     */
    protected function getChoiceListMap()
    {
        return $this->choicesMap;
    }
}
