<?php
// view_events.php

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
    $_SESSION["user_id"] = $user["id"];
} else {
    // If the user's email is not found in the Users table, redirect to index.php or display an error message.
    header("Location: index.php");
    exit;
}

// Fetch all events and their organizers from the database
$event_query = "SELECT Events.*, Admins.username AS OrganizerName FROM Events
                INNER JOIN Admins ON Events.Organizer = Admins.id";
$result = mysqli_query($conn, $event_query);
$events = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle attendance submission if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_id"])) {
    $event_id = $_POST["event_id"];
    $user_id = $_SESSION["user_id"];

    // Check if the user has already marked attendance for this event
    $attendance_check_query = "SELECT * FROM Attendance WHERE EventID = '$event_id' AND UserID = '$user_id'";
    $result = mysqli_query($conn, $attendance_check_query);
    if (mysqli_num_rows($result) > 0) {
        $error = "You have already marked attendance for this event.";
    } else {
        // Insert the attendance record into the database
        $attendance_query = "INSERT INTO Attendance (EventID, UserID) VALUES ('$event_id', '$user_id')";
        if ($conn->query($attendance_query) === TRUE) {
            $message = "Attendance marked successfully!";
        } else {
            $error = "Error: " . $attendance_query . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Events</title>
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
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h2 class="text-center">Latest and Upcoming Events</h2>
        <?php if (isset($message)) { ?>
            <p class="text-center"><?php echo $message; ?></p>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <p class="text-center text-danger"><?php echo $error; ?></p>
        <?php } ?>
        <div class="row">
            <?php foreach ($events as $event) { ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $event['Title']; ?></h5>
                            <p class="card-text"><?php echo $event['Description']; ?></p>
                            <p class="card-text">Date and Time: <?php echo $event['DateTime']; ?></p>
                            <p class="card-text">Location: <?php echo $event['Location']; ?></p>
                            <p class="card-text">Organizer: <?php echo $event['OrganizerName']; ?></p>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input type="hidden" name="event_id" value="<?php echo $event['EventID']; ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Mark Attendance</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
