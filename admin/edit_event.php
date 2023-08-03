<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventId = $_POST["event_id"];
    $title = $_POST["title"];
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $datetime = $_POST["datetime"];
    $location = $_POST["location"];
    
    $updateSql = "UPDATE Events SET Title = '$title', Description = '$description', 
                  DateTime = '$datetime', Location = '$location' WHERE EventID = $eventId";
    
    if ($conn->query($updateSql) === TRUE) {
        $updateMessage = "Event updated successfully!";
    } else {
        $updateError = "Error updating event: " . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];
    $selectSql = "SELECT * FROM Events WHERE EventID = $eventId";
    $result = $conn->query($selectSql);
    $event = $result->fetch_assoc();
} else {
    header("Location: view_events.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
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
    <?php include('admin_navbar.php'); ?>

    <div class="container">
        <h2>Edit Event</h2>
        <?php if (isset($updateMessage)) { ?>
            <p class="text-success"><?php echo $updateMessage; ?></p>
        <?php } ?>
        <?php if (isset($updateError)) { ?>
            <p class="text-danger"><?php echo $updateError; ?></p>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="event_id" value="<?php echo $event['EventID']; ?>">
            
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $event['Title']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $event['Description']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="datetime">Date and Time:</label>
                <input type="datetime-local" class="form-control" id="datetime" name="datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($event['DateTime'])); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="location">Location:</label>
                <select class="form-control" id="location" name="location">
                    <option value="Online" <?php if ($event['Location'] === 'Online') echo 'selected'; ?>>Online</option>
                    <option value="In-person" <?php if ($event['Location'] === 'In-person') echo 'selected'; ?>>In-person</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
