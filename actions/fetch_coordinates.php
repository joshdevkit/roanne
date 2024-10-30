<?php

require '../database/databaseModel.php';

$tableOwners = 'dog_owners';

$data = retrieve($conn, $tableOwners);

echo json_encode($data);