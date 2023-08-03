<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle candidate deletion
if (isset($_GET['delete'])) {
    $candidateID = $_GET['delete'];
    $deleteSql = "DELETE FROM Candidates WHERE CandidateID = $candidateID";
    if ($conn->query($deleteSql) === TRUE) {
        $deleteMessage = "Candidate deleted successfully!";
    } else {
        $deleteError = "Error deleting candidate: " . $conn->error;
    }
}

// Fetch candidates with position titles
$selectSql = "SELECT Candidates.CandidateID, VotingPositions.PositionTitle, Candidates.CandidateName, Candidates.CandidatePicture, Candidates.Statement 
              FROM Candidates
              INNER JOIN VotingPositions ON Candidates.PositionID = VotingPositions.PositionID";
$result = $conn->query($selectSql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Candidates</title>
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
        <h2>Manage Candidates</h2>
        <?php if (isset($deleteMessage)) { ?>
            <p class="success-message"><?php echo $deleteMessage; ?></p>
        <?php } ?>
        <?php if (isset($deleteError)) { ?>
            <p class="error-message"><?php echo $deleteError; ?></p>
        <?php } ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Candidate ID</th>
                    <th>Position Title</th>
                    <th>Candidate Name</th>
                    <th>Candidate Picture</th>
                    <th>Statement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['CandidateID']; ?></td>
                        <td><?php echo $row['PositionTitle']; ?></td>
                        <td><?php echo $row['CandidateName']; ?></td>
                        <td><img src="<?php echo $row['CandidatePicture']; ?>" alt="Candidate Picture" width="100"></td>
                        <td><?php echo $row['Statement']; ?></td>
                        <td>
                            <a href="edit_candidate.php?id=<?php echo $row['CandidateID']; ?>" class="btn btn-primary">Edit</a>
                            <a href="?delete=<?php echo $row['CandidateID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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
