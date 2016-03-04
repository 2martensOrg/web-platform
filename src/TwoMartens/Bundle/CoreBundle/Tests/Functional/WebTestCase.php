<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;

class WebTestCase extends BaseWebTestCase
{
    protected static function createClient(array $options = array(), array $server = array())
    {
        $client = parent::createClient($options, array_merge_recursive(
            $server,
            array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin'
            )
        ));

        return $client;
    }

    public static function assertRedirect(Response $response, $location)
    {
        self::assertTrue(
            $response->isRedirect(),
            'Response is not a redirect, got status code: '.$response->getStatusCode()
        );
        self::assertEquals('http://localhost'.$location, $response->headers->get('Location'));
    }

    protected function deleteTmpDir($testCase)
    {
        if (!file_exists($dir = sys_get_temp_dir().'/'.Kernel::VERSION.'/'.$testCase)) {
            return;
        }

        $fs = new Filesystem();
        $fs->remove($dir);
    }

    protected static function getKernelClass()
    {
        require_once __DIR__.'/app/AppKernel.php';

        return 'TwoMartens\Bundle\CoreBundle\Tests\Functional\app\AppKernel';
    }

    protected static function createKernel(array $options = array())
    {
        $class = self::getKernelClass();

        if (!isset($options['test_case'])) {
            throw new \InvalidArgumentException('The option "test_case" must be set.');
        }

        return new $class(
            $options['test_case'],
            'default.yml',
            isset($options['environment']) ?
                $options['environment'] :
                'frameworkbundletest'.strtolower($options['test_case']),
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}
