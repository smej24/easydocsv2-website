<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session at the beginning

// Include database connection
include("database_conn.php");

$user_id = $_SESSION['user_id']; // Retrieve the user_id from the session

// Check if doc_id is set in the URL
if(isset($_GET['doc_id'])) {
    // Get the doc_id from the URL
    $doc_id = $_GET['doc_id'];

    // Check if doc_id is from clearance_db
    $query_clearance = "SELECT doc_id FROM clearance_db WHERE doc_id = ?";
    $stmt_clearance = $conn->prepare($query_clearance);
    $stmt_clearance->bind_param("s", $doc_id);
    $stmt_clearance->execute();
    $result_clearance = $stmt_clearance->get_result();

    // Check if there are rows returned from clearance_db
    if ($result_clearance->num_rows > 0) {
        // Prepare and execute SQL query to fetch purpose from clearance_db
        $query_purpose_clearance = "SELECT purpose FROM clearance_db WHERE doc_id = ?";
        $stmt_purpose_clearance = $conn->prepare($query_purpose_clearance);
        $stmt_purpose_clearance->bind_param("s", $doc_id);
        $stmt_purpose_clearance->execute();
        $result_purpose_clearance = $stmt_purpose_clearance->get_result();

        // Check if there are rows returned
        if ($result_purpose_clearance->num_rows > 0) {
            // Display "Brgy. Clearance" as an h1 heading
            echo "<h1>Brgy. Clearance</h1>";

            // Fetch user information
            $user_query = "SELECT * FROM users_db WHERE user_id = '$user_id'";
            $user_result = mysqli_query($conn, $user_query);

            if ($user_result === false) {
                // Handle query error
                echo "Error: " . mysqli_error($conn);
            } else {
                if ($user_result->num_rows > 0) {
                    $user_row = $user_result->fetch_assoc();
                    // Output user information with left alignment
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>First Name: " . $user_row['f_name'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Middle Name: " . $user_row['m_name'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Last Name: " . $user_row['l_name'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Street Address, Brgy/Subdivision: " . $user_row['street_address'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>City/Province: " . $user_row['city'] . "</span>";
                    echo "</div>";
                } else {
                    echo "0 results for user query";
                }
            }

            // Fetch the data and output purpose with left alignment
            while ($row = $result_purpose_clearance->fetch_assoc()) {
                echo "<div style='margin-bottom: 10px; text-align: left;'>";
                echo "<span class='field-style'>Purpose: " . $row['purpose'] . "</span>";
                echo "</div>";
            }
        } else {
            // No purpose found
            echo "No purpose found for ID: " . $doc_id;
        }

        // Close statement
        $stmt_purpose_clearance->close();
    } else {
        // Check if doc_id is from cedula_db
        $query_cedula = "SELECT doc_id FROM cedula_db WHERE doc_id = ?";
        $stmt_cedula = $conn->prepare($query_cedula);
        $stmt_cedula->bind_param("s", $doc_id);
        $stmt_cedula->execute();
        $result_cedula = $stmt_cedula->get_result();

        // Check if there are rows returned from cedula_db
        if ($result_cedula->num_rows > 0) {
            // Prepare and execute SQL query to fetch data from cedula_db
            $query_cedula_data = "SELECT * FROM cedula_db WHERE doc_id = ?";
            $stmt_cedula_data = $conn->prepare($query_cedula_data);
            $stmt_cedula_data->bind_param("s", $doc_id);
            $stmt_cedula_data->execute();
            $result_cedula_data = $stmt_cedula_data->get_result();

            // Check if there are rows returned
            if ($result_cedula_data->num_rows > 0) {

                // Display "Cedula" as an h1 heading
            echo "<h1>Cedula</h1>";

            // Fetch user information
            $user_query = "SELECT * FROM users_db WHERE user_id = '$user_id'";
            $user_result = mysqli_query($conn, $user_query);

            if ($user_result === false) {
                // Handle query error
                echo "Error: " . mysqli_error($conn);
            } else {
                if ($user_result->num_rows > 0) {
                    $user_row = $user_result->fetch_assoc();
                    // Output user information with left alignment
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>First Name: " . $user_row['f_name'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Middle Name: " . $user_row['m_name'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Last Name: " . $user_row['l_name'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Gender: " . $user_row['gender'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Date of Birth: " . $user_row['birth_date'] . "</span>";
                    echo "</div>";
                } else {
                    echo "0 results for user query";
                }
            }
                // Fetch the data and output with left alignment
                while ($row = $result_cedula_data->fetch_assoc()) {
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Citizenship: " . $row['citizenship'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Citizenship: " . $row['civil_status'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Place of Birth: " . $row['PlaceofBirth'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Tin No.: " . $row['tin_no'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Height: " . $row['height'] . "</span>";
                    echo "</div>";
                    echo "<div style='margin-bottom: 10px; text-align: left;'>";
                    echo "<span class='field-style'>Weight: " . $row['weight'] . "</span>";
                    echo "</div>";
                    
                }
            } else {
                // No data found
                echo "No data found for ID: " . $doc_id;
            }

            // Close statement
            $stmt_cedula_data->close();
        } else {
            // No matching doc_id found in either clearance_db or cedula_db
            echo "No matching document found for ID: " . $doc_id;
        }

        // Close statement
        $stmt_cedula->close();
    }

    // Close statement
    $stmt_clearance->close();
}

// Close the database connection
$conn->close();
?>

<!-- Close button -->
<button onclick="closePopup()">Close</button>

<script>
    // Function to close the popup window
    function closePopup() {
        // Set the display of the popup to none
        document.getElementById("popup").style.display = "none";
    }
</script>
