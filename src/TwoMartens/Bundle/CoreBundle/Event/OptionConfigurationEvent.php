<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Event object used for the option configuration form.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OptionConfigurationEvent extends Event
{
    /**
     * the form itself
     * @var FormBuilderInterface
     */
    private $builder;

    /**
     * the data provided by the form
     * @var mixed
     */
    private $data;

    /**
     * Initializes the event.
     *
     * @param FormBuilderInterface $builder
     * @param mixed                $data
     */
    public function __construct(FormBuilderInterface $builder, $data)
    {
        $this->builder = $builder;
        $this->data = $data;
    }

    /**
     * Returns the builder.
     *
     * @return FormBuilderInterface
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Returns the provided data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
