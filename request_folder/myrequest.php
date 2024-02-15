<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Start the session at the beginning

include("database_conn.php");

$user_id = $_SESSION['user_id']; // Retrieve the user_id from the session

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASYDOCS</title>
    <link rel="stylesheet" type="text/css" href="css/myrequeststyle.css">
</head>
<body>

<div class="sidenav">
    <img src="your-logo.png" alt="Logo">
    <h2>EASYDOCS</h2>
    <a href="../homepage_folder/homepage.php">Home</a>
    <a href="request.php">Request</a>
    <a href="#about">My Request</a>
    <a href="#contact">Account</a>
    <a href="../index.php" class="logout">Log Out</a>
</div>

<div class="steps"></div>

<div class="content">
    <p>Barangay Sampaloc 1 Dasmariñas, Cavite Document Request System</p>
    <h2>REQUEST HISTORY</h2>
    <div class="req">
        <p>You can set-pick up schedule and view all the document request transactions you made on this section.</p>
    </div>

    <!-- All Request here-->
     <div class="card">
        <button class="accordion">All Requests</button>
        <div class="panel">
            <table>
                <tr>
                    <th>Request No.</th>
                    <th>Request Document</th>
                    <th>Payment Method</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    <th>Pick-up Schedule</th>
                </tr>
                <tr>
                    <td><span class='request-number' onclick="openPopup(1)">1</span></td>
                    <td>Document 1</td>
                    <td>Credit Card</td>
                    <td>2023-07-01</td>
                    <td><!-- status here --></td>
                    <td>
                        <div>
                            <input type="datetime-local" id="pick-up-schedule" value="2023-07-10T10:00" disabled>
                            <button class="set-date-button" onclick="openDatePicker()">Set Date</button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    <!-- Cedula here-->
        <button class="accordion">Cedula</button>
        <div class="panel">
        <table>
            <tr>
                <th>Request No.</th>
                <th>Request Document</th>
                <th>Payment Method</th>
                <th>Date Requested</th>
                <th>Status</th>
                <th>Pick-up Schedule</th>
            </tr>
            <tr>
            <?php

$query = "SELECT * FROM request_db WHERE user_id = ? AND doc_type = 'cedula'";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><span class='request-number' onclick='openPopup(" . $row['doc_id'] . ")'>" . $row['doc_id'] . "</span></td>";
            echo "<td>" . $row["doc_type"] . "</td>";
            echo "<td>Payment Method</td>";
            echo "<td>" . $row["date_requested"] . "</td>";
            echo "<td>" . $row["status"] . "</td>";
            echo "<td>";
            echo '<div>';
            echo '<input type="datetime-local" id="pick-up-schedule" value="';
            echo date('Y-m-d\TH:i', $row["date_pickup"] ? strtotime($row["date_pickup"]) : 0); // Handling null date
            echo '" disabled>';
            echo '<button class="set-date-button" onclick="openDatePicker()">Set Date</button>';
            echo '</div>';
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "0 results";   
    }

    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

?>
            </tr>
        </table>
        </div>

    <!-- Clearance here-->
    <button class="accordion">Brgy. Clearance</button>
    <div class="panel">
        <table>
            <tr>
                <th>Request No.</th>
                <th>Request Document</th>
                <th>Payment Method</th>
                <th>Date Requested</th>
                <th>Status</th>
                <th>Pick-up Schedule</th>
            </tr>
            <?php

            $query = "SELECT * FROM request_db WHERE user_id = ? AND doc_type = 'clearance'";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><span class='request-number' onclick='openPopup(" . $row['doc_id'] . ")'>" . $row['doc_id'] . "</span></td>";
                        echo "<td>" . $row["doc_type"] . "</td>";
                        echo "<td>Payment Method</td>";
                        echo "<td>" . $row["date_requested"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td>";
                        echo '<div>';
                        echo '<input type="datetime-local" id="pick-up-schedule" value="';
                        echo date('Y-m-d\TH:i', $row["date_pickup"] ? strtotime($row["date_pickup"]) : 0); // Handling null date
                        echo '" disabled>';
                        echo '<button class="set-date-button" onclick="openDatePicker()">Set Date</button>';
                        echo '</div>';
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "0 results";   
                }

                $stmt->close();
            } else {
                echo "Error: " . $conn->error;
            }

            ?>
        </table>
    </div>

    <!-- Brgy.ID here-->
    <button class="accordion">Brgy. ID</button> 
    <div class="panel">
        <table>
            <tr>
                <th>Request No.</th>
                <th>Request Document</th>
                <th>Payment Method</th>
                <th>Date Requested</th>
                <th>Status</th>
                <th>Pick-up Schedule</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Document 1</td>
                <td>Credit Card</td>
                <td>2023-07-01</td>
                <td>
                //status here
                </td>
                <td>
                    <div>
                        <input type="datetime-local" id="pick-up-schedule" value="2023-07-10T10:00" disabled>
                        <button class="set-date-button" onclick="openDatePicker()">Set Date</button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- Popup Content -->
<div class="popup" id="popup">
    <div class="popup-content" id="popup-content">
       
    
        </div>
    </div>
</div>


<script>
    // Add this function for debugging purposes
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }

    function openPopup(docId) {
    // Set the display of the popup to block
    document.getElementById("popup").style.display = "block";
    // Pass the docId parameter to the PHP file using AJAX
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Update the content of the popup with the response from the PHP file
            document.getElementById("popup-content").innerHTML = this.responseText;
        }
    };
    // Send the docId parameter to the PHP file without any extra spaces
    xmlhttp.open("GET", "fetch_popup_data.php?doc_id=" + docId, true);
    xmlhttp.send();
}

    

    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }

</script>


</body>
</html>

