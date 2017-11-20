<div class="container">
	<div class="btn btn-orange btn-add-card">+</div>
	<?php
		require_once('php/card.php');

		foreach (Card::getCards() as $card)
		{
			$encoded_fields = str_replace("'", "&#39", json_encode($card));
			echo "
				<div class='card' data-fields='" . $encoded_fields . "' data-id='{$card['id']}'>
					<div class='card-image' style='background-image: url(\"" . $card['img'] . "\")'></div>
					<div class='card-title'>{$card['name']}</div>
				</div>
			";
		}
	?>

</div>
