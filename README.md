# README

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/2martens/web-platform/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/2martens/web-platform/?branch=master)
[![Build Status](https://travis-ci.org/2martens/web-platform.svg?branch=master)](https://travis-ci.org/2martens/web-platform)
[![Total Downloads](https://poser.pugx.org/2martens/web-platform/downloads)](https://packagist.org/packages/2martens/web-platform)
[![License](https://poser.pugx.org/2martens/web-platform/license)](https://packagist.org/packages/2martens/web-platform)

* TODO: will be changed

## What is the Web Platform?

The Web Platform is the Symfony2 Standard Edition plus a lot more.
If you already know Symfony2 then go ahead and read the following lines.
If you don't know Symfony2, go to [http://symfony.com][1] and find out
what Symfony2 is.

### Additions to the Standard Edition:

This is a small list compared to the amount of code behind it. It should
give you a good idea what you can expect.

- Administrator Control Panel
- Style System
- User and Group system
- Language system for user-generated content
- Package system for installing applications/plugins that depend on this platform
- Project system
  * you can decide which of the globally installed applications are available for the project

This list of features is only a small subset of what is delivered with the
platform. There are many things coming as simple library functionality
that is not actively used by the platform.

Another important difference:
The Web Platform is an **actual** application with accessible frontend.
It provides some collection pages for the frontend that allow plugins and
applications to plug them in and display their stuff.

## Requirements

The Web Platform requires the Symfony2 Standard Edition. It has therefore
the same restrictions:

Symfony2 is only supported on PHP 5.3.3 and up.

Be warned that PHP versions before 5.3.8 are known to be buggy and might not
work for you:

 * before PHP 5.3.4, if you get "Notice: Trying to get property of
   non-object", you've hit a known PHP bug (see
   https://bugs.php.net/bug.php?id=52083 and
   https://bugs.php.net/bug.php?id=50027);

 * before PHP 5.3.8, if you get an error involving annotations, you've hit a
   known PHP bug (see https://bugs.php.net/bug.php?id=55156).

 * PHP 5.3.16 has a major bug in the Reflection subsystem and is not suitable to
   run Symfony2 (https://bugs.php.net/bug.php?id=62715)

## Installation

### GUI process
The best and easiest way to install the Web Application is downloading
the bundled archive (TODO: link) which provides a graphical installation process.

This graphical process provides these features
- multilingual installation process (German, English as of now)
- composer.phar already bundled (will be updated with selfupdate)
- unpacking the archive (the download archive contains both installation
  process files and the source archive; the latter is unpacked here)
- using Composer to install the dependencies (no dev requirements)
- configuration of global database (parameters.yml)
- creating needed contents (user groups, package information, etc.)
- creation of first user (member of admin group which has access to all ROLE_* controlled
  areas)
- switching to ACP
- cleanup (removing installation files)
- post-install configuration (each bundle can specify steps, the user
  is guided through all of them)
  - this configuration process is optional and can be skipped
  - for beginners it is **highly recommended** to use this guided process

### Composer all the way

The final possibility is to install this Web Platform only with Composer.
For details on how to do so, refer to the [2martens/web-platform-edition][3]
GitHub repository.

## Documentation

The Web Platform extends the functionality of Symfony2. It provides documentation
on the platform-wide level ([2martens/web-platform-docs][4] plus documentation for each bundle (under Resources/doc). 
This documentation is limited to the added functionality.

If you are just starting with Symfony2, you should refer to the "[Quick Tour][5]"
tutorial and then to the official [Symfony2 documentation][6].

## Contributing

The Web Platform is an open source and free software project. We follow mostly
the Contributing rules of Symfony2. Therefore you should take a read on the
[Contributing Code][7] part of the Symfony2 documentation.

For more information refer to the CONTRIBUTING.md file in this repository.

## Running Tests

For information on how to run the tests, refer to [Testing][8] which
contains information on how the tests of the whole library can be run.

[1]: http://symfony.com/
[2]: http://symfony.com/download
[3]: https://github.com/2martens/web-platform-edition
[4]: https://github.com/2martens/web-platform-docs
[5]: http://symfony.com/get_started
[6]: http://symfony.com/doc/current/
[7]: http://symfony.com/doc/current/contributing/code/index.html
[8]: https://github.com/2martens/web-platform-docs/tests.rst
