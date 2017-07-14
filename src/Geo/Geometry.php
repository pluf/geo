<?php

/**
 * ساختار داده‌ای یک مکان را تعیین می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Geo_Geometry extends Pluf_Model
{
    
    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'geo_geometry';
        $this->_a['engine'] = 'MyISAM';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'geometry' => array(
                        'type' => 'Geo_DB_Field_Geometry',
                        'blank' => true
                )
        );
        $this->_a['idx'] = array();
        $this->_a['views'] = array();
    }
}