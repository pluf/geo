<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Geo_SearchStatistic extends Pluf_Model
{

    /**
     *
     * {@inheritdoc}
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'geo_searchstatistic';
        $this->_a['model'] = 'Geo_SearchStatistic';
        $this->_model = 'Geo_SearchStatistic';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true
            ),
            'user' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'User',
                'blank' => true
            ),
            'tag_key' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 100
            ),
            'tag_value' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 100
            ),
            'spa' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 100
            ),
            'device' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 100
            ),
            'latitude' => array(
                'type' => 'Pluf_DB_Field_Float',
                'blank' => true
            ),
            'longitude' => array(
                'type' => 'Pluf_DB_Field_Float',
                'blank' => true
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true
            )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
    }
}