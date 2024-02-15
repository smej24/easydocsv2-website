<!DOCTYPE html>
<html lang="en">
<head>
    <title> Register | Easydocs </title>
    <link rel="icon" href="icon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/registercss.css">
    <script>
        <?php
        session_start();
        if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {
            echo "window.onload = function() { alert('User registered successfully!'); window.location.href = 'loginhtml.php'; }";
            unset($_SESSION['registration_success']);
        }
        ?>
    </script>
</head>
<body background="">
    <div class="container">
        <div class="content">
            <div class="left-content">
                <img src="logo.png" alt="Logo">
                <h1 class="title">EASYDOCS</h1>
                <p class="paragraph">Request a document through online: Barangay Sampaloc 1 Dasmariñas,<br> Cavite</p>
            </div>
            <form action="" method="POST">
                <h3>REGISTER</h3>
                <div class="form-content">
                    <ul class="labels">
                        <li>
                            <label for="first_name">First Name</label>
                            <label for="middle_name">Middle Name</label>
                            <label for="last_name">Last Name</label>
                        </li>
                    </ul>
                    <ul>
                        <li class="group-input3">
                            <input type="text" name="first_name" class="field-style field-split align-left" placeholder="First Name" />
                            <input type="text" name="middle_name" class="field-style field-split align-center" placeholder="Middle Name" />
                            <input type="text" name="last_name" class="field-style field-split align-right" placeholder="Last Name" />
                        </li>
                    </ul>
                    <ul class="labels2">
                        <li>
                            <label for="username">Username</label>
                            <label for="gender">Gender</label>
                            <label for="date_of_birth">Date of Birth</label>
                        </li>
                    </ul>
                    <ul>    
                        <li class="group-input3">
                            <input type="text" name="username" class="field-style field-split align-left" placeholder="Username" />
                            <div class="radio">
                            <input type="radio" id="male" name="gender" class="field-style field-split align-center" value="male">
                            <label for="male" class="field-style field-split align-left">Male</label>
                            <input type="radio" id="female" name="gender" class="field-style field-split align-center" value="female">
                            <label for="female" class="field-style field-split align-left">Female</label>
                            </div>
                            <div class="date-input">
                                <input type="date" id="date" name="birth_date" class="field-style field-split align-right">
                            </div>
                        </li>
                    </ul>
                    <ul class="labels3">
                        <li>
                            <label for="email">Email</label>
                        </li>
                    </ul>
                    <ul>              
                        <li class="group-input1">
                            <input type="text" name="email" class="field-style field-full align-none" placeholder="Email" />
                        </li>
                    </ul>
                    <ul class="labels4">
                            <li>
                                <label for="street_address">Street Address</label>
                                <label for="city">City/Province</label>
                            </li>
                    </ul>
                    <ul>
                        <li>
                            <input type="varchar" name="street_address" class="field-style field-split align-left" placeholder="Street Address, Brgy/Subdivision" />
                            <input type="varchar" name="city" class="field-style field-split align-right" placeholder="City/Province" />
                        </li>
                    </ul>
                    <ul class="labels5">
                            <li>
                                <label for="password">Password</label>
                                <label for="confirm_password">Confirm Password</label>
                            </li>
                    </ul>
                    <ul>
                        <li class="group-input2">
                            <input type="password" name="password" class="field-style field-split align-left" placeholder="Password" />
                            <input type="password" name="confirm_password" class="field-style field-split align-right" placeholder="Confirm Password" />
                        </li>                     
                    </ul>
                </div>
                <input type="submit" class="button" value="REGISTER" name="submit"/>
            </form>
        </div>
    </div>
    <?php
    // Include the database connection file
    include("database_conn.php");

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];
        $last_name = $_POST["last_name"];
        $username = $_POST["username"];
        $gender = $_POST["gender"];
        $birth_date = $_POST["birth_date"];
        $email = $_POST["email"];
        $street_address = $_POST["street_address"];
        $city = $_POST["city"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Validate password confirmation
        if ($password !== $confirm_password) {
            echo "<script>alert('Error: Passwords do not match.');</script>";
            // Log detailed error information to a file or database table
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and bind the INSERT statement
            $stmt = $conn->prepare("INSERT INTO users_db (f_name, m_name, l_name, street_address, city, email, username, gender, birth_date, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die("Error in preparing statement: " . $conn->error);
            }

            // Set parameters and execute the statement
            $stmt->bind_param("ssssssssss", $first_name, $middle_name, $last_name, $street_address, $city, $email, $username, $gender, $birth_date, $hashed_password);
            if ($stmt->execute()) {
                // Set session variable to indicate successful registration
                session_start();
                $_SESSION['registration_success'] = true;
                echo "<script>window.location.href = 'index.php';</script>"; // Redirect to login page after successful registration
            } else {
                echo "<script>alert('Error: Registration failed. Please try again later.');</script>";
                // Log detailed error information to a file or database table
            }

            // Close the statement
            $stmt->close();
        }
    }
    ?>
</body>
</html>
