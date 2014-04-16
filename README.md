# PHP EMV

A collection of EMV tools and utilities for PHP 5.3+

## Installation

The recommended way to install PHP EMV is through [Composer][COMPOSER]:

```json
{
    "require": {
        "unwiredbrain/php-emv": "@stable"
    }
}
```

```bash
$ composer install
```

**ProTip** -- avoid the `@stable` keyword, [use a proper version tag][PACKAGIST_VERSION] instead.

[COMPOSER]: https://getcomposer.org/
[PACKAGIST_VERSION]: https://packagist.org/packages/unwiredbrain/php-emv

## Testing

PHP EMV comes with a full-fledged test suite. To run it, install [PHPUnit][PHPUNIT] via [Composer][COMPOSER]:

```bash
$ composer install --dev
$ php vendor/bin/phpunit
```

[PHPUNIT]: http://phpunit.de/
[COMPOSER]: https://getcomposer.org/

## Contributing

See the bundled `CONTRIBUTING` file for details.

## Credits

* [Massimo Lombardo][CREDITS_ML], original author
* [Open source community][CREDITS_OSC]

[CREDITS_ML]: https://github.com/unwiredbrain
[CREDITS_OSC]: https://github.com/unwiredbrain/emv-utils/graphs/contributors

## License

PHP EMV is released under the MIT license. See the bundled `LICENSE` file for details.
