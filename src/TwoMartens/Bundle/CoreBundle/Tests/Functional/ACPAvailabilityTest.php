<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Functional;

/**
 * Tests the availability of ACP urls.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2016 Jim Martens
 */
class ACPAvailabilityTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     * @param string $url
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->createClient(['test_case' => 'ACPAvailability']);
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return [
            ['/acp/'],
            ['/acp/system/'],
            ['/acp/system/options/'],
            ['/acp/user/'],
            ['/acp/user/group/add/'],
            ['/acp/user/group/list/'],
            ['/acp/user/user/add/'],
            ['/acp/user/user/list/'],
            ['/acp/appearance/'],
            ['/acp/content/'],
        ];
    }
}
