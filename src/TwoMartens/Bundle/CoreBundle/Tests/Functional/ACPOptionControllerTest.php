<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Functional;

/**
 * Tests the ACPOptionController.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
class ACPOptionControllerTest extends WebTestCase
{
    /**
     * Tests the option form.
     */
    public function testOptionForm()
    {
        $client = $this->createClient(array('test_case' => 'ACPOption'));
        $crawler = $client->request('GET', '/acp/system/options');
        $html = $crawler->html();

        $containsExpected = (strpos($html, '<p class="alert alert-info" role="alert">') !== -1);
        $containsExpected = $containsExpected && (strpos(
            $html,
            '<button type="submit" id="option_configuration_save" name="'.
            'option_configuration[save]" class="btn-primary btn">'
        ) !== -1);
        $this->assertTrue($containsExpected);
    }
}
