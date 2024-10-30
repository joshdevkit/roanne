<?php
$title = "Dog Owners' | Profiling and Mapping System";
include('includes/header.php');
?>
<style>
    #barangayContainer {
        border: 1px solid #ddd;
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .barangay-item {
        padding: 6px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }

    .barangay-item:hover {
        background-color: #f0f0f0;
    }

    .barangay-item.selected {
        background-color: #28a745;
        color: white;
    }

    #paginationControls {
        text-align: center;
    }

    .pagination-btn {
        background-color: #00695C;
        border: none;
        color: white;
        padding: 10px 15px;
        margin: 5px;
        border-radius: 4px;
        cursor: pointer;
    }

    .pagination-btn:hover {
        background-color: #005B4C;
    }

    .pagination-btn.disabled {
        background-color: #ddd;
        cursor: not-allowed;
    }

    .pagination-btn.active {
        background-color: #6c757d;
        color: white;
    }
</style>
<div class="app-title">
    <div>
        <h1>Add Dog Owner's</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">Add Dog Owner's</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <a class="btn btn-primary float-end" href="dog-owners.php">Go Back</a>
    </div>
    <div class="card-body">
        <h5 class="card-title">Owner Details</h5>
        <form id="addOwnerForm" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="firstname">Firstname</label>
                            <input type="text" name="firstname" id="firstname" class="form-control validate" placeholder="Enter Firstname">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="middlename">Middlename</label>
                            <input type="text" name="middlename" id="middlename" class="form-control validate" placeholder="Enter Middlename">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lastname">Lastname</label>
                            <input type="text" name="lastname" id="lastname" class="form-control validate" placeholder="Enter Lastname">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="contact">Contact</label>
                        <input type="number" name="contact" autocomplete="off" id="contact" placeholder="+63" class="form-control validate">
                    </div>
                    <div class="form-group mb-3">
                        <label for="purok">Purok</label>
                        <input type="text" name="purok" id="purok" placeholder="Enter Purok" class="form-control validate">
                    </div>

                    <div id="options" class="form-group mb-3">
                        <input type="hidden" name="selectedBrgy" id="selectedBrgy">
                        <label for="barangay">Barangay</label>
                        <div id="barangayContainer" class="mt-2">
                        </div>
                        <div id="paginationControls">
                        </div>
                    </div>
                    <!-- <div id="displayselectedBrgy" class="input-group mb-3 d-none">
                        <label for="barangay">Selected Barangay</label>

                        <div class="input-group-prepand">
                            <span class="input-group-text" id="basic-addon1">@</span>
                            <input readonly class="form-control" id="displayedSelectedBrgy">
                        </div>
                    </div> -->
                    <div id="displayselectedBrgy" class="input-group mb-3">
                        <input readonly class="form-control" id="displayedSelectedBrgy">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2"><button class="btn btn-sm btn-info btn-change-selected-brgy" type="button"><i class="fas fa-edit"></i></button></span>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="province">Province</label>
                        <select name="province" id="province" class="form-select form-control">
                            <option value="">Select Province</option>
                        </select>
                        <input type="hidden" name="selectedProvince" id="selectedProvince">
                    </div>
                    <div class="form-group mb-3">
                        <label for="municipality">Municipality</label>
                        <select name="municipality" id="municipality" class="form-select form-control">
                            <option value="">Select Municipality</option>
                        </select>
                        <input type="hidden" name="selectedMunicipality" id="selectedMunicipality">
                    </div>

                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary float-end" id="addPetButton">Add Pet</button>
                    <h5 class="mt-2">
                        Pet Details
                    </h5>
                    <hr>
                    <div id="petCardsContainer">
                    </div>
                </div>
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {

        $('#contact').on('input', function() {
            var contactValue = $(this).val();

            // Ensure the first character is between 1 and 9
            if (contactValue.charAt(0) === '0') {
                $(this).val(contactValue.slice(1)); // Remove the leading '0'
            }

            // Enforce a maximum length of 10 digits
            if (contactValue.length > 10) {
                $(this).val(contactValue.slice(0, 10));
            }

            // Add or remove validation class based on length
            if (contactValue.length === 10) {
                $('#contact').removeClass('is-invalid');
            } else {
                $('#contact').addClass('is-invalid');
            }
        });


        $('#addPetButton').on('click', function() {
            $.ajax({
                url: 'actions/get-category.php',
                method: 'GET',
                dataType: 'json',
                success: function(categories) {
                    var categoryOptions = '<option value="">Select a category</option>';
                    categories.forEach(function(category) {
                        categoryOptions += `<option value="${category.id}">${category.category_name}</option>`;
                    });
                    var petCard = `
                    <div class="card pet-card mb-3">
                        <div class="row g-0">
                            <div class="col-md-3 d-flex align-items-center justify-content-center">
                                <img src="image/sample.png" alt="Pet Image" class="img-fluid rounded-circle pet-image-preview" style="max-width: 120px; height: 120px;">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dogtag">Dog Tag</label>
                                            <input type="text" name="dogtag[]" class="form-control validate dogtag" placeholder="Enter dog tag">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="pet_name">Pet Name</label>
                                            <input type="text" name="pet_name[]" class="form-control validate" placeholder="Enter pet name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="pet_age">Age</label>
                                            <input type="text" name="pet_age[]" class="form-control validate" placeholder="Enter age">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="color">Color</label>
                                            <input type="text" name="color[]" class="form-control validate" placeholder="Enter color">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="breed">Breed</label>
                                            <input type="text" name="breed[]" class="form-control validate" placeholder="Enter breed">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="pet_image">Pet Image</label>
                                            <input type="file" name="pet_image[]" class="form-control pet-image-input validate" accept="image/*">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="category">Category</label>
                                            <select name="category[]" class="form-control form-select validate">
                                                ${categoryOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger remove-pet-card">Remove Pet</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    $('#petCardsContainer').append(petCard);
                },
                error: function() {
                    alert('Failed to load categories. Please try again.');
                }
            });
        });

        // Remove pet card
        $(document).on('click', '.remove-pet-card', function() {
            $(this).closest('.pet-card').remove();
        });

        // Handle image preview
        $(document).on('change', '.pet-image-input', function(event) {
            var reader = new FileReader();
            var $imagePreview = $(this).closest('.pet-card').find('.pet-image-preview');
            reader.onload = function(e) {
                $imagePreview.attr('src', e.target.result);
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        // Form submission logic
        $('#addOwnerForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
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
                    url: base_url + '/actions/insert-owner.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response)
                        if (response.status) {
                            Swal.fire({
                                title: "Success",
                                text: "New Owner Added",
                                icon: "success",
                                button: "Okay",
                            }).then(function() {
                                window.location.href = "dog-owners.php";
                            });
                        }
                    }
                });
            }
        });

        $(document).ready(function() {
            const itemsPerPage = 10;
            let currentPage = 1;
            let barangays = [];

            function renderBarangays() {
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageItems = barangays.slice(start, end);
                const barangayContainer = $('#barangayContainer');
                barangayContainer.empty();

                $.each(pageItems, function(index, barangay) {
                    barangayContainer.append(`<div class="barangay-item" data-code="${barangay.code}">${barangay.name}</div>`);
                });
            }

            function renderPaginationControls() {
                const totalPages = Math.ceil(barangays.length / itemsPerPage);
                const paginationControls = $('#paginationControls');
                paginationControls.empty();

                if (totalPages > 1) {
                    if (currentPage > 1) {
                        paginationControls.append(`<button class="pagination-btn" data-page="${currentPage - 1}">Previous</button>`);
                    } else {
                        paginationControls.append(`<button class="pagination-btn disabled">Previous</button>`);
                    }

                    for (let i = 1; i <= totalPages; i++) {
                        let activeClass = i === currentPage ? 'active' : '';
                        paginationControls.append(`<button class="pagination-btn ${activeClass}" data-page="${i}">${i}</button>`);
                    }

                    if (currentPage < totalPages) {
                        paginationControls.append(`<button class="pagination-btn" data-page="${currentPage + 1}">Next</button>`);
                    } else {
                        paginationControls.append(`<button class="pagination-btn disabled">Next</button>`);
                    }
                }
            }

            $.getJSON('barangay.json', function(data) {
                barangays = data;

                barangays.sort(function(a, b) {
                    return a.name.localeCompare(b.name);
                });
                renderBarangays();
                renderPaginationControls();
            }).fail(function() {
                console.error('Error fetching barangays.json');
            });

            $(document).on('click', '.pagination-btn', function() {
                if (!$(this).hasClass('disabled')) {
                    currentPage = parseInt($(this).data('page'));
                    renderBarangays();
                    renderPaginationControls();
                }
            });

            $(document).on('click', '.barangay-item', function() {
                $('.barangay-item').removeClass('selected');
                $(this).addClass('selected');
                //hide pagination and list
                $('#displayselectedBrgy').removeClass('d-none')
                $('#options').addClass('d-none')
                const barangayCode = $(this).data('code');
                const barangayName = $(this).text();
                $('#selectedBrgy').val(barangayName);
                //display the selected on input hidden
                $('#displayedSelectedBrgy').val(barangayName)

            });
            $(document).on('click', '.btn-change-selected-brgy', function() {
                $('#displayselectedBrgy').addClass('d-none')
                $('#options').removeClass('d-none')
            })
        });


        $.getJSON('provinces.json', function(data) {
            const provinces = data;

            provinces.forEach(function(province) {
                $("#province").append('<option value="' + province.code + '">' + province.name + '</option>');
            });
        });

        // Event listener for province change
        $("#province").change(function() {
            const selectedProvinceCode = $(this).val();
            const selectedProvinceName = $("#province option:selected").text();

            // Update the selected province text input
            $("#selectedProvince").val(selectedProvinceName);

            // Clear the municipality dropdown
            $("#municipality").empty();
            $("#municipality").append('<option value="">Select a municipality</option>');

            if (selectedProvinceCode) {
                $.getJSON(`https://psgc.gitlab.io/api/provinces/${selectedProvinceCode}/municipalities/`, function(data) {
                    const municipalities = data;

                    municipalities.forEach(function(municipality) {
                        $("#municipality").append('<option value="' + municipality.code + '">' + municipality.name + '</option>');
                    });
                });
            }
        });

        // Event listener for municipality change
        $("#municipality").change(function() {
            const selectedMunicipalityCode = $(this).val();
            const selectedMunicipalityName = $("#municipality option:selected").text();

            // Update the selected municipality text input
            $("#selectedMunicipality").val(selectedMunicipalityName);
        });

    });
</script>



<?php include('includes/footer.php'); ?>