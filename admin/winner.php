<?php
session_start();
include 'config.php';

// Check if the user is an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch the positions from the Votes table
$positions_query = "SELECT DISTINCT PositionID FROM Votes";
$positions_result = mysqli_query($conn, $positions_query);

if (!$positions_result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

$positions = mysqli_fetch_all($positions_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Winners</title>
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

        img {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container">
        <h2>Winners</h2>

        <?php if (count($positions) > 0) { ?>
            <ul>
                <?php foreach ($positions as $position) {
                    $position_id = $position['PositionID'];

                    // Fetch the winner for the position based on the candidate with the highest VoteCount
                    $winner_query = "SELECT CandidateID FROM Votes WHERE PositionID = '$position_id' GROUP BY CandidateID ORDER BY COUNT(*) DESC LIMIT 1";
                    $winner_result = mysqli_query($conn, $winner_query);

                    if (!$winner_result || mysqli_num_rows($winner_result) === 0) {
                        echo "Error: " . mysqli_error($conn);
                        continue;
                    }

                    $winner_data = mysqli_fetch_assoc($winner_result);
                    $winner_candidate_id = $winner_data['CandidateID'];

                    // Fetch the winner's details from the Candidates table
                    $candidate_query = "SELECT * FROM Candidates WHERE CandidateID = '$winner_candidate_id'";
                    $candidate_result = mysqli_query($conn, $candidate_query);

                    if (!$candidate_result || mysqli_num_rows($candidate_result) === 0) {
                        echo "Error: " . mysqli_error($conn);
                        continue;
                    }

                    $winner_candidate = mysqli_fetch_assoc($candidate_result);

                    // Fetch the total votes for the winner candidate
                    $total_votes_query = "SELECT COUNT(*) AS TotalVotes FROM Votes WHERE CandidateID = '$winner_candidate_id'";
                    $total_votes_result = mysqli_query($conn, $total_votes_query);

                    if (!$total_votes_result || mysqli_num_rows($total_votes_result) === 0) {
                        echo "Error: " . mysqli_error($conn);
                        continue;
                    }

                    $total_votes_data = mysqli_fetch_assoc($total_votes_result);
                    $total_votes = $total_votes_data['TotalVotes'];
                    ?>
                    <li>
                        <h3>Position: <?php echo $position_id; ?></h3>
                        <p>Winner: <?php echo $winner_candidate['CandidateName']; ?></p>
                        <p>Total Votes: <?php echo $total_votes; ?></p>
                        <?php if ($winner_candidate['CandidatePicture']) { ?>
                            <img src="<?php echo $winner_candidate['CandidatePicture']; ?>" alt="Winner Picture">
                        <?php } ?>
                        <p>Statement: <?php echo $winner_candidate['Statement']; ?></p>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No positions or winners found.</p>
        <?php } ?>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
