<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Страна в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 11:49
 */
class Gate_Gds_Country
{
	public $id;

	/**
	 * @var string Двухбуквенный код страны
	 */
	public $code;

	/**
	 * @var string Название страны (по справочнику Защитаинфотранс)
	 */
	public $name;

	/**
	 * @var string Полное название страны
	 */
	public $fullName;
}