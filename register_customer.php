<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("db_connection.php"); // Include the database connection file

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password
    $userType = "customer";

    $query = "INSERT INTO users (name, email, password, user_type) VALUES ('$name', '$email', '$password', '$userType')";

    if ($connection->query($query) === TRUE) {
        echo "Registration successful!";
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $connection->error;
    }

    $connection->close();
}
?>
