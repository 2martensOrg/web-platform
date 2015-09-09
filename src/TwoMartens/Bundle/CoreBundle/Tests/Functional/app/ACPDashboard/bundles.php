<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\TestBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use TwoMartens\Bundle\CoreBundle\TwoMartensCoreBundle;

return array(
    new FrameworkBundle(),
    new TwigBundle(),
    new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
    new TwoMartensCoreBundle(),
    new TestBundle(),
);
