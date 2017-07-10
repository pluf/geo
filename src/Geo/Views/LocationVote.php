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
class Geo_Views_LocationVote
{

    /**
     * تعیین خلاصه از ارائه داده شده
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function find ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        $count = 10;
        $pag = new Pluf_Paginator(new SaaSKM_Vote());
        $pag->list_filters = array(
                'voter'
        );
        $sql = new Pluf_SQL('tenant=%s AND owner_class=%s AND owner_id=%s', 
                array(
                        $request->tenant->id,
                        'Geo_Location',
                        $location->id
                ));
        $pag->forced_where = $sql;
        $list_display = array(
                'vote_value' => __('location title'),
                'vote_comment' => __('description')
        );
        $search_fields = array(
                'voter',
                'vote_comment'
        );
        $sort_fields = array(
                'voter',
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
     * اطلاعات یک رای را به روز می‌کند.
     *
     * در صورتی که رای وجود نداشته باشد آن را ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function create ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        $vote = $location->getUserVote($request->tenant, $request->user);
        $extra = array(
                'user' => $request->user,
                'tenant' => $request->tenant,
                'location' => $location,
                'vote' => $vote
        );
        $form = new Geo_Form_Vote(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $old = $vote;
        if ($vote === null) {
            $vote = $form->save();
//             $old = new SaaSKM_Vote();
//             $old->vote_value = ! $vote->vote_value;
//             User_Shortcuts_UpdateLeveFor($request, "jahanjoo_location_like");
        } else {
            $vote = $form->update();
        }
        
        if ($old->vote_value != $vote->vote_value) {
//             User_Shortcuts_UpdateLeveFor($location->get_reporter(), 
//                     "jahanjoo_location_like", $vote->like);
        }
        return new Pluf_HTTP_Response_Json($vote);
    }


    /**
     * رای کاربر به مکان مورد نظر را تعیین می‌کند.
     *
     * @param unknown $request
     * @param unknown $match
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    function myVote ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        $vote = $location->getUserVote($request->tenant, $request->user);
        if ($vote != null) {
            return new Pluf_HTTP_Response_Json($vote);
        }
        throw new Pluf_Exception_DoesNotExist();
    }

    /**
     * رای مربوط به مکان را حذف می‌کند
     *
     * این فراخوانی رای کاربر جاری به مکان تعیین شده را حذف می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    function delete ($request, $match)
    {
        $location = Geo_Shortcuts_GetLocationOr404($match[1]);
        $vote = $location->getUserVote($request->tenant, $request->user);
        if ($vote != null) {
//             User_Shortcuts_UpdateLeveFor($request->user, 
//                     "jahanjoo_location_like", false);
//             User_Shortcuts_UpdateLeveFor($location->get_reporter(), 
//                     "jahanjoo_location_like", ! $vote->like);
            $vote->delete();
            return new Pluf_HTTP_Response_Json($location);
        }
        throw new Pluf_Exception_DoesNotExist();
    }
}