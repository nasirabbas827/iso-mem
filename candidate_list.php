<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

// Check if the position_id is provided in the URL
if (isset($_GET["position_id"]) && !empty($_GET["position_id"])) {
    $position_id = $_GET["position_id"];
} else {
    // If position_id is not provided, redirect to event_details.php or display an error message.
    header("Location: event_details.php");
    exit;
}

// Fetch voting position details
$position_query = "SELECT * FROM VotingPositions WHERE PositionID = '$position_id'";
$position_result = mysqli_query($conn, $position_query);

if (!$position_result || mysqli_num_rows($position_result) === 0) {
    echo "Invalid position ID or position not found.";
    exit;
}

$position = mysqli_fetch_assoc($position_result);

// Fetch candidates for the position
$candidates_query = "SELECT * FROM Candidates WHERE PositionID = '$position_id'";
$candidates_result = mysqli_query($conn, $candidates_query);

if (!$candidates_result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

$candidates = mysqli_fetch_all($candidates_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Candidate List</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
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
        <h2 class="text-center">Candidate List for <?php echo $position['PositionTitle']; ?></h2>
        <p class="text-center">Description: <?php echo $position['Description']; ?></p>

        <?php if (count($candidates) > 0) { ?>
            <div class="row">
                <?php foreach ($candidates as $candidate) { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Name: <?php echo $candidate['CandidateName']; ?></h5>
                                <?php if ($candidate['CandidatePicture']) { ?>
                                    <img src="./admin/<?php echo $candidate['CandidatePicture']; ?>" alt="Candidate Picture" class="img-fluid rounded-circle mb-3" style="max-width: 100px; max-height: 100px;">
                                <?php } ?>
                                <p class="card-text">Statement: <?php echo $candidate['Statement']; ?></p>
                                <form action="submit_vote.php" method="post">
                                    <input type="hidden" name="position_id" value="<?php echo $position_id; ?>">
                                    <input type="hidden" name="candidate_id" value="<?php echo $candidate['CandidateID']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Vote</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="text-center">No candidates for this position.</p>
        <?php } ?>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
