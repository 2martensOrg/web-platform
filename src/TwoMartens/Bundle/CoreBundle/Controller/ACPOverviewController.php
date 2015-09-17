<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use TwoMartens\Bundle\CoreBundle\Event\OverviewEvent;
use TwoMartens\Bundle\CoreBundle\Model\Breadcrumb;

/**
 * Manages the routes for the overview pages.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ACPOverviewController extends AbstractACPController
{
    /**
     * the current page
     * @var string
     */
    private $page;

    /**
     * Initializes the controller.
     */
    public function __construct()
    {
        parent::__construct();
        $this->page = '';
    }

    /**
     * Shows the system overview page.
     *
     * @return Response
     */
    public function systemAction()
    {
        $this->page = 'system';
        return $this->overviewAction();
    }

    /**
     * Shows the user overview page.
     *
     * @return Response
     */
    public function userAction()
    {
        $this->page = 'user';
        return $this->overviewAction();
    }

    /**
     * Shows the appearance overview page.
     *
     * @return Response
     */
    public function appearanceAction()
    {
        $this->page = 'appearance';
        return $this->overviewAction();
    }

    /**
     * Shows the content overview page.
     *
     * @return Response
     */
    public function contentAction()
    {
        $this->page = 'content';
        return $this->overviewAction();
    }

    /**
     * {@inheritdoc}
     */
    protected function setBreadcrumbs()
    {
        $activeBreadcrumb = new Breadcrumb(
            'acp.'.$this->page,
            $this->get('translator')->trans(
                'acp.breadcrumb.'.$this->page,
                [],
                'TwoMartensCoreBundle'
            )
        );
        $activeBreadcrumb->activate();
        $this->breadcrumbs = [
            $activeBreadcrumb
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
                'title' => $this->get('translator')->trans('acp.'.$this->page, [], 'TwoMartensCoreBundle')
            ],
            'siteTitle' => $this->get('translator')->trans(
                'acp.siteTitle',
                ['globalTitle' => 'CoreBundle Test'],
                'TwoMartensCoreBundle'
            ),
            'navigation' => [
                'active' => $this->page
            ]
        ];
        parent::assignVariables();
    }

    /**
     * Includes all the main action.
     *
     * @return Response
     */
    private function overviewAction()
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->get('event_dispatcher');
        $event = new OverviewEvent();
        $eventDispatcher->dispatch(
            'twomartens.core.'. $this->page . '_overview',
            $event
        );
        $categories = $event->getCategories();
        $entries = $event->getEntries();

        $this->assignVariables();
        $this->templateVariables['categories'] = $categories;
        $this->templateVariables['entries'] = $entries;

        return $this->render(
            'TwoMartensCoreBundle:ACPOverview:index.html.twig',
            $this->templateVariables
        );
    }
}
