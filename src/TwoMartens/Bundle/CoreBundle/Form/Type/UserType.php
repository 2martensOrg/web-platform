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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
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
    private $disabledGroups;
    private $data;

    /**
     * the translator
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * UserType constructor.
     *
     * @param Collection          $groups
     * @param TranslatorInterface $translator
     * @param bool                $isAddMode
     */
    public function __construct(Collection $groups, TranslatorInterface $translator, $isAddMode)
    {
        $this->groups = $groups;
        $this->isAddMode = $isAddMode;
        $this->translator = $translator;
        $this->disabledGroups = [];
        $this->data = [];
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
        $userGroups = $user->getGroups();
        foreach ($this->groups as $group) {
            /** @var Group $group */
            $translatedName = $this->translator->trans(
                $group->getPublicName(),
                [],
                'TwoMartensGroups'
            );
            $choices[$translatedName] = $group->getRoleName();
            if ($userGroups->contains($group)) {
                $this->data[] = $group->getRoleName();
                if (!$group->canBeEmpty() && $group->getUsers()->count() <= 1) {
                    $this->disabledGroups[] = $group->getRoleName();
                }
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
                'data' => $this->data
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
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view->children['groups']->children as $group) {
            $groupRole = $this->data[$group->vars['value']];
            if (in_array($groupRole, $this->disabledGroups, true)) {
                $group->vars['attr']['disabled'] = 'disabled';
            }
        }
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
