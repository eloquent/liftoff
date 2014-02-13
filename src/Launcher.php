<?php

/*
 * This file is part of the Liftoff package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Liftoff;

use Icecave\Isolator\Isolator;

/**
 * Launches files in their default GUI application.
 */
class Launcher implements LauncherInterface
{
    /**
     * Create a new launcher.
     *
     * @param Isolator|null $isolator The isolator to use.
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Launch a file or URI in its default GUI application.
     *
     * @param string                     $target    The path or URI to launch.
     * @param array<integer,string>|null $arguments An array of arguments to pass to the associated application.
     *
     * @throws Exception\LaunchException If the launch command fails, or is unavailable.
     */
    public function launch($target, array $arguments = null)
    {
        if (null === $arguments) {
            $arguments = array();
        }

        $os = $this->isolator()->php_uname('s');

        if ('win' === strtolower(substr($os, 0, 3))) {
            $this->launchWindows($target, $arguments);
        } elseif ('Darwin' === $os) {
            $this->launchOsx($target, $arguments);
        } else {
            $this->launchUnix($target, $arguments);
        }
    }

    /**
     * Launch a file or URI in its default GUI application via the OSX open
     * command.
     *
     * @param string                $target    The path or URI to launch.
     * @param array<integer,string> $arguments An array of arguments to pass to the associated application.
     *
     * @throws Exception\LaunchException If the launch command fails, or is unavailable.
     */
    protected function launchOsx($target, array $arguments)
    {
        if (count($arguments) > 0) {
            array_unshift($arguments, '--args');
        }
        array_unshift($arguments, $target);

        $this->launchCommand('open', $arguments);
    }

    /**
     * Launch a file or URI in its default GUI application via the Unix xdg-open
     * command.
     *
     * @param string                $target    The path or URI to launch.
     * @param array<integer,string> $arguments An array of arguments to pass to the associated application.
     *
     * @throws Exception\LaunchException If the launch command fails, or is unavailable.
     */
    protected function launchUnix($target, array $arguments)
    {
        array_unshift($arguments, $target);

        $this->launchCommand('xdg-open', $arguments);
    }

    /**
     * Launch a file or URI in its default GUI application via the Windows start
     * command.
     *
     * @param string                $target    The path or URI to launch.
     * @param array<integer,string> $arguments An array of arguments to pass to the associated application.
     *
     * @throws Exception\LaunchException If the launch command fails, or is unavailable.
     */
    protected function launchWindows($target, array $arguments)
    {
        array_unshift($arguments, "liftoff", $target);

        $this->launchCommand('start', $arguments);
    }

    /**
     * Lanuch an arbitrary command.
     *
     * @param string                $command   The command to execute.
     * @param array<integer,string> $arguments An array of arguments to pass to the associated application.
     *
     * @throws Exception\LaunchException If the launch command fails, or is unavailable.
     */
    protected function launchCommand($command, array $arguments)
    {
        $command = implode(
            ' ',
            array_merge(
                array($command),
                array_map('escapeshellarg', $arguments)
            )
        );

        $handle = $this->isolator()->proc_open(
            $command,
            array(
                array('pipe', 'r'),
                array('pipe', 'w'),
                array('pipe', 'w'),
            ),
            $pipes
        );
        if (false === $handle) {
            throw new Exception\LaunchException($arguments[0]);
        }

        foreach ($pipes as $pipe) {
            $this->isolator()->fclose($pipe);
        }

        $this->isolator()->proc_close($handle);
    }

    /**
     * Get the isolator.
     *
     * @return Isolator The isolator.
     */
    protected function isolator()
    {
        return $this->isolator;
    }

    private $isolator;
}
