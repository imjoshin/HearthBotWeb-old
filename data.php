<?php
header('Content-Type: application/json');
require_once "./auth.php";

$name_overrides = [
	"brann" => "Brann Bronzebeard",
	"yogg" => "Yogg-Saron, Hope's End",
	"yoggsaron" => "Yogg-Saron, Hope's End",
	"yshaarj" => "Y'Shaarj, Rage Unbound",
];

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
else if (!isset($_GET['name']) || !preg_match('/[0-9a-zA-Z]*/', $_GET['name']))
{
	echo json_encode(["error" => "No card name or id given."]);
}
else
{
	$search_name = preg_replace("/[^A-Za-z0-9]/", '', strtolower($_GET['name']));

	if (array_key_exists($search_name, $name_overrides))
	{
		$search_name = preg_replace("/[^A-Za-z0-9]/", '', strtolower($name_overrides[$search_name]));
	}

	$collectible_only = isset($_GET['collectible']) && $_GET['collectible'] != 0;

	echo json_encode(getCard($search_name, $collectible_only));
}

function getCard($search_name, $collectible_only)
{
	$cards = json_decode(file_get_contents('cards.json'), true);
	$min_leven = PHP_INT_MAX;
	$min_level_index = -1;

	foreach ($cards as $id => $card)
	{
		// skip if we ignore token cards this card isn't collectible
		if ($collectible_only && !$card['collectible'] || $card['cost'] == "hero")
		{
			continue;
		}

		$testing_name = preg_replace("/[^A-Za-z0-9]/", '', strtolower($card['name']));

		// calculate difference between cards without considering removals
		$leven = levenshtein(strtolower($testing_name), strtolower($search_name), 1, 1, 0);

		// if this card contains the search word and has a lesser leven score
		if ($leven <= $min_leven && strpos(strtolower($testing_name), strtolower($search_name)) !== false)
		{
			if ($leven == $min_leven)
			{
				$selected_name = preg_replace("/[^A-Za-z0-9]/", '', strtolower($cards[$min_level_index]['name']));

				// if they are equal, take the shorter name
				if (strlen($testing_name) < strlen($selected_name))
				{
					$min_level_index = $id;
				}
			}
			else
			{
				// set the new selected card
				$min_leven = $leven;
				$min_level_index = $id;
			}
		}
	}

	if ($min_level_index >= 0)
	{
		dbQuery("INSERT INTO hearthsearch (search, card_id) VALUES (?, ?)", [$_GET['name'], $min_level_index]);
		return $cards[$min_level_index];
	}

	return ['error' => 'Card not found.'];
}
