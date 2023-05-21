<?php 
      //Declare a variable of web page title
      $page_title = 'Search Results';
      //Include the header.html file stored in the sub-folder "inc"
      include ('inc/header.html');
  ?>
  
  <h1>Book Search Results</h1>

  <?php
    //Get search info sent from HTML Form
    $searchtype = $_POST['searchtype'];
    $searchterm = trim($_POST['searchterm']);

    //Validate data
    if (!$searchtype || !$searchterm) {
     echo 'You have not entered search details.  Please go back and try again.';
     exit;
    }

    //Connect to Database 
    include('inc/dbcon.php');
    
    //Query data from database and compare
    $sql = "SELECT * FROM books WHERE " . $searchtype . " like '%" . $searchterm . "%'";
    $results = mysqli_query($conn, $sql);
    $num_results = mysqli_num_rows($results);

    echo "<p>Number of books found: ".$num_results . "</p>";    
    for ($i=0; $i < $num_results; $i++) {
       $row = $results->fetch_assoc();       
       echo "<p><strong>".($i+1).". Title: ";
       echo htmlspecialchars(stripslashes($row['title']));
       echo "</strong><br />Author: ";
       echo stripslashes($row['author']);
       echo "<br />ISBN: ";
       echo stripslashes($row['isbn']);
       echo "<br />Price: ";
       echo stripslashes($row['price']);
       echo "</p>";
    }

    $results->free();//Empty the array to release memory
    mysqli_close($conn);//Close the connection
  ?>
  
<!--Include the footer.html file stored in the sub-folder "inc"-->
<?php
    include ('inc/footer.html');
?>
