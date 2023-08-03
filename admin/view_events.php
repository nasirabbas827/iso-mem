<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle event deletion
if (isset($_GET['delete'])) {
    $eventId = $_GET['delete'];
    $deleteSql = "DELETE FROM Events WHERE EventID = $eventId";
    if ($conn->query($deleteSql) === TRUE) {
        $deleteMessage = "Event deleted successfully!";
    } else {
        $deleteError = "Error deleting event: " . $conn->error;
    }
}

// Fetch events
$selectSql = "SELECT * FROM Events";
$result = $conn->query($selectSql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Events</title>
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
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #f8f9fa;
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container">
        <h2>Manage Events</h2>
        <?php if (isset($deleteMessage)) { ?>
            <p class="success-message"><?php echo $deleteMessage; ?></p>
        <?php } ?>
        <?php if (isset($deleteError)) { ?>
            <p class="error-message"><?php echo $deleteError; ?></p>
        <?php } ?>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date and Time</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['EventID']; ?></td>
                        <td><?php echo $row['Title']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['DateTime']; ?></td>
                        <td><?php echo $row['Location']; ?></td>
                        <td>
                            <a class="btn btn-primary" href="edit_event.php?id=<?php echo $row['EventID']; ?>">Edit</a>
                            <a class="btn btn-danger" href="?delete=<?php echo $row['EventID']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
