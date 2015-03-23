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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description
 *
 * @author Jim Martens <github@2martens.de>
 */
abstract class AbstractACPController extends Controller
{
    protected $templateVariables;

    public function __construct()
    {
        $this->templateVariables = array();
    }

    /**
     * Assigns variables to $templateVariables.
     */
    protected function assignVariables()
    {
        $variables = array();
        $this->templateVariables = array_merge($this->templateVariables, $variables);
    }
}
