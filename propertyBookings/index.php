<?php 
    //Declare a variable of web page title
    $page_title = 'Home';
    //Include the header.html file stored in the sub-folder "inc"
    include ('inc/header.html');
  ?>

  <h1>Simple Bookstore</h1>
  <hr>
  <h2>LIST OF ALL BOOK IN THE DATABASE</h2>
  
  <?php 
    //Connect to Database 
    include('inc/dbcon.php');
    
    //SQL SELECT Statement
    $sql = "SELECT * FROM books";
    $results = mysqli_query($conn, $sql);
    
    //Check the query results:    
    $num = mysqli_num_rows($results);//Count the number of returned rows
    if ($num > 0) {
      //Print how many records in database
      echo "<p>There are currently $num books in the database</p>\n";
      //Create "Table" header by using PHP echo function - opening "table" tag
      echo '<table align="center" cellspacing="3" cellpadding="3" width="75%">
          <tr>
            <td align="left"><b>ISBN</b></td>
            <td align="left"><b>Author</b></td>
            <td align="left"><b>Title</b></td>
            <td align="left"><b>Price</b></td>
          </tr>
         ';     
      //Fetch and print all the records
      while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
        echo '<tr>'.
            '<td align="left">' . $row['isbn'] . '</td>' . 
            '<td align="left">' . $row['author'] . '</td>' .
            '<td align="left">' . $row['title'] . '</td>' .
            '<td align="left">' . $row['price'] . '</td>' .
           '</tr>';
      }

      //Close the table tag
      echo '</table>'; // Close the table.
      
      //Free up the data (records) stored in $results variable
      mysqli_free_result ($results); // Free up the resources.  
      mysqli_close($conn);//Close the connection

    } else { 
      // If no records were returned.
      echo '<p class="error">No item in database</p>';
    }       
  ?>
  
  <hr>
  <h2>INSERT A NEW BOOK</h2>  
  <form action="insertbook.php" method="post">
    <table>
      <tr>
        <td>ISBN: </td> 
        <td> <input name="isbn"type="text" size=20></td>
      </tr>
      <tr>
        <td>Author:</td>
        <td><input name="author" type="text" size=30></td>
      </tr>
      <tr>
        <td>Title:  </td>
        <td>  <input name="title" type="text" size=30></td>
      </tr>
      <tr>
        <td>Price: $</td>
        <td><input name="price" type="text" size=20></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" value="ADD BOOK"></td>
      </tr>
    </table>
  </form>
  
  <hr>
  <h2>SEARCH</h2> 
  <form action="searchbook.php"method="post">
    Choose search type:<br>
    <select name="searchtype">
      <option value="author">Author</option>
      <option value="title">Title</option>
      <option value="isbn">ISBN</option>
    </select>
    
    <br>
    Enter search iterm:<br>
    <input name="searchterm" type="text"size="40"/>
    <br>
    <input type="submit"name="submit"value="SEARCH">
  </form>
  <br>
  
<!--Include the footer.html file stored in the sub-folder "inc"-->
<?php
    include ('inc/footer.html');
?>
