<?php
$title = "Dog Owners' | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1> Pet Owner's</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">Pet Owner's</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <a class="btn btn-primary float-end" href="add-owner.php">Add New Owner</a>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-<?= $_SESSION['status'] ?> alert-dismissible fade show" role="alert">
                <strong>Success</strong> <?= $_SESSION['message'] ?>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="table-responsive">



            <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Number of Heads</th>
                        <th>Date Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("
                    SELECT 
                    o.*, 
                    COUNT(pt.owner_id) as number_of_heads,
                    CONCAT(o.firstname, ' ', o.middlename, ' ', o.lastname) AS fullname,
                    CONCAT(p.purok, ' ',b.barangay, ' ',m.municipality, ' ',pr.province) as address
                        FROM owners o 
                        INNER JOIN tbl_purok p ON p.owner_id = o.owner_id
                        INNER JOIN tbl_barangay b ON b.barangay_id = p.barangay_id
                        LEFT JOIN tbl_municipality m ON m.municipality_id = b.municipality_id
                        LEFT JOIN tbl_province pr ON pr.province_id = m.province_id
                        LEFT JOIN pets pt ON pt.owner_id = o.owner_id
                        GROUP BY o.owner_id
                    ");
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        $ownersData = $stmt->fetchAll();
                        foreach ($ownersData as $owner) {
                    ?>
                            <tr>
                                <td><?php echo $owner['fullname']; ?></td>
                                <td><?php echo $owner['contact']; ?></td>
                                <td><?php echo $owner['address']; ?></td>
                                <td><?=  $owner['number_of_heads'] ?></td>
                                <td><?php echo date('F m, Y h:i A', strtotime($owner['date_created'])); ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="view-owned-dog.php?id=<?= $owner['owner_id'] ?>">View Pets</a>
                                    <a class="btn btn-sm btn-info" href="edit-data.php?id=<?= $owner['owner_id'] ?>">Edit</a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>