<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

// Fetch the user_id based on the email from the Users table
$email = $_SESSION["email"];
$user_query = "SELECT id FROM Users WHERE Email = '$email'";
$result = mysqli_query($conn, $user_query);
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION["user_id"] = $user["id"];
} else {
    // If the user's email is not found in the Users table, redirect to index.php or display an error message.
    header("Location: index.php");
    exit;
}
// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];

    // Update user profile
    $sql = "UPDATE `users` SET `username` = '$new_username', `email` = '$new_email', `phone` = '$new_phone' WHERE `id` = $user_id";

    if (mysqli_query($conn, $sql)) {
        $message = "Profile updated successfully";
    } else {
        $error = "Error updating profile: " . mysqli_error($conn);
    }
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM `users` WHERE `id` = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
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
<?php include('navbar.php'); ?>
    <div class="container">
        <h1 class="mt-5">Update Profile</h1>
        <?php if (isset($message)) { echo '<p class="alert alert-success">' . $message . '</p>'; } ?>
        <?php if (isset($error)) { echo '<p class="alert alert-danger">' . $error . '</p>'; } ?>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
