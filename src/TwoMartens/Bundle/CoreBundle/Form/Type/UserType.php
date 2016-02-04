<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Form\Type;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwoMartens\Bundle\CoreBundle\Model\Group;
use TwoMartens\Bundle\CoreBundle\Model\User;

/**
 * Represents the user add/edit form.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class UserType extends AbstractType
{
    private $isAddMode;
    private $groups;

    /**
     * UserType constructor.
     *
     * @param Collection $groups
     * @param bool       $isAddMode
     */
    public function __construct(Collection $groups, $isAddMode)
    {
        $this->groups = $groups;
        $this->isAddMode = $isAddMode;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['data'];

        $builder->add(
            'username',
            TextType::class,
            [
                'label' => 'acp.user.username',
                'mapped' => true,
                'required' => true,
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );
        $builder->add(
            'email',
            TextType::class,
            [
                'label' => 'acp.user.email',
                'mapped' => true,
                'required' => true,
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'invalid_message' => 'acp.user.password.error.invalid',
                'first_options' => [
                    'label' => 'acp.user.password',
                ],
                'second_options' => [
                    'label' => 'acp.user.password_confirm',
                ],
                'mapped' => true,
                'required' => $this->isAddMode,
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );

        $choices = [];
        $selected = [];
        $userGroups = $user->getGroups();
        foreach ($this->groups as $group) {
            /** @var Group $group */
            $choices[$group->getPublicName()] = $group->getRoleName();
            if ($userGroups->contains($group)) {
                $selected[] = $group->getRoleName();
            }
        }

        $builder->add(
            'groups',
            ChoiceType::class,
            [
                'label' => 'acp.user.groups',
                'mapped' => false,
                'required' => true,
                'expanded' => true,
                'multiple' => true,
                'translation_domain' => 'TwoMartensCoreBundle',
                'choices_as_values' => true,
                'choices' => $choices,
                'data' => $selected
            ]
        );

        $builder->add(
            'save',
            SubmitType::class,
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
        return 'user';
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
}
