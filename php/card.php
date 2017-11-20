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

	public static function saveCard($form)
	{
		if (strlen($form['name']) == 0 || strlen($form['text']) == 0 || strlen($form['img']) == 0 || strlen($form['cost']) == 0 || strlen($form['set']) == 0)
		{
			return array('success'=>false, 'output'=>'Check all fields.');
		}

		if (!@getimagesize($form['img'])) {
			return array('success'=>false, 'output'=>'Image URL is not a valid image.');
		}

		$collectible = array_key_exists('collectible', $form) && $form['collectible'] == "on" ? 1 : 0;

		// update existing card
		if ($form['id'] != -1)
		{
			dbQuery(
				"UPDATE hearthcard SET name = ?, `set` = ?, type = ?, text = ?, rarity = ?, cost = ?, img = ?, collectible = ?, expiration = ?, modified_by = ? WHERE id = ?",
				[$form['name'], $form['set'], $form['type'], $form['text'], $form['rarity'], $form['cost'], $form['img'], $collectible, $form['expiration'], $_SESSION['user_id'], $form['id']]
			);

			$card = dbQuery("SELECT * FROM hearthcard WHERE id = ?", [$form['id']]);
			return array('success'=>true, 'output'=>json_encode($card[0]));
		}
		else
		{
			dbQuery(
				"INSERT INTO hearthcard (name, `set`, type, text, rarity, cost, img, collectible, expiration, added_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
				[$form['name'], $form['set'], $form['type'], $form['text'], $form['rarity'], $form['cost'], $form['img'], $collectible, $form['expiration'], $_SESSION['user_id']]
			);

			$card = dbQuery("SELECT * FROM hearthcard ORDER BY id DESC LIMIT 1");
			return array(
				'success'=>true,
				'output'=>json_encode($card[0]),
				'new'=>$form['id'] != -1
			);
		}
	}
}
