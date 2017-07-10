<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

Pluf::loadFunction('SaaSKM_Shortcuts_GetTagOr404');

Pluf::loadFunction('User_Shortcuts_UpdateLeveFor');

Pluf::loadFunction('KM_Shortcuts_GetLabelOr404');

Pluf::loadFunction('Geo_Shortcuts_locationBound');
Pluf::loadFunction('Geo_Shortcuts_GetLocationOr404');

/**
 * لایه نمایش برای دستکاری تگ‌های مکان
 *
 * متدهای مورد نیاز برای مدیریت برچسب‌های یک مکان پیاده سازی شده است.
 *
 * @author maso
 *        
 */
class Geo_Views_LocationTag
{

    /**
     * فهرست برچسب‌هایی را تعیین می‌کند که به یک مگان داده شده است.
     * 
     * @param unknown $request
     * @param unknown $match
     * @return Pluf_HTTP_Response_Json
     */
    public function tags ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        Geo_Precondition::canEditLocation($request, $location);
        // Pluf pagination
        $pag = new Pluf_Paginator(new SaaSKM_Tag());
        $pag->model_view = 'join_row';
        $pag->forced_where = new Pluf_SQL(
                'saaskm_tag.tenant=%s AND owner_class=%s AND owner_id=%s', 
                array(
                        $request->tenant->id,
                        $location->_a['model'],
                        $location->id
                ));
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
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * یک تگ به مکان اضافه می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function addTag ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        Geo_Precondition::canEditLocation($request, $location);
        $tag = SaaSKM_Shortcuts_GetTagOr404($match[2]);
        SaaSKM_Precondition::userCanAccessTag($request, $tag); 
        SaaSKM_TagRow::add($request->tenant, $location, $tag);
        return new Pluf_HTTP_Response_Json($location);
    }

    /**
     * یک تگ را از مکان حذف می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function deleteTag ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        Geo_Precondition::canEditLocation($request, $location);
        $tag = SaaSKM_Shortcuts_GetTagOr404($match[2]);
        SaaSKM_TagRow::remove($request->tenant, $location, $tag);
        return new Pluf_HTTP_Response_Json($location);
    }

    /**
     * یک تگ را به مکان اضافه می‌کند
     *
     * در این روش تگ با استفاده از کلدی و مقدار تعیین مس‌شود.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function addTagBykeyvalue ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        Geo_Precondition::canEditLocation($request, $location);
        $tag = SaaSKM_Tag::getFromString($request->tenant, $request->REQUEST['tag']);
        if(! $tag){
            throw new Pluf_Exception("Tag not found");
        }
        SaaSKM_Precondition::userCanAccessTag($request, $tag);
        SaaSKM_TagRow::add($request->tenant, $location, $tag);
        return new Pluf_HTTP_Response_Json($location);
    }

    /**
     * یک تگ را از یک مکان حذف می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function deleteTagBykeyvalue ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        Geo_Precondition::canEditLocation($request, $location);
        $tag = SaaSKM_Tag::getFromString($request->tenant, $request->REQUEST['tag']);
        if(! $tag){
            throw new Pluf_Exception("Tag not found");
        }
        // XXX: maso, 1394: بررسی وجود برچسب
        SaaSKM_TagRow::remove($request->tenant, $location, $tag);
        return new Pluf_HTTP_Response_Json($location);
    }
}