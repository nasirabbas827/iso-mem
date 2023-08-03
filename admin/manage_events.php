<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $datetime = $_POST["datetime"];
    $location = $_POST["location"];
    
    if(isset($_SESSION["userid"])) {
        $organizer = $_SESSION["userid"];
        
        $sql = "INSERT INTO Events (Title, Description, DateTime, Location, Organizer) 
                VALUES ('$title', '$description', '$datetime', '$location', $organizer)";

        if ($conn->query($sql) === TRUE) {
            $message = "Event created successfully!";
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $error = "User ID not defined in session.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <?php include('admin_navbar.php'); ?>
    <div class="container">
        <h2>Create Event</h2>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="datetime">Date and Time:</label>
                <input type="datetime-local" class="form-control" id="datetime" name="datetime" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <select class="form-control" id="location" name="location">
                    <option value="Online">Online</option>
                    <option value="In-person">In-person</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Event</button>
            <a href="view_events.php">View All Events</a>
        </form>
    </div>
</body>
</html>
