<?php

require '../database/databaseModel.php';

$vaccinationTable = 'vaccine_records';


extract($_POST);


$dataToInsert = array(
    'dog_id' => $dog_id,
    'vaccine_id' => $vaccine_id,
    'date_of_vaccination' => $date_of_vaccination,
);

$response = insert($conn, $vaccinationTable, $dataToInsert);
if ($response) {
    echo json_encode(array('status' => true), 200);
}
