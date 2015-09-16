<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use TwoMartens\Bundle\CoreBundle\Option\OptionServiceInterface;

/**
 * This listener adds a block to the ACP Dashboard which displays system data.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class SystemStatsDashboardListener
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var OptionServiceInterface
     */
    private $optionService;

    /**
     * @param EngineInterface        $templating
     * @param TranslatorInterface    $translator
     * @param OptionServiceInterface $optionService
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        OptionServiceInterface $optionService
    )
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->optionService = $optionService;
    }

    /**
     * @param BlockEvent $event
     */
    public function onBlock(BlockEvent $event)
    {
        if (!$this->optionService->get('twomartens.core', 'showSystemStats')->getValue()) {
            return;
        }
        $variables = [
            'systemData' => [
                [
                    'key' => $this->translator->trans(
                        'acp.dashboard.blocks.systemstats.os',
                        [],
                        'TwoMartensCoreBundle'
                    ),
                    'value' => PHP_OS
                ],
                [
                    'key' => $this->translator->trans(
                        'acp.dashboard.blocks.systemstats.webserver',
                        [],
                        'TwoMartensCoreBundle'
                    ),
                    'value' => (isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '')
                ],
                [
                    'key' => $this->translator->trans(
                        'acp.dashboard.blocks.systemstats.php',
                        [],
                        'TwoMartensCoreBundle'
                    ),
                    'value' => PHP_VERSION
                ]
            ]
        ];
        $content = $this->templating->render('TwoMartensCoreBundle:blocks:systemStatsBlock.html.twig', $variables);

        $block = new Block();
        $block->setId(uniqid());
        $block->setSetting('content', $content);
        $block->setType('sonata.block.service.text');

        $event->addBlock($block);
    }
}
