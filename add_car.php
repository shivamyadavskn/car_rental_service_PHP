<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "agency") {
    header("Location: login.html");
    exit();
}

include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $agencyId = $_SESSION["user_id"];
    $vehicleModel = $_POST["vehicle_model"];
    $vehicleNumber = $_POST["vehicle_number"];
    $seatingCapacity = $_POST["seating_capacity"];
    $rentPerDay = $_POST["rent_per_day"];

    $query = "INSERT INTO cars (agency_id, vehicle_model, vehicle_number, seating_capacity, rent_per_day) VALUES ('$agencyId', '$vehicleModel', '$vehicleNumber', '$seatingCapacity', '$rentPerDay')";

    if ($connection->query($query) === TRUE) {
        header("Location: agency_dashboard.php");
        exit();
    } else {
        $error = "Error: " . $query . "<br>" . $connection->error;
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Car</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <center class="mt-5">
    <div class="container-fluid">

    <h2><strong>Add New Car</strong></h2><br>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Vehicle Model: <input type="text" class="form-control" name="vehicle_model" required></label><br>
        <label>Vehicle Number: <input type="text" class="form-control" name="vehicle_number" required></label><br>
        <label>Seating Capacity: <input type="number" class="form-control" name="seating_capacity" required></label><br>
        <label>Rent per Day: <input type="number" step="0.01" class="form-control" name="rent_per_day" required></label><br>
        <button type="submit" class="btn btn-success btn-lg mt-4">Add Car</button>
    </form>
    <p><a href="agency_dashboard.php">Back to Dashboard</a></p>

    </div>
    </center>
</body>
</html>
