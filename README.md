# Liftoff

*Open any file or URI using the default GUI application from within PHP.*

[![Build Status]][Latest build]
[![Test Coverage]][Test coverage report]

## Installation and documentation

* Available as [Composer] package [eloquent/liftoff].
* [API documentation] available.

## What is Liftoff?

Liftoff provides a simple interface to launch a file or URI in the host
operating system's default application, in a cross-platform manner. Liftoff can
be used, for example, to launch a HTML page in the operating system's default
browser from within a command line PHP application.

## Usage

```php
use Eloquent\Liftoff\Launcher;

$launcher = new Launcher;

$launcher->launch('/path/to/file.html');
$launcher->launch('/path/to/file.html', array('--with', '--arguments'));

$launcher->launch('http://example.org/');
$launcher->launch('http://example.org/', array('--with', '--arguments'));
```

## Command line interface

    liftoff /path/to/file.html
    liftoff /path/to/file.html --with --arguments

    liftoff http://example.org/
    liftoff http://example.org/ --with --arguments

<!-- References -->

[API documentation]: http://lqnt.co/liftoff/artifacts/documentation/api/
[Build Status]: https://raw.github.com/eloquent/liftoff/gh-pages/artifacts/images/icecave/regular/build-status.png
[Latest build]: http://travis-ci.org/eloquent/liftoff
[Test coverage report]: http://lqnt.co/liftoff/artifacts/tests/coverage/
[Test Coverage]: https://raw.github.com/eloquent/liftoff/gh-pages/artifacts/images/icecave/regular/coverage.png
