<?php
namespace Bitrix\Write;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;

Loc::loadMessages(__FILE__);

/**
 * Class WriteTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> BOOK_ID int mandatory
 * <li> AUTHOR_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Write
 **/

class WriteTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'data_write';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('WRITE_ENTITY_ID_FIELD')
				]
			),
			new IntegerField(
				'BOOK_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('WRITE_ENTITY_BOOK_ID_FIELD')
				]
			),
			new IntegerField(
				'AUTHOR_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('WRITE_ENTITY_AUTHOR_ID_FIELD')
				]
			),
		];
	}
}
?>