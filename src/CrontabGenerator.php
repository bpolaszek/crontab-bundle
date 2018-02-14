<?php

namespace BenTools\CrontabBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

class CrontabGenerator
{
    /**
     * @param                    $content
     * @param ContainerInterface $container
     * @return string
     */
    public function replaceWithContainerParameters($content, ContainerInterface $container)
    {
        return preg_replace_callback('/\{%([^\}]*)\%}/', function ($matches) use ($container) {
            return $container->getParameter($matches[1]);
        }, $content);
    }

    /**
     * @param string $content
     * @param null   $dir
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
     * @param $content
     * @param $filename
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
