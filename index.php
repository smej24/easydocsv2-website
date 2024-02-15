<!DOCTYPE html>
<html lang="en">
<head>
    <title> Log in | Easydocs </title>
    <link rel="icon" href="icon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/logincss.css">


    <link rel="stylesheet" type="text/css" href="">
</head>
<body background="">
    <div class="container">
        <div class="content">
            <div class="left-content">
                <img src="logo.png" alt="Logo">
                <h1 class="title">EASYDOCS</h1>
                <p class="paragraph">Request a document through online: Barangay Sampaloc 1 Dasmariñas,<br> Cavite</p>
            </div>
            <form action="" method="POST"> <!-- Replace action attribute with empty string -->
                <div class="form-back">
                    <ul>
                        <li><a href="">User</a></li>
                        <li><a href="">Admin</a></li>
                    </ul>
                    <div class="form">
                        <h3>LOG IN YOUR ACCOUNT</h3>
                        <p>Effortlessly request documents online</p>

                        <label for="username">Username</label>
                        <input type="text" placeholder="Username" id="username" name="username" >

                        <label for="password">Password</label>
                        <input type="password" placeholder="Password" id="password" name="password">
                        <div class="register">
                            <a href="registerhtml.php"> Create an account </a>
                        </div>
                    </div>
                    <input type="submit" class="button" value="L O G I N" name="submit"/> <!-- Name the submit button 'submit' -->
                </div>
            </form>
        </div>
    </div>
    <div class="copyright">
        <p><span class="copyright-span">Easydocs-BSIT-MWA | </span> © Copyright 2024 NU-Dasmariñas | Document Request System</p>
    </div>
</body>
</html>

<?php
// Include the database connection file
include("database_conn.php");

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to retrieve the hashed password for the given username
    $query = "SELECT user_id, password FROM users_db WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    // Check if any row is returned
    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['password'];

        // Verify the password
        if(password_verify($password, $hashedPassword)) {
            // Password verified, set user_id in session
            session_start();
            $_SESSION['user_id'] = $row['user_id'];

            // Redirect to a success page
            header("Location:homepage_folder/homepage.php");
            exit();
        } else {
            // Password doesn't match, display an error message
            echo "Invalid username or password.";
        }
    } else {
        // User not found, display an error message
        echo "Invalid username or password.";
    }
}
?>
