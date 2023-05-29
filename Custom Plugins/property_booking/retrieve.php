<link rel="stylesheet" href="includes/style.css" type="text/css">

<?php
    $page_title = 'Retrieve';
    
    include('includes/dbconnect.php');
    
    $sql = "Select * from properties";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="listing">';
            echo '<img class="hello" src="' . $row["images"] . '" alt="hope this works">';
            echo '<h2>' . $row["address"] . '</h2>';
            echo '<p>' . 'Located in ' . $row["city"] . '</p>';
            echo '<p>' . '$' . $row["price"] . ' per night.' .'</p>';
            echo '<button><a href="listing.php?id=' . $row["propertyID"] . '">View Property</a></button>';
            echo '</div>';
        }
    } else {
        echo "No listings found";
    }

?>