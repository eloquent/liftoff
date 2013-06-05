<?php

/*
 * This file is part of the Liftoff package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Liftoff\Exception;

use Exception;
use RuntimeException;

/**
 * Launch command failed, or is unavailable.
 */
final class LaunchException extends RuntimeException
{
    /**
     * Create a new LaunchException instance.
     *
     * @param string         $path     The path that Liftoff attempted to launch.
     * @param Exception|null $previous The previous exception, if available.
     */
    public function __construct($path, Exception $previous = null)
    {
        $this->path = $path;

        parent::__construct(
            sprintf('Unable to launch %s.', var_export($path, true)),
            0,
            $previous
        );
    }

    /**
     * Get the path that Liftoff attempted to launch.
     *
     * @return string The path that Liftoff attempted to launch.
     */
    public function path()
    {
        return $this->path;
    }

    private $path;
}
