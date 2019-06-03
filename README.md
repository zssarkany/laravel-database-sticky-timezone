# laravel-database-sticky-timezone

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Service for keeping database connection timezone synchronized to PHP default timezone.

## Install

Via Composer

``` bash
$ composer require zssarkany/laravel-database-sticky-timezone
```

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
[ico-travis]: https://img.shields.io/travis/zssarkany/laravel-database-sticky-timezone/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/zssarkany/laravel-database-sticky-timezone.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/zssarkany/laravel-database-sticky-timezone.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/zssarkany/laravel-database-sticky-timezone.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/zssarkany/laravel-database-sticky-timezone
[link-travis]: https://travis-ci.org/zssarkany/laravel-database-sticky-timezone
[link-scrutinizer]: https://scrutinizer-ci.com/g/zssarkany/laravel-database-sticky-timezone/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/zssarkany/laravel-database-sticky-timezone
[link-downloads]: https://packagist.org/packages/zssarkany/laravel-database-sticky-timezone
[link-author]: https://github.com/zssarkany
[link-contributors]: ../../contributors
