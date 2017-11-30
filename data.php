<?php
header('Content-Type: application/json');
require_once "./php/auth.php";

$name_overrides = [
	"brann" => "Brann Bronzebeard",
	"yogg" => "Yogg-Saron, Hope's End",
	"yogg saron" => "Yogg-Saron, Hope's End",
	"yoggsaron" => "Yogg-Saron, Hope's End",
	"yshaarj" => "Y'Shaarj, Rage Unbound",
	"aya" => "Aya Blackpaw",
];

$keys = dbQuery("SELECT * FROM `key` WHERE rtime IS NULL");
$keys = array_column($keys, 'key');
if (!isset($_GET['key']) || !in_array($_GET['key'], $keys))
{
	echo json_encode(["error" => "Invalid key."]);
	return;
}

if (isset($_GET['id']))
{
	$cards = json_decode(file_get_contents('cards.json'), true);

	$ids = explode(',', $_GET['id']);
	$ret = [];
	foreach($ids as $id)
	{
		if (array_key_exists($id, $cards))
		{
			$ret[$id] = $cards[$id];
		}
	}

	if (count($ret) > 0)
	{
		echo json_encode($ret);
	}
	else
	{
		echo json_encode(["error" => "Invalid card id."]);
	}
}
else if (isset($_GET['deck']))
{
	logDeck();
}
else if (!isset($_GET['name']) || !preg_match('/[0-9a-zA-Z]*/', $_GET['name']))
{
	echo json_encode(["error" => "No card name or id given."]);
}
else
{
	$search_name = trim(preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($_GET['name'])));

	if (array_key_exists($search_name, $name_overrides))
	{
		$search_name = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($name_overrides[$search_name]));
	}

	$collectible_only = isset($_GET['collectible']) && $_GET['collectible'] != 0;

	echo json_encode(getCard($search_name, $collectible_only));
}

function getCard($search_name, $collectible_only)
{
	$json_cards = json_decode(file_get_contents('cards.json'), true);
	$db_cards = getDBCards();
	$cards = array_merge($json_cards, $db_cards);
	$min_leven = PHP_INT_MAX;
	$min_leven_index = -1;
	$min_level_id = -1;

	foreach ($cards as $id => $card)
	{
		// skip if we ignore token cards this card isn't collectible
		if ($collectible_only && !$card['collectible'] || $card['cost'] == "hero")
		{
			continue;
		}

		$testing_name = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($card['name']));

		// calculate difference between cards without considering removals
		$leven = levenshtein(strtolower($testing_name), strtolower($search_name), 1, 1, 0);

		// if this card contains the search word and has a lesser leven score
		if ($leven <= $min_leven && strpos(strtolower($testing_name), strtolower($search_name)) !== false)
		{
			if ($leven == $min_leven)
			{
				$selected_name = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($cards[$min_leven_index]['name']));

				// if they are equal, take the shorter name
				if (strlen($testing_name) < strlen($selected_name))
				{
					$min_leven_index = $id;
				}
			}
			else
			{
				// set the new selected card
				$min_leven = $leven;
				$min_leven_index = $id;
			}
		}
	}

	$card = $cards[$min_leven_index];

	if ($min_leven_index >= 0)
	{
		dbQuery(
			"INSERT INTO search (search, card_id, type, user, user_id, channel_id, `key`) VALUES (?, ?, ?, ?, ?, ?, ?)",
			[
				$_GET['name'],
				$card['id'],
				isset($_GET['t']) ? $_GET['t'] : null,
				isset($_GET['u']) ? $_GET['u'] : null,
				isset($_GET['uid']) ? $_GET['uid'] : null,
				isset($_GET['cid']) ? $_GET['cid'] : null,
				isset($_GET['key']) ? $_GET['key'] : null,
			]
		);

		if (isset($_GET['conly']))
		{
			return [
				'img' => $card['img']
			];
		}

		return $card;
	}

	dbQuery("INSERT INTO search (search, card_id) VALUES (?, ?)", [$_GET['name'], -1]);
	return ['error' => 'Card not found.'];
}

function getDBCards()
{
	$cards = dbQuery("SELECT * FROM card WHERE expiration > CURDATE() AND rtime IS NULL");
	if (!is_array($cards))
	{
		return [];
	}

	$ret = [];
	foreach($cards as $card)
	{
		unset($card['expiration']);
		unset($card['added_by']);
		unset($card['modified_by']);
		unset($card['ctime']);
		unset($card['rtime']);

		$card['id'] = 1000000 + $card['id'];
		$card['collectible'] = $card['collectible'] == 1;
		$ret[] = $card;
	}

	return $ret;
}

function logDeck()
{
	dbQuery(
		"INSERT INTO deck (deck, format, class, cost, user, user_id, channel_id, `key`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
		[
			$_GET['deck'],
			isset($_GET['f']) ? $_GET['f'] : null,
			isset($_GET['c']) ? $_GET['c'] : null,
			isset($_GET['d']) ? $_GET['d'] : null,
			isset($_GET['u']) ? $_GET['u'] : null,
			isset($_GET['uid']) ? $_GET['uid'] : null,
			isset($_GET['cid']) ? $_GET['cid'] : null,
			isset($_GET['key']) ? $_GET['key'] : null,
		]
	);
}
