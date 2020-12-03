<?php

declare(strict_types=1);

namespace BenTools\CrontabBundle\Tests;

use BenTools\CrontabBundle\Command\CrontabUpdateCommand;
use Symfony\Component\Console\Tester\CommandTester;

it('works 👍', function () {
    $projectDir = \dirname(__DIR__).'/app';
    $command = container()->get(CrontabUpdateCommand::class);
    $tester = new CommandTester($command);
    $tmpFile = \tempnam(\sys_get_temp_dir(), 'crontab_bundle');
    $status = $tester->execute([
        '--dry-run' => true,
        '--dump' => true,
        '--output-file' => $tmpFile
    ]);

    // Test return status
    expect($status)->toEqual(0);

    // Test output
    expect($tester->getDisplay())->toBe(
        <<<EOF
Generated crontab:
@reboot $projectDir/bin/console app:hello-world

Writing crontab to $tmpFile... Success!

EOF
    );

    // Test generated file
    $content = \Safe\file_get_contents($tmpFile);
    expect($content)->toBe(
        <<<EOF
@reboot $projectDir/bin/console app:hello-world

EOF
    );
});
