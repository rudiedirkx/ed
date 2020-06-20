<?php

use rdx\ed\Article;

require __DIR__ . '/inc.bootstrap.php';

$checkedCategories = $db->select_fields('categories', 'category', ['checked' => 1]) ?: ['x'];
$newTime = strtotime('-7 days');

$articles = Article::all('category IN (?) AND saved_on > ? ORDER BY id DESC LIMIT 500', [$checkedCategories, $newTime]);

$lastSync = $db->max('articles', 'saved_on');

?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta charset="utf-8" />
<meta name="referrer" content="no-referrer" />

<style>
label {
	display: inline-block;
	margin-right: .5em;
}

ul {
	padding-left: 2em;
}
li {
	margin-bottom: .5em;
}
</style>

<?php ob_start(); ?>
<ul>
	<li><?= date('D d M H:i', $lastSync) ?> - <?= date('D d M H:i', $newTime) ?></li>
	<li><?= count($articles) ?> articles</li>
	<li><a href="config.php">Config</a></li>
</ul>
<?php $meta = ob_get_contents(); ?>

<hr>

<ul>
	<? foreach ($articles as $article): ?>
		<li>
			[<?= date('D H:i', $article->saved_on) ?>]
			<a href="<?= html($article->url) ?>" rel="noreferrer">
				[<?= html($article->category) ?>]
				<?= html($article->title) ?>
			</a>
		</li>
	<? endforeach ?>
</ul>

<hr>

<?= $meta ?>

<details>
	<summary>Queries (<?= count($db->queries) ?>)</summary>
	<pre><?= implode("\n\n", $db->queries) ?></pre>
</details>
