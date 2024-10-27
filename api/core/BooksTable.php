<?php
namespace Bitrix\Books;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class BooksTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(255) mandatory
 * <li> PUBLISHED int mandatory
 * <li> AVAILABLE string(1) mandatory
 * <li> USER string(255) mandatory
 * </ul>
 *
 * @package Bitrix\Books
 **/

class BooksTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'data_books';
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
					'title' => Loc::getMessage('BOOKS_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'NAME',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateName'],
					'title' => Loc::getMessage('BOOKS_ENTITY_NAME_FIELD')
				]
			),
			new IntegerField(
				'PUBLISHED',
				[
					'required' => true,
					'title' => Loc::getMessage('BOOKS_ENTITY_PUBLISHED_FIELD')
				]
			),
			new StringField(
				'AVAILABLE',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateAvailable'],
					'title' => Loc::getMessage('BOOKS_ENTITY_AVAILABLE_FIELD')
				]
			),
			new StringField(
				'USER',
				[
					'required' => false,
					'validation' => [__CLASS__, 'validateUser'],
					'title' => Loc::getMessage('BOOKS_ENTITY_USER_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for NAME field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	/**
	 * Returns validators for AVAILABLE field.
	 *
	 * @return array
	 */
	public static function validateAvailable()
	{
		return [
			new LengthValidator(null, 1),
		];
	}

	/**
	 * Returns validators for USER field.
	 *
	 * @return array
	 */
	public static function validateUser()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}
?>