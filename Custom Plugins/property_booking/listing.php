<?php

include('includes/dbconnect.php');

if (isset($_GET['id'])) {
    $listingId = $_GET['id'];
    
    // Retrieve the listing details based on the id
    $sql = "Select * from properties where propertyID = $listingId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<img class="property_image" src="images/properties/' . $row["images"] . '">';
        echo '<h2>' . $row["address"] . '</h2>';
        echo '<p>' . 'Located in ' . $row["city"] . '</p>';
        echo '<p>' . '$' . $row["price"] . ' per night.' .'</p>';
        echo '<button><a href="retrieve.php">Go back</a></button>';
        echo '<button><a href="booking.php?id=' . $row["propertyID"] . '">Book this Property</a></button>';
    } else {
        echo "Listing not found.";
    }
} else {
    echo "Listing not found.";
}
?>


