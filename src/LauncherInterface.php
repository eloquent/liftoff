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

/**
 * The interface implemented by launchers.
 */
interface LauncherInterface
{
    /**
     * Launch a file or URI in its default GUI application.
     *
     * @param string                     $target    The path or URI to launch.
     * @param array<integer,string>|null $arguments An array of arguments to pass to the associated application.
     *
     * @throws Exception\LaunchException If the launch command fails, or is unavailable.
     */
    public function launch($target, array $arguments = null);
}
