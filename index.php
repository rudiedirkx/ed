<?php

use rdx\ed\Article;

require __DIR__ . '/inc.bootstrap.php';

$hiddenCategories = $db->select_fields('categories', 'category', ['hide' => 1]) ?: ['x'];
$checkedCategories = $db->select_fields('categories', 'category', ['checked' => 1]) ?: ['x'];

$articles = Article::all('category NOT IN (?) ORDER BY id DESC LIMIT 150', [$hiddenCategories]);
$categories = $db->select_fields('articles', 'category', 'category NOT IN (?) GROUP BY category', [$hiddenCategories]);

$lastSync = $db->max('articles', 'saved_on');

?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta charset="utf-8" />

<style>
label {
	display: inline-block;
	margin-right: .5em;
}
</style>

<p class="filters">
	<? foreach ($categories as $category): ?>
		<label>
			<input type="checkbox" value="<?= html($category) ?>" <? if (isset($checkedCategories[$category])): ?>checked<? endif ?> />
			<?= html($category) ?>
		</label>
	<? endforeach ?>
</p>

<ul>
	<? foreach ($articles as $article): ?>
		<li data-category="<?= html($article->category) ?>">
			<a href="<?= html($article->url) ?>">
				[<?= html($article->category) ?>]
				<?= html($article->title) ?>
			</a>
		</li>
	<? endforeach ?>
</ul>

<p>Laatste sync: <kbd><?= date('Y-m-d H:i', $lastSync) ?></kbd></p>

<p><a href="config.php">Config</a></p>

<script>
const filter = function() {
	const shows = [].map.call(document.querySelectorAll('input:checked'), el => el.value);
	document.querySelectorAll('li[data-category]').forEach(el => el.hidden = shows.indexOf(el.dataset.category) == -1);
};
document.querySelector('.filters').addEventListener('change', filter);
filter();
</script>
