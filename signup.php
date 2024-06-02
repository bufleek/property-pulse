<?php
session_start();
include 'config/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$next_url = isset($_GET['next_url']) ? $_GET['next_url'] : null;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (full_name, email, password, age) VALUES ('$fullname', '$email', '$hashed_password', $age)";

        if (mysqli_query($conn, $query)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);

            if (isset($_GET['next_url'])) {
                header("Location: " . $_GET['next_url']);
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Property Pulse</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="auth-body">
    <div class="auth-container">
        <div class="auth-logo">
            <img src="assets/images/logo.png" alt="Property Pulse" height="50" width="50">
        </div>
        <form action="signup.php<?php echo $next_url ? "?next_url=$next_url" : ""; ?>" method="POST">
            <h2>Sign Up</h2>
            <?php if (isset($error)) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="form-body">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="age">Age</label>
                <input type="number" id="age" name="age" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>

            <button style="margin-top: 30px;" type="submit">Sign Up</button>
        </form>
        <?php
        if ($next_url) {
            echo "<p style='margin-top: 30px;'>Already have an account? <a href='login.php?next_url=$next_url'>Login</a></p>";
        } else {
            echo "<p style='margin-top: 30px;'>Already have an account? <a href='login.php'>Login</a></p>";
        }
        ?>
    </div>
</body>

</html>