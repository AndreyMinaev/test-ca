<?php

class Application_Model_Rates implements JsonSerializable
{
    protected $_id;
    protected $_name;
    protected $_charcode;
    protected $_value;
    protected $_nominal;
    protected $_appear;

    public function __construct($data)
    {
        if ($data) {
            $this->set('id', $data->id)
                ->set('name', $data->name)
                ->set('charcode', $data->charcode)
                ->set('nominal', $data->nominal)
                ->set('appear', (bool) $data->appear)
                ->set('value', $data->value);
        }
    }

    public function set($name, $value)
    {
        $this->{"_$name"} = $value;

        return $this;
    }

    public function get($name)
    {
        return $this->{"_$name"};
    }

    /**
     * форматирование модели при попадании в json_encode
     * @return array[string]string|int
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->_id,
            'name' => $this->_name,
            'value' => $this->_value,
            'charcode' => $this->_charcode,
            'nominal' => $this->_nominal,
            'appear' => $this->_appear,
        ];
    }
}

