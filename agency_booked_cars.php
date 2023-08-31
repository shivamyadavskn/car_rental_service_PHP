<?php
session_start();

include("db_connection.php");

// Check if the user is logged in as an agency
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "agency") {
    header("Location: login.html");
    exit();
}

$agencyId = $_SESSION["user_id"];

// Fetch agency's username and email from the users table
$userQuery = "SELECT name, email FROM users WHERE id='$agencyId'";
$userResult = $connection->query($userQuery);
$agencyUser = $userResult->fetch_assoc();

// Fetch bookings associated with the agency's cars
$query = "SELECT b.id AS booking_id, b.car_id, b.customer_id, b.start_date, b.rental_days, u.name AS customer_name
          FROM bookings b
          JOIN users u ON b.customer_id = u.id
          WHERE b.car_id IN (SELECT id FROM cars WHERE agency_id='$agencyId')";
$result = $connection->query($query);

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agency Booked Cars</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <center>
    <h2><strong>Booked Cars</strong></h2>
    <p><strong>Agency: <?php echo $agencyUser["name"]; ?> (<?php echo $agencyUser["email"]; ?>)</strong></p>
    <table border="1">
        <tr>
            <th>Booking ID</th>
            <th>Car ID</th>
            <th>Customer Name</th>
            <th>Start Date</th>
            <th>Number of Days</th>
            <!-- Add more columns as needed -->
        </tr>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo $booking["booking_id"]; ?></td>
                <td><?php echo $booking["car_id"]; ?></td>
                <td><?php echo $booking["customer_name"]; ?></td>
                <td><?php echo $booking["start_date"]; ?></td>
                <td><?php echo $booking["rental_days"]; ?></td>
                <!-- Add more columns as needed -->
            </tr>
        <?php endforeach; ?>
    </table>
        </center>
</body>
</html>
