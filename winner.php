<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

// Fetch the user ID based on their email from the Users table
$email = $_SESSION["email"];
$user_query = "SELECT id FROM Users WHERE Email = '$email'";
$result = mysqli_query($conn, $user_query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "User not found.";
    exit;
}

$user = mysqli_fetch_assoc($result);
$user_id = $user["id"];

// Fetch the positions the user voted for from the Votes table
$positions_query = "SELECT DISTINCT PositionID FROM Votes WHERE UserID = '$user_id'";
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
        .container {
            padding: 50px 0;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h2 class="text-center">Winners</h2>

        <?php if (count($positions) > 0) { ?>
            <div class="row">
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
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Position: <?php echo $position_id; ?></h4>
                                <p class="card-text">Winner: <?php echo $winner_candidate['CandidateName']; ?></p>
                                <p class="card-text">Total Votes: <?php echo $total_votes; ?></p>
                                <?php if ($winner_candidate['CandidatePicture']) { ?>
                                    <img src="./admin/<?php echo $winner_candidate['CandidatePicture']; ?>" alt="Winner Picture" class="img-fluid mb-3" style="max-width: 100px; max-height: 100px;">
                                <?php } ?>
                                <p class="card-text">Statement: <?php echo $winner_candidate['Statement']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="text-center">No positions you voted for or winners found.</p>
        <?php } ?>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
