<?php
/**
 * Синицын АВ - 2020
 */

namespace Litebox\Z1;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class PositionsTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'litebox_z1_positions';
    }
    
    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            //Наименование
            new Entity\StringField('NAME', array(
                'required' => true,
            )),
            //Описание
            new Entity\StringField('DESCRIPTION')
        );
    }
}
