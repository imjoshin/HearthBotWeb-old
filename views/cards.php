<div class="container">
	<?php
		require_once('php/card.php');

		foreach (Card::getCards() as $card)
		{
			echo "
				<div class='card' data-fields='" . json_encode($card) . "'>
					<div class='card-image' style='background-image: url(\"" . $card['img'] . "\")'></div>
					<div class='card-title'>{$card['name']}</div>
				</div>
			";
		}
	?>

</div>
