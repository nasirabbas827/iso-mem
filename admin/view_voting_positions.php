<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle position deletion
if (isset($_GET['delete'])) {
    $positionID = $_GET['delete'];
    $deleteSql = "DELETE FROM VotingPositions WHERE PositionID = $positionID";
    if ($conn->query($deleteSql) === TRUE) {
        $deleteMessage = "Position deleted successfully!";
    } else {
        $deleteError = "Error deleting position: " . $conn->error;
    }
}

// Fetch positions with event names
$selectSql = "SELECT VotingPositions.PositionID, Events.Title AS EventName, VotingPositions.PositionTitle, VotingPositions.Description 
              FROM VotingPositions
              INNER JOIN Events ON VotingPositions.EventID = Events.EventID";
$result = $conn->query($selectSql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Voting Positions</title>
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
        <h2>Manage Voting Positions</h2>
        <?php if (isset($deleteMessage)) { ?>
            <p class="success-message"><?php echo $deleteMessage; ?></p>
        <?php } ?>
        <?php if (isset($deleteError)) { ?>
            <p class="error-message"><?php echo $deleteError; ?></p>
        <?php } ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Position ID</th>
                    <th>Event Name</th>
                    <th>Position Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['PositionID']; ?></td>
                        <td><?php echo $row['EventName']; ?></td>
                        <td><?php echo $row['PositionTitle']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td>
                            <a href="edit_positions.php?id=<?php echo $row['PositionID']; ?>" class="btn btn-primary">Edit</a>
                            <a href="?delete=<?php echo $row['PositionID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
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
