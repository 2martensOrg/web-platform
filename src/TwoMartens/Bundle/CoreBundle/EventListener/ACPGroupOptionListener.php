<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\EventListener;

use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Provides the acp options for the group add and edit forms.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ACPGroupOptionListener extends AbstractGroupOptionListener
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
     * {@inheritdoc}
     */
    public function __construct()
    {
        // TODO add real options as they are determined
        $this->fieldMap = [
            'access'        => CheckboxType::class,
            'group_add'     => CheckboxType::class,
            'group_list'    => CheckboxType::class,
            'group_edit'    => CheckboxType::class,
            'group_delete'  => CheckboxType::class,
            'user_add'      => CheckboxType::class,
            'user_list'     => CheckboxType::class,
            'user_edit'     => CheckboxType::class,
            'user_delete'   => CheckboxType::class
        ];
        $this->multipleMap = [];
        $this->choicesMap = [];
    }

    /**
     * {@inheritdoc}
     */
    protected function getLabelPrefix()
    {
        return parent::getLabelPrefix().'.acp';
    }

    /**
     * Returns the name of the category this listener is responsible for.
     *
     * @return string
     */
    protected function getCategoryName()
    {
        return 'twomartens.core';
    }

    /**
     * Returns the field map.
     *
     * The field map maps the option name to the form field type.
     *
     * @return string[]
     */
    protected function getFieldMap()
    {
        return $this->fieldMap;
    }

    /**
     * Returns the multiple map.
     *
     * The multiple map maps the option name to the value of the multiple
     * setting. Only relevant for choice fields.
     *
     * @return boolean[]
     */
    protected function getMultipleMap()
    {
        $this->multipleMap;
    }

    /**
     * Returns the choice list map.
     *
     * The choice list map maps the option name to the corresponding
     * choice list. Only relevant for choice fields.
     *
     * @return ChoiceListInterface[]
     */
    protected function getChoiceListMap()
    {
        return $this->choicesMap;
    }

    /**
     * Returns the translation domain.
     *
     * @return string
     */
    protected function getDomain()
    {
        return 'TwoMartensCoreBundle';
    }
}
