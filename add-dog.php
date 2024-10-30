<?php
$title = "Add New Dog | Profiling and Mapping System";
include('includes/header.php');
$categories = [];
$stmt = $conn->prepare("SELECT id, category_name FROM category");
$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer"></i>Add New Dog Data</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">Add New Dog Data</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-body">
        <form id="addOwnerForm" enctype="multipart/form-data">
            <div class="table-responsive">
                <table class="table table-bordered" id="petTable">
                    <thead>
                        <tr>
                            <th>Dog Tag</th>
                            <th>Name of Dog</th>
                            <th>Image</th>
                            <th>Age</th>
                            <th>Color</th>
                            <th>Breed</th>
                            <th>Category</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="pet-row">
                            <td>
                                <input type="text" name="dogtag[]" class="form-control validate dogtag">
                            </td>
                            <td>
                                <input type="hidden" name="pet_count" id="count" value="1">
                                <input type="text" name="pet_name[]" class="form-control validate">
                            </td>
                            <td>
                                <input type="file" name="pet_image[]" class="form-control validate">
                            </td>
                            <td><input type="text" name="pet_age[]" class="form-control validate"></td>
                            <td><input type="text" name="color[]" class="form-control validate"></td>
                            <td><input type="text" name="breed[]" class="form-control validate"></td>
                            <td>
                                <select name="category[]" class="form-control validate">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary add-pet"><i class="bi bi-plus"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-group mt-3">
                <input type="hidden" name="owner_id" value="<?= $_GET['id'] ?>">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        $(document).on('click', '.add-pet', function() {
            var count = parseInt($("#count").val());
            count++;
            $("#count").val(count);
            $('.add-pet').remove();

            var newRow = '<tr class="pet-row">' +
                '<td><input type="text" name="dogtag[]" class="form-control validate dogtag"></td>' +
                '<td><input type="text" name="pet_name[]" class="form-control validate"></td>' +
                '<td><input type="file" name="pet_image[]" class="form-control validate"></td>' +
                '<td><input type="text" name="pet_age[]" class="form-control validate"></td>' +
                '<td><input type="text" name="color[]" class="form-control validate"></td>' +
                '<td><input type="text" name="breed[]" class="form-control validate"></td>' +
                ` <td>
                    <select name="category[]" class="form-control validate">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>` +
                '<td>' +
                '<button type="button" class="btn btn-danger remove-pet"><i class="bi-trash fs-6"></i></button>' +
                '</td>' +
                '</tr>';
            $("#petTable tbody").append(newRow);
            $("#petTable tbody tr:last td:last").append('<button type="button" class="btn btn-primary add-pet mx-2"><i class="bi bi-plus"></i></button>');
        });

        $(document).on('click', '.remove-pet', function() {
            $(this).closest('tr').remove();
            if ($("#petTable tbody tr").length === 0) {
                var count = parseInt($("#count").val());
                if (count > 1) {
                    count--;
                }
                $("#count").val(count);

                var newRow = '<tr class="pet-row">' +
                    '<td><input type="text" name="dogtag[]" class="form-control validate dogtag"></td>' +
                    '<td><input type="text" name="pet_name[]" class="form-control validate"></td>' +
                    '<td><input type="file" name="pet_image[]" class="form-control validate"></td>' +
                    '<td><input type="text" name="pet_age[]" class="form-control validate"></td>' +
                    '<td><input type="text" name="color[]" class="form-control validate"></td>' +
                    '<td><input type="text" name="breed[]" class="form-control validate"></td>' +
                    ` <td>
                    <select name="category[]" class="form-control validate">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>` +
                    '<td>' +
                    '<button type="button" class="btn btn-danger remove-pet"><i class="bi-trash fs-6"></i></button>' +
                    '</td>' +
                    '</tr>';
                $("#petTable tbody").append(newRow);
            } else {
                $('.add-pet').remove();
                $("#petTable tbody tr:last td:last").append('<button type="button" class="btn btn-primary mx-2 add-pet"><i class="bi bi-plus"></i></button>');
            }
            var count = parseInt($("#count").val());
            if (count > 1) {
                count--;
            }
            $("#count").val(count);
        });

        $(document).on('keydown', '.dogtag', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                let nextInput = $(this).closest('td').next().find('input, select');
                if (nextInput.length > 0) {
                    nextInput.focus();
                }
            }
        });

        $('#addOwnerForm').submit(function(e) {
            e.preventDefault()
            let formIsValid = true;
            var formData = new FormData(this)
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
                    url: base_url + '/actions/insert-newdog.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if (response.status) {
                            swal({
                                title: "Success",
                                text: "New Owner Added",
                                icon: "success",
                                button: "Okay",
                            }).then(function() {
                                window.location.href = "view-owned-dog.php?id=" + response.resp_id + "";
                            });
                        }
                    }
                });
            }
        });
    });
</script>


<?php include('includes/footer.php'); ?>