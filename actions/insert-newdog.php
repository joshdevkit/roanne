<?php

require '../database/conn.php';

// Table name
$tablePets = 'pets';

extract($_POST);

$pet_count = (int)$_POST['pet_count'];
$owner_id = (int)$_POST['owner_id'];

try {
    $conn->beginTransaction();

    for ($i = 0; $i < $pet_count; $i++) {
        // Set each pet's values
        $dogtag = $_POST['dogtag'][$i];
        $pet_name = $_POST['pet_name'][$i];
        $pet_age = $_POST['pet_age'][$i];
        $color = $_POST['color'][$i];
        $breed = $_POST['breed'][$i];
        $category_id = $_POST['category'][$i];
        $image = $_FILES['pet_image']['name'][$i];

        // Image upload
        $uploadDir = '../image/pet_images/';
        $imagePath = $uploadDir . basename($image);
        if (move_uploaded_file($_FILES['pet_image']['tmp_name'][$i], $imagePath)) {
            // Prepare the insert statement for each pet
            $stmt = $conn->prepare("INSERT INTO $tablePets (owner_id, category_id, dogtag, pet_name, pet_age, color, breed, image) VALUES (:owner_id, :category_id, :dogtag, :pet_name, :pet_age, :color, :breed, :image)");
            $stmt->execute([
                ':owner_id' => $owner_id,
                ':category_id' => $category_id,
                ':dogtag' => $dogtag,
                ':pet_name' => $pet_name,
                ':pet_age' => $pet_age,
                ':color' => $color,
                ':breed' => $breed,
                ':image' => $image
            ]);
        } else {
            throw new Exception("Image upload failed for pet: " . htmlspecialchars($pet_name));
        }
    }

    $conn->commit(); // Commit the transaction

    echo json_encode(["status" => true, "message" => "All pets inserted successfully.", 'resp_id' => $owner_id]);
} catch (Exception $e) {
    $conn->rollBack(); // Roll back the transaction on error
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
