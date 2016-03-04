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
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwoMartens\Bundle\CoreBundle\Event\FormEvent;
use TwoMartens\Bundle\CoreBundle\Model\Group;

/**
 * Represents the group add/edit form.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class GroupType extends AbstractType
{
    /**
     * the event dispatcher
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * true, if edit form
     * @var boolean
     */
    private $editForm;

    /**
     * Initializes the form.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param boolean                  $editForm
     */
    public function __construct(EventDispatcherInterface $dispatcher, $editForm = true)
    {
        $this->dispatcher = $dispatcher;
        $this->editForm = $editForm;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $acpOptionsBuilder = clone $builder;
        $modOptionsBuilder = clone $builder;
        $userOptionsBuilder = clone $builder;

        /** @var Group $group */
        $group = $options['data'];
        $acpCategory = $group->getACPCategory();
        $modCategory = $group->getFrontendModCategory();
        $userCategory = $group->getFrontendUserCategory();

        // events are only responsible for options
        $this->dispatcher->dispatch(
            'twomartens.core.group_type.acp_options',
            new FormEvent($acpOptionsBuilder, $acpCategory)
        );
        $this->dispatcher->dispatch(
            'twomartens.core.group_type.mod_options',
            new FormEvent($modOptionsBuilder, $modCategory)
        );
        $this->dispatcher->dispatch(
            'twomartens.core.group_type.user_options',
            new FormEvent($userOptionsBuilder, $userCategory)
        );

        $builder->add(
            'name',
            'text',
            [
                'label' => 'acp.group.name',
                'mapped' => true,
                'required' => true,
                'data' => $group->getPublicName(),
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );
        $builder->add(
            'roleName',
            'text',
            [
                'label' => 'acp.group.roleName',
                'mapped' => true,
                'required' => true,
                'read_only' => $this->editForm,
                'data' => $group->getRoleName(),
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );

        $this->addWithPrefix($acpOptionsBuilder, $builder, 'acp_');
        $this->addWithPrefix($modOptionsBuilder, $builder, 'mod_');
        $this->addWithPrefix($userOptionsBuilder, $builder, 'frontend_');

        $builder->add(
            'save',
            'submit',
            [
                'label' => 'button.save',
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Registration'],
        ]);
    }

    /**
     * Adds elements of $source to $target with $prefix as prefix for field names.
     *
     * @param FormBuilderInterface $source
     * @param FormBuilderInterface $target
     * @param string               $prefix
     */
    private function addWithPrefix(
        FormBuilderInterface $source,
        FormBuilderInterface $target,
        $prefix
    ) {
        /** @var FormBuilderInterface $field */
        foreach ($source as $field) {
            $newName = $prefix . $field->getName();
            $target->add(
                $newName,
                $field->getType()->getInnerType(),
                $field->getOptions()
            );
        }
    }
}
