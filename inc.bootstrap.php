<?php

require __DIR__ . '/env.php';
require __DIR__ . '/vendor/autoload.php';

$db = db_sqlite::open(['database' => ED_DB_FILE]);
$db->ensureSchema(require 'inc.db-schema.php');

db_generic_model::$_db = $db;

// Everything. UTF-8. Always. Everywhere.
mb_internal_encoding('UTF-8');
header('Content-type: text/html; charset=utf-8');
