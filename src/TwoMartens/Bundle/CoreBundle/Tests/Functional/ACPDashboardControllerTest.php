<?php
/*
 * This file is part of the 2martens Web Platform.
 *
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Functional;

/**
 * Tests the ACPDashboardController.
 *
 * @author Jim Martens <github@2martens.de>
 */
class ACPDashboardControllerTest extends WebTestCase
{
    public function testDashboard()
    {
        $client = $this->createClient(array('test_case' => 'ACPDashboard'));
        $crawler = $client->request('GET', '/acp');
        $html = $crawler->html();

        $containsExpected = (strpos($html, '<p class="alert alert-info" role="alert">') !== -1);
        $this->assertTrue($containsExpected);
    }
}
