<?php
require '../database/conn.php';

// Check if the 'barangay' POST variable is set
if (isset($_POST['barangay'])) {
    $selectedBarangay = $_POST['barangay'];

    try {
        $sql = "
            SELECT 
                bgy.barangay,
                pr.purok,
                COUNT(p.dog_id) AS pet_count
            FROM 
                pets p
            JOIN 
                owners o ON p.owner_id = o.owner_id
            JOIN 
                tbl_purok pr ON o.owner_id = pr.owner_id
            JOIN 
                tbl_barangay bgy ON bgy.barangay_id = pr.barangay_id
            WHERE 
                bgy.barangay = :barangay
            GROUP BY 
                bgy.barangay, pr.purok
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':barangay', $selectedBarangay, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }

    $conn = null;
} else {
    echo json_encode(["error" => "Barangay not specified."]);
}
