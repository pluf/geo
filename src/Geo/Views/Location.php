<?php
Pluf::loadFunction('User_Shortcuts_UpdateLeveFor');
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

Pluf::loadFunction('KM_Shortcuts_GetLabelOr404');

Pluf::loadFunction('Geo_Shortcuts_locationBound');
Pluf::loadFunction('Geo_Shortcuts_GetLocationOr404');

/**
 * لایه نمایش برای دستری به مکان‌ها را ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Geo_Views_Location
{

    /**
     * مکان مورد نظر کاربر را جستجو می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function find ($request, $match)
    {
        /*
         * XXX: maso, 1394: استفاده از برچسب‌ها در جستجو
         *
         * هر مکان با استفاده از یک برچسب تعیین دسته بندی می‌شود. این برچسب‌ها
         * در حال
         * حاضر در جستجو استفاده نمی‌شود؟!
         */
        $count = 10;
        if (array_key_exists("_px_c", $request->REQUEST)) {
            $count = $request->REQUEST["count"];
        }
        $count = Geo_Shortcuts_locationCount($request, $count);
        $distance = 1000;
        if (array_key_exists("radius", $request->REQUEST)) {
            $distance = $request->REQUEST["radius"];
        }
        $distance = Geo_Shortcuts_locationRadios($request, $distance);
        
        if (! array_key_exists('latitude', $request->REQUEST)) {
            throw new Pluf_Exception("Latitude is not defined.", 4000, null, 405, 
                    "/");
        }
        $latitude = $request->REQUEST['latitude'];
        if (! array_key_exists('longitude', $request->REQUEST)) {
            throw new Pluf_Exception("Longitude is not defined.", 4000, null, 
                    405, "/");
        }
        $longitude = $request->REQUEST['longitude'];
        $bound = Geo_Shortcuts_locationBound($request, $latitude, $longitude, 
                $distance);
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        // Paginator to paginate messages
        $pag = new Pluf_Paginator(new Geo_Point());
        $pag->list_filters = array(
                'reporter',
                'community'
        );
        $pag->forced_where = new Pluf_SQL(
                'jayab_location.tenant=%s AND latitude<%s AND latitude>%s AND longitude<%s AND longitude>%s', 
                array(
                        $request->tenant->id,
                        $bound['max']['lat'],
                        $bound['min']['lat'],
                        $bound['max']['long'],
                        $bound['min']['long']
                ));
        $tag = new SaaSKM_Tag();
        if (array_key_exists('tag_key', $request->REQUEST) &&
                 array_key_exists('tag_key', $request->REQUEST)) {
            $pag->model_view = 'with_tag';
            $tag = SaaSKM_Tag::getFromString($request->tenant, 
                    $request->REQUEST['tag_key'] . '.' .
                             $request->REQUEST['tag_value']);
            if(!$tag){
                throw new Pluf_Exception_DoesNotExist("Tag not found");
            }
            $pag->forced_where->SAnd(
                    new Pluf_SQL('tag=%s', 
                            array(
                                    $tag->id
                            )));
        }
        $list_display = array(
                'title' => __('location title'),
                'description' => __('description')
        );
        $search_fields = array(
                'name',
                'description'
        );
        $sort_fields = array(
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $count;
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        
        // // Add statistic
        // $stat = new Geo_SearchStatistic();
        // $stat->user = $request->user;
        // $stat->application = $request->application;
        // $stat->tag = $tag;
        // if (array_key_exists('spa', $request->REQUEST))
        // $stat->spa = $request->REQUEST['spa'];
        // if (array_key_exists('device', $request->REQUEST))
        // $stat->spa = $request->REQUEST['device'];
        // $stat->latitude = $latitude;
        // $stat->longitude = $longitude;
        // $stat->create();
        
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * فهرستی از تمام مکان‌های موجود
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function locations ($request, $match)
    {
        $count = Geo_Shortcuts_locationCount($request);
        // Paginator to paginate messages
        $pag = new Pluf_Paginator(new Geo_Point());
        $pag->list_filters = array(
                'reporter',
                'community'
        );
        $sql = new Pluf_SQL('tenant=%s', 
                array(
                        $request->tenant->id
                ));
        $pag->forced_where = $sql;
        $list_display = array(
                'title' => __('location title'),
                'description' => __('description')
        );
        $search_fields = array(
                'name',
                'description'
        );
        $sort_fields = array(
                'name',
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $count;
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * یک مکان جدید را ایجاد می کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function create ($request, $match)
    {
        $extra = array(
                'user' => $request->user,
                'tenant' => $request->tenant
        );
        $form = new Geo_Form_Location(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $cuser = $form->save();
        $request->user->setMessage(
                sprintf(__('the location %s has been created.'), 
                        (string) $cuser->name));
        
        User_Shortcuts_UpdateLeveFor($request->user, "jahanjoo_location_add");
        // Return response
        return new Pluf_HTTP_Response_Json($cuser);
    }

    /**
     * به روز کردن اطلاعات یک مکان
     *
     * با استفاده از این فراخوانی اطلاعات یک مکان را به روز می‌کند. کابر باید
     * دسترسی‌های مجاز را داشته باشد.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function update ($request, $match)
    {
        // Get data
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        // Check access
        Geo_Precondition::canEditLocation($request, $location);
        // Do
        $extra = array(
                'user' => $request->user,
                'location' => $location
        );
        $form = new Geo_Form_LocationUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $new_location = $form->update();
        $request->user->setMessage(
                sprintf(__('the location %s has been updated.'), 
                        (string) $new_location->name));
        return new Pluf_HTTP_Response_Json($new_location);
    }

    /**
     * اطلاعات یک مکان را دریافت می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        Geo_Precondition::canAccessLocation($request, $location);
        return new Pluf_HTTP_Response_Json($location);
    }

    /**
     * اطلاعات یک مکان را حذف می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function delete ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        // بررسی دسترسی دستکاری داده‌ها
        Geo_Precondition::canDeleteLocation($request, $location);
        $tl = new Geo_Point($location->id);
        $tl->id = 0;
        $location->delete();
        $request->user->setMessage(
                sprintf(__('the location %s has been deleted.'), 
                        (string) $tl->name));
        return new Pluf_HTTP_Response_Json($tl);
    }
}