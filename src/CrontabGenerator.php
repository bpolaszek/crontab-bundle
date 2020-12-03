<?php

namespace BenTools\CrontabBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

class CrontabGenerator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $content
     * @return string
     */
    public function replaceWithContainerParameters($content)
    {
        return preg_replace_callback('/\{%([^\}]*)\%}/', function ($matches) {
            return $this->container->getParameter($matches[1]);
        }, $content);
    }

    /**
     * @param string|null $dir
     * @return string
     */
    public function createTemporaryFile($dir = null)
    {
        if (null === $dir) {
            $dir = sys_get_temp_dir();
        }
        return tempnam($dir, 'bentools_crontab');
    }

    /**
     * @param string $content
     * @param string $filename
     * @throws \RuntimeException
     */
    public function write($content, $filename)
    {
        if (!is_writable($filename)) {
            throw new \RuntimeException(sprintf('Failed writing in file %s', $filename));
        }
        file_put_contents($filename, $content);
    }
}
