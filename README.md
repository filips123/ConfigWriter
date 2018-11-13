PHP Config Writer
=================

[![Latest Stable Version][icon-stable-version]][link-packagist]
[![Latest Untable Version][icon-unstable-version]][link-packagist]
[![Total Downloads][icon-downloads]][link-packagist]
[![License][icon-license]][link-license]
[![PHP][icon-php]][link-php]

[![Linux Build Status][icon-travis]][link-travis]
[![Windows Build Status][icon-appveyor]][link-appveyor]
[![Code Coverage][icon-coverage]][link-coverage]
[![Code Quality][icon-quality]][link-quality]

Lightweight configuration writer for PHP.

## Installation

### Requirements

MonologPHPMailer requires *[PHP][link-php] 5.5.9* or higher.
Also, the config directory needs to be writable by web server in order to save config file.

### Using Composer

The reccomended way to install ConfigWriter is with [Composer][link-composer], dependency manager for PHP.

You should just require `filips123/config-writer` in your project.

```bash
composer require filips123/config-writer:^2.0
```

You would only need to include autoloader and namespace in your script.

```php
<?php

use ConfigWriter\Config;

require 'vendor/autoload.php';

$config = new Config;
```

### Manually Installation

Alternatively, you could download files from GitHub and then manually include them in your script.

You whould need to include all files and namespace in your script.

```php
<?php

use ConfigWriter\Config;

require 'src/Exceptions/UnsupportedFormatException.php';
require 'src/Exceptions/WriteException.php';
require 'src/ConfigInterface.php';
require 'src/AbstractConfig.php';
require 'src/Config.php';
require 'src/Record.php';
require 'src/Writers/WriterInterface.php';
require 'src/Writers/PhpWriter.php';

$config = new Config;
```

## Usage

### Making the configuration

Configuration making is possible using `ConfigWriter\Config` class.

```php
$config = new Config;
```

It accepts two parameters, data and comment, and both are optional.
Data parameter contains pre-set data for configuration and comment contains additional comment (or code) on top of the configuration file.

```php
$config = new Config(
    [
        'username' => 'user',
        'password' => 'pass',
    ],
    <<<EOD
/**
 * The configuration file.
 *
 * It contains configuration data.
 */
EOD;
);
```

### Adding records

Records can be added using `ConfigWriter\Config::addRecord()` method.

```php
$config->addRecord('application', 'ConfigWriter');
```

They can also have comments, which will be generated in documentation.

```php
$config->addRecord('application', 'ConfigWriter', 'Application name');
```

### Adding sections

Sections visually and functionally separate multiple records. They can be added using `ConfigWriter\Config::addSection()` method.

```php
$database = $config->addSection('database', [], 'Database settings');

$database->addRecord('host', 'localhost', 'Database host');
$database->addRecord('port', '3306', 'Database port');
```

They can also have pre-set data using second parameter.

```php
$config->addSection(
    'database',
    [
        'host' => 'localhost',
        'port' => '3306',
    ],
    'Database settings');
```

### Saving configuration

You can save configuration using `ConfigWriter\Config::toString()`  or `ConfigWriter\Config::toFile()`.

When saving to string, configuration writer is required, and when saving to file, writer will be automatically determined.

```php
$config->toString(new ConfigWriter\Writers\PhpWriter);
$config->toFile('config.php');
```

Writers can also have specific options for writing.

```php
$config->toFile('config.php', new ConfigWriter\Writers\PhpWriter, ['indentation' => '	']);
```

The only supported writer is for PHP array, but more writers will be added later.

## Versioning
This project uses [SemVer][link-semver] for versioning. For the versions available, see the [tags on this repository][link-tags].

## License
This project is licensed under the MIT license. See the [`LICENSE`][link-license-file] file for details.

[icon-stable-version]: https://img.shields.io/packagist/v/filips123/config-writer.svg?style=flat-square&label=Latest+Stable+Version
[icon-unstable-version]: https://img.shields.io/packagist/vpre/filips123/config-writer.svg?style=flat-square&label=Latest+Unstable+Version
[icon-downloads]: https://img.shields.io/packagist/dt/filips123/config-writer.svg?style=flat-square&label=Downloads
[icon-license]: https://img.shields.io/packagist/l/filips123/config-writer.svg?style=flat-square&label=License
[icon-php]: https://img.shields.io/packagist/php-v/filips123/config-writer.svg?style=flat-square&label=PHP
[icon-travis]: https://img.shields.io/travis/com/filips123/ConfigWriter.svg?style=flat-square&label=Linux+Build+Status
[icon-appveyor]: https://img.shields.io/appveyor/ci/filips123/ConfigWriter.svg?style=flat-square&label=Windows+Build+Status
[icon-coverage]: https://img.shields.io/scrutinizer/coverage/g/filips123/ConfigWriter.svg?style=flat-square&label=Code+Coverage
[icon-quality]: https://img.shields.io/scrutinizer/g/filips123/ConfigWriter.svg?style=flat-square&label=Code+Quality

[link-packagist]: https://packagist.org/packages/filips123/config-writer/
[link-license]: https://choosealicense.com/licenses/mit/
[link-php]: https://php.net/
[link-composer]: https://getcomposer.org/
[link-travis]: https://travis-ci.com/filips123/ConfigWriter/
[link-appveyor]: https://ci.appveyor.com/project/filips123/configwriter/
[link-coverage]: https://scrutinizer-ci.com/g/filips123/ConfigWriter/code-structure/
[link-quality]: https://scrutinizer-ci.com/g/filips123/ConfigWriter/
[link-semver]: https://semver.org/
[link-tags]: https://github.com/filips123/ConfigWriter/tags/
[link-license-file]: https://github.com/filips123/ConfigWriter/blob/master/LICENSE

