<?php

/*
 * This file is part of the Liftoff package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Liftoff\Console;

use Phake;
use PHPUnit_Framework_TestCase;
use Icecave\Isolator\Isolator;

class LiftoffApplicationTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->launcher = Phake::mock('Eloquent\Liftoff\LauncherInterface');
        $this->isolator = Phake::mock(Isolator::className());
        $this->application = new LiftoffApplication($this->launcher, $this->isolator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->launcher, $this->application->launcher());
    }

    public function testConstructorDefaults()
    {
        $this->application = new LiftoffApplication;

        $this->assertInstanceOf('Eloquent\Liftoff\Launcher', $this->application->launcher());
    }

    public function testExecute()
    {
        $this->application->execute(array('argv' => array('liftoff', '/path/to/file', '--foo', 'bar')));

        Phake::verify($this->launcher)->launch('/path/to/file', array('--foo', 'bar'));
    }

    public function testExecuteWithDefaultVariables()
    {
        $server = $_SERVER;
        $_SERVER = array('argv' => array('liftoff', '/path/to/file', '--foo', 'bar'));
        $this->application->execute();
        $_SERVER = $server;

        Phake::verify($this->launcher)->launch('/path/to/file', array('--foo', 'bar'));
    }

    public function testExecuteUsageShort()
    {
        $this->application->execute(array('argv' => array('liftoff', '-h')));

        Phake::verify($this->isolator)->echo('Usage: liftoff <target> [argument...]' . PHP_EOL);
    }

    public function testExecuteUsageLong()
    {
        $this->application->execute(array('argv' => array('liftoff', '--help')));

        Phake::verify($this->isolator)->echo('Usage: liftoff <target> [argument...]' . PHP_EOL);
    }

    public function testVersionUsageShort()
    {
        $this->application->execute(array('argv' => array('liftoff', '-v')));

        Phake::verify($this->isolator)->echo('Liftoff 0.1.0' . PHP_EOL);
    }

    public function testVersionUsageLong()
    {
        $this->application->execute(array('argv' => array('liftoff', '--version')));

        Phake::verify($this->isolator)->echo('Liftoff 0.1.0' . PHP_EOL);
    }

    public function testExecuteFailureBadVariables()
    {
        $this->setExpectedException('RuntimeException', 'Unable to determine arguments.');
        $this->application->execute(array());
    }

    public function testExecuteFailureNoArguments()
    {
        $this->setExpectedException('RuntimeException', 'No arguments provided.');
        $this->application->execute(array('argv' => array('liftoff')));
    }
}
