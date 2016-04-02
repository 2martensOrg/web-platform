<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use TwoMartens\Bundle\CoreBundle\Model\Package;

/**
 * Represents the package install form.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class PackageInstallType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Package $package */
        $package = $options['data'];

        $builder->add(
            'composerName',
            TextType::class,
            [
                'label' => 'acp.package.composerName',
                'mapped' => true,
                'required' => true,
                'data' => $package->getComposerName(),
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );
        $builder->add(
            'version',
            TextType::class,
            [
                'label' => 'acp.package.version',
                'mapped' => true,
                'required' => true,
                'data' => $package->getVersion(),
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );

        $builder->add(
            'install',
            SubmitType::class,
            [
                'label' => 'button.install',
                'translation_domain' => 'TwoMartensCoreBundle'
            ]
        );
    }
}
