<?php
session_start();
include "db.php"; // Ensure this file connects to your database

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Google reCAPTCHA secret key
    $secret_key = '6LewK3MqAAAAAI0EwVyC_EDf1UbfmMoVnty2-A7d';
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    // Verify the reCAPTCHA response
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$response}&remoteip={$remoteip}");
    $responseData = json_decode($verifyResponse);

    if ($responseData->success) {
        // Proceed with username and password verification
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password using password_verify
            if (password_verify($password, $row['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password.";
                header("Location: login.php"); // Redirect back to login
                exit();
            }
        } else {
            $_SESSION['error'] = "User not found. Please sign up.";
            header("Location: sign-up.php"); // Redirect to sign-up
            exit();
        }

        // Close statement
        $stmt->close();
    } else {
        $_SESSION['error'] = "reCAPTCHA verification failed. Please try again.";
        header("Location: login.php"); // Redirect back to login
        exit();
    }
}

// Close the database connection
$conn->close();
?>
