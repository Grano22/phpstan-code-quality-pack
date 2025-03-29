<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\Process\Process;

#[Group('acceptance')]
class AllRulesTest extends TestCase
{
    use MatchesSnapshots;

    public function testAllRulesWorksWithProvidedConfigurationFromPhpstanCli(): void
    {
        // Arrange
        $process = new Process([
            __DIR__ . '/../../bin/phpstan',
            'analyse',
            __DIR__ . '/data/ValidCodeBase/',
            '-c',
            __DIR__ . '/phpstan.test.dist.neon',
            '-l8',
            '--no-progress'
        ]);

        // Act
        $process->run();

        // Assert
        self::assertSame('', $process->getErrorOutput(), 'Stderr is not empty in the phpstan process');
        self::assertMatchesTextSnapshot($process->getOutput());
    }
}
