<?php
include_once '../database/databaseModel.php';

getSession();

include_once '../database/conn.php';

$action = $_POST['action'];

if ($action == 'createUser') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
}

if ($action == 'fetchUsers') {
    $userId = $_SESSION['user_id']; // Get the authenticated user's ID
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id != :sessionID");
    $stmt->bindParam(":sessionID", $userId, PDO::PARAM_INT); // Bind the session ID parameter
    $stmt->execute(); // Execute the query
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all users except the authenticated user
    echo json_encode($users); // Return the users as a JSON response
}
if ($action == 'getUser') {
    $user_id = $_POST['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
}

if ($action == 'updateUser') {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $stmt = $conn->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE user_id = :user_id");
        $stmt->bindParam(":password", $password);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = :name, email = :email WHERE user_id = :user_id");
    }

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
}

if ($action == 'deleteUser') {
    $user_id = $_POST['user_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
}
