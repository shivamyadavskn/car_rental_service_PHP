<?php
session_start();

include("db_connection.php");

$query = "SELECT * FROM cars";
$result = $connection->query($query);

$cars = [];
while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}

$connection->close();

$loggedIn = isset($_SESSION["user_id"]) && $_SESSION["user_type"] === "customer";

$bookingError = "";

if ($loggedIn && $_SERVER["REQUEST_METHOD"] === "POST") {
    $carId = $_POST["car_id"];
    $numDays = $_POST["num_days"];
    $startDates = $_POST["start_date"];
    $customerId = $_SESSION["user_id"];
    $bookingDate = date("Y-m-d");
    include("db_connection.php");

    $bookingQuery = "INSERT INTO bookings (car_id, customer_id, start_date,booking_date, rental_days) VALUES ('$carId', '$customerId', '$startDates','$bookingDate', '$numDays')";

    if ($connection->query($bookingQuery) === TRUE) {
        // Successful booking, redirect to confirmation page
        header("Location: booking_confirmation.php");
        exit();
    } else {
        $bookingError = "Error: " . $connection->error;
    }

    $connection->close();
}
// Logout functionality
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: login.html");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Available Cars to Rent</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<center>
    <h1 id="bgc">Car Rental Service</h1>
    <p></p>
    <div class="container-fluid mt-5">
        <strong>
                <?php if ($loggedIn) : ?>
                    <p>Welcome, <?php echo $_SESSION["user_type"]; ?>! <a href="?logout=true">Logout</a></p>
                <?php endif; ?>
    </div>
    <h3>Available Cars to Rent</h3>
    <table border="1">
        <tr>
            <th>Vehicle Model</th>
            <th>Vehicle Number</th>
            <th>Seating Capacity</th>
            <th>Rent per Day</th>
            <?php if ($loggedIn) : ?>
                <th>Number of Days</th>
                <th>Start Date</th>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
        <?php foreach ($cars as $car) : ?>
            <tr>
                <td><?php echo $car["vehicle_model"]; ?></td>
                <td><?php echo $car["vehicle_number"]; ?></td>
                <td><?php echo $car["seating_capacity"]; ?></td>
                <td><?php echo $car["rent_per_day"]; ?></td>
                <?php if ($loggedIn) : ?>

                    <form method="POST">
                        <input type="hidden" name="car_id" value="<?php echo $car["id"]; ?>">
                        <td> <select name="num_days" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <!-- Add options for number of days -->
                            </select></td>
                        <td><input type="date" name="start_date" required></td>
                        <td><button type="submit">Rent Car</button></td>
                    </form>

                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (!$loggedIn) : ?>
        <p class="mt-4">Please <a href="login.html" class="btn btn-outline-success btn-lg">Log in</a> to book a car.</p>
    <?php endif; ?>
    <?php if ($bookingError !== "") : ?>
        <p style="color: red;"><?php echo $bookingError; ?></p>
    <?php endif; ?>
    </strong>
    </div>
    <div>
    </center>
</body>

</html>