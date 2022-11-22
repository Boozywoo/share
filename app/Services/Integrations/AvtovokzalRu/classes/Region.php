<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Регион в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 11:54
 */
class Gate_Gds_Region
{
	public $id;

	/**
	 * @var string Код региона
	 */
	public $code;

	/**
	 * @var string Название региона
	 */
	public $name;

	/**
	 * @var string Тип региона
	 */
	public $type;
	public $country;
}