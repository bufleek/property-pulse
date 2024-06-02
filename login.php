<?php
session_start();

include 'config/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$next_url = isset($_GET['next_url']) ? $_GET['next_url'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];

            if (isset($_GET['next_url'])) {
                header("Location: " . $_GET['next_url']);
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Property Pulse</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="auth-body">
    <div class="auth-container">
        <div class="auth-logo">
            <img src="assets/images/logo.png" alt="Property Pulse" height="50" width="50">
        </div>
        <form action="login.php<?php echo $next_url ? "?next_url=$next_url" : ""; ?>" method="POST">
            <h2>Login</h2>
            <?php if (isset($error)) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="form-body">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button style="margin-top: 30px;" type="submit">Login</button>
        </form>
        <?php
        if ($next_url) {
            echo "<p style='margin-top: 30px;'>Don't have an account? <a href='signup.php?next_url=$next_url'>Sign Up</a></p>";
        } else {
            echo "<p style='margin-top: 30px;'>Don't have an account? <a href='signup.php'>Sign Up</a></p>";
        }
        ?>
    </div>
</body>

</html>