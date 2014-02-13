<?php

/*
 * This file is part of the Liftoff package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Liftoff\Exception;

use Exception;
use PHPUnit_Framework_TestCase;

class LaunchExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $exception = new LaunchException('/path/to/file', $previous);

        $this->assertSame("Unable to launch '/path/to/file'.", $exception->getMessage());
        $this->assertSame('/path/to/file', $exception->target());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
