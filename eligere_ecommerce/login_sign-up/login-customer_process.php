<?php
include_once "../database.php";
session_start();

$cust_username = $_POST['cust_username'];
$cust_password = $_POST['cust_password'];
$errors = array();

$sql = "SELECT * FROM customers WHERE cust_username =?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cust_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($cust_password, $row['cust_password'])) {
        // if login is successful
        session_start();
        $_SESSION['cust_username'] = $cust_username;
        header("Location: ../index.php");
    } else {
        // if password is incorrect
        array_push($errors, "Password is incorrect");
    }
} else {
    // if username not found
    array_push($errors, "Username not found");
}

if (!empty($errors)) {
    echo "Error: ". implode("<br>", $errors);
}

$conn->close();
?>