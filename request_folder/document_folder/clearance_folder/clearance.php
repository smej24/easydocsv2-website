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
    $street_address = $row['street_address'];
    $city = $row['city'];
} else {
    // Handle error if user data is not found
    echo "Error: User data not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>EASYDOCS</title>
    
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
            <h1>BRGY. CLEARANCE</h1>
        </div>
        <div class="form-content">
            <form method="post" action="clearance.php">
            <ul>
        <li>
            <input type="text" name="first_name" class="field-style field-split align-left" placeholder="First Name" value="<?php echo $first_name; ?>"/>
        </li>
        <li>
            <input type="text" name="middle_name" class="field-style field-split align-right" placeholder="Middle Name" value="<?php echo $middle_name; ?>"/>
        </li>
        <li>
            <input type="text" name="last_name" class="field-style field-full align-none" placeholder="Last Name" value="<?php echo $last_name; ?>"/>
        </li>
        <li>
            <input type="text" name="street_address" class="field-style field-full align-none" placeholder="Street Address, Brgy/Subdivision" value="<?php echo $street_address; ?>"/>
        </li>
        <li>
            <input type="text" name="city" class="field-style field-full align-none" placeholder="City/Province" value="<?php echo $city; ?>"/>
        </li>
        <li>
            <textarea name="purpose" class="field-style" placeholder="Purpose"></textarea>
        </li>
        <li class="total">
                <label for="amount">Total Cost</label>
                <input type="int" id="amount" name="amount" class="amount" value="30" readonly/>
            </li>
    </ul>
                <div class="btn">
                     <a href="../../request.php" class="back">BACK</a>
                    <button type="submit" class="next">SUBMIT</button>
                </div>
            </form>
        </div>
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
    if(empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['street_address']) || empty($_POST['city']) || empty($_POST['purpose']) || empty($_POST['amount'])) {
        // Handle empty fields error
        echo "All fields are required.";
        exit();
    }

    // Retrieve user_id from session
    session_start();
    if(!isset($_SESSION['user_id'])) {
        // Handle user session error
        echo "User session not found.";
        exit();
    }
    $user_id = $_SESSION['user_id'];

    // Retrieve user data from the users_db table
    $query = "SELECT * FROM users_db WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);

    // Check if a row is returned
    if(mysqli_num_rows($result) == 1){
        // Fetch the user data
        $row = mysqli_fetch_assoc($result);
        $first_name = $row['f_name'];
        $middle_name = $row['m_name'];
        $last_name = $row['l_name'];
        $street_address = $row['street_address'];
        $city = $row['city'];
    } else {
        // Handle error if user data is not found
        echo "Error: User data not found.";
        exit();
    }

    // Get form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $street_address = $_POST['street_address'];
    $city = $_POST['city'];
    $purpose = $_POST['purpose'];
    $amount = $_POST['amount'];

    // Generate unique doc_id
    $doc_id = generateDocID();

    // Get the current date and time in Philippines (Manila) timezone
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert data into request_db
    $insertRequestQuery = "INSERT INTO request_db (doc_id, user_id, doc_type, date_requested, status, date_pickup) 
                            VALUES ('$doc_id', '$user_id', 'Clearance', '$currentDateTime', 'Pending', NULL)";

    // Insert data into clearance_db
    $insertClearanceQuery = "INSERT INTO clearance_db (doc_id, user_id, purpose, doc_type, cost) 
                            VALUES ('$doc_id', '$user_id', '$purpose', 'Clearance', '$amount')";

    // Execute the insert queries
    if(mysqli_query($conn, $insertRequestQuery) && mysqli_query($conn, $insertClearanceQuery)) {
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
        $query = "SELECT doc_id FROM clearance_db WHERE doc_id = '$doc_id'";
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



