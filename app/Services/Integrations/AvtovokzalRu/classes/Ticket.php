<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Информация о забронированном/проданном билете в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 13:01
 */
class Gate_Gds_Ticket
{
	/**
	 * Статус "Билет забронирован"
	 */
	const STATUS_BOOKED = 'B';

	/**
	 * Статус "Билет продан"
	 */
	const STATUS_SOLD = 'S';

	/**
	 * Статус "Билет возвращен"
	 */
	const STATUS_RETURNED = 'R';

	/**
	 * Статус "Билет испорчен (отменен)"
	 */
	const STATUS_CANCELLED = 'C';

	/**
	 * Класс билета: пассажирский
	 */
	const CLASS_PASSENGER = 'P';

	/**
	 * Класс билета: багажный
	 */
	const CLASS_BAGGAGE = 'B';

	public $id;

	/**
	 * @var string Код билета на стороне сервера автовокзала
	 */
	public $ticketCode;

	/**
	 * @var string Номер билета. На момент бронирования значение может быть не определено.
	 */
	public $ticketNum;

	/**
	 * @var string Серия билета. На момент бронирования значение может быть не определено. Серия у билета есть не во всех системах.
	 */
	public $ticketSeries;

	/**
	 * @var string Класс билета
	 */
	public $ticketClass;

	/**
	 * @var string Название типа билета
	 */
	public $ticketType;

	/**
	 * @var string UID рейса
	 * @since 1.10
	 */
	public $raceUid;

	/**
	 * @var string Номер маршрута
	 */
	public $raceNum;

	/**
	 * @var string Название маршрута
	 */
	public $raceName;

	/**
	 * @var int ID класса рейса
	 */
	public $raceClassId;

	/**
	 * @var string Дата и время отправления
	 */
	public $dispatchDate;

	/**
	 * @var string Название станции отправления
	 */
	public $dispatchStation;

	/**
	 * @var string Адрес отправления
	 */
	public $dispatchAddress;

	/**
	 * @var string Дата и время прибытия
	 */
	public $arrivalDate;

	/**
	 * @var string Название станции прибытия
	 */
	public $arrivalStation;

	/**
	 * @var string Адрес прибытия
	 */
	public $arrivalAddress;

	/**
	 * @var string Место пассажира в автобусе
	 */
	public $seat;

	/**
	 * @var string Платформа, с которой происходит отправление
	 */
	public $platform;

	/**
	 * @var string Фамилия пассажира
	 */
	public $lastName;

	/**
	 * @var string Имя пассажира
	 */
	public $firstName;

	/**
	 * @var string Отчество пассажира
	 */
	public $middleName;

	/**
	 * @var string Название типа документа
	 */
	public $docType;

	/**
	 * @var string Серия документа
	 */
	public $docSeries;

	/**
	 * @var string Номер документа
	 */
	public $docNum;

	/**
	 * @var string Гражданство
	 */
	public $citizenship;

	/**
	 * @var string Пол
	 */
	public $gender;

	/**
	 * @var string Дата рождения
	 */
	public $birthday;

	/**
	 * @var string Контактный телефон пассажира
	 * @since 1.11
	 */
	public $phone;

	public $supplierFare;
	public $supplierDues;
	public $supplierPrice;
	public $supplierRepayment;
	public $dues;
	public $price;

	/**
	 * @var float ИТОГО НДС
	 * @since 1.12
	 */
	public $vat;

	/**
	 * @var float Сумма, подлежащая возврату покупателю. Рассчитывается при выполнении возврата.
	 */
	public $repayment;

	/**
	 * @var string Информация об автобусе
	 */
	public $busInfo;

	/**
	 * @var string Перевозчик
	 */
	public $carrier;

	/**
	 * @var string ИНН перевозчика
	 */
	public $carrierInn;

	/**
	 * @var string Штрих-код билета
	 * @since 1.11
	 */
	public $barcode;

	/**
	 * @var boolean Флаг возможности изменить данные в билете
	 *
	 * @since 1.10
	 */
	public $updatable;

	/**
	 * @var string Статус билета
	 */
	public $status;

	/**
	 * @var string Дата выполнения возврата/отмены
	 */
	public $returned;

	public $benefit;

	/**
	 * @var string Хэш-код билета. Используется для загрузки файла билета, сгенерированного на стороне GDS.
	 * Значение получает только после успешного подтверждения оплаты.
	 * @since 1.11.0
	 */
	public $hash;
}
