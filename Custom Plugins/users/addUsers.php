<?php 
    include('includes/dbconnect.php');

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
			$sql = "Insert into users (firstName, lastName, phoneNumber, email, password, address, licenceNumber, photoIdentification) Values ('$firstName', '$lastName', '$phoneNumber', '$email', '$hashedPassword', '$address', '$licenceNumber', '$photoIdentification')";
			
			if ($conn->query($sql) === TRUE) {
				echo "User added added";
			} else {
				echo "An error occured";
			}
		}    
    }
	
	//Login users
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    //Retrieve the email information from the database
    $getEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $getEmailResult = $conn->query($getEmailQuery);

    if ($getEmailResult->num_rows == 1) {
        $email = $getEmailResult->fetch_assoc();
        $hashedPassword = $email["password"];

        //Verify the password
        if (password_verify($password, $hashedPassword)) {
            //If Password is correct, start a new session
            $_SESSION["email"] = $email;
            echo "Logged in successfully.";
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
</head>
<body>
	<h1>Create User Account</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
		<label for="firstName">First Name:</label>
		<input type="text" name="firstName" id="firstName" required><br>

		<label for="lastName">Last Name:</label>
		<input type="text" name="lastName" id="lastName" required><br>

		<label for="phoneNumber">Phone Number:</label>
		<input type="text" name="phoneNumber" id="phoneNumber" required><br>

		<label for="email">Email:</label>
		<input type="email" name="email" id="email" required><br>

		<label for="password">Password:</label>
		<input type="password" name="password" id="password" required><br>
		
		<label for="address">Address:</label>
		<input type="text" name="address" id="address" required><br>
		
		<label for="LicenceNumber">LicenceNumber:</label>
		<input type="text" name="licenceNumber" id="licenceNumber" required><br>
		
		<label for="photoIdentification">Photo Identification:</label>
		<input type="text" name="photoIdentification" id="photoIdentification" required><br>

		<input type="submit" name="register" value="Register">
    </form>
	
	<h1>User Login</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" name="login" value="Login">
    </form>

</body>

</html>