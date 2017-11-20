<?php

require_once('auth.php');

class Card
{
	public static function getCards()
	{
		$cards = dbQuery("SELECT * FROM hearthcard");

		if (!is_array($cards))
		{
			return [];
		}

		return $cards;
	}
}
