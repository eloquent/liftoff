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
        //                                    os             target            arguments               expectedCommand
        return array(
            'OSX'                    => array('Darwin',      '/path/to/file',  null,                   "open '/path/to/file'"),
            'OSX with arguments'     => array('Darwin',      '/path/to/file',  array('--foo', 'bar'),  "open '/path/to/file' '--args' '--foo' 'bar'"),
            'Windows'                => array('Windows NT',  '/path/to/file',  null,                   "start 'liftoff' '/path/to/file'"),
            'Windows with arguments' => array('Windows NT',  '/path/to/file',  array('--foo', 'bar'),  "start 'liftoff' '/path/to/file' '--foo' 'bar'"),
            'Unix'                   => array('Linux',       '/path/to/file',  null,                   "xdg-open '/path/to/file'"),
            'Unix with arguments'    => array('Linux',       '/path/to/file',  array('--foo', 'bar'),  "xdg-open '/path/to/file' '--foo' 'bar'"),
        );
    }

    /**
     * @dataProvider launchData
     */
    public function testLaunch($os, $target, $arguments, $expectedCommand)
    {
        $expectedDescriptorSpec = array(array('pipe', 'r'), array('pipe', 'w'), array('pipe', 'w'));
        Phake::when($this->isolator)->php_uname('s')->thenReturn($os);
        Phake::when($this->isolator)
            ->proc_open($expectedCommand, $expectedDescriptorSpec, Phake::setReference(array(222, 333, 444)))
            ->thenReturn(111);
        $this->launcher->launch($target, $arguments);

        Phake::inOrder(
            Phake::verify($this->isolator)->proc_open($expectedCommand, $expectedDescriptorSpec, null),
            Phake::verify($this->isolator)->fclose(222),
            Phake::verify($this->isolator)->fclose(333),
            Phake::verify($this->isolator)->fclose(444),
            Phake::verify($this->isolator)->proc_close(111)
        );
    }

    public function testLaunchFailure()
    {
        Phake::when($this->isolator)->proc_open(Phake::anyParameters())->thenReturn(false);

        $this->setExpectedException('Eloquent\Liftoff\Exception\LaunchException', "Unable to launch '/path/to/file'");
        $this->launcher->launch('/path/to/file');
    }
}
