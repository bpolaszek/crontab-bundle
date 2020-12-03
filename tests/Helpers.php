<?php

declare(strict_types=1);

namespace BenTools\CrontabBundle\Tests;

use BenTools\CrontabBundle\Tests\SampleApp\Kernel;
use LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

function app(bool $reInstanciate = false): KernelInterface
{
    static $kernel;

    if (null === $kernel || $reInstanciate) {
        $kernel = new Kernel($_SERVER['APP_ENV'] ?? 'test', false);
        $kernel->boot();
    }

    return $kernel;
}

/**
 * @phpstan-ignore-next-line
 */
function container(?KernelInterface $app = null): ContainerInterface
{
    $app       = $app ?? app();
    $container = $app->getContainer();

    if (!$container instanceof ContainerInterface) {
        throw new LogicException('Unable to retrieve ContainerInterface');
    }

    return $container;
}
