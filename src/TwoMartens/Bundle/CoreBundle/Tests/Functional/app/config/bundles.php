<?php
/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\TestBundle;

return array(
    new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
    new Symfony\Bundle\SecurityBundle\SecurityBundle(),
    new Symfony\Bundle\TwigBundle\TwigBundle(),
    new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
    new Symfony\Bundle\AsseticBundle\AsseticBundle(),
    new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
    new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    new Sonata\CoreBundle\SonataCoreBundle(),
    new Sonata\BlockBundle\SonataBlockBundle(),
    new FOS\UserBundle\FOSUserBundle(),
    new TwoMartens\Bundle\CoreBundle\TwoMartensCoreBundle(),
    new TestBundle(),
);
