<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Form\Type;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use TwoMartens\Bundle\CoreBundle\Event\OptionConfigurationEvent;

/**
 * Represents the option configuration form.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class OptionConfigurationType extends AbstractType
{
    /**
     * the event dispatcher
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Initializes the form.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->dispatcher->dispatch(
            'twomartens.core.option_configuration.build_form',
            new OptionConfigurationEvent($builder, $options['data'])
        );
        $builder->add('save', 'submit');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'option_configuration';
    }
}
