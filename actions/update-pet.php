<?php

require '../database/conn.php';

if (isset($_GET['update'])) {
    $petId = $_POST['petId'];
    $category_id = $_POST['category_id'];
    $pet_name = $_POST['pet_name'];
    $pet_age = $_POST['pet_age'];
    $color = $_POST['color'];
    $breed = $_POST['breed'];
    $pet_status = $_POST['pet_status'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $targetDir = "../image/pet_images/";
        $targetFilePath = $targetDir . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $sql = "UPDATE pets SET category_id = :category_id, pet_name = :pet_name, pet_age = :pet_age, color = :color, breed = :breed, image = :image, pet_status =:pet_status WHERE dog_id = :petId";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':category_id' => $category_id,
                ':pet_name' => $pet_name,
                ':pet_age' => $pet_age,
                ':color' => $color,
                ':breed' => $breed,
                ':image' => $image,
                ':pet_status' => $pet_status,
                ':petId' => $petId
            ]);
        }
    } else {
        $sql = "UPDATE pets SET category_id = :category_id, pet_name = :pet_name, pet_age = :pet_age, color = :color, breed = :breed, pet_status = :pet_status WHERE dog_id = :petId";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':category_id' => $category_id,
            ':pet_name' => $pet_name,
            ':pet_age' => $pet_age,
            ':color' => $color,
            ':breed' => $breed,
            ':pet_status' => $pet_status,
            ':petId' => $petId
        ]);
    }
    $_SESSION['message'] = "Pet Details Updated";
    header("location: ../edit-pet-details.php?petId=" . $_GET['update']);
    exit();
} else {
    try {
        extract($_POST);
        $stmt = $conn->prepare("UPDATE pets SET is_archived = 1 WHERE dog_id = :dog_id");

        $stmt->bindParam(':dog_id', $petID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Pet record archived successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to archive pet record.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
