<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

// Fetch the user_id based on the email from the Users table
$email = $_SESSION["email"];
$user_query = "SELECT id FROM Users WHERE Email = '$email'";
$result = mysqli_query($conn, $user_query);
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $user_id = $user["id"];
} else {
    // If the user's email is not found in the Users table, redirect to index.php or display an error message.
    header("Location: index.php");
    exit;
}

// Fetch user's attendance events from the database
$attendance_query = "SELECT Events.*, Attendance.AttendanceID FROM Events 
                    JOIN Attendance ON Events.EventID = Attendance.EventID
                    WHERE Attendance.UserID = '$user_id'";
$result = mysqli_query($conn, $attendance_query);
$attendance_events = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Attendance Events</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
 body{
            background-color: aquamarine;
        }
h1, h2, h3, h4{
    margin-top: 30px;
    margin-bottom: 30px;
    text-align: center;
}
table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #f8f9fa;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h2 class="text-center">User Attendance Events</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Date and Time</th>
                <th>Location</th>
                <th>Details</th>
            </tr>
            <?php foreach ($attendance_events as $event) { ?>
                <tr>
                    <td><?php echo $event['Title']; ?></td>
                    <td><?php echo $event['DateTime']; ?></td>
                    <td><?php echo $event['Location']; ?></td>
                    <td>
                        <a href="event_details.php?event_id=<?php echo $event['EventID']; ?>">See Details</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
