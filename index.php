<?php

use rdx\ed\Article;

require __DIR__ . '/inc.bootstrap.php';

$articles = Article::all('1 ORDER BY id DESC LIMIT 150');
$categories = $db->select_fields('articles', 'category', '1 GROUP BY category');

?>
<p><select><?= html_options($categories, 'eindhoven', '-- all') ?></select></p>

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

<script>
const filter = function(cat) {
	document.querySelectorAll('li[data-category]').forEach(el => el.hidden = cat && cat != el.dataset.category);
};
document.querySelector('select').addEventListener('change', e => filter(e.target.value));
filter(document.querySelector('select').value);
</script>
