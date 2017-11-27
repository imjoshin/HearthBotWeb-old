<div class="container cards-container">
	<div class="btn btn-orange btn-add-card">+</div>
	<div class="btn btn-orange btn-logout">Logout</div>
	<h1>HearthBot Cards</h1>
	<div class='card card-template' data-fields='' data-id=''>
		<div class='card-image' style=''></div>
		<div class='card-title'></div>
	</div>
	<?php
		require_once('php/card.php');

		foreach (Card::getCards() as $card)
		{
			$encoded_fields = str_replace("'", "&#39", json_encode($card));
			echo "
				<div class='card' data-fields='" . $encoded_fields . "' data-id='{$card['id']}'>
					<div class='card-image' style='background-image: url(\"" . $card['img'] . "\")'>
						<div class='card-delete'>x</div>
					</div>
					<div class='card-title'>{$card['name']}</div>
				</div>
			";
		}
	?>

</div>
