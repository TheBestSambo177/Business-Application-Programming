<?php

include('includes/dbconnect.php');

if (isset($_GET['id'])) {
    $listingId = $_GET['id'];

    // Retrieve the listing details based on the id
    $sql = "Select * from properties where propertyID = $listingId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<h2>Checkout</h2>';
        echo '<h3>Confirm your details:</h3>';
        echo '<p><strong>Your Name:</strong> </p>';
        echo '<p><strong>Booking Address:</strong> ' . $row["address"] . ', ' . $row["city"] . '.</p>';
        echo '<p><strong>Duration of Stay:</strong> ' . $_POST['arrival'] . ' to ' . $_POST['departure'] . '</p>';

        
        echo '<button><a href="booking.php?id=' . $row["propertyID"] . '">Go Back</a></button>';
    }
}

?>