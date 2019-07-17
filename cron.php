<?php

use rdx\ed\Article;
use rdx\ed\Client;

require __DIR__ . '/inc.bootstrap.php';

$client = new Client();
$links = $client->getArticleLinks();

$total = count($links);
if ($total == 0) {
	echo "Can't find articles! Cookie wall? =(\n";
	exit(1);
}

$new = 0;
foreach ($links as $link) {
	// echo "(" . $link->getCategory() . ") " . $link->getLabel() . "\n" . $link->getUrl() . "\n";
	if ($link->save()) {
		$new++;
	}
	// echo "\n";
}

echo "$new / $total new\n";
