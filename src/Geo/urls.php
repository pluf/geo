<?php
return array (
        /*
     * Locations
     */
	array( // جستجوی مکانها
        'regex' => '#^/location/find$#',
        'model' => 'Geo_Views_Location',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array( // فهرست مکانها
        'regex' => '#^/location/list$#',
        'model' => 'Geo_Views_Location',
        'method' => 'locations',
        'http-method' => 'GET'
    ),
    array( // ایجاد یک مکان جدید
        'regex' => '#^/location/create$#',
        'model' => 'Geo_Views_Location',
        'method' => 'create',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    // 'User_Precondition::loginRequired'
    
    array( // لود کردن پرونده‌های جیسان
        'regex' => '#^/location/load/gson$#',
        'model' => 'Geo_Views_LocationBulky',
        'method' => 'loadGsonFile',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // دریافت اطلاعات یک مکان
        'regex' => '#^/location/(\d+)$#',
        'model' => 'Geo_Views_Location',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array()
    ),
    array( // به روز رسانی اطلاعات یک مکان
        'regex' => '#^/location/(\d+)$#',
        'model' => 'Geo_Views_Location',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array()
    ),
    array( // حذف اطلاعات یک مکان
        'regex' => '#^/location/(\d+)$#',
        'model' => 'Geo_Views_Location',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    /*
     * تگ‌های یک مکان
     */
    array( //
        'regex' => '#^/location/(\d+)/tag/find$#',
        'model' => 'Geo_Views_LocationTag',
        'method' => 'tags',
        'http-method' => array(
            'GET'
        ),
        'precond' => array()
    ),
    array( // اضافه کردن یک تگ به یک مکان
        'regex' => '#^/location/(\d+)/tag/(\d+)$#',
        'model' => 'Geo_Views_LocationTag',
        'method' => 'addTag',
        'http-method' => array(
            'POST'
        ),
        'precond' => array()
    ),
    array( // حذف کردن یک تگ به یک مکان
        'regex' => '#^/location/(\d+)/tag/(\d+)$#',
        'model' => 'Geo_Views_LocationTag',
        'method' => 'deleteTag',
        'http-method' => 'DELETE',
        'precond' => array()
    ),
    array( // اضافه کردن یک تگ به یک مکان
        'regex' => '#^/location/(\d+)/tag$#',
        'model' => 'Geo_Views_LocationTag',
        'method' => 'addTagBykeyvalue',
        'http-method' => array(
            'POST'
        ),
        'precond' => array()
    ),
    array( // حذف کردن یک تگ از یک مکان
        'regex' => '#^/location/(\d+)/tag$#',
        'model' => 'Geo_Views_LocationTag',
        'method' => 'deleteTagBykeyvalue',
        'http-method' => array(
            'DELETE'
        ),
        'precond' => array()
    ),
    /*
     * کار با تگ‌ها
     */
    array(
        'regex' => '#^/tag/find$#',
        'model' => 'Geo_Views_Tag',
        'method' => 'find',
        'http-method' => array(
            'GET'
        )
    ),
    array(
        'regex' => '#^/tag/create$#',
        'model' => 'Geo_Views_Tag',
        'method' => 'create',
        'precond' => array(
            'Geo_Precondition::userCanCreateTag'
        ),
        'http-method' => array(
            'POST'
        )
    ),
    array(
        'regex' => '#^/tag/bulkyCreate$#',
        'model' => 'Geo_Views_TagBulky',
        'method' => 'create',
        'precond' => array(
            'Geo_Precondition::userCanCreateTag'
        ),
        'http-method' => array(
            'POST'
        )
    ),
    array(
        'regex' => '#^/tag/(\d+)$#',
        'model' => 'Geo_Views_Tag',
        'method' => 'get',
        'http-method' => array(
            'GET'
        )
    ),
    array(
        'regex' => '#^/tag$#',
        'model' => 'Geo_Views_Tag',
        'method' => 'getByString',
        'http-method' => array(
            'GET'
        )
    ),
    array(
        'regex' => '#^/tag/(\d+)$#',
        'model' => 'Geo_Views_Tag',
        'method' => 'update',
        'precond' => array(
            'User_Precondition::loginRequired'
        ),
        'http-method' => array(
            'POST'
        )
    ),
    array(
        'regex' => '#^/tag/(\d+)$#',
        'model' => 'Geo_Views_Tag',
        'method' => 'delete',
        'precond' => array(
            'User_Precondition::loginRequired'
        ),
        'http-method' => array(
            'DELETE'
        )
    ),
//     /*
//      * 
//      * رای به مکان‌ها
//      */

//     array( // فهرست تمام ارا به یک مکان
//         'regex' => '#^/location/(\d+)/vote/find$#',
//         'model' => 'Geo_Views_LocationVote',
//         'method' => 'find',
//         'http-method' => 'GET'
//     ),
//     array( // گرفتن اطلاعات رای به یک مکان
//         'regex' => '#^/location/(\d+)/vote$#',
//         'model' => 'Geo_Views_LocationVote',
//         'method' => 'myVote',
//         'http-method' => 'GET',
//         'precond' => array(
//             'User_Precondition::loginRequired'
//         )
//     ),
//     array( // به روز کردن و یا ایجاد رای به یک مکان
//         'regex' => '#^/location/(\d+)/vote$#',
//         'model' => 'Geo_Views_LocationVote',
//         'method' => 'create',
//         'http-method' => 'POST',
//         'precond' => array(
//             'User_Precondition::loginRequired'
//         )
//     ),
//     array( // حذف رای کاربر از یک مکان
//         'regex' => '#^/location/(\d+)/vote$#',
//         'model' => 'Geo_Views_LocationVote',
//         'method' => 'delete',
//         'http-method' => 'DELETE',
//         'precond' => array(
//             'User_Precondition::loginRequired'
//         )
//     )
);