# LaraLogger - Track Users' Activity

## Budge 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vendor_name/package_name.svg?style=flat-square)](https://packagist.org/packages/ps/LaraLogger)

[![GitHub Tests Status](https://img.shields.io/github/workflow/status/vendor_name/package_name/Tests?label=Tests)](https://github.com/prithwijoysaha/LaraLogger/actions?query=workflow%3ATests+branch%3Amaster)

## Installation

You can install the package via composer:

```bash
composer require prithwijoysaha/laralogger
```

## Clone Package

```bash
git clone https://github.com/prithwijoysaha/LaraLogger LaraLogger
```

## Usage 
Step 1: RUN Command: ```bash php artisan migrate```
Step 2: Use It On Any Model: ```php use LaraLogger;```
Step 3: Use It On Any Model: ```php use prithwijoysaha\laralogger\LaraLogger;```

## Example (Just Like) : Model\User.php
```php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ps\LaraLogger\LaraLogger;

class User extends Authenticatable
{
    use LaraLogger;
}
```
## Customization:
By default its uses the Auth()->id for userId to customize it just publish it.
After publishing you will find a file named laralogger.php at config directory.

## Thing To Keep In Mind:

1. LaraLogger only works with DML queries of Laravel Eloquent
Example:
```php
User::find(1)->delete();        // For this LaraLogger is made for.
User::where('id',1)->delete();  // For this LaraLogger don't work.
```
2. LaraLogger will average execution time is 10ms approx.
4. LaraLogger wont save the geo-location details and isp-details for localhost/127.0.0.1
5. It can throw exceptions only in local environment. And save exceptions at log file for production environment for smoother experience.
6. LaraLogger by default use two open source APIs:
=> www.geoplugin.net
=> www.ip-api.com
Thanks to geoplugin.net and ip-api.com


## Testing

```bash
composer require prithwijoysaha/laralogger/Test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
