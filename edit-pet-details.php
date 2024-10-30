<?php
$title = "Edit Pet Details | Profiling and Mapping System";
include('includes/header.php');
?>
<style>
    .dropzone {
        border: 2px dashed #6c757d;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        background-color: #f9f9f9;
        position: relative;
        transition: background-color 0.3s ease;
    }

    .dropzone:hover {
        background-color: #e9ecef;
    }

    .upload-message {
        font-size: 1rem;
        color: #6c757d;
    }

    #previewImage {
        margin-top: 10px;
        max-width: 150px;
        display: block;
    }
</style>

<div class="app-title">
    <div>
        <h1><i class="bi bi-paw"></i> Edit Pet Details</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="dog-owners.php">Go back to Pet Owners</a></li>
    </ul>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Pet Information</h4>
    </div>
    <div class="card-body">
        <?php
        if (isset($_SESSION['message'])) {
        ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>
                    <?= $_SESSION['message'] ?>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        unset($_SESSION['message']);
        ?>

        <?php
        $petId = $_GET['petId'];
        $ownersData = $conn->prepare('SELECT p.*, c.category_name FROM pets p LEFT JOIN category c ON c.id = p.category_id WHERE p.dog_id = ?');
        $ownersData->execute([$petId]);
        $pet = $ownersData->fetch(PDO::FETCH_ASSOC);

        $categoryList = $conn->prepare('SELECT * FROM category');
        $categoryList->execute();
        $categories = $categoryList->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <form action="actions/update-pet.php?update=<?= $petId ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="petId" value="<?php echo $petId; ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Category:</label>
                        <select name="category_id" class="form-control form-select">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo $category['id'] == $pet['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo $category['category_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Dog Tag:</label>
                        <input type="text" class="form-control" value="<?php echo $pet['dogtag']; ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label>Pet Name:</label>
                        <input name="pet_name" type="text" class="form-control" value="<?php echo $pet['pet_name']; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label>Pet Age:</label>
                        <input name="pet_age" type="text" class="form-control" value="<?php echo $pet['pet_age']; ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label>Status:</label>
                        <select name="pet_status" class="form-control form-select">
                            <option value="">Select Status</option>
                            <option value="Alive" <?= $pet['pet_status'] === 'Alive' ? 'selected' : ''; ?>>Alive</option>
                            <option value="Missing" <?= $pet['pet_status'] === 'Missing' ? 'selected' : ''; ?>>Missing</option>
                            <option value="Death" <?= $pet['pet_status'] === 'Death' ? 'selected' : ''; ?>>Death</option>
                        </select>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="color">Color:</label>
                        <input type="text" name="color" class="form-control" value="<?php echo $pet['color']; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="breed">Breed:</label>
                        <input type="text" name="breed" class="form-control" value="<?php echo $pet['breed']; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="image">Pet Image:</label>
                        <div id="dropzone" class="dropzone">
                            <input type="file" name="image" id="imageInput" class="form-control-file" style="display: none;">
                            <div class="upload-message" id="uploadMessage">Click to Upload</div>
                            <?php if ($pet['image']): ?>
                                <img src="image/pet_images/<?php echo $pet['image']; ?>" alt="Pet Image" id="previewImage" class="img-thumbnail mt-3 w-100" style="width: 100px;">
                            <?php else: ?>
                                <img src="" alt="Preview" id="previewImage" class="img-thumbnail mt-3" style="display: none; width: 100px;">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right mt-4">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="dog-owners.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>


<script>
    document.getElementById('dropzone').addEventListener('click', function() {
        document.getElementById('imageInput').click();
    });

    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImage = document.getElementById('previewImage');
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                document.getElementById('uploadMessage').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php include('includes/footer.php'); ?>