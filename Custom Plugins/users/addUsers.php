<?php 
    include('includes/dbconnect.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $phoneNumber = $_POST["phoneNumber"];
        $email = $_POST["email"];
        $password = $_POST["password"];
		$address = $_POST["address"];
		$licenceNumber = $_POST["licenceNumber"];
		$photoIdentification = $_POST["photoIdentification"];

        $sql = "Insert into users (firstName, lastName, phoneNumber, email, password, address, licenceNumber, photoIdentification) Values ('$firstName', '$lastName', '$phoneNumber', '$email', '$password', '$address', '$licenceNumber', '$photoIdentification')";
        
        if ($conn->query($sql) === TRUE) {
            echo "User added added";
        } else {
            echo "An error occured";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create a user account</title>
</head>
<body>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    
    <label for="firstName">First Name:</label>
    <input type="text" name="firstName" id="firstName" required><br>

    <label for="lastName">Last Name:</label>
    <input type="text" name="lastName" id="lastName" required><br>

    <label for="phoneNumber">Phone Number:</label>
    <input type="text" name="phoneNumber" id="phoneNumber" required><br>

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" required><br>

    <label for="password">Password:</label>
    <input type="text" name="password" id="password" required><br>
	
	<label for="address">Address:</label>
    <input type="text" name="address" id="address" required><br>
	
	<label for="password">LicenceNumber:</label>
    <input type="text" name="licenceNumber" id="licenceNumber" required><br>
	
	<label for="photoIdentification">Photo Identification:</label>
    <input type="text" name="photoIdentification" id="photoIdentification" required><br>

    <input type="submit" value="Create Account">
    </form>

</body>

</html>