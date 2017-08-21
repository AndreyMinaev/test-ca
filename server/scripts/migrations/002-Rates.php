<?php

class Rates extends Akrabat_Db_Schema_AbstractChange
{
    function up()
    {
        $tableName = $this->_tablePrefix . 'rates';
        $sql = "
             CREATE TABLE IF NOT EXISTS $tableName (
               id int(11) NOT NULL,
               name text NOT NULL,
               value varchar(50) NOT NULL,
               charcode varchar(3) NOT NULL,
               nominal int(11) NOT NULL,
               appear BOOLEAN NOT NULL,
               PRIMARY KEY (id)
             ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ";
        $this->_db->query($sql);

        $showByDefault = [
            978/* евро */,
            840/* доллар */,
            980/* гривна */,
            933/* белорусский рубль */
        ];

        $config = parse_ini_file(realpath(null) . '/application/configs/application.ini');

        $url = $config['remotes.rates'];
        $xmlstr = file_get_contents($url);
        $xml = new SimpleXMLElement($xmlstr);
        foreach ($xml->Valute as $item) {
            $this->_db->insert(
                $tableName,
                array(
                    'id' => $item->NumCode,
                    'name' => $item->Name,
                    'value' => $item->Value,
                    'charcode' => $item->CharCode,
                    'nominal' => $item->Nominal,
                    'appear' => (int) in_array($item->NumCode, $showByDefault)
                )
            );
        }
    }

    function down()
    {
        $tableName = $this->_tablePrefix . 'rates';
        $sql= "DROP TABLE IF EXISTS $tableName";
        $this->_db->query($sql);
    }

}