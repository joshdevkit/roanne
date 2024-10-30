<?php
$title = "Dog Owned | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1>Add Vaccination Data</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#"><?= strtoupper($_GET['petname']) ?></a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h4>
            Add Vaccination Details : <?= strtoupper($_GET['petname']) ?>
            <button onclick="history.back()" class="btn btn-primary float-end">Back</button>
        </h4>
    </div>
    <div class="card-body">
        <form id="addVaccination">
            <div class="row">
                <!-- Form Section -->
                <div class="col-md-6">
                    <form id="addVaccination">
                        <?php
                        $id = $_GET['key'];
                        $stmt = $conn->prepare("SELECT * FROM pets WHERE dog_id = :dogID ");
                        $stmt->bindParam(":dogID", $id);
                        $stmt->execute();
                        $dogData = $stmt->fetch();
                        ?>
                        <input type="hidden" name="dog_id" value="<?= $_GET['key'] ?>">
                        <div class="mb-3">
                            <label for="vaccine_id">Vaccine</label>
                            <select name="vaccine_id" class="form-select form-control validate" id="vaccine_id">
                                <option value="">Select Vaccine</option>
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM vaccines");
                                $stmt->execute();

                                $data = $stmt->fetchAll(PDO::FETCH_OBJ);

                                foreach ($data as $datarow) {
                                    echo "<option value='" . $datarow->id . "'>" . $datarow->vaccine_name . "</option>";
                                }
                                ?>

                            </select>
                            <div class="invalid-feedback">Please Select Vaccine</div>
                        </div>

                        <!-- Date of Vaccination -->
                        <div class="mb-3">
                            <label for="date_of_vaccination">Date of Vaccination</label>
                            <input type="date" class="form-control validate" name="date_of_vaccination" id="date_of_vaccination">
                            <div class="invalid-feedback">Choose a date</div>
                        </div>


                        <!-- Submit Button -->
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Pet Details</h5>
                            <p><strong>Name:</strong> <?= $dogData['pet_name']; ?></p>
                            <p><strong>Breed:</strong> <?= $dogData['breed']; ?></p>
                            <p><strong>Color:</strong> <?= $dogData['color']; ?></p>
                            <p><strong>Age:</strong> <?= $dogData['pet_age']; ?></p>
                        </div>
                        <div>
                            <img src="image/pet_images/<?= $dogData['image'] ?>" alt="Dog Image" class="img-fluid rounded border shadow-sm" style="width: 150px; height: auto;">
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#addVaccination').submit(function(e) {
            e.preventDefault();
            let formIsValid = true;
            $('.validate').each(function() {
                if ($(this).val().trim() === '') {
                    $(this).addClass('is-invalid');
                    formIsValid = false;
                    setTimeout(() => {
                        $(this).removeClass('is-invalid');
                    }, 1500);
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            if (formIsValid) {
                $.ajax({
                    url: base_url + '/actions/insert-vaccination-details.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.status) {
                            Swal.fire({
                                title: "Success",
                                text: "New Vaccination Record Added",
                                icon: "success",
                                confirmButtonText: "Okay"
                            }).then(function() {
                                window.location.href = "vaccine-list.php?key=<?php echo $_GET['key']; ?>&petname=<?php echo $_GET['petname']; ?>";
                            });
                        }
                    }

                });
            }
        });
    });
</script>

<?php include('includes/footer.php'); ?>