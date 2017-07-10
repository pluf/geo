<?php

/**
 * فرم به روز رسانی یک رای
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Geo_Form_Vote extends Pluf_Form
{

    public $tenant = null;

    public $user = null;

    public $location = null;

    public $vote = null;

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
        $this->user = $extra['user'];
        $this->location = $extra['location'];
        if (array_key_exists('vote', $extra)) {
            $this->vote = $extra['vote'];
        }
        $this->vote = Geo_Form_Vote::voteFactory($this->vote);
        
        $this->fields['vote_value'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => true,
                        'label' => __('vote value'),
                        'initial' => $this->vote->vote_value
                ));
        $this->fields['vote_comment'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('vote comment'),
                        'initial' => $this->vote->vote_comment
                ));
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot vote the location from an invalid form'));
        }
        // Set attributes
        $this->vote->setFromFormData($this->cleaned_data);
        $this->vote->tenant = $this->tenant;
        $this->vote->voter = $this->user;
        $this->vote->owner_class = $this->location->_model;
        $this->vote->owner_id = $this->location->id;
        if ($commit) {
            if (! $this->vote->create()) {
                throw new Pluf_Exception(
                        __('fail to update the vote of the location'));
            }
        }
        return $this->vote;
    }

    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot vote the location from an invalid form'));
        }
        // Set attributes
        $this->vote->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->vote->update()) {
                throw new Pluf_Exception(
                        __('fail to update the vote of the location'));
            }
        }
        return $this->vote;
    }

    public static function voteFactory ($object)
    {
        if ($object == null || ! isset($object))
            return new SaaSKM_Vote();
        return $object;
    }
}
