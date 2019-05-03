<?php

return [
	'version' => 2,
	'tables' => [
		'articles' => [
			'id' => ['pk' => true],
			'title' => ['null' => false],
			'url' => ['null' => false],
			'category' => [],
			'saved_on' => ['unsigned' => true, 'default' => 0],
			'published_on' => ['unsigned' => true, 'default' => 0],
			'updated_on' => ['unsigned' => true, 'default' => 0],
		],
	],
];
