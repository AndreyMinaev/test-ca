<?php

class Application_Model_RatesMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Rates');
        }
        return $this->_dbTable;
    }

    /**
     * возвращает курсы валют
     * @return Application_Model_Rates[]
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = [];

        foreach ($resultSet as $row) {
            $entry = new Application_Model_Rates($row);
            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * возвращает курс валюты по ид
     * @param int $id - ид валюты
     * @return Application_Model_Rates
     */
    public function find($id)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();

        $rate = new Application_Model_Rates($row);

        return $rate;
    }


    /**
     * переключает статус отображения
     * @param int $id - ид валюты, которой будет присвоен статус
     * @param bool $appear - новый статус
     */
    public function toggleAppearance($id, $appear)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('id = ?', $id);
        $table->update(['appear' => (int) $appear], $where);
    }

    /**
     * обновляет данные в таблице с удалённого ресурса
     */
    public function refresh()
    {
        $table = $this->getDbTable();
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');

        $url = $config->getOption('remotes')['rates'];
        $xmlstr = file_get_contents($url);
        $xml = new SimpleXMLElement($xmlstr);

        foreach ($xml->Valute as $item) {
            $table->update(
                [
                    'name' => $item->Name,
                    'value' => $item->Value,
                    'nominal' => $item->Nominal
                ],
                $table->getAdapter()->quoteInto('id = ?', $item->NumCode)
            );
        }
    }
}

