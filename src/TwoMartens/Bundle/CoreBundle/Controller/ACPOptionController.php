<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TwoMartens\Bundle\CoreBundle\Model\Breadcrumb;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;
use TwoMartens\Bundle\CoreBundle\Option\OptionServiceInterface;

/**
 * Manages the routes for the option system.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ACPOptionController extends AbstractACPController
{
    /**
     * the global options
     * @var OptionCategory
     */
    private $options;

    /**
     * Shows the options main page.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        /** @var OptionServiceInterface $optionService */
        $optionService = $this->container->get('twomartens.core.option');
        $this->options = $optionService->getOptions();

        $form = $this->createForm(
            'option_configuration',
            $this->options
        );

        $form->handleRequest($request);
        $this->assignVariables();

        if ($form->isValid()) {
            // save changed options to file
            $submittedData = $request->request->all();
            $submittedData = $submittedData['option_configuration'];

            $categories = $this->options->getCategories();
            foreach ($categories as $category) {
                $categoryName = $category->getName();
                $options = $category->getOptions();

                foreach ($options as $option) {
                    $optionName = $option->getName();
                    $optionType = $option->getType();
                    $fieldName = str_replace('.', '_', $categoryName) . '_' . $optionName;
                    if (!isset($submittedData[$fieldName])) {
                        // should be the case only for checkbox
                        $fieldValue = false;
                    } else {
                        $fieldValue = $submittedData[$fieldName];
                    }
                    settype($fieldValue, $optionType);
                    $option->setValue($fieldValue);
                }
            }

            $optionService->setOptions($this->options);
            $this->templateVariables['success'] = true;
        }

        $this->templateVariables['form'] = $form->createView();

        return $this->render(
            'TwoMartensCoreBundle:ACPOption:index.html.twig',
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
        $optionsBreadcrumb = new Breadcrumb(
            'acp.system.options',
            $this->get('translator')->trans('acp.breadcrumb.options', [], 'TwoMartensCoreBundle')
        );
        $optionsBreadcrumb->activate();
        $this->breadcrumbs = [
            $systemBreadcrumb,
            $optionsBreadcrumb
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function assignVariables()
    {
        $this->templateVariables = [
            'area' => [
                'showBreadcrumbs' => true,
                'title' => $this->get('translator')->trans('acp.options', [], 'TwoMartensCoreBundle')
            ],
            'siteTitle' => $this->get('translator')->trans(
                'acp.siteTitle',
                ['globalTitle' => 'CoreBundle Test'],
                'TwoMartensCoreBundle'
            ),
            'navigation' => [
                'active' => 'system'
            ],
            'success' => false
        ];
        parent::assignVariables();
    }
}
