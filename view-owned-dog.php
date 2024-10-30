<?php
$title = "Pet Owned | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-paw"></i> Pets</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="dog-owners.php">Go back to Pet Owners</a></li>
    </ul>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Pet List</h4>
        <div>
            <?php if ($_SESSION['account_type'] == 'Administrator'): ?>

                <select id="categoryFilter" class="form-select form-select-sm d-inline-block w-auto me-2">
                    <option value="All">All</option>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                </select>
            <?php endif; ?>

            <a href="add-dog.php?id=<?= $_GET['id'] ?>" class="btn btn-primary btn-sm">Add New Pet</a>
        </div>
    </div>

    <div class="card-body">
        <div class="row" id="petList">
            <?php
            $petId = $_GET['id'];
            $ownersData = $conn->prepare('SELECT p.*, c.category_name FROM pets p LEFT JOIN category c ON c.id = p.category_id WHERE p.owner_id = ? AND p.is_archived = 0  ');
            $ownersData->execute([$petId]);
            $pets = $ownersData->fetchAll(PDO::FETCH_ASSOC);

            foreach ($pets as $pet):
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 pet-item" data-category="<?= $pet['category_name'] ?>">
                    <div class="card h-100">
                        <img src="image/pet_images/<?= $pet['image'] ?>" class="card-img-top img-fluid" alt="Pet Image" style="height:350px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $pet['pet_name'] ?></h5>
                            <p class="card-text small text-muted">
                                <strong>Category:</strong> <?= $pet['category_name'] ?><br>
                                <strong>Tag:</strong> <?= $pet['dogtag'] ?><br>
                                <strong>Age:</strong> <?= $pet['pet_age'] ?> years<br>
                                <strong>Color:</strong> <?= $pet['color'] ?><br>
                                <strong>Breed:</strong> <?= $pet['breed'] ?><br>
                                <strong>Pet Status:</strong> <?= $pet['pet_status'] ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu w-100">

                                    <li><a class="dropdown-item" href="add-vacination.php?key=<?= $pet['dog_id'] ?>&petname=<?= $pet['pet_name'] ?>">Add Vaccination</a></li>
                                    <li><a class="dropdown-item" href="vaccine-list.php?key=<?= $pet['dog_id'] ?>&petname=<?= $pet['pet_name'] ?>">Vaccination List</a></li>
                                    <li><a class="dropdown-item" href="edit-pet-details.php?petId=<?= $pet['dog_id'] ?>">Edit Info</a></li>
                                    <li><button type="button" class="dropdown-item text-danger archive" data-id="<?= $pet['dog_id'] ?>">Archive</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('categoryFilter').addEventListener('change', function() {
        var selectedCategory = this.value;
        var petItems = document.querySelectorAll('.pet-item');

        petItems.forEach(function(item) {
            if (selectedCategory === 'All' || item.getAttribute('data-category') === selectedCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    $(document).on('click', '.archive', function() {
        var dataId = $(this).data('id');

        // SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "This will archive the pet record.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, archive it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/update-pet.php',
                    type: 'POST',
                    data: {
                        petID: dataId
                    },
                    success: function(response) {
                        Swal.fire(
                            'Archived!',
                            'The pet record has been archived.',
                            'success'
                        );
                        setTimeout(() => {
                            location.reload()
                        }, 1500);
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'An error occurred. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>

<?php include('includes/footer.php'); ?>