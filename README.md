# Liftoff

*Open any file or URI using the default GUI application from within PHP.*

[![The most recent stable version is 0.1.0][version-image]][Semantic versioning]
[![Current build status image][build-image]][Current build status]
[![Current coverage status image][coverage-image]][Current coverage status]

## Installation and documentation

- Available as [Composer] package [eloquent/liftoff].
- [Executable phar] available for download.
- [API documentation] available.

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

[Executable phar]: http://lqnt.co/liftoff/liftoff

[API documentation]: http://lqnt.co/liftoff/artifacts/documentation/api/
[Composer]: http://getcomposer.org/
[build-image]: http://img.shields.io/travis/eloquent/liftoff/develop.svg "Current build status for the develop branch"
[Current build status]: https://travis-ci.org/eloquent/liftoff
[coverage-image]: http://img.shields.io/coveralls/eloquent/liftoff/develop.svg "Current test coverage for the develop branch"
[Current coverage status]: https://coveralls.io/r/eloquent/liftoff
[eloquent/liftoff]: https://packagist.org/packages/eloquent/liftoff
[Semantic versioning]: http://semver.org/
[version-image]: http://img.shields.io/:semver-0.1.0-yellow.svg "This project uses semantic versioning"
