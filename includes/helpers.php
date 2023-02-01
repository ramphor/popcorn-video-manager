<?php
function get_real_ip_address()
{
    $ip_headers = array(
        'HTTP_CF_IPCOUNTRY',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    );

    foreach ($ip_headers as $ip_header) {
        if (!empty($_SERVER[$ip_header])) {
            return $_SERVER[$ip_header];
        }
    }
    return '127.0.0.1';
}

function assetUrl($path = '')
{
    $abspath = constant('ABSPATH');
    $embratiAbspath = dirname(__DIR__);
    if (PHP_OS === 'WINNT') {
        $abspath = str_replace('\\', '/', $abspath);
        $embratiAbspath = str_replace('\\', '/', $embratiAbspath);
    }
    $assetUrl = str_replace($abspath, site_url('/'), $embratiAbspath);

    return sprintf(
        '%s/assets/%s',
        $assetUrl,
        $path
    );
}
