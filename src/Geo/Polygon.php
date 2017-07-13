<?php

/**
 * ساختار داده‌ای یک مکان را تعیین می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Geo_Polygon extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'geo_polygon';
        $this->_a['engine'] = 'MyISAM';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'polygon' => array(
                        'type' => 'Geo_DB_Field_Polygon',
                        'blank' => false
                )
        );
        $this->_a['idx'] = array();
        $this->_a['views'] = array();
    }
}