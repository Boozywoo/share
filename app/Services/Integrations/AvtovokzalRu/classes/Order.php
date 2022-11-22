<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Информация о забронированном/проданном заказе в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 13:01
 */
class Gate_Gds_Order
{
	/**
	 * Статус "Заказ создан"
	 */
	const STATUS_CREATED = 'N';

	/**
	 * Статус "Заказ забронирован"
	 */
	const STATUS_BOOKED = 'B';

	/**
	 * Статус "Заказ оплачен"
	 */
	const STATUS_SOLD = 'S';

	/**
	 * Статус "Ошибка при оформлении заказа
	 */
	const STATUS_ERROR = 'E';

	/**
	 * Статус "Заказ возвращен"
	 */
	const STATUS_RETURNED = 'R';

	/**
	 * Статус "Заказ частично возвращен"
	 */
	const STATUS_PARTIALLY_RETURNED = 'P';

	/**
	 * Статус "Заказ отменен"
	 */
	const STATUS_CANCELLED = 'C';

	/**
	 * @var int ID заказа
	 */
	public $id;

	/**
	 * @var string Код заказа на стороне сервера автовокзала
	 */
	public $reserveCode;

	/**
	 * @var float Сумма заказа
	 */
	public $total;

	/**
	 * @var string Способ оплаты
	 */
	public $paymentMethod;

	/**
	 * @var float Сумма возврата
	 */
	public $repayment;

	/**
	 * @var string Статус заказа
	 */
	public $status;

	/**
	 * @var string Время создания заказа (системное время сервера)
	 */
	public $created;

	/**
	 * @var string Время успешного завершения обработки заказа (системное время сервера)
	 *
	 * @since 1.10
	 */
	public $finished;

	/**
	 * @var string Время истечения срока жизни заказа (системное время сервера)
	 */
	public $expired;

	/**
	 * @var Gate_Gds_User Пользователь, оформивший заказ
	 */
	public $user;

	/**
	 * @var Gate_Gds_Company Организация-агент, продавшая билет
	 */
	public $agent;

	/**
	 * @var Gate_Gds_Ticket[] Билеты
	 */
	public $tickets;
}
