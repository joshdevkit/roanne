<?php
include_once '../database/databaseModel.php';
baseUrl();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);
    $error = false;

    // Clear any previous errors
    unset($_SESSION['error_email'], $_SESSION['error_password']);

    // Validate email field
    if (empty($email)) {
        $_SESSION['error_email'] = "Email is required.";
        $error = true;
    }

    // Validate password field
    if (empty($password)) {
        $_SESSION['error_password'] = "Password is required.";
        $error = true;
    }

    if ($error) {
        header("location: " . base_url . "");
        exit();
    }

    // Check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['error_email'] = "No account found with this email.";
        header("location: " . base_url . "");
    } else {
        if (password_verify($password, $user['password'])) {
            $_SESSION['authenticated'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['fullname'] = $user['name'];
            $_SESSION['account_type'] = $user['account_type'];
            getSession();
            header("location: " . base_url . "dashboard.php");
        } else {
            $_SESSION['error_password'] = "Invalid password.";
            header("location: " . base_url . "");
        }
    }
    exit();
}
