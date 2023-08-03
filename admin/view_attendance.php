<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch attendance records
$selectSql = "SELECT Attendance.AttendanceID, Events.Title AS EventTitle, Users.username AS Username, Attendance.AttendanceDate
              FROM Attendance
              INNER JOIN Events ON Attendance.EventID = Events.EventID
              INNER JOIN Users ON Attendance.UserID = Users.id";
$result = $conn->query($selectSql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Attendance</title>
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
table , th , td , tr{
    border: 2px solid black;
}
    </style>
</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container">
        <h2>View Attendance</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Attendance ID</th>
                    <th>Event Title</th>
                    <th>Username</th>
                    <th>Attendance Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['AttendanceID']; ?></td>
                        <td><?php echo $row['EventTitle']; ?></td>
                        <td><?php echo $row['Username']; ?></td>
                        <td><?php echo $row['AttendanceDate']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
