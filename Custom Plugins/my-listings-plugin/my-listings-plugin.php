<style>
    .listing {
    margin: 2%;
    border-style: solid;
    display: inline-grid;
    width: 25%;
    text-align: center;
    }

    img {
    height: 50px;
    display: block;
    margin-left: auto;
    margin-right: auto;
    }
</style>

<?php
/**
 * Plugin Name: Haukainga HomeWinds Booking Plugin
 * Description: Displays properties from the database and allows them to be booked by users.
 * Version: 1.0.0
 * Author: Javarn Tromp de Haas & Samuel Kennedy
*/

//Display all property listings.
function my_listings_plugin_shortcode() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'includes/dbconnect.php');
    
    $sql = "SELECT * FROM properties";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="listing">';
            echo '<img class="hello" src="' . plugins_url('../Property_Management/images/' . $row["images"], __FILE__) . '" alt="Image of the property.">';
            echo '<h2>' . $row["address"] . '</h2>';
            echo '<p>' . 'Located in ' . $row["city"] . '</p>';
            echo '<p>' . '$' . $row["price"] . ' per night.' .'</p>';
            echo '<button class="button"><a href="' . esc_url(add_query_arg('id', $row["propertyID"], '?page_id=16')) . '">View Property</a></button>';
            echo '</div>';
        }
    } else {
        echo "No listings found";
    }

    $output = ob_get_clean();
    return $output;
}
add_shortcode('my_listings', 'my_listings_plugin_shortcode');

//Open a listing to show more information regarding it.
function property_listing_shortcode() {
    ob_start();

    include('includes/dbconnect.php');

    if (isset($_GET['id'])) {
        $listingId = $_GET['id'];

        // Retrieve the listing details based on the id
        global $wpdb;
        $sql = $wpdb->prepare("SELECT * FROM properties WHERE propertyID = %d", $listingId);
        $row = $wpdb->get_row($sql);

        if ($row) {
            echo '<img class="hello" src="' . plugins_url('../Property_Management/images/' . $row->images, __FILE__) . '" alt="Image of the property.">';
            echo '<h2>' . $row->address . '</h2>';
            echo '<p>' . 'Located in ' . $row->city . '</p>';
            echo '<p>' . '$' . $row->price . ' per night.' .'</p>';
            echo '<button><a href="' . get_permalink(8) . '">Go back</a></button>';
            echo '<button><a href="' . get_permalink(27) . '&id=' . $row->propertyID . '">Book this Property</a></button>';
        } else {
            echo "Listing not found.";
        }
    } else {
        echo "Listing not found.";
    }

    return ob_get_clean();
}
add_shortcode('property_listing', 'property_listing_shortcode');

//Book a property, which allows the user to set information such as dates.
function property_booking_shortcode() {
    include('includes/dbconnect.php');

    if (isset($_GET['id'])) {
        $listingId = $_GET['id'];

        $currentDate = date('Y-m-d');

        // Retrieve the listing details based on the id
        $sql = "SELECT * FROM properties WHERE propertyID = $listingId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo '<form method="POST" action="?page_id=30&id=' . $row["propertyID"] . '">'; 
            echo '<h2>You are about to book ' . $row["address"] . ', ' . $row["city"] . '</h2>';
            
            echo '<label for="arrival">Arrival:</label><br>';
            echo '<input name="arrival" id="arrival" type="date" min="' . $currentDate . '" required><br>';

            echo '<label for="departure">Departure:</label><br>';
            echo '<input name="departure" id="departure" type="date" min="' . $currentDate . '" required><br>';
            
            echo '<button><a href="?page_id=30&id=' . $row["propertyID"] . '">Go Back</a></button>';
            echo '<input type="submit" value="Proceed to Checkout" id="checkoutButton">';
        
            echo '</form>';

            // Validate that the departure date is later than the arrival date using Javascript. Also ensure that the date being displayed is in the correct format.
            echo '<script>
            document.getElementById("checkoutButton").addEventListener("click", function(event) {                
                
                var arrival = new Date(document.getElementById("arrival").value);
                var departure = new Date(document.getElementById("departure").value);
                var formattedArrivalDate = arrival.toLocaleDateString("en-GB");
                
                if (departure <= arrival) {
                    event.preventDefault();
                    alert("Sorry, the departure date must be later than " + formattedArrivalDate + ".");
                }
            });
            </script>';
        }
    }
}
add_shortcode('property_booking', 'property_booking_shortcode');

//Checkout, where the booking is finalized and added to the database.
function checkout_shortcode() {
    include('includes/dbconnect.php');

    if (isset($_GET['id'])) {
        $listingId = $_GET['id'];

        // Retrieve the listing details based on the id
        $sql = "Select * from properties where propertyID = $listingId";
        $result = $conn->query($sql);


        $arrival = date('d/m/Y', strtotime($_POST['arrival']));
        $departure = date('d/m/Y', strtotime($_POST['departure']));

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo '<h3>Confirm your details:</h3>';
            echo '<p><strong>Your Name:</strong> </p>';
            echo '<p><strong>Booking Address:</strong> ' . $row["address"] . ', ' . $row["city"] . '.</p>';
            echo '<p><strong>Duration of Stay:</strong> ' . $arrival . ' to ' . $departure . '</p>';
            echo '<button><a href="?page_id=27&id=' . $row["propertyID"] . '">Go Back</a></button>';
        }
    }
}
add_shortcode('checkout', 'checkout_shortcode');