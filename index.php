<?php

use rdx\ed\Article;

require __DIR__ . '/inc.bootstrap.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['toggle'], $_REQUEST['read']) ) {
	$id = $_REQUEST['toggle'];
	$read = (int) !empty($_REQUEST['read']);
	var_dump(Article::updateAll(['read' => $read], ['id' => $id]));
	exit;
}

$checkedCategories = $db->select_fields('categories', 'category', ['checked' => 1]) ?: ['x'];
$newTime = strtotime('-7 days -6 hours');

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

ul.checkbox-bullets {
	list-style: none;
}
ul.checkbox-bullets li {
	position: relative;
}
ul.checkbox-bullets input {
	position: absolute;
	left: -1.8em;
}

li.read,
li.read ~ li {
	opacity: 0.7;
	filter: grayscale(1);
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

<ul class="checkbox-bullets">
	<? foreach ($articles as $article): ?>
		<li class="<?= $article->read ? 'read' : '' ?>">
			<input type="checkbox" value="<?= $article->id ?>" <?= $article->read ? 'checked' : '' ?> />
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

<script>
document.addEventListener('click', function(e) {
	if (e.target.matches('input[value]')) {
		const id = e.target.value;
		const read = Number(e.target.checked);
		fetch(`?toggle=${id}&read=${read}`, {method: 'post'});
		e.target.closest('li').classList.toggle('read', read);
	}
});
</script>

<details>
	<summary>Queries (<?= count($db->queries) ?>)</summary>
	<pre><?= implode("\n\n", $db->queries) ?></pre>
</details>
