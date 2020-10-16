<?php
/**
 * Синицын АВ - 2020
 */

namespace Litebox\Z1;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class StaffTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'litebox_z1_staff';
    }
  
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            // ФИО сотрудника
            new Entity\StringField('FIO', array(
                'required' => true,
            )),
            // Дата принятия на работу
            new Entity\DatetimeField('DАTE_HIRING', array(
                'default_value' => new Type\DateTime
            )),
            new Entity\IntegerField('POSITION_ID'),
            new Entity\ReferenceField(
                'POSITION',
                '\Litebox\Z1\PositionsTable',
                array('=this.POSITION_ID' => 'ref.ID')
            )
        );
    }
}
