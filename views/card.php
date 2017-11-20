<div class="modal-container card-container">
	<div class="modal-container-close">x</div>
	<form id="card">
		<div class="field">
			<div class="field-label">Name</div>
			<input class="field-input" id='name' name='name' type='text' maxlength="64">
		</div>
		<div class="field">
			<div class="field-label">Set</div>
			<input class="field-input" id='set' name='set' type='text' maxlength="64" value="Kobolds and Catacombs">
		</div>
		<div class="field">
			<div class="field-label">Type</div>
			<select class="field-label" id='type' name='type'>
				<option>Minion</option>
				<option>Weapon</option>
				<option>Spell</option>
				<option>Token</option>
			</select>
		</div>
		<div class="field">
			<div class="field-label">Text</div>
			<input class="field-input" id='text' name='text' type='text' maxlength="512">
		</div>
		<div class="field">
			<div class="field-label">Rarity</div>
			<select class="field-label" id='rarity' name='rarity'>
				<option>Common</option>
				<option>Rare</option>
				<option>Epic</option>
				<option>Legendary</option>
			</select>
		</div>
		<div class="field">
			<div class="field-label field-label-inline">Cost</div>
			<input class="field-input" id='cost' name='cost' type='number' min="0" max="30">
		</div>
		<div class="field">
			<div class="field-label">Image</div>
			<input class="field-input" id='img' name='img' type='text' maxlength="512">
		</div>
		<div class="field">
			<div class="field-label field-label-inline">Collectible</div>
			<input class="field-input" id='collectible' name='collectible' type='checkbox'>
		</div>
		<div class="field">
			<div class="field-label">Expiration</div>
			<input class="field-input" id='expiration' name='expiration' type='date' value='2017-12-15'>
		</div>
		<input id="id" name="id" type="hidden" value='-1'>
		<div class="btn btn-save">Save</div>
	</div>
</div>
