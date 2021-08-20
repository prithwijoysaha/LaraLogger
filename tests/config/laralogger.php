<?php


/**
 * Read Before Customization
 * -------------------------
 * PARAMETER [  userId  ] : By default useId set as Auth()->id keep it NULL. if using Auth userId (As Integer)
 * If userId is not integer change the migration also.
 *
 * PARAMETER [  geoLocationApi  ] : By default using the 'http://www.geoplugin.net/php.gp?ip=' (An open source API)
 * If you found any better open source resource, please request it from git.
 *
 * PARAMETER [  ispApi  ] : By default using the 'http://ip-api.com/php/' (An open source API)
 * If you found any better open source resource, please request it from git.
 *
 * Please encourage by giving a rating to do this kind of open source project.
 * API customization features coming soon.
 */

return [
    'userId' => NULL,
    'geoLocationApi' => 'http://www.geoplugin.net/php.gp?ip=',
    'ispApi' => 'http://ip-api.com/php/',
];