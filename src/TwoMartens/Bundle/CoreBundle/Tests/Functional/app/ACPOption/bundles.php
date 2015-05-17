<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\TestBundle;
use TwoMartens\Bundle\CoreBundle\TwoMartensCoreBundle;

return array(
    new TwoMartensCoreBundle(),
    new FrameworkBundle(),
    new TestBundle(),
);
