<?php
/**
 * Plugin Name: Haukainga HomeWinds Booking Plugin
 * Description: Displays properties from the database and allows them to be booked by users.
 * Version: 1.0.0
 * Author: Javarn Tromp de Haas & Samuel Kennedy
*/

//Allow the user to create and log into accounts
function accounts_shortcode() {
    include("includes/dbconnect.php");
    
    //Create user account
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $phoneNumber = $_POST["phoneNumber"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $address = $_POST["address"];
        $licenceNumber = $_POST["licenceNumber"];
        $photoIdentification = $_POST["photoIdentification"];
        
        $checkEmailQuery = "SELECT * from users WHERE email = '$email'";
        $checkEmailQuery = $conn->query($checkEmailQuery);
        
        if ($checkEmailQuery->num_rows > 0) {
            echo "Email already exists. Please use another one.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);            
            $query = "Insert into users (firstName, lastName, phoneNumber, email, password, address, licenceNumber, photoIdentification) Values (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'ssisssss', $firstName, $lastName, $phoneNumber, $email, $hashedPassword, $address, $licenceNumber, $photoIdentification);
            mysqli_stmt_execute($stmt);
        }    
    }

    echo "<h1>Create User Account</h1>";
    echo '<form method="POST" action="my-listings-plugin.php"';
    echo '<label for="firstName">First Name:</label>';
    echo '<input type="text" name="firstName" id="firstName" required><br>';

    echo '<label for="lastName">Last Name:</label>';
    echo '<input type="text" name="lastName" id="lastName" required><br>';

    echo '<label for="phoneNumber">Phone Number:</label>';
    echo '<input type="text" name="phoneNumber" id="phoneNumber" required><br>';

    echo '<label for="email">Email:</label>';
    echo '<input type="email" name="email" id="email" required><br>';

    echo '<label for="password">Password:</label>';
    echo '<input type="password" name="password" id="password" required><br>';
        
    echo '<label for="address">Address:</label>';
    echo '<input type="text" name="address" id="address" required><br>';
        
    echo '<label for="LicenceNumber">LicenceNumber:</label>';
    echo '<input type="text" name="licenceNumber" id="licenceNumber" required><br>';
        
    echo '<label for="photoIdentification">Photo Identification:</label>';
    echo '<input type="text" name="photoIdentification" id="photoIdentification" required><br>';

    echo '<input type="submit" name="register" value="Register">';
    echo '</form>';
}

//Allow the user to log into accounts
function accountSignIn_shortcode() {
    //Login users
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Retrieve the user from the database
        $getUserQuery = "SELECT * FROM users WHERE email = '$email'";
        $getUserResult = $conn->query($getUserQuery);

        //If user does not exist return error
        if ($getUserResult->num_rows == 0) {
            echo"User does not exist";
        }

        // Check if the user exists
        if ($getUserResult->num_rows == 1) {
            $row = mysqli_fetch_assoc($getUserResult);
		    $hashedPassword = $row['password'];

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Password is correct, start a new session
                $_SESSION["email"] = $email;
                echo "Logged in successfully.";
            } else {
                echo "Invalid email or password.";
            }
        } 
    }

    echo '<h1>User Login</h1>';
    echo '<form method="POST" action="<$_SERVER["PHP_SELF"]; ?>';
        echo '<label for="email">Email:</label>';
        echo '<input type="email" name="email" id="email" required><br>';

        echo '<label for="password">Password:</label>';
        echo '<input type="password" name="password" id="password" required><br>';

        echo '<input type="submit" name="login" value="Login">';
    echo '</form>';
}


//Users shortcode
add_shortcode('createUser', 'accounts_shortcode');
add_shortcode('signIn', 'accountSignIn_shortcode');


//Display all property listings.
function my_listings_plugin_shortcode() {
    ?>
 
    <!-- Small bit of styling in CSS to display the properties nicer. -->
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            align-items: center;
            text-align: center;
        }
        
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        .property_image {
            width: 100px;   
        }
        
        .property_address {
            width: 50%;
        }
        
        .property_button {
            width: 100px;
        }
    </style>
 
    <?php
    ob_start();
    include(plugin_dir_path(__FILE__) . 'includes/dbconnect.php');
     
    $sql = "SELECT * FROM properties";
    $result = $conn->query($sql);
 
    //Display all relevant information, including the image, address, and a button to view the property for each listing.
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<table>';
            echo '<tr>';
            echo '<td class="property_image">';
            echo '<img class="hello" src="' . plugins_url('../Property_Management/images/' . $row["images"], __FILE__) . '" alt="Image of the property.">';
            echo '</td>';
            echo '<td class="property_address">';
            echo '<h2>' . $row["address"] . '</h2>';
            echo '<p>' . $row["city"] . '</p>';
            echo '</td>';
            echo '<td class="property_button">';
            echo '<button class="button"><a href="' . esc_url(add_query_arg('id', $row["propertyID"], '?page_id=16')) . '">View Property</a></button>';
            echo '</td>';   
            echo '</tr>';   
            echo '</table>';
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
            
            echo '<button><a href="?page_id=16&id=' . $row["propertyID"] . '">Go Back</a></button>';
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

        // Retrieve the listing details based on the id.
        $sql = "SELECT * FROM properties WHERE propertyID = $listingId";
        $result = $conn->query($sql);

        // Properly format the date.
        $arrival = date('d/m/Y', strtotime($_POST['arrival']));
        $departure = date('d/m/Y', strtotime($_POST['departure']));
       
        // Get the duration of stay in days.
        $date1 = new DateTime($_POST['arrival']);
        $date2 = new DateTime($_POST['departure']);
        $interval = $date1->diff($date2);
        $days = $interval->days;   

        // If the user books at least 4 days, one of them is free.
        if ($days > 3) {
            $discount = $days - 1;
            $message = ' (Book 3 get one free).';
        } else {
            $discount = $days;
            $message = '';
        }    

        // Display the details of the booking.
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            //Retrieve the information regarding the property, and calulate the price based on factors such as nights stayed and discounts applied.
            $id = $row["propertyID"];
            $address = $row["address"];
            $city = $row["city"];
            $price = $row["price"];
            $total = $price * $discount;
            
            //User confirmed details
            echo '<h3>Confirm your details:</h3>';
            echo '<p><strong>Your Name:</strong> </p>';
            echo '<p><strong>Booking Address:</strong> ' . $address . ', ' . $city . '.</p>';
            echo '<p><strong>Duration of Stay:</strong> ' . $arrival . ' to ' . $departure . ' (' . $days . ' day/s).</p>';
            echo '<p><strong>Price Per Night:</strong> $' . $price . '</p>';
            echo '<p><strong>Total Price:</strong> $' . $total . '.00' . $message . '</p>';

            // Allow the user to enter their payment information after confirming their booking details.
            echo '<form method="POST" action="?page_id=48&id=' . $row["propertyID"] . '">'; 
            echo '<h3>Enter payment details:</h3>';
            echo '<label for="cardName">Name:</label><br>';
            echo '<input name="cardName" id="cardNumber" type="text" required><br>';
            echo '<label for="number">Num:</label><br>';
            echo '<input name="number" id="number" type="text" required><br>';
            echo '<label for="date">Expiration:</label><br>';
            echo '<input name="date" id="date" type="month" required><br>';
            echo '<label for="cvv">CVV:</label><br>';
            echo '<input name="cvv" id="cvv" type="number" required><br>';
            echo '<input type="hidden" name="total" value="' . $total . '">';
            echo '<input type="hidden" name="arrival" value="' . $_POST['arrival'] . '">';
            echo '<input type="hidden" name="departure" value="' . $_POST['departure'] . '">';
            echo '<input type="submit" value="Proceed to Checkout" name="checkoutButton" id="checkoutButton">';
            echo '</form>'; 
            process_data($total);  
        }                               
    }
}
add_shortcode('checkout', 'checkout_shortcode');

//This function runs after the user has entered their payment details. Information regarding the booking is then added to the database.
function process_data() {    
    //Post to database
    include('includes/dbconnect.php');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['checkoutButton'])) {
            $listingId = $_GET['id'];
            $total = $_POST['total'];
            $arrival = $_POST['arrival'];
            $departure = $_POST['departure'];
            //Run an SQL query to create a booking that gets added to the database.                       
            $query = 'insert into bookings (propertyID, arrivalDate, departureDate, cost) values (?, ?, ?, ?)';
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'issi', $listingId, $arrival, $departure, $total);
            mysqli_stmt_execute($stmt);
            echo 'Your booking has been created.';
            echo '<script>window.location.href = "?page_id=8";</script>';
        }         
    }
}
add_shortcode('process', 'process_data');

function display_bookings() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'includes/dbconnect.php');
    
    //Get both bookings and properties from the database so they can be displayed alongside each other.
    $sql = "select * from bookings inner join properties where properties.propertyID = bookings.propertyID;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {   
            echo '<div class="listing">';
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
add_shortcode('all_bookings', 'display_bookings');