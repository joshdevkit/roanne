<?php
require '../database/conn.php';

try {
    // Get the current year
    $currentYear = date('Y');

    $sql = "
        SELECT 
            bgy.barangay,
            COUNT(DISTINCT p.dog_id) AS pet_count
        FROM 
            pets p
        JOIN 
            owners o ON p.owner_id = o.owner_id
        JOIN 
            tbl_purok pr ON o.owner_id = pr.owner_id
        JOIN 
            tbl_barangay bgy ON bgy.barangay_id = pr.barangay_id
        INNER JOIN 
            vaccine_records vr ON p.dog_id = vr.dog_id 
            AND YEAR(vr.date_of_vaccination) = :currentYear
        GROUP BY 
            bgy.barangay
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        'counts' => []
    ];

    foreach ($data as $row) {
        $response['counts'][$row['barangay']] = (int)$row['pet_count'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$conn = null;
