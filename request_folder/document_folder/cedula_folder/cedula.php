<?php
// Include the database connection file
include("database_conn.php");

// Retrieve user_id from session
session_start();
if(!isset($_SESSION['user_id'])) {
    // Handle user session error
    echo "User session not found.";
    exit();
}
$user_id = $_SESSION['user_id'];

// Retrieve user data from the users_db table
$query = "SELECT * FROM users_db WHERE user_id = '$user_id'"; // Assuming $user_id is set elsewhere
$result = mysqli_query($conn, $query);

// Check if a row is returned
if(mysqli_num_rows($result) == 1){
    // Fetch the user data
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['f_name'];
    $middle_name = $row['m_name'];
    $last_name = $row['l_name'];
    $gender = $row['gender'];
    $birth_date = $row['birth_date'];
    $street_address = $row['street_address'];
    $city = $row['city'];
} else {
    // Handle error if user data is not found
    echo "Error: User data not found.";
    exit();
}

    //get data from 



    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASYDOCS</title>
    <link rel="stylesheet"  href="cedulacss.css">
</head>

<body>

    <div class="sidenav">
        <img src="your-logo.png" alt="Logo">
        <h2>EASYDOCS</h2>
        <a href="#home">Home</a>
        <a href="#services">Request</a>
        <a href="#about">My Request</a>
        <a href="#contact">Account</a>
        <a href="#logout" class="logout">Log Out</a>
    </div>

    <div class="steps"></div>

    <div class="content">
        <p>Barangay Sampaloc 1 Dasmariñas, Cavite Document Request System</p>
        <div class="form-container">
            <div class="form-header">
                <h1>CEDULA</h1>
            </div>
            <form action="cedula.php" method="post" enctype="multipart/form-data">
                <div class="form-content">
                    <ul>
                        <li>
                            <input type="text" name="first_name" class="field-style field-split align-left" placeholder="First Name" value="<?php echo $first_name; ?>"/>
                            <input type="text" name="gender" class="field-style field-split align-right" placeholder="Gender" value="<?php echo $gender; ?>"/>
                        </li>
                        <li>
                            <input type="text" name="middle_name" class="field-style field-split align-left" placeholder="Middle Name" value="<?php echo $middle_name; ?>"/>
                            <input type="text" name="citizenship" class="field-style field-split align-right" placeholder="Citizenship"/>
                        </li>
                        <li>
                            <input type="text" name="last_name" class="field-style field-split align-left" placeholder="Last Name" value="<?php echo $last_name; ?>"/>
                            <input type="text" name="civil_status" class="field-style field-split align-right" placeholder="Civil Satus"/>
                        </li>
                        <li class="bday">
                            <label for="date">Date of Birth</label>
                            <input type="text" name="birth_date" class="field-style field-split align-right" placeholder="Birth Date" value="<?php echo $birth_date; ?>"/>
                        </li>
                    </ul>
                        <div class="form-content2">
                            <ul>
                        <li>
                            <input type="text" name="tin_number" class="field-style field-split align-left" placeholder="TIN No."/>
                            <input type="text" name="birth_place" class="field-style field-split align-right" placeholder="Place of Birth"/>
                        </li>
                        <li>
                            <input type="text" name="height" class="field-style field-split align-left" placeholder="Height"/>
                            <input type="text" name="weight" class="field-style field-split align-right" placeholder="Weight"/>
                        </li>
                        </ul>
                    </div>

                    <div class="total">
                        <label for="amount">Total Cost</label>
                        <input type="int" id="amount" name="amount" class="amount" value="100" style="width: 10%;" readonly />
                    </div>
                </div>
                <div class="btn">
                <a href="../../request.php" class="back">BACK</a>
                    <button type="submit" class="next">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>

<?php
// Include the database connection file
include("database_conn.php");

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if all form fields are filled
    if(empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['middle_name'])|| empty($_POST['gender']) || empty($_POST['citizenship']) || 
       empty($_POST['civil_status']) || empty($_POST['birth_date']) || empty($_POST['tin_number'])  || empty($_POST['birth_place']) || empty($_POST['height']) || 
       empty($_POST['weight'])) {
        // Handle empty fields error
        echo "All fields are required.";
        exit();
    }
    
    // Get form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $street_address = $_POST['gender'];
    $citizenship = $_POST['citizenship'];
    $civil_status = $_POST['civil_status'];
    $birth_date = $_POST['birth_date'];
    $tin_number = $_POST['tin_number'];
    $birth_place = $_POST['birth_place'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $amount = $_POST['amount'];



    // Generate unique doc_id
    $doc_id = generateDocID();

    // Get the current date and time in Philippines (Manila) timezone
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert data into request_db
    $insertRequestQuery = "INSERT INTO request_db (doc_id, user_id, doc_type, date_requested, status, date_pickup) 
                            VALUES ('$doc_id', '$user_id', 'Cedula', '$currentDateTime', 'Pending', NULL)";

    // Insert data into clearance_db
    $insertcedulaQuery = "INSERT INTO cedula_db (doc_id, user_db, citizenship, civil_status, PlaceofBirth, Tin_no, height, weight, cost, doc_type) 
    VALUES ('$doc_id', '$user_id', '$citizenship', '$civil_status','$birth_place', '$tin_number', '$height', '$weight', '$amount', 'cedula')";

    // Execute the insert queries
    if(mysqli_query($conn, $insertRequestQuery) && mysqli_query($conn, $insertcedulaQuery)) {
        // Redirect to a success page
        header("Location: ../../request.php");
        exit();
    } else {
        // Handle database insertion error
        echo "Error: " . mysqli_error($conn);
        exit();
    }
}

// Function to generate unique doc_id
function generateDocID() {
    global $conn; // Assuming $conn is your database connection variable

    // Loop until a unique document ID is generated
    do {
        // Generate random 8-digit number
        $randomNumber = mt_rand(10000000, 99999999);
        // Create unique doc_id
        $doc_id = $randomNumber;

        // Check if the generated document ID already exists in the database
        $query = "SELECT doc_id FROM cedula_db WHERE doc_id = '$doc_id'";
        $result = mysqli_query($conn, $query);

        // If there is an error with the query, handle it
        if (!$result) {
            // Handle database query error
            echo "Error: " . mysqli_error($conn);
            exit(); // Stop further execution
        }

        // If the document ID doesn't exist in the database, break the loop
        if(mysqli_num_rows($result) == 0) {
            return $doc_id;
        }
        // If the document ID already exists, generate a new one
    } while (true);
}

?>