<?php

require '../database/databaseModel.php';

$tableOwners = 'owners';
$tablePets = 'pets';

extract($_POST);

$pet_count = count($_POST['pet_name']);

$dataOwner = array(
    'firstname' => $firstname,
    'middlename' => $middlename,
    'lastname' => $lastname,
    'contact' => $contact,
    'dog_own' => $pet_count
);

$ownerId = insert($conn, $tableOwners, $dataOwner);



$insertedProvince = array(
    'province' => $selectedProvince,
);

$provinceId = insert($conn, 'tbl_province', $insertedProvince);

$municipalityData = array(
    'province_id' => $provinceId,
    'municipality' => $selectedMunicipality
);

$municipalityId = insert($conn, 'tbl_municipality', $municipalityData);


$barangayData = array(
    'municipality_id' => $municipalityId,
    'barangay' => $selectedBrgy
);

$barangayID = insert($conn, 'tbl_barangay', $barangayData);


$purokData = array(
    'owner_id' => $ownerId,
    'barangay_id' => $barangayID,
    'purok' => $purok
);

$purokId = insert($conn, 'tbl_purok', $purokData);


if ($purokId) {
    $uploadDir = '../image/pet_images/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $uploadedFiles = [];

    foreach ($_FILES['pet_image']['tmp_name'] as $key => $tmpName) {
        $fileName = $_FILES['pet_image']['name'][$key];
        $fileTmpPath = $_FILES['pet_image']['tmp_name'][$key];
        $fileType = $_FILES['pet_image']['type'][$key];
        $fileSize = $_FILES['pet_image']['size'][$key];
        $fileError = $_FILES['pet_image']['error'][$key];

        if ($fileError === UPLOAD_ERR_OK) {
            $newFileName = time() . '_' . $fileName;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                $uploadedFiles[] = $newFileName;
            } else {
                echo json_encode(array('message' => 'Failed to move uploaded file.', 'status' => false), 500);
                exit;
            }
        } else {
            echo json_encode(array('message' => 'Error uploading file.', 'status' => false), 500);
            exit;
        }
    }

    for ($i = 0; $i < $pet_count; $i++) {
        $dataPet = array(
            'owner_id' => $ownerId,
            'category_id' => $_POST['category'][$i] ?? null,
            'dogtag' => $_POST['dogtag'][$i] ?? null,
            'pet_name' => $_POST['pet_name'][$i] ?? null,
            'pet_age' => $_POST['pet_age'][$i] ?? null,
            'color' => $_POST['color'][$i] ?? null,
            'breed' => $_POST['breed'][$i] ?? null,
            'image' => $uploadedFiles[$i] ?? null
        );
        insert($conn, $tablePets, $dataPet);
    }

    echo json_encode(array('message' => 'Success', 'status' => true), 200);
} else {
    echo json_encode(array('message' => 'Error inserting owner data.', 'status' => false), 500);
}
