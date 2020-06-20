<?php

return [
	'version' => 4,
	'tables' => [
		'articles' => [
			'id' => ['pk' => true],
			'title' => ['null' => false],
			'url' => ['null' => false],
			'category' => [],
			'saved_on' => ['unsigned' => true, 'default' => 0],
			'published_on' => ['unsigned' => true, 'default' => 0],
			'updated_on' => ['unsigned' => true, 'default' => 0],
			'read' => ['type' => 'int', 'default' => 0],
		],
		'categories' => [
			'category' => ['unique' => true],
			'checked' => ['type' => 'boolean', 'default' => 0],
			'hide' => ['type' => 'boolean', 'default' => 0],
		],
	],
];
