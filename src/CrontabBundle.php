<?php

namespace BenTools\CrontabBundle;

use BenTools\CrontabBundle\DependencyInjection\CrontabExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CrontabBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new CrontabExtension();
    }
}
