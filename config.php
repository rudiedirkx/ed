<?php

require __DIR__ . '/inc.bootstrap.php';

$categories = $db->select_fields('articles', 'category', '1 GROUP BY category');

if ( isset($_POST['categories']) ) {
	$db->delete('categories', '1');

	foreach ($_POST['categories'] as $category => $options) {
		$db->insert('categories', [
			'category' => $category,
			'hide' => !empty($options['hide']),
			'checked' => !empty($options['checked']),
		]);
	}

	return do_redirect();
}

$hidden = $db->select_fields('categories', 'category', ['hide' => 1]);
$checked = $db->select_fields('categories', 'category', ['checked' => 1]);

?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta charset="utf-8" />

<style>
label {
	display: inline-block;
	margin-right: .5em;
}
</style>

<form method="post" action>
	<p>
		Helemaal verbergen:<br>
		<? foreach ($categories as $category): ?>
			<label>
				<input type="checkbox" name="categories[<?= html($category) ?>][hide]" <? if (isset($hidden[$category])): ?>checked<? endif ?> />
				<?= html($category) ?>
			</label>
		<? endforeach ?>
	</p>
	<p>
		Standaard geselecteerd:<br>
		<? foreach ($categories as $category): if (isset($hidden[$category])) continue; ?>
			<label>
				<input type="checkbox" name="categories[<?= html($category) ?>][checked]" <? if (isset($checked[$category])): ?>checked<? endif ?> />
				<?= html($category) ?>
			</label>
		<? endforeach ?>
	</p>

	<p>
		<button>Opslaan</button>
	</p>
</form>
