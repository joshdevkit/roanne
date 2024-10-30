<?php
$title = "Dog Owners' | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer"></i>Edit Dog Owner</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">Edit Dog Owner</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <a class="btn btn-primary float-end" href="dog-owners.php">Go Back</a>
    </div>
    <div class="card-body">

        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-<?= $_SESSION['status'] ?> alert-dismissible fade show" role="alert">
                <strong>Success</strong> <?= $_SESSION['message'] ?>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <?php
        $ownerID = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM owners o 
        INNER JOIN tbl_purok p ON p.owner_id = o.owner_id
        INNER JOIN tbl_barangay b ON b.barangay_id = p.barangay_id
        LEFT JOIN tbl_municipality m ON m.municipality_id = b.municipality_id
        LEFT JOIN tbl_province pr ON pr.province_id = m.province_id
        WHERE o.owner_id = ? ");
        $stmt->execute([$ownerID]);
        $data = $stmt->fetch();
        ?>
        <form action="actions/update-owner.php" method="POST">
            <div class="row">
                <input type="hidden" name="owner_id" value="<?= $_GET['id'] ?>">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Firstname</label>
                        <input type="text" value="<?= $data['firstname'] ?>" name="firstname" class="form-control validate">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Middlename</label>
                        <input type="text" value="<?= $data['middlename'] ?>" name="middlename" class="form-control validate">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Lastname</label>
                        <input type="text" value="<?= $data['lastname'] ?>" name="lastname" class="form-control validate">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Contact</label>
                        <input type="text" value="<?= $data['contact'] ?>" name="contact" class="form-control validate">
                    </div>
                </div>
                <hr>
                <h6>Complete Address</h6>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Purok</label>
                        <input type="text" value="<?= $data['purok'] ?>" name="purok" class="form-control validate">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Barangay</label>
                        <input type="text" value="<?= $data['barangay'] ?>" name="barangay" class="form-control validate">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Municipality</label>
                        <input type="text" value="<?= $data['municipality'] ?>" name="municipality" class="form-control validate">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Province</label>
                        <input type="text" value="<?= $data['province'] ?>" name="province" class="form-control validate">
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">Update Owner</button>
                </div>
            </div>
        </form>
    </div>
</div>



<?php include('includes/footer.php'); ?>