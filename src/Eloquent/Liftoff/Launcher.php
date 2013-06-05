<?php

/*
 * This file is part of the Liftoff package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Liftoff;

use Icecave\Isolator\Isolator;

/**
 * Launches files in their default GUI application.
 */
class Launcher implements LauncherInterface
{
    /**
     * Create a new Launcher instance.
     *
     * @param Isolator|null $isolator
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Launch a file in its default GUI application.
     *
     * @param string             $path      The path of the file to launch.
     * @param array<string>|null $arguments An array of arguments to pass to the
     *     associated application.
     *
     * @throws Exception\LaunchException If the launch command fails, or is
     *     unavailable.
     */
    public function launch($path, array $arguments = null)
    {
        if (null === $arguments) {
            $arguments = array();
        }

        $os = $this->isolator->php_uname('s');

        if ('win' === strtolower(substr($os, 0, 3))) {
            $this->launchWindows($path, $arguments);
        } elseif ('Darwin' === $os) {
            $this->launchOsx($path, $arguments);
        } else {
            $this->launchUnix($path, $arguments);
        }
    }

    /**
     * @param string        $path
     * @param array<string> $arguments
     *
     * @throws Exception\LaunchException
     */
    protected function launchOsx($path, array $arguments)
    {
        if (count($arguments) > 0) {
            array_unshift($arguments, '--args');
        }

        $this->launchWithCommand('open', $path, $arguments);
    }

    /**
     * @param string        $path
     * @param array<string> $arguments
     *
     * @throws Exception\LaunchException
     */
    protected function launchUnix($path, array $arguments)
    {
        $this->launchWithCommand('xdg-open', $path, $arguments);
    }

    /**
     * @param string        $path
     * @param array<string> $arguments
     *
     * @throws Exception\LaunchException
     */
    protected function launchWindows($path, array $arguments)
    {
        $this->launchWithCommand('start', $path, $arguments);
    }

    /**
     * @param string        $command
     * @param string        $path
     * @param array<string> $arguments
     *
     * @throws Exception\LaunchException
     */
    protected function launchWithCommand($command, $path, array $arguments)
    {
        $command = implode(
            ' ',
            array_merge(
                array($command, escapeshellarg($path)),
                array_map('escapeshellarg', $arguments)
            )
        );

        $handle = $this->isolator->proc_open($command, array(), $pipes);
        if (false === $handle) {
            throw new Exception\LaunchException($path);
        }

        $this->isolator->proc_close($handle);
    }

    private $isolator;
}
