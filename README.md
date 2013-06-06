# Liftoff

*Open any file or URI using the default GUI application from within PHP.*

[![Build Status]][Latest build]
[![Test Coverage]][Test coverage report]

## Installation and documentation

* Available as [Composer] package [eloquent/liftoff].
* [Executable phar] available for download.
* [API documentation] available.

## What is Liftoff?

*Liftoff* provides a simple interface to launch a file or URI in the host
operating system's default application, in a cross-platform manner. *Liftoff*
can be used, for example, to launch a HTML page in the operating system's
default browser from within a command line PHP application.

## Usage

*Liftoff* can be used as a library, to launch files and URIs from within a PHP
application:

```php
use Eloquent\Liftoff\Launcher;

$launcher = new Launcher;

$launcher->launch('/path/to/file.html');
$launcher->launch('/path/to/file.html', array('--with', '--arguments'));

$launcher->launch('http://example.org/');
$launcher->launch('http://example.org/', array('--with', '--arguments'));
```

Arguments provided as the second parameter will be forwarded on to the
associated application.

## Command line interface

*Liftoff* comes with a command line interface which can be used to launch files
and URIs from the command line:

    liftoff /path/to/file.html
    liftoff /path/to/file.html --with --arguments

    liftoff http://example.org/
    liftoff http://example.org/ --with --arguments

Arguments after the path (or URI) will be forwarded on to the associated
application.

<!-- References -->

[API documentation]: http://lqnt.co/liftoff/artifacts/documentation/api/
[Build Status]: https://raw.github.com/eloquent/liftoff/gh-pages/artifacts/images/icecave/regular/build-status.png
[Composer]: http://getcomposer.org/
[eloquent/liftoff]: https://packagist.org/packages/eloquent/liftoff
[Executable phar]: http://lqnt.co/liftoff/liftoff
[Latest build]: http://travis-ci.org/eloquent/liftoff
[Test coverage report]: http://lqnt.co/liftoff/artifacts/tests/coverage/
[Test Coverage]: https://raw.github.com/eloquent/liftoff/gh-pages/artifacts/images/icecave/regular/coverage.png
