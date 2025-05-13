<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['username']);  
    $password = $_POST['password'];  

    if (empty($name) || empty($password)) {
        header("Location: login.html?error=Please%20fill%20in%20all%20fields");
        exit();
    }

    $sql = "SELECT password FROM user WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $name;
            header("Location: index.php"); // Redirect here after login
            exit();
        } else {
            header("Location: login.html?error=Incorrect%20password");
            exit();
        }
    } else {
        header("Location: login.html?error=Username%20not%20found");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>


