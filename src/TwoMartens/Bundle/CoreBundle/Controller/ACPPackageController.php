<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TwoMartens\Bundle\CoreBundle\Form\Type\PackageInstallType;
use TwoMartens\Bundle\CoreBundle\Model\Breadcrumb;
use TwoMartens\Bundle\CoreBundle\Model\Package;
use TwoMartens\Bundle\CoreBundle\Package\PackageServiceInterface;

/**
 * Manages the routes for the package system.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class ACPPackageController extends AbstractACPController
{
    /**
     * the current action.
     *
     * @var string
     */
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->action = '';
    }

    /**
     * Shows the install package form.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function installAction(Request $request)
    {
        $this->action = 'install';

        $this->denyAccessUnlessGranted('ROLE_ACP_TWOMARTENS.CORE_PACKAGE_INSTALL');

        $package = new Package(
            null, // no id known yet
            '', // no name yet
            '', // no description yet
            '', // no version yet
            '', // no author yet
            '', // no website yet
            ''  // no composer name yet
        );
        
        $form = $this->createForm(
            PackageInstallType::class,
            $package
        );
        
        $form->handleRequest($request);
        $this->assignVariables();
        $this->templateVariables['form'] = $form->createView();
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.package.install', [], 'TwoMartensCoreBundle');

        if ($form->isValid()) {
            /** @var PackageServiceInterface $packageService */
            $packageService = $this->get('twomartens.core.package');
            $packageService->installPackage($package->getComposerName(), $package->getVersion());
            /** @var ObjectManager $objectManager */
            $objectManager = $this->get('twomartens.core.db_manager');
            $objectManager->persist($package);
            $objectManager->flush();
            return $this->listAction();
        }

        return $this->render(
            'TwoMartensCoreBundle:ACPPackage:install.html.twig',
            $this->templateVariables
        );
    }

    /**
     * Shows the package list.
     *
     * @return Response
     */
    public function listAction()
    {
        $this->action = 'list';

        /** @var ObjectManager $objectManager */
        $objectManager = $this->get('twomartens.core.db_manager');
        $repository = $objectManager->getRepository('TwoMartensCoreBundle:Package');
        $packages = $repository->findAll();

        $this->assignVariables();
        $this->templateVariables['area']['title'] = $this->get('translator')
            ->trans('acp.package.list', [], 'TwoMartensCoreBundle');
        $this->templateVariables['packages'] = $packages;

        return $this->render(
            'TwoMartensCoreBundle:ACPPackage:list.html.twig',
            $this->templateVariables
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function setBreadcrumbs()
    {
        $systemBreadcrumb = new Breadcrumb(
            'acp.system',
            $this->get('translator')->trans('acp.breadcrumb.system', [], 'TwoMartensCoreBundle')
        );
        $active = new Breadcrumb(
            'acp.system.package.'.$this->action,
            $this->get('translator')->trans('acp.breadcrumb.package.'.$this->action, [], 'TwoMartensCoreBundle')
        );
        $active->activate();
        $this->breadcrumbs = [
            $systemBreadcrumb,
            $active,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function assignVariables()
    {
        $this->templateVariables = [
            'area' => [
                'showBreadcrumbs' => true
            ],
            'siteTitle' => $this->get('translator')->trans(
                'acp.siteTitle',
                ['globalTitle' => 'CoreBundle Test'],
                'TwoMartensCoreBundle'
            ),
            'navigation' => [
                'active' => 'system',
            ],
            'success' => false,
        ];
        parent::assignVariables();
    }
}
