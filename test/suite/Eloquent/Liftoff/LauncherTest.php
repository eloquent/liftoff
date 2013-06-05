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

use Phake;
use PHPUnit_Framework_TestCase;
use Icecave\Isolator\Isolator;

class LauncherTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        $this->launcher = new Launcher($this->isolator);
    }

    public function launchData()
    {
        //                                    os             path              arguments               expectedCommand
        return array(
            'OSX'                    => array('Darwin',      '/path/to/file',  null,                   "open '/path/to/file'"),
            'OSX with arguments'     => array('Darwin',      '/path/to/file',  array('--foo', 'bar'),  "open '/path/to/file' '--args' '--foo' 'bar'"),
            'Windows'                => array('Windows NT',  '/path/to/file',  null,                   "start '/path/to/file'"),
            'Windows with arguments' => array('Windows NT',  '/path/to/file',  array('--foo', 'bar'),  "start '/path/to/file' '--foo' 'bar'"),
            'Unix'                   => array('Linux',       '/path/to/file',  null,                   "xdg-open '/path/to/file'"),
            'Unix with arguments'    => array('Linux',       '/path/to/file',  array('--foo', 'bar'),  "xdg-open '/path/to/file' '--foo' 'bar'"),
        );
    }

    /**
     * @dataProvider launchData
     */
    public function testLaunch($os, $path, $arguments, $expectedCommand)
    {
        Phake::when($this->isolator)->php_uname('s')->thenReturn($os);
        Phake::when($this->isolator)
            ->proc_open(Phake::anyParameters())
            ->thenReturn(111);
        $this->launcher->launch($path, $arguments);

        Phake::inOrder(
            Phake::verify($this->isolator)->proc_open($expectedCommand, array(), null),
            Phake::verify($this->isolator)->proc_close(111)
        );
    }

    public function testLaunchFailure()
    {
        Phake::when($this->isolator)
            ->proc_open(Phake::anyParameters())
            ->thenReturn(false);

        $this->setExpectedException(
            __NAMESPACE__ . '\Exception\LaunchException',
            "Unable to launch '/path/to/file'"
        );

        $this->launcher->launch('/path/to/file');
    }
}
