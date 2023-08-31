<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "agency") {
    header("Location: login.html");
    exit();
}

include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carId = $_POST["car_id"];

    $query = "DELETE FROM cars WHERE id='$carId'";

    if ($connection->query($query) === TRUE) {
        header("Location: agency_dashboard.php");
        exit();
    } else {
        $error = "Error: " . $query . "<br>" . $connection->error;
    }
} else {
    $carId = $_GET["id"];
    $query = "SELECT * FROM cars WHERE id='$carId'";
    $result = $connection->query($query);
    if ($result->num_rows !== 1) {
        header("Location: agency_dashboard.php");
        exit();
    }
    $car = $result->fetch_assoc();
}

$connection->close();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Delete Car</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <center class="mt-4">
        <strong>
            <h2>Delete Car</h2>
            <?php if (isset($error)) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <p>Are you sure you want to delete this car?</p>
            <p>Vehicle Model: <?php echo $car["vehicle_model"]; ?></p>
            <p>Vehicle Number: <?php echo $car["vehicle_number"]; ?></p>
            <form method="POST">
                <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
                <button type="submit" class="btn btn-lg btn-danger">Delete Car</button>
            </form>
            <p><a href="agency_dashboard.php">Back to Dashboard</a></p>
        </strong>
    </center>
</body>

</html>