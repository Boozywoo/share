<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Информация об автовокзале в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 12:15
 */
class Gate_Gds_Depot
{
	public $id;

	/**
	 * @var string Название автовокзала
	 */
	public $name;

	/**
	 * @var string Адрес автовокзала
	 */
	public $address;

	/**
	 * @var string Контактные келефоны автовокзала
	 */
	public $phones;

	/**
	 * @var string Сайт автовокзала
	 */
	public $site;

	/**
	 * @var string Режим работы автовокзала
	 */
	public $workingTime;

	/**
	 * @var string Дополнительная информация об автовокзале
	 */
	public $info;

	/**
	 * @var string Информация о порядке печати билетов
	 */
	public $printInfo;

	/**
	 * @var string Информация о порядке выполнения возвратов
	 */
	public $returnInfo;

	/**
	 * @var string Координаты автовокзала: широта
	 */
	public $latitude;

	/**
	 * @var string Координаты автовокзала: долгота
	 */
	public $longitude;

	/**
	 * @var string Временная зона, в которой находится автовокзал (хост автовокзала)
	 */
	public $timezone;

	/**
	 * @var string Тип сервера, используемый автовокзалом
	 */
	public $engine;

	/**
	 * @var string Версия сервера, используемая автовокзалом. Для многих типов серверов понятия версии не существует.
	 */
	public $version;

	/**
	 * @var string Описание особых возможностей автовокзала. Содержит значение "inactive", если автовокзал или хост отключены.
	 */
	public $features;

	/**
	 * @var int Максимальное кол-во билетов, которое можно забронировать за один раз.
	 */
	public $ticketLimit;

	/**
	 * @var int Количество минут до отправления автобуса, за которое прекращается продажа билетов на рейс.
	 */
	public $bookingTimeLimit;

	/**
	 * @var bool Признак обязательности ввода номера контактного телефона пассажира
	 * @since 1.13.1
	 */
	public $phoneRequired;

	/**
	 * @var bool Признак доступности автовокзала
	 */
	public $online;
}