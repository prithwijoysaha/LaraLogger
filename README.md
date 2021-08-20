# LaraLogger - Track Users' Activity

[![Issues](https://img.shields.io/github/issues/prithwijoysaha/LaraLogger?style=for-the-badge)](https://github.com/prithwijoysaha/LaraLogger/issues) [![Stars](https://img.shields.io/github/stars/prithwijoysaha/LaraLogger?style=for-the-badge)](https://github.com/prithwijoysaha/LaraLogger/issues)

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
