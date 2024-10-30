<?php
$title = "Dog Owners' | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1> Reports</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">Reports</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h4>Vaccination Report</h4>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered" id="sampleTable">
            <thead>
                <tr>
                    <th rowspan="2">Brgy</th>
                    <th rowspan="2">Pet Owner's Name</th>
                    <th rowspan="2">Age</th>
                    <th rowspan="2">No. of heads</th>
                    <th rowspan="2">Vaccine Use</th>
                    <th rowspan="2">batch / Lot No</th>
                    <th rowspan="2">Vaccine Source</th>
                    <th colspan="4">Remarks</th>
                </tr>
                <tr>
                    <th>Name of Pet</th>
                    <th>Category</th>
                    <th>Color</th>
                    <th>Breed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $currentYear = date('Y');

                $sql = "
                    SELECT 
                        *,
                    CONCAT(o.firstname, ' ',o.middlename, ' ',o.lastname) AS fullname,
                    CONCAT(p.purok, ' ',b.barangay,' ',m.municipality, ' ',pr.province) as address 
                    FROM owners o 
                    LEFT JOIN pets ON pets.owner_id = o.owner_id 
                    LEFT JOIN category c ON c.id = pets.category_id
                    INNER JOIN tbl_purok p ON p.owner_id = o.owner_id
                    INNER JOIN tbl_barangay b ON b.barangay_id = p.barangay_id
                    LEFT JOIN tbl_municipality m ON m.municipality_id = b.municipality_id
                    LEFT JOIN tbl_province pr ON pr.province_id = m.province_id
                    INNER JOIN vaccine_records vr ON vr.dog_id = pets.dog_id 
                    INNER JOIN vaccines vc ON vc.id = vr.vaccine_id
                    WHERE YEAR(vr.date_of_vaccination) = :currentYear
                ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($data) {
                    foreach ($data as $row) {
                ?>
                        <tr>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td><?= htmlspecialchars($row['pet_age']) ?></td>
                            <td></td>
                            <td><?= htmlspecialchars($row['vaccine_name'] ?? 'N/A') ?></td>
                            <td></td>
                            <td></td>
                            <td><?= htmlspecialchars($row['pet_name']) ?></td>
                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                            <td><?= htmlspecialchars($row['color']) ?></td>
                            <td><?= htmlspecialchars($row['breed']) ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>


    </div>
</div>

<?php include('includes/footer.php'); ?>