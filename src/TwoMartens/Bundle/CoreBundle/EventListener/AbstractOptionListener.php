<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\EventListener;

use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Translation\TranslatorInterface;
use TwoMartens\Bundle\CoreBundle\Event\OptionConfigurationEvent;
use TwoMartens\Bundle\CoreBundle\Model\Option\OptionCategory;

/**
 * Abstract class for option listeners.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
abstract class AbstractOptionListener
{
    /**
     * the translator
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Initializes the option listener.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param OptionConfigurationEvent $event
     */
    public function onBuildForm(OptionConfigurationEvent $event)
    {
        $builder = $event->getBuilder();
        /** @var OptionCategory $data */
        $data = $event->getData();

        $categories = $data->getCategories();
        $ourCategory = null;
        foreach ($categories as $category) {
            $name = $category->getName();
            if ($name == $this->getCategoryName()) {
                $ourCategory = $category;
                break;
            }
        }

        if ($ourCategory !== null) {
            $options = $ourCategory->getOptions();
            $categoryName = $ourCategory->getName();
            $fieldMap = $this->getFieldMap();
            $multipleMap = $this->getMultipleMap();
            $choicesMap = $this->getChoiceListMap();
            foreach ($options as $option) {
                $optionName = $option->getName();
                $fieldType = $fieldMap[$optionName];
                $settings = [
                    'label' => $this->translator->trans(
                        'acp.options.' . $categoryName . '.' . $optionName . '.label',
                        [],
                        $this->getDomain()
                    ),
                    'mapped' => false,
                    'required' => false,
                    'data' => $option->getValue()
                ];
                if ($fieldType == 'choice') {
                    $settings['multiple'] = $multipleMap[$optionName];
                    $settings['choice_list'] = $choicesMap[$optionName];
                    $settings['placeholder'] = false;
                }
                $builder->add(
                    str_replace('.', '_', $categoryName) . '_' . $optionName,
                    $fieldType,
                    $settings
                );
            }
        }
    }

    /**
     * Returns the name of the category this listener is responsible for.
     * @return string
     */
    abstract protected function getCategoryName();

    /**
     * Returns the field map.
     *
     * The field map maps the option name to the form field type.
     *
     * @return string[]
     */
    abstract protected function getFieldMap();

    /**
     * Returns the multiple map.
     *
     * The multiple map maps the option name to the value of the multiple
     * setting. Only relevant for choice fields.
     *
     * @return boolean[]
     */
    abstract protected function getMultipleMap();

    /**
     * Returns the choice list map.
     *
     * The choice list map maps the option name to the corresponding
     * choice list. Only relevant for choice fields.
     *
     * @return ChoiceListInterface[]
     */
    abstract protected function getChoiceListMap();

    /**
     * Returns the translation domain.
     *
     * @return string
     */
    abstract protected function getDomain();
}
