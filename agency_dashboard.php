<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "agency") {
    header("Location: login.html");
    exit();
}

include("db_connection.php"); // Include the database connection

$agencyId = $_SESSION["user_id"];

$query = "SELECT * FROM cars WHERE agency_id='$agencyId'";
$result = $connection->query($query);



$cars = [];
while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agency Dashboard</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <center class="mt-4">
<h2 class="mb-4"><strong>Welcome *<?php echo $_SESSION["user_name"]; ?>* Car Rental Agency!</strong></h2><a href="available_cars.php" class="btn btn-success">Car Availability</a>
    <h3 class="m-4"><strong>Your Cars</strong></h3>
    <table border="1">
        <tr>
            <th>Vehicle Model</th>
            <th>Vehicle Number</th>
            <th>Seating Capacity</th>
            <th>Rent per Day</th>
            <th>Action</th>
        </tr>
        <?php foreach ($cars as $car): ?>
            <tr>
                <td><?php echo $car["vehicle_model"]; ?></td>
                <td><?php echo $car["vehicle_number"]; ?></td>
                <td><?php echo $car["seating_capacity"]; ?></td>
                <td><?php echo $car["rent_per_day"]; ?></td>
                <td>
                    <a href="edit_car.php?id=<?php echo $car["id"]; ?>" class="btn btn-outline-success">Edit</a>
                    <a href="delete_car.php?id=<?php echo $car["id"]; ?>" class="btn btn-danger">Delete</a>
                    <a href="agency_booked_cars.php?car_id=<?php echo $car["id"]; ?>" class="btn btn-info">View Booked Cars</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p class="mt-4"><a href="add_car.php" class="btn btn-success btn-lg">Add New Car</a> <a href="logout.php" class="btn btn-outline-danger btn-lg">Logout</a></p>
    </center>
</body>
</html>
