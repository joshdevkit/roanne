<?php
$title = "Add Vaccination to Existing Owner's Dog' | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1>Add Vaccination to Existing Owner's Dog</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">Add Vaccination to Existing Owner's Dog</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h4>
            Scan RFID
        </h4>
    </div>
    <div class="card-body">
        <div id="alertDiv" class="alert alert-warning fade show" role="alert">
            <strong>Attention!</strong> Please scan the RFID to proceed.
            <form id="rfScanForm">
                <input type="text" name="rfvalue" id="rfvalue" style="opacity: 0; height: 1px; position: absolute;">
            </form>
        </div>
        <table id="dataTable" class="table d-none">
            <thead>
                <tr>
                    <th>DOG NAME</th>
                    <th>OWNER</th>
                    <th>ADDRESS</th>
                    <th>OWNER'S CONTACT #</th>
                    <th>VACCINATION STATUS</th>
                </tr>
            </thead>
            <tbody id="data_table">

            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="notFoundModal" tabindex="-1" aria-labelledby="notFoundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="notFoundModalLabel">Pet Not Found</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                No matching pet found for the specified dog tag.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#rfvalue').focus();
        $('#rfScanForm').submit(function(e) {
            e.preventDefault()
            var rfValue = $('#rfvalue').val()
            $.ajax({
                url: 'actions/scan-rf.php?action=vaccination',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        const data = response.data
                        window.location.href = "add-vacination.php?key=" + data.dog_id + "&petname=" + data.pet_name;
                    } else {
                        var notFoundModal = new bootstrap.Modal(document.getElementById('notFoundModal'));
                        notFoundModal.show();
                    }
                    response.forEach(item => {
                        var dog_id = item.dog_id
                        var pet_name = item.pet_name;
                        window.location.href = "add-vacination.php?key=" + dog_id + "&petname=" + pet_name;
                        console.log(item);
                    });

                }
            })
        })
    })
</script>




<?php include('includes/footer.php'); ?>