<?php
Pluf::loadFunction('User_Shortcuts_UpdateLeveFor');
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

Pluf::loadFunction('KM_Shortcuts_GetLabelOr404');

Pluf::loadFunction('Geo_Shortcuts_locationBound');
Pluf::loadFunction('Geo_Shortcuts_GetLocationOr404');
Pluf::loadFunction('Geo_Shortcuts_LoadJson');

/**
 * لایه نمایش برای دستری به مکان‌ها را ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Geo_Views_LocationBulky
{

    /**
     * یک پرونده بار می‌شود.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function loadGsonFile ($request, $match)
    {
        foreach ($request->FILES as $file) {
            if (is_readable($file['tmp_name'])) {
                Geo_Shortcuts_LoadJson($request->tenant, $request->user, $file['tmp_name']);
            }
        }
        return new Pluf_HTTP_Response_Json(
                new ArrayObject(array(
                        'success' => true
                )));
    }
}