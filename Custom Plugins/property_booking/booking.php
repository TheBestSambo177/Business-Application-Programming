<?php

include('includes/dbconnect.php');

if (isset($_GET['id'])) {
    $listingId = $_GET['id'];

    $currentDate = date('Y-m-d');


    // Retrieve the listing details based on the id
    $sql = "Select * from properties where propertyID = $listingId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<form method="POST" action="checkout.php?id=' . $row["propertyID"] . '">'; 
        echo '<h2>Book ' . $row["address"] . ', ' . $row["city"] . '</h2>';
        
        echo '<label for="arrival">Arrival:</label><br>';
        echo '<input name="arrival" id="arrival" type="date" min="' . $currentDate . '" ><br>';

        echo '<label for="departure">Departure:</label><br>';
        echo '<input name="departure" type="date" min="' . $currentDate . '" ><br>';
        
        $arrivalDate = $_POST['arrival']?? '';
        $departureDate = $_POST['departure']?? '';

        echo '<button><a href="listing.php?id=' . $row["propertyID"] . '">Go Back</a></button>';
        echo '<input type="submit" value="Proceed to Checkout">';
       
        echo '</form>';
        
    }
    
    


}
?>
