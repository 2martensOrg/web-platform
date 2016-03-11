<?php
/*
 * This file is part of the 2martens Web Platform.
 *
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Controller;

/**
 * Base class for all ACP controllers.
 *
 * @author Jim Martens <github@2martens.de>
 */
abstract class AbstractACPController extends AbstractController
{
    protected $templateVariables;

    /**
     * @var \TwoMartens\Bundle\CoreBundle\Model\Breadcrumb[]
     */
    protected $breadcrumbs;

    public function __construct()
    {
        $this->templateVariables = array();
        $this->breadcrumbs = array();
    }

    /**
     * Sets the breadcrumbs.
     *
     * @return void
     */
    abstract protected function setBreadcrumbs();

    /**
     * Assigns variables to $templateVariables.
     */
    protected function assignVariables()
    {
        $this->setBreadcrumbs();

        $variables = array(
            'breadcrumbs' => $this->breadcrumbs
        );
        $this->templateVariables = array_merge($this->templateVariables, $variables);
    }
}
