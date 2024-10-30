<?php
$title = "Dog Owners' | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer"></i> Dog Vaccination List</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">Dog Vaccination List</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h4><?= strtoupper($_GET['petname']) ?> Vaccination Data</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="sampleTable">
                <thead>
                    <tr>
                        <th>Record No.</th>
                        <th>Pet Name</th>
                        <th>Vaccine Name</th>
                        <th>Date of Vaccination</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $dogId = $_GET['key'];
                    $stmt = $conn->prepare("SELECT v.*, d.dog_id, d.pet_name, vc.vaccine_name as vaccine_used
                    FROM vaccine_records v 
                    INNER JOIN pets d ON d.dog_id = v.dog_id 
                    INNER JOIN vaccines vc ON vc.id = v.vaccine_id
                    WHERE v.dog_id = :dogId ");
                    $stmt->bindParam(':dogId', $dogId);
                    $stmt->execute();
                    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($records as $row) {
                    ?>
                        <tr>
                            <td><?= $row['record_id'] ?></td>
                            <td><?= $row['pet_name'] ?></td>
                            <td><?= $row['vaccine_used'] ?></td>
                            <td><?= date('F d, Y', strtotime($row['date_of_vaccination'])) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>