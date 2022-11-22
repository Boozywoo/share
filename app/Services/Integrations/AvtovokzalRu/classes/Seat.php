<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Место в автобусе в подсистеме GDS
 *
 * @author V.Skorykh
 * @since 02.02.2016 12:39
 */
class Gate_Gds_Seat
{
	/**
	 * @var string ID места
	 */
	public $code;

	/**
	 * @var string Название места
	 */
	public $name;

	/**
	 * @var string Тип места. Например, "сидячее". Данное поле имеет значение не на всех автовокзалах.
	 */
	public $type;
}