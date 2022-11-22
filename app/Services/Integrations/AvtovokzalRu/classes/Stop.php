<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Остановка на рейсе в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 12:39
 */
class Gate_Gds_Stop
{
	/**
	 * @var string ID остановки
	 */
	public $code;

	/**
	 * @var string Название остановки
	 */
	public $name;

	/**
	 * @var string Название региона
	 */
	public $regionName;

	/**
	 * @var string Дата-время прибытия на остановку
	 */
	public $arrivalDate;

	/**
	 * @var string Дата-время отправления с остановки
	 */
	public $dispatchDate;

	/**
	 * @var int Время стоянки в минутах (0 или NULL - нет данных)
	 */
	public $stopTime;

	/**
	 * @var int Расстояние от пункта отправления до остановки в км (0 или NULL - нет данных)
	 */
	public $distance;
}