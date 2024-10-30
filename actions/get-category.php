<?php

require '../database/databaseModel.php';

$table = 'category';

extract($_GET);

$data = retrieve($conn, $table);

echo json_encode($data);
