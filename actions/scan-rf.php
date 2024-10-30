<?php

require '../database/conn.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_GET['action'] == 'concern') {
        extract($_POST);
        $stmt = $conn->prepare("SELECT * FROM pets WHERE dogtag = :dogtag ");
        $stmt->bindParam(':dogtag', $rfvalue);
        $stmt->execute();

        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        $Owner = $conn->prepare("SELECT * FROM owners o LEFT JOIN tbl_purok p ON p.owner_id = o.owner_id 
        LEFT JOIN tbl_barangay b ON b.barangay_id = p.barangay_id
        LEFT JOIN tbl_municipality mp ON mp.municipality_id = b.municipality_id
        LEFT JOIN tbl_province pr ON pr.province_id = mp.municipality_id
        WHERE o.owner_id = :owner_id ");
        $Owner->bindParam(":owner_id", $record['owner_id']);
        $Owner->execute();

        $records = $Owner->fetch(PDO::FETCH_ASSOC);

        $vaccine = $conn->prepare("SELECT * FROM vaccine_records v LEFT JOIN vaccines c ON c.id = v.vaccine_id WHERE v.dog_id = :dog_id ");
        $vaccine->bindParam(":dog_id", $record['dog_id']);
        $vaccine->execute();
        $vaccines = $vaccine->fetch(PDO::FETCH_ASSOC);
        $response =
            [
                'success' => true,
                'record' => $record,
                'owner' => $records,
                'vaccine' => $vaccines,
            ];


        echo json_encode($response);
    }


    if ($_GET['action'] == "vaccination") {
        extract($_POST);
        $stmt = $conn->prepare("SELECT * FROM pets WHERE dogtag = :dogtag ");
        $stmt->bindParam(':dogtag', $rfvalue);
        $stmt->execute();

        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            $response = [
                'success' => true,
                'data' => $record
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No matching pet found for the specified dog tag.'
            ];
        }

        echo json_encode($response);
    }
}
