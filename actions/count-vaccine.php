<?php

require '../database/conn.php';

// Get the current year
$currentYear = date('Y');

$stmt = $conn->prepare("
    SELECT 
        SUM(CASE WHEN YEAR(v.date_of_vaccination) = :currentYear THEN 1 ELSE 0 END) AS vaccinated_count,
        SUM(CASE WHEN YEAR(v.date_of_vaccination) IS NULL OR YEAR(v.date_of_vaccination) <> :currentYear THEN 1 ELSE 0 END) AS unvaccinated_count
    FROM 
        dog d
    LEFT JOIN 
        vaccination_records v 
    ON 
        d.dog_id = v.dog_id
");

$stmt->execute(['currentYear' => $currentYear]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$vaccinatedCount = $result['vaccinated_count'];
$unvaccinatedCount = $result['unvaccinated_count'];

$response = [
    'vaccinated' => $vaccinatedCount,
    'unvaccinated' => $unvaccinatedCount
];

echo json_encode($response);