<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Тип билета в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 12:41
 */
class Gate_Gds_TicketType
{
	const CLASS_PASSENGER = 'P';
	const CLASS_BAGGAGE = 'B';

	/**
	 * @var string Код типа билета. Следует исходить из того, что на разных автовокзалах коды типов билетов могут быть разными.
	 * В списке типов билетов тип полного билета всегда первый.
	 */
	public $code;

	/**
	 * @var string Название типа билета. Следует исходить из того, что на разных автовокзалах названия типов билетов могут быть
	 * разными.
	 */
	public $name;

	/**
	 * @var float Цена билета со всеми наценками
	 */
	public $price;

	/**
	 * @var string Класс билета (пассажирский/багажный)
	 */
	public $ticketClass;

	/**
	 * Поиск нужного типа билета в списке
	 *
	 * @param Gate_Gds_TicketType[] $ticket_types Информация о типах продаваемых билетов
	 * @param string $ticket_type_code Код типа билета
	 * @return Gate_Gds_TicketType|null
	 */
	public static function find($ticket_types, $ticket_type_code) {
		foreach ($ticket_types as $ticket_type) {
			if ($ticket_type->code == $ticket_type_code) {
				return $ticket_type;
			}
		}
		return NULL;
	}

}