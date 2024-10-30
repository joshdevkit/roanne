<?php
require '../database/databaseModel.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $ownerID = $_POST['owner_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $purok = $_POST['purok'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];

    $conn->beginTransaction();

    try {
        $sqlOwner = "UPDATE owners SET firstname = ?, middlename = ?, lastname = ?, contact = ? WHERE owner_id = ?";
        $stmtOwner = $conn->prepare($sqlOwner);
        $stmtOwner->execute([$firstname, $middlename, $lastname, $contact, $ownerID]);

        $sqlPurok = "UPDATE tbl_purok SET purok = ? WHERE owner_id = ?";
        $stmtPurok = $conn->prepare($sqlPurok);
        $stmtPurok->execute([$purok, $ownerID]);

        $stmtPurokID = $conn->prepare("SELECT purok_id, barangay_id FROM tbl_purok WHERE owner_id = ?");
        $stmtPurokID->execute([$ownerID]);
        $data = $stmtPurokID->fetch();

        $stmtBrgy = $conn->prepare('SELECT municipality_id FROM tbl_barangay WHERE barangay_id = ? ');
        $stmtBrgy->execute([$data['barangay_id']]);
        $brgy = $stmtBrgy->fetch();


        $stmtmunicipality = $conn->prepare('SELECT province_id FROM tbl_municipality WHERE municipality_id = ? ');
        $stmtmunicipality->execute([$brgy['municipality_id']]);
        $munici = $stmtmunicipality->fetch();

        $updateMunicipality = $conn->prepare("UPDATE tbl_municipality SET municipality = ? WHERE municipality_id = ?  ");
        $updateMunicipality->execute([$municipality, $brgy['municipality_id']]);

        $updatebrgy = $conn->prepare("UPDATE tbl_province SET province = ? WHERE province_id = ?  ");
        $updatebrgy->execute([$province, $munici['province_id']]);

        $updatebrgy = $conn->prepare("UPDATE tbl_barangay SET barangay = ? WHERE barangay_id = ?  ");
        $updatebrgy->execute([$barangay, $data['barangay_id']]);


        $conn->commit();

        $_SESSION['status'] = "success";
        $_SESSION['message'] = "Information Updated Successfully";
        header("location: ../edit-data.php?id=" . $ownerID);
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Update failed: " . $e->getMessage();
        header("location: ../edit-data.php?id=" . $ownerID);
        exit();
    }
}
