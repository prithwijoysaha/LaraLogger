<?php

namespace prithwijoysaha\laralogger;

use prithwijoysaha\laralogger\Models\ActivityLog;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use ReflectionClass;

trait LaraLogger
{
    protected static function bootLaraLogger()
    {
        try {
            foreach (static::getRecordActivityEvents() as $eventName) {
                static::$eventName(function (Model $model) use ($eventName) {
                    try {
                        $reflect = new ReflectionClass($model);
                        $action = static::getActionName($eventName);
                        $geoLocation = static::getGeoLocation();
                        $isp = static::getISP();

                        if (!empty($geoLocation)) {
                            $latitude = (!empty($geoLocation['geoplugin_latitude']) && !empty($geoLocation['geoplugin_longitude']) ? $geoLocation['geoplugin_latitude'] : (!empty($isp['lat']) && !empty($isp['lon']) ? $isp['lat'] : NULL));
                            $longitude = (!empty($geoLocation['geoplugin_longitude']) && !empty($geoLocation['geoplugin_latitude']) ? $geoLocation['geoplugin_longitude'] : (!empty($isp['lon']) && !empty($isp['lat']) ? $isp['lon'] : NULL));
                            $city = (!empty($geoLocation['geoplugin_city']) ? $geoLocation['geoplugin_city'] : (!empty($isp['city']) ? $isp['city'] : NULL));
                            $region = (!empty($geoLocation['geoplugin_region']) ? $geoLocation['geoplugin_region'] : NULL);
                            $region_code = (!empty($geoLocation['geoplugin_regionCode']) ? $geoLocation['geoplugin_regionCode'] : (!empty($isp['region']) ? $isp['region'] : NULL));
                            $region_name = (!empty($geoLocation['geoplugin_regionName']) ? $geoLocation['geoplugin_regionName'] : (!empty($isp['regionName']) ? $isp['regionName'] : NULL));
                            $area_code = (!empty($geoLocation['geoplugin_areaCode']) ? $geoLocation['geoplugin_areaCode'] : NULL);
                            $dma_code = (!empty($geoLocation['geoplugin_dmaCode']) ? $geoLocation['geoplugin_dmaCode'] : NULL);
                            $country_code = (!empty($geoLocation['geoplugin_countryCode']) ? $geoLocation['geoplugin_countryCode'] : (!empty($isp['countryCode']) ? $isp['countryCode'] : NULL));
                            $country_name = (!empty($geoLocation['geoplugin_countryName']) ? $geoLocation['geoplugin_countryName'] : (!empty($isp['country']) ? $isp['country'] : NULL));
                            $continent_code = (!empty($geoLocation['geoplugin_continentCode']) ? $geoLocation['geoplugin_continentCode'] : NULL);
                            $continent_name = (!empty($geoLocation['geoplugin_continentName']) ? $geoLocation['geoplugin_continentName'] : NULL);
                            $timezone = (!empty($geoLocation['geoplugin_timezone']) ? $geoLocation['geoplugin_timezone'] : NULL);
                            $location_accuracy_radius = (!empty($geoLocation['geoplugin_locationAccuracyRadius']) ? $geoLocation['geoplugin_locationAccuracyRadius'] : NULL);
                            $geo_location_service_delay = (!empty($geoLocation['geoplugin_delay']) ? $geoLocation['geoplugin_delay'] : NULL);
                            $geo_location_service_status = (!empty($geoLocation['geoplugin_status']) ? $geoLocation['geoplugin_status'] : NULL);
                        }
                        if (!empty($isp)) {
                            $isp_service_status = (!empty($isp['status']) ? $isp['status'] : NULL);
                            $isp_name = (!empty($isp['isp']) ? $isp['isp'] : NULL);
                            $isp_organization = (!empty($isp['org']) ? $isp['org'] : NULL);
                            $isp_autonomous_system_number = (!empty($isp['as']) ? $isp['as'] : NULL);
                        }

                        $return  = ActivityLog::create([
                            'user_id'                       => empty(config('laralogger.userId')) ? Auth::check() ? Auth::id() : NULL : config('laralogger.userId'),
                            'table_id'                      => $model->id,
                            'table'                         => $model->getTable(),
                            'model'                         => "\\" . get_class($model),
                            'action'                        => $action,
                            'description'                   => ucfirst($eventName) . " a " . $reflect->getShortName(),
                            'old_data'                      => ($action == 'update') ? json_encode(static::getOldData($model)) : NULL,
                            'new_data'                      => ($action != 'delete' && $action != 'restore') ? json_encode(static::getNewData($model)) : NULL,
                            'url'                           => Request::fullUrl(),
                            'method'                        => Request::method(),
                            'ip'                            => static::getIP(),
                            'os'                            => static::getOS(),
                            'browser'                       => static::getBrowsers(),
                            'device'                        => static::getDevice(),
                            'agent'                         => Request::header('user-agent'),
                            'latitude'                      => (!empty($latitude) ? $latitude : NULL),
                            'longitude'                     => (!empty($longitude) ? $longitude : NULL),
                            'city'                          => (!empty($city) ? $city : NULL),
                            'region'                        => (!empty($region) ? $region : NULL),
                            'region_code'                   => (!empty($region_code) ? $region_code : NULL),
                            'region_name'                   => (!empty($region_name) ? $region_name : NULL),
                            'area_code'                     => (!empty($area_code) ? $area_code : NULL),
                            'dma_code'                      => (!empty($dma_code) ? $dma_code : NULL),
                            'country_code'                  => (!empty($country_code) ? $country_code : NULL),
                            'country_name'                  => (!empty($country_name) ? $country_name : NULL),
                            'continent_code'                => (!empty($continent_code) ? $continent_code : NULL),
                            'continent_name'                => (!empty($continent_name) ? $continent_name : NULL),
                            'timezone'                      => (!empty($timezone) ? $timezone : NULL),
                            'location_accuracy_radius'      => (!empty($location_accuracy_radius) ? $location_accuracy_radius : NULL),
                            'geo_location_service_delay'    => (!empty($geo_location_service_delay) ? $geo_location_service_delay : NULL),
                            'geo_location_service_status'   => (!empty($geo_location_service_status) ? $geo_location_service_status : NULL),
                            'isp_name'                      => (!empty($isp_name) ? $isp_name : NULL),
                            'isp_organization'              => (!empty($isp_organization) ? $isp_organization : NULL),
                            'isp_autonomous_system_number'  => (!empty($isp_autonomous_system_number) ? $isp_autonomous_system_number : NULL),
                            'isp_service_status'            => (!empty($isp_service_status) ? $isp_service_status : NULL),
                        ]);
                        if ($action == 'restore') {
                            ActivityLog::where('table_id', $model->id)->where('table', $model->getTable())->where('action', 'delete')->delete();
                        }

                        return $return;
                    } catch (Exception $e) {
                        if (App::environment(['local', 'staging'])) {
                            throw $e;
                        } else {
                            Log::error($e);
                            return true;
                        }
                    }
                });
            }
        } catch (Exception $e) {
            if (App::environment(['local', 'staging'])) {
                dd("LaraLogger Exception (Please Report Of This Exception. This Open Source Project Can Be Improved). Exception Message:- " . $e);
            } else {
                Log::error($e);
                return true;
            }
        }
    }

    private static function getRecordActivityEvents()
    {
        try {
            static::restored(function (Model $model) {
            });
            return [
                'created',
                'updated',
                'deleted',
                'restored',
            ];
        } catch (Exception $e) {
            return [
                'created',
                'updated',
                'deleted',
            ];
        }
    }

    private static function getActionName($event)
    {
        switch (strtolower($event)) {
            case 'created':
                return 'create';
                break;
            case 'updated':
                return 'update';
                break;
            case 'deleted':
                return 'delete';
                break;
            case 'restored':
                return 'restore';
                break;
            default:
                return 'unknown';
        }
    }
    private static function getOldData($model)
    {
        try {
            $dirty = $model->getDirty();
            $oldDataSet = [];
            if (!empty($dirty)) {
                $dirtyField = array_keys($dirty);

                foreach ($dirtyField as $value) {
                    $oldDataSet[$value] = $model->getOriginal($value);
                }
            }
            return $oldDataSet;
        } catch (Exception $e) {
            Log::error($e);
            return NULL;
        }
    }
    private static function getNewData($model)
    {
        try {
            return $model->getDirty();
        } catch (Exception $e) {
            Log::error($e);
            return NULL;
        }
    }


    private static function getIP()
    {
        $ip = '';
        if (!empty(Request::server('HTTP_CLIENT_IP')))
            $ip = Request::server('HTTP_CLIENT_IP');
        else if (!empty(Request::server('HTTP_X_FORWARDED_FOR')))
            $ip = Request::server('HTTP_X_FORWARDED_FOR');
        else if (!empty(Request::server('HTTP_X_FORWARDED')))
            $ip = Request::server('HTTP_X_FORWARDED');
        else if (!empty(Request::server('HTTP_FORWARDED_FOR')))
            $ip = Request::server('HTTP_FORWARDED_FOR');
        else if (!empty(Request::server('HTTP_FORWARDED')))
            $ip = Request::server('HTTP_FORWARDED');
        else if (!empty(Request::server('REMOTE_ADDR')))
            $ip = Request::server('REMOTE_ADDR');
        else
            $ip = 'UNKNOWN';
        return $ip;
    }

    private static function getOS()
    {
        try {
            $user_agent = Request::header('user-agent');
            $os_platform = "Unknown OS Platform";
            $os_array = [
                '/windows nt 10/i'  => 'Windows 10',
                '/windows nt 6.3/i'  => 'Windows 8.1',
                '/windows nt 6.2/i'  => 'Windows 8',
                '/windows nt 6.1/i'  => 'Windows 7',
                '/windows nt 6.0/i'  => 'Windows Vista',
                '/windows nt 5.2/i'  => 'Windows Server 2003/XP x64',
                '/windows nt 5.1/i'  => 'Windows XP',
                '/windows xp/i'  => 'Windows XP',
                '/windows nt 5.0/i'  => 'Windows 2000',
                '/windows me/i'  => 'Windows ME',
                '/win98/i'  => 'Windows 98',
                '/win95/i'  => 'Windows 95',
                '/win16/i'  => 'Windows 3.11',
                '/macintosh|mac os x/i' => 'Mac OS X',
                '/mac_powerpc/i'  => 'Mac OS 9',
                '/linux/i'  => 'Linux',
                '/ubuntu/i'  => 'Ubuntu',
                '/iphone/i'  => 'iPhone',
                '/ipod/i'  => 'iPod',
                '/ipad/i'  => 'iPad',
                '/android/i'  => 'Android',
                '/blackberry/i'  => 'BlackBerry',
                '/webos/i'  => 'Mobile',
            ];

            foreach ($os_array as $regex => $value) {
                if (preg_match($regex, $user_agent)) {
                    $os_platform = $value;
                }
            }
            return $os_platform;
        } catch (Exception $e) {
            Log::error($e);
            return 'Unknown OS Platform';
        }
    }

    private static function getBrowsers()
    {
        try {
            $user_agent = Request::header('user-agent');

            $browser = "Unknown Browser";

            $browser_array = [
                '/msie/i'  => 'Internet Explorer',
                '/Trident/i'  => 'Internet Explorer',
                '/firefox/i'  => 'Firefox',
                '/safari/i'  => 'Safari',
                '/chrome/i'  => 'Chrome',
                '/CriOS/i'  => 'Chrome',
                '/edge/i'  => 'Edge',
                '/edg/i'  => 'Edge',
                '/opera/i'  => 'Opera',
                '/opr/i'  => 'Opera',
                '/netscape/'  => 'Netscape',
                '/maxthon/i'  => 'Maxthon',
                '/knoqueror/i'  => 'Konqueror',
                '/ubrowser/i'  => 'UC Browser',
                '/UCBrowser/i'  => 'UC Browser',
                '/mobile/i'  => 'Safari Browser',
            ];

            foreach ($browser_array as $regex => $value) {
                if (preg_match($regex, $user_agent)) {
                    $browser = $value;
                }
            }
            return $browser;
        } catch (Exception $e) {
            Log::error($e);
            return 'Unknown Browser';
        }
    }

    private static function getDevice()
    {
        try {
            $tablet_browser = 0;
            $mobile_browser = 0;

            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower(Request::server('HTTP_USER_AGENT')))) {
                $tablet_browser++;
            }

            if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower(Request::server('HTTP_USER_AGENT')))) {
                $mobile_browser++;
            }

            if ((strpos(
                    strtolower(Request::server('HTTP_ACCEPT')),
                    'application/vnd.wap.xhtml+xml'
                ) > 0) || ((!empty(Request::server('HTTP_X_WAP_PROFILE')) || !empty(Request::server('HTTP_PROFILE'))))
            ) {
                $mobile_browser++;
            }

            $mobile_ua = strtolower(substr(Request::header('user-agent'), 0, 4));
            $mobile_agents = [
                'w3c', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac', 'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
                'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-', 'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',

                'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox', 'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',

                'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
                'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
            ];

            if (in_array($mobile_ua, $mobile_agents)) {
                $mobile_browser++;
            }

            if (strpos(strtolower(Request::header('user-agent')), 'opera mini') > 0) {
                $mobile_browser++;
                $stock_ua =
                    strtolower(!empty(Request::server('HTTP_X_OPERAMINI_PHONE_UA')) ?
                        Request::server('HTTP_X_OPERAMINI_PHONE_UA') : (!empty(Request::server('HTTP_DEVICE_STOCK_UA')) ?
                            Request::server('HTTP_DEVICE_STOCK_UA') : ''));

                if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                    $tablet_browser++;
                }
            }

            if ($tablet_browser > 0) {
                return 'Tablet';
            } else if ($mobile_browser > 0) {
                return 'Mobile';
            } else {
                return 'Computer';
            }
        } catch (Exception $e) {
            Log::error($e);
            return 'Unknown Device';
        }
    }

    private static function getGeoLocation()
    {
        try {
            if (static::getIP() != '127.0.0.1' && static::getIP() != '::1') {
                $response  = Http::timeout(2)->get(config('laralogger.geoLocationApi') . static::getIP());
                if ($response->ok()) {
                    return unserialize($response->body());
                } else {
                    return [];
                }
            } else {
                return [];
            }
        } catch (Exception $e) {
            Log::error($e);
            return [];
        }
    }

    private static function getISP()
    {
        try {
            if (static::getIP() != '127.0.0.1' && static::getIP() != '::1') {
                $response  = Http::timeout(2)->get(config('laralogger.ispApi') . static::getIP());
                if ($response->ok()) {
                    return unserialize($response->body());
                } else {
                    return [];
                }
            } else {
                return [];
            }
        } catch (Exception $e) {
            Log::error($e);
            return [];
        }
    }
}
