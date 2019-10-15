# laravel-database-sticky-timezone

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]
[![Scrutinizer Code Quality][ico-scrutinizer-quality]][link-scrutinizer]
[![Code Coverage][ico-scrutinizer-coverage]][link-scrutinizer]
[![Open Issues][ico-issues]][link-issues]
[![Total Downloads][ico-downloads]][link-downloads]

Service for keeping database connection timezone synchronized to PHP default timezone.

## Purpose

There are at least two different approaches for handling database fields of
`timestamp` type in projects:

- fetch data using some predefined timezone offset and adjust resulted string
  representation values with JavaScript or PHP;
- adjust database connection by setting its timezone offset, then fetch timestamp
  values with desired string representation.

Obviously there are pros and cons for both approaches, but if there is a
requirement to support LIKE matching (e.g.: 2019-06-% or %19-06%), the first
approach sems to be quite challenging.

This package aims to implement the second approach.

Storage engines store these values as 32bits unsigned integers, and string
representation (YYYY-MM-DD HH:MM:SS) depends on the connection-specific timezone
offset. Select, update, where expressions could provide unexpected results without
appropriate caution.

Under the hood `Illuminate\Database\MySqlConnection` gets extended and `getPdo()`
and `getReadPdo()` methods get overwritten by a change detector and actuator logic.
When mentioned methods are invoked, underlying PDO object gets checked for
replacement and PHP default timezone offset (`date('P')`) gets compared to
previously set offset value. In case of change is detected, `'SET time_zone = "+hh:mm"'`
is executed on the specific PDO instance, where +hh:mm equals to `date('P')`.

This makes developer able to simply update PHP default time zone with
`date_default_timezone_set(...)` and database queries executed afterwards
will be affected by the new offset. Effect is limited for `timestamp` fields,
`datetime` is not affected.

## Requirements

- PHP >= 7.1
- Laravel >= 5.5

See [PHP and Laravel unit and integration tests](#php-and-laravel-unit-and-integration-tests)

## Install

Via Composer

``` bash
$ composer require zssarkany/laravel-database-sticky-timezone
```

It is not necessary to add service provider to `config/app.php`, but if service
discovery is disabled via `composer.json`,
`ZsSarkany\LaravelDatabaseStickyTimezone\DatabaseStickyTimezoneServiceProvider::class`
needs to be added before `Illuminate\Database\DatabaseServiceProvider::class` to
`providers` array, otherwise the the connection resolver will be bound after
the database connection is resolved already.

## Usage

Append `sticky_timezone => true` to mysql connection configuration in
`config/database.php` to enable automatic timezone adjustment.

Example:
``` php
return [
    // ...
    'connections' => [
        // ...
        'mysql' => [
            // ...
            'sticky_timezone' => true,
        ],
        // ...
    ],
    // ...
];
```

SQL statements executed on this connection are taking PHP default timezone
into account for timestamp fields.

## PHP and Laravel unit and integration tests

Package is tested with the following conditions:

|PHP|Laravel|Stability|
|:-:|:-:|:-:|
|7.1|5.5|stable|
|7.1|5.6|stable|
|7.1|5.7|stable|
|7.1|5.8|stable|
|7.2|5.5|stable|
|7.2|5.6|stable|
|7.2|5.7|stable|
|7.2|5.8|stable|
|7.2|6.0|stable|
|7.3|5.5|stable|
|7.3|5.6|stable|
|7.3|5.7|stable|
|7.3|5.8|stable|
|7.3|6.0|stable|

## Supported drivers

Package currently support MySQL driver only, but it should be easy to implement
support for other drivers, which are provided by Laravel.

Contributions are welcome :)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email zsolt.sarkany@gmail.com instead of using the issue tracker.

## Credits

- [Zsolt Sarkany][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/zssarkany/laravel-database-sticky-timezone.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/com/zssarkany/laravel-database-sticky-timezone/master.svg?style=flat-square
[ico-styleci]: https://github.styleci.io/repos/190005727/shield?branch=master
[ico-scrutinizer-quality]: https://scrutinizer-ci.com/g/zssarkany/laravel-database-sticky-timezone/badges/quality-score.png?b=master
[ico-scrutinizer-coverage]: https://scrutinizer-ci.com/g/zssarkany/laravel-database-sticky-timezone/badges/coverage.png?b=master
[ico-issues]: https://img.shields.io/github/issues-raw/zssarkany/laravel-database-sticky-timezone.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/zssarkany/laravel-database-sticky-timezone.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/zssarkany/laravel-database-sticky-timezone
[link-travis]: https://travis-ci.com/zssarkany/laravel-database-sticky-timezone
[link-styleci]: https://github.styleci.io/repos/190005727
[link-scrutinizer]: https://scrutinizer-ci.com/g/zssarkany/laravel-database-sticky-timezone/?branch=master
[link-issues]: https://github.com/zssarkany/laravel-database-sticky-timezone/issues
[link-downloads]: https://packagist.org/packages/zssarkany/laravel-database-sticky-timezone
[link-author]: https://github.com/zssarkany
[link-contributors]: ../../contributors
