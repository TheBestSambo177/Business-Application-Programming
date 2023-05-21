<?php
//Declare a variable of web page title
$page_title = 'Insert Result';
//Include the header.html file stored in the sub-folder "inc"
include ('inc/header.html');
?>

<h1>Book Entry Results: </h1> 

<?php   
//Get book info sent from HMTL form
$isbn = $_POST['isbn'];
$author = $_POST['author'];
$title = $_POST['title'];
$price = $_POST['price'];

//Validate input
if (!$isbn||!$author||!$title||!$price) {
echo "you did not enter all details";
exit;
}

//Connect to Database 
include('inc/dbcon.php');


//INSERT BOOK
$sql = "INSERT INTO books VALUES('".$isbn."' , '".$author."' , '".$title."' , '".$price."')";
$results = mysqli_query($conn, $sql);

if ($results) {
echo  mysqli_affected_rows($conn) . " book insert into databases successfully!!!"; 
} else {
echo "An error occured. The item was not added";
}

mysqli_close($conn);//Close the connection
?>

<!--Include the footer.html file stored in the sub-folder "inc"-->
<?php
include ('inc/footer.html');
?>
