<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "agency") {
    header("Location: login.html");
    exit();
}

include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carId = $_POST["car_id"];
    $vehicleModel = $_POST["vehicle_model"];
    $vehicleNumber = $_POST["vehicle_number"];
    $seatingCapacity = $_POST["seating_capacity"];
    $rentPerDay = $_POST["rent_per_day"];

    $query = "UPDATE cars SET vehicle_model='$vehicleModel', vehicle_number='$vehicleNumber', seating_capacity='$seatingCapacity', rent_per_day='$rentPerDay' WHERE id='$carId'";

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
    if ($result->num_rows === 1) {
        $car = $result->fetch_assoc();
    } else {
        header("Location: agency_dashboard.php");
        exit();
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Car</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <center>
        <strong>
    <h2>Edit Car</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
        <label>Vehicle Model: <input type="text" class="form-control" name="vehicle_model" value="<?php echo $car["vehicle_model"]; ?>" required></label><br>
        <label>Vehicle Number: <input type="text"  class="form-control" name="vehicle_number" value="<?php echo $car["vehicle_number"]; ?>" required></label><br>
        <label>Seating Capacity: <input type="number" class="form-control"  name="seating_capacity" value="<?php echo $car["seating_capacity"]; ?>" required></label><br>
        <label>Rent per Day: <input type="number"  class="form-control"  step="0.01" name="rent_per_day" value="<?php echo $car["rent_per_day"]; ?>" required></label><br>
        <button type="submit" class="btn btn-lg btn-success mt-4">Update Car</button>
    </form>
    <p><a href="agency_dashboard.php">Back to Dashboard</a></p>
    </strong>
    </center>
</body>
</html>
