<?php
$title = "User Management | Profiling and Mapping System";
include('includes/header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer"></i> Users</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">User Management</a></li>
    </ul>
</div>

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <h4>
                All Users
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    Create New User
                </button>
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="sampleTable" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userData">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createUserForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editUserForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="form-group mb-3">
                        <label for="edit_name">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_email">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_password">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        fetchUsers();

        function fetchUsers() {
            $.ajax({
                url: 'actions/ajax.php',
                method: 'POST',
                data: {
                    action: 'fetchUsers'
                },
                dataType: 'json',
                success: function(data) {
                    let userRows = '';
                    $.each(data, function(index, user) {
                        userRows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editUser(${user.user_id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.user_id})">Delete</button>
                        </td>
                    </tr>
                `;
                    });
                    $('#userData').html(userRows);
                }
            });
        }

        $('#createUserForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'actions/ajax.php',
                method: 'POST',
                data: $(this).serialize() + '&action=createUser',
                dataType: 'json',
                success: function(response) {
                    $('#createUserModal').modal('hide');
                    fetchUsers(); // Reload the users
                    alert(response.message);
                }
            });
        });

        // Function to populate the edit modal with user data
        window.editUser = function(user_id) {
            $.ajax({
                url: 'actions/ajax.php',
                method: 'POST',
                data: {
                    user_id: user_id,
                    action: 'getUser'
                },
                dataType: 'json',
                success: function(user) {
                    $('#edit_user_id').val(user.user_id);
                    $('#edit_name').val(user.name);
                    $('#edit_email').val(user.email);
                    $('#editUserModal').modal('show');
                }
            });
        }

        // Handle form submission for updating a user
        $('#editUserForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'actions/ajax.php',
                method: 'POST',
                data: $(this).serialize() + '&action=updateUser',
                dataType: 'json',
                success: function(response) {
                    $('#editUserModal').modal('hide');
                    fetchUsers(); // Reload the users
                    alert(response.message);
                }
            });
        });

        // Function to delete a user
        window.deleteUser = function(user_id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: 'actions/ajax.php',
                    method: 'POST',
                    data: {
                        user_id: user_id,
                        action: 'deleteUser'
                    },
                    dataType: 'json',
                    success: function(response) {
                        fetchUsers(); // Reload the users
                        alert(response.message);
                    }
                });
            }
        }
    });
</script>

<?php include('includes/footer.php'); ?>