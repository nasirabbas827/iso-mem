<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

// Check if the position_id and candidate_id are provided via POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["position_id"]) && isset($_POST["candidate_id"])) {
    $position_id = $_POST["position_id"];
    $candidate_id = $_POST["candidate_id"];

    // Get the user ID based on their email from the Users table
    $email = $_SESSION["email"];
    $user_query = "SELECT id FROM Users WHERE Email = '$email'";
    $result = mysqli_query($conn, $user_query);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "User not found.";
        exit;
    }

    $user = mysqli_fetch_assoc($result);
    $user_id = $user["id"];

    // Check if the user has already voted for this position
    $vote_query = "SELECT * FROM Votes WHERE UserID = '$user_id' AND PositionID = '$position_id'";
    $vote_result = mysqli_query($conn, $vote_query);

    if (!$vote_result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    if (mysqli_num_rows($vote_result) > 0) {
        echo "You have already voted for this position.";
        exit;
    }

    // Insert the vote into the Votes table
    $insert_vote_query = "INSERT INTO Votes (UserID, PositionID, CandidateID) VALUES ('$user_id', '$position_id', '$candidate_id')";
    if (mysqli_query($conn, $insert_vote_query)) {
        echo "Vote submitted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
