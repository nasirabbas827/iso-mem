<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidateID = $_POST["candidate_id"];
    $candidateName = $_POST["candidate_name"];
    $statement = mysqli_real_escape_string($conn, $_POST["statement"]);

    $updateSql = "UPDATE Candidates SET CandidateName = '$candidateName', Statement = '$statement'
                  WHERE CandidateID = $candidateID";

    if ($conn->query($updateSql) === TRUE) {
        $updateMessage = "Candidate updated successfully!";
    } else {
        $updateError = "Error updating candidate: " . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $candidateID = $_GET['id'];
    $selectSql = "SELECT * FROM Candidates WHERE CandidateID = $candidateID";
    $result = $conn->query($selectSql);
    $candidate = $result->fetch_assoc();
} else {
    header("Location: manage_candidates.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Candidate</title>
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
        <h2>Edit Candidate</h2>
        <?php if (isset($updateMessage)) { ?>
            <p class="success-message"><?php echo $updateMessage; ?></p>
        <?php } ?>
        <?php if (isset($updateError)) { ?>
            <p class="error-message"><?php echo $updateError; ?></p>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="candidate_id" value="<?php echo $candidate['CandidateID']; ?>">

            <div class="form-group">
                <label for="candidate_name">Candidate Name:</label>
                <input type="text" class="form-control" id="candidate_name" name="candidate_name" value="<?php echo $candidate['CandidateName']; ?>" required>
            </div>

            <div class="form-group">
                <label for="statement">Statement:</label>
                <textarea class="form-control" id="statement" name="statement" required><?php echo $candidate['Statement']; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Candidate</button>
        </form>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
