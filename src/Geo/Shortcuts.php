<?php

/**
 * مدل داده‌ای مکان را ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return Pluf_User
 */
function Geo_Shortcuts_locationFactory ($object)
{
    if ($object == null || ! isset($object))
        return new Geo_Location();
    return $object;
}

/**
 * بیشترین و کمترین مقدار در نقاط جستجو را تعیین می‌کند.
 *
 * @param unknown $request            
 * @param unknown $lat            
 * @param unknown $long            
 * @param unknown $meters            
 * @return number
 */
function Geo_Shortcuts_locationBound ($request, $lat, $long, $meters = 1000)
{
    $equator_circumference = 6371000; // meters
    $polar_circumference = 6356800; // meters
    
    $m_per_deg_long = 360 / $polar_circumference;
    
    $rad_lat = ($lat * M_PI / 180);
    $m_per_deg_lat = 360 / (cos($rad_lat) * $equator_circumference);
    
    $deg_diff_long = $meters * $m_per_deg_long;
    $deg_diff_lat = $meters * $m_per_deg_lat;
    
    $coordinates['max']['lat'] = $lat + $deg_diff_lat;
    $coordinates['max']['long'] = $long + $deg_diff_long;
    
    $coordinates['min']['lat'] = $lat - $deg_diff_lat;
    $coordinates['min']['long'] = $long - $deg_diff_long;
    
    return $coordinates;
}

/**
 * بر اساس سطح دسترسی کاربر تعداد مکان‌های قابل دسترسی را تعیین می‌کند.
 *
 * @param unknown $request            
 * @param number $count            
 * @return number
 */
function Geo_Shortcuts_locationCount ($request)
{
    $count = 10;
    if (array_key_exists("_px_c", $request->REQUEST)) {
        $count = $request->REQUEST["_px_c"];
    }
    if ($count <= 10)
        return 10;
    if ($request->user->isAnonymous())
        return 10;
    $MAX_LEVEL = 10000.0;
    $MAX = 50.0;
    $level = 0;
    try {
        $level = $request->user->getProfile()->level;
    } catch (Exception $e) {}
    $c = ($MAX * $level / $MAX_LEVEL) + 10;
    if ($c > $MAX)
        $c = $MAX;
    if ($count < $c)
        return $count;
    return $c;
}

/**
 * محدوده جستجو را تعیین می‌کند.
 *
 * @param unknown $request            
 * @param number $radius            
 */
function Geo_Shortcuts_locationRadios ($request, $radius = 1000)
{
    if ($radius < 1000)
        return $radius;
    if ($request->user->isAnonymous())
        return 1000;
    $MAX_LEVEL = 10000.0;
    $MAX = 15000.0;
    $level = 0;
    try {
        $level = $request->user->getProfile()->level;
    } catch (Exception $e) {}
    $c = ($MAX * $level / $MAX_LEVEL) + 1000;
    if ($c > $MAX)
        $c = $MAX;
    if ($radius < $c)
        return $radius;
    return $c;
}

/**
 *
 * @param unknown $id            
 * @throws Pluf_HTTP_Error404
 */
function Geo_Shortcuts_GetLocationOr404 ($id)
{
    $item = new Geo_Location($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404(sprintf(__("location not found (%s)"), $id), 
            4311);
}

function Geo_Shortcuts_LoadJson ($tenant, $user, $filePath)
{
    $myfile = fopen($filePath, "r") or die("Unable to open file!");
    $json = fread($myfile, filesize($filePath));
    fclose($myfile);
    $gosm = json_decode($json, true);
    if (! array_key_exists('elements', $gosm)) {
        return;
    }
    $TAG = new SaaSKM_Tag();
    foreach ($gosm['elements'] as $node) {
        if (array_key_exists('type', $node) && $node['type'] == 'node') {
            // create location
            $location = new Geo_Location();
            $location->reporter = $user;
            $location->community = $user->administrator;
            if (array_key_exists('tags', $node) &&
                     array_key_exists('name', $node['tags']))
                $location->name = $node['tags']['name'];
            $location->description = '';
            $location->latitude = $node['lat'];
            $location->longitude = $node['lon'];
            $location->tenant = $tenant;
            $location->create();
            
            // add tags
            if (array_key_exists('tags', $node)) {
                foreach ($node['tags'] as $tk => $tv) {
                    try {
                        $tag = $tk . '.' . $tv;
                        SaaSKM_TagRow::add($tenant, $location, $tag, true);
                    } catch (Exception $e) {
                        var_dump($e);
                    }
                }
            }
        }
    }
}
