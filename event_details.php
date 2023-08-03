<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

// Check if the event_id is provided in the URL
if (isset($_GET["event_id"]) && !empty($_GET["event_id"])) {
    $event_id = $_GET["event_id"];
} else {
    // If event_id is not provided, redirect to user_attendance.php or display an error message.
    header("Location: user_attendance.php");
    exit;
}

// Fetch event details
$event_query = "SELECT * FROM Events WHERE EventID = '$event_id'";
$result = mysqli_query($conn, $event_query);
$event = mysqli_fetch_assoc($result);

// Fetch voting positions related to the event
$voting_query = "SELECT * FROM VotingPositions WHERE EventID = '$event_id'";
$result = mysqli_query($conn, $voting_query);
$voting_positions = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Details</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
 body{
            background-color: aquamarine;
        }
        .container {
            padding: 50px 0;
        }
        .card {
            margin-bottom: 20px;
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
        <h2 class="text-center">Event Details</h2>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Title: <?php echo $event['Title']; ?></h3>
                <p class="card-text">Date and Time: <?php echo $event['DateTime']; ?></p>
                <p class="card-text">Location: <?php echo $event['Location']; ?></p>
            </div>
        </div>

        <?php foreach ($voting_positions as $position) { ?>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title"><?php echo $position['PositionTitle']; ?></h3>
                    <p class="card-text">Description: <?php echo $position['Description']; ?></p>
                    <a href="candidate_list.php?event_id=<?php echo $event_id; ?>&position_id=<?php echo $position['PositionID']; ?>" class="card-link">See Candidates</a>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
