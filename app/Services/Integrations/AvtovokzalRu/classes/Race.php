<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Рейс в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 11:59
 */
class Gate_Gds_Race
{
	/**
	 * @var string Уникальный идентификатор рейса. Генерируется на стороне GDS, используется для получения дополнительной информации по рейсу.
	 */
	public $uid;

	/**
	 * @var int ID автовокзала
	 */
	public $depotId;

	/**
	 * @var string Номер рейса
	 */
	public $num;

	/**
	 * @var string Название рейса
	 */
	public $name;

	/**
	 * @var string Дата и время отправления
	 */
	public $dispatchDate;

	/**
	 * @var string Дата и время прибытия
	 */
	public $arrivalDate;

	/**
	 * @var string Название станции отправления. Чаще всего это название автовокзала, но для некоторых коннекторов это название
	 * станции отправления.
	 */
	public $dispatchStationName;

	/**
	 * @var string Название станции прибытия на стороне автовокзала
	 */
	public $arrivalStationName;

	/**
	 * @var int ID пункта отправления (на стороне GDS)
	 * @since 1.10.7
	 */
	public $dispatchPointId;

	/**
	 * @var int ID пункта прибытия (на стороне GDS)
	 * @since 1.10.7
	 */
	public $arrivalPointId;

	/**
	 * @var float Цена полного билета на автовокзале
	 */
	public $supplierPrice;

	/**
	 * @var float Стоимость полного проездного билета (включая сбор агента). Другие типы доступных тарифов
	 * можно получить методом getTicketTypes().
	 */
	public $price;

	/**
	 * @var int Количество свободных мест на момент выполнения запроса к серверу автовокзала. Если информация о рейсе
	 * извлечена из кэша, то значение freeSeatCount может быть неточным и лучше отображать freeSeatEstimation.
	 */
	public $freeSeatCount;

	/**
	 * @var string Оценочное кол-во свободных мест. Может содержать как точное значение, если данные получены
	 * непосредственно с сервера, так и приблизительное оценочное, если данные о рейсе извлечены из кэша.
	 */
	public $freeSeatEstimation;

	/**
	 * @var string Описание автобуса
	 */
	public $busInfo;

	/**
	 * @var string Название организации-перевозчика
	 */
	public $carrier;

	/**
	 * @var string ИНН организации-перевозчика
	 * @since 1.7.4
	 */
	public $carrierInn;

	/**
	 * @var bool Признак необходимости воодить расширенный набор персональных данных
	 */
	public $dataRequired;

	/**
	 * @var Gate_Gds_RaceType Тип рейса (Междугородный/Межрегиональный/...)
	 */
	public $type;

	/**
	 * @var Gate_Gds_RaceClass Класс рейса (Регулярный/Заказной)
	 * @since 1.10
	 */
	public $clazz;

	/**
	 * @var Gate_Gds_RaceStatus Cтатус рейса
	 * @since 1.8
	 */
	public $status;

	/**
	 * @var bool Признак поступления данных из кэша
	 */
	public $fromCache;

	public function isOnSale()
	{
		return ($this->status->id == Gate_Gds_RaceStatus::STATUS_ON_SALE);
	}

}