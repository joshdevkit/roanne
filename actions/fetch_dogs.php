<?php

require '../database/databaseModel.php';

$ownersDog = 'dog';

extract($_GET);

$data = getOne($conn, $ownersDog, 'owner_id', $owner_id);

echo json_encode($data);