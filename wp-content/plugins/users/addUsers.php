<?php 
    include('includes/dbconnect.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $address = $_POST["address"];
        $price = $_POST["price"];
        $city = $_POST["city"];
        $specifications = $_POST["specifications"];
        $images = $_POST["images"];

        $sql = "Insert into properties (address, price, city, specifications, images) Values ('$address', '$price', '$city', '$specifications', '$images')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Property added";
        } else {
            echo "An error occured";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create a listing</title>
</head>
<body>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    
    <label for="address">Address:</label>
    <input type="text" name="address" id="address" required><br>

    <label for="price">Price:</label>
    <input type="text" name="price" id="price" required><br>

    <label for="city">City:</label>
    <input type="text" name="city" id="city" required><br>

    <label for="specifications">Specifications:</label>
    <input type="text" name="specifications" id="specifications" required><br>

    <label for="images">Images:</label>
    <input type="text" name="images" id="images" required><br>

    <input type="submit" value="Add Property">
    </form>

</body>

</html>