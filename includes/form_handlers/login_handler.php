<?php
if (isset($_POST['login_button'])) {
    // Sanitize and store email
    $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);
    $_SESSION['log_email'] = $email;

    // Get and hash the password
    $password = md5($_POST['log_password']);

    // Check if the user with the provided email and password exists
    $check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $check_login_query = mysqli_num_rows($check_database_query);

    if ($check_login_query == 1) {
        // User exists, fetch user data
        $row = mysqli_fetch_array($check_database_query);
        $username = $row['username'];

        // Check if the user's account is closed and reopen it if necessary
        $user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
        if (mysqli_num_rows($user_closed_query) == 1) {
            $reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
        }

        // Set the session username and redirect to index.php
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        // Invalid email or password
        array_push($error_array, "Email or password was incorrect<br>");
    }
}
?>