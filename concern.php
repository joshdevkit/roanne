<?php
$title = "Dog Concern | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1>Concern Records</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h4>
            Pet Concern
        </h4>
    </div>
    <div class="card-body">
        <div id="alertDiv" class="alert alert-warning fade show" role="alert">
            <strong>Attention!</strong> Please scan the RFID to proceed.
            <form id="rfScanForm">
                <input type="text" name="rfvalue" id="rfvalue" style="opacity: 0; height: 1px; position: absolute;">
            </form>
        </div>
        <div id="pet_card" class="row align-items-center shadow-lg p-4 rounded bg-light py-2 d-none">
            <div class="col-md-4 col-lg-4 col-sm-12 text-center">
                <img style="height: 32rem; width: 22rem;" alt="Dog Image" id="dogImage" class="w-100 border border-secondary shadow-sm">
            </div>
            <div class="col-md-8 col-lg-8 col-sm-12" id="dogAndOwner">
                <h4 id="petName" class="text-primary"></h4>
                <p><strong>Breed:</strong> <span id="breed"></span></p>
                <p><strong>Color:</strong> <span id="color"></span></p>
                <p><strong>Age:</strong> <span id="petAge"></span></p>

                <div id="vaccineSection" class="mt-3 d-none">
                    <hr>
                    <h5 class="text-success">Vaccination Details</h5>
                    <p><strong>Status:</strong> <span id="vaccineStatus"></span></p>
                    <p><strong>Vaccine Name:</strong> <span id="vaccineName"></span></p>
                    <p><strong>Date of Vaccination:</strong> <span id="vaccineDate"></span></p>
                </div>

                <hr>
                <h5 class="text-secondary">Owner Details</h5>
                <p><strong>Full Name:</strong> <span id="ownerName"></span></p>
                <p><strong>Contact:</strong> <span id="ownerContact"></span></p>
                <p><strong>Address:</strong> <span id="ownerAddress"></span></p>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('#rfvalue').focus();
        $('#rfScanForm').submit(function(e) {
            e.preventDefault();
            var rfValue = $('#rfvalue').val();
            $.ajax({
                url: 'actions/scan-rf.php?action=concern',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $('#pet_card').removeClass('d-none')
                        $('#alertDiv').addClass('d-none');
                        $('#dogImage').attr('src', 'image/pet_images/' + response.record.image);
                        $('#petName').text(response.record.pet_name);
                        $('#breed').text(response.record.breed);
                        $('#color').text(response.record.color);
                        $('#petAge').text(response.record.pet_age);

                        $('#ownerName').text(response.owner.firstname + " " + response.owner.middlename + " " + response.owner.lastname);
                        $('#ownerContact').text(response.owner.contact);
                        $('#ownerAddress').text(response.owner.purok + " " + response.owner.barangay + " " + response.owner.municipality + " " + response.owner.province);

                        // Check for vaccination details
                        if (response.vaccine) {
                            $('#vaccineSection').removeClass('d-none');
                            $('#vaccineStatus').text(response.vaccine.status);
                            $('#vaccineName').text(response.vaccine.vaccine_name);
                            $('#vaccineDate').text(response.vaccine.date_of_vaccination);
                        }
                    }
                }
            });
        });
    });
</script>




<?php include('includes/footer.php'); ?>