<?php

/**
 * Geometry value
 *
 * @author maso
 *
 */
class Geo_DB_Field_Point extends Pluf_DB_Field
{

    public $type = 'geometry';

    public $extra = array();

    /**
     * Gets form field
     * 
     * {@inheritDoc}
     * @see Pluf_DB_Field::formField()
     */
    function formField ($def, $form_field = 'Geo_Form_Field_Geometry')
    {
        return parent::formField($def, $form_field);
    }
}
