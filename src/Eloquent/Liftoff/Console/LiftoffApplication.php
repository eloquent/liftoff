<?php

/*
 * This file is part of the Liftoff package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Liftoff\Console;

use Eloquent\Liftoff\Launcher;
use Eloquent\Liftoff\LauncherInterface;
use Icecave\Isolator\Isolator;
use RuntimeException;

/**
 * Handles the Liftoff command line interface.
 */
class LiftoffApplication
{
    const VERSION = '0.1.0';

    /**
     * Create a new Application instance.
     *
     * @param LauncherInterface|null $launcher The launcher to use.
     * @param Isolator|null          $isolator The isolator to use.
     */
    public function __construct(
        LauncherInterface $launcher = null,
        Isolator $isolator = null
    ) {
        if (null === $launcher) {
            $launcher = new Launcher;
        }

        $this->launcher = $launcher;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Get the launcher used by this application.
     *
     * @return LauncherInterface The launcher.
     */
    public function launcher()
    {
        return $this->launcher;
    }

    /**
     * Runs the Liftoff command line application.
     *
     * @param array<string,mixed>|null $variables The server variables to use.
     *     Defaults to $_SERVER.
     */
    public function execute(array $variables = null)
    {
        if (null === $variables) {
            $variables = $_SERVER;
        }

        $arguments = $this->arguments($variables);
        if (count($arguments) < 1) {
            throw new RuntimeException('No arguments provided.');
        }

        switch ($arguments[0]) {
            case '-h':
            case '--help':
                $this->executeUsage();

                return;

            case '-v':
            case '--version':
                $this->executeVersion();

                return;
        }

        $this->executeLaunch($arguments);
    }

    /**
     * @param array<string> $arguments
     */
    protected function executeLaunch(array $arguments)
    {
        $this->launcher()->launch($arguments[0], array_slice($arguments, 1));
    }

    protected function executeUsage()
    {
        $this->isolator->echo('Usage: liftoff <path> [argument...]' . PHP_EOL);
    }

    protected function executeVersion()
    {
        $this->isolator->echo('Liftoff ' . static::VERSION . PHP_EOL);
    }

    /**
     * @param array<string,mixed> $variables
     *
     * @return array<string>
     */
    protected function arguments(array $variables)
    {
        if (
            !array_key_exists('argv', $variables) ||
            !is_array($variables['argv'])
        ) {
            throw new RuntimeException('Unable to determine arguments.');
        }

        $arguments = $variables['argv'];
        array_shift($arguments);

        return $arguments;
    }

    private $launcher;
    private $isolator;
}
