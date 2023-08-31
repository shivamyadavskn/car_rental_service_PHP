<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $passwords = $_POST["password"];
    $userType = $_POST["user_type"];

    include("db_connection.php");

    $query = "SELECT id, password, name FROM users WHERE email='$email' AND user_type='$userType'";
    $result = $connection->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($passwords, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_type"] = $userType;
            $_SESSION["user_name"]=$row["name"];

            if ($userType === "customer") {
                //header("Location: customer_dashboard.php");
                header("Location: available_cars.php");
            } elseif ($userType === "agency") {
                header("Location: agency_dashboard.php");
            } else {
                // Handle invalid user type
                header("Location: login.html");
            }
            exit();
        }
    }

    $connection->close();
    header("Location: login.html");
    exit();
} else {
    header("Location: login.html");
    exit();
}
