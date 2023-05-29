<?php
/*
Plugin Name: Property Management
Plugin URI: 
Description: This plugin will allow you to add, edit, and delete properties from the database.
Author: Javarn Tromp de Haas
Version: 0.1
Last update: 24 May 2023
*/

$property_dbversion = "0.1";

if (!function_exists('pr')) {
    function pr($var) { echo '<pre>'; var_dump($var); echo '</pre>';}
}

register_activation_hook(__FILE__,'property_install');
//register_deactivation_hook( __FILE__, 'WAD_faq_uninstall' );
register_uninstall_hook( __FILE__, 'property_uninstall' );

add_action('plugins_loaded', 'property_update_db_check');
add_action('plugin_action_links_'.plugin_basename(__FILE__), 'propertysettingslink' );  

add_shortcode('displayfaq', 'displayProperties');
add_shortcode('CRUDproperty', 'property_CRUD');
add_action('admin_menu', 'property_menu');

function property_update_db_check() {
	global $property_dbversion;
	if (get_site_option('property_dbversion') != $property_dbversion) property_install();   
}

function property_install () {
	global $wpdb;
	global $property_dbversion;

	$currentversion = get_option( "property_dbversion" ); //retrieve the version of FAQ database if it has been installed
	if ($property_dbversion > $currentversion) { //version still good?
		if($wpdb->get_var("show tables like 'properties'") != 'properties') {//check if the table already exists
	
			$sql = 'CREATE table properties (
                propertyID int PRIMARY KEY AUTO_INCREMENT,
                address VARCHAR(100),
                price DECIMAL(13,2),
                city VARCHAR(50),
                specifications VARCHAR(200),
                images VARCHAR(50)
            );';

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			//update the version of the database with the new one
			update_option( "property_dbversion", $property_dbversion );
			add_option("property_dbversion", $property_dbversion);
		}  
    }	
} 

function property_uninstall() {
	delete_site_option($property_dbversion);
	delete_option($property_dbversion);
}

function property_menu() {
    add_submenu_page( 'plugins.php', 'Properties', 'Properties', 'manage_options', 'add_property', 'property_CRUD');
}

function propertysettingslink($links) { 
    array_unshift($links, '<a href="'.admin_url('plugins.php?page=add_property').'">Settings</a>'); 
    return $links; 
}

function displayProperties() {
    global $wpdb;

    $query = "SELECT address, price, city, specifications, images FROM properties";
    $allproperties = $wpdb->get_results($query);
//pr($allfaqs); //uncomment this line to see the out of the query - it is typically an array of objects
    $buffer = '<ol>';
	//note the naming convention adopted here. results (array) from a query in plural and a singular result element from the array
    foreach ($allproperties as $properties) {
	
    }
    $buffer .= '</ol>';
    return $buffer;
}


function property_CRUD() {
	echo  '<div id="msg" style="overflow: auto"></div>
		<div class="wrap">
		<h2>Property Management <a href="?page=add_property&command=new" class="add-new-h2">Add New</a></h2>
		<div style="clear: both"></div>';

// !!WARNING: there is no data validation conducted on the _REQUEST or _POST information. It is highly 
// recommend to parse ALL data/variables before using		
	$propertydata = $_POST; //our form data from the insert/update
	
//current FAQ id for delete/edit commands
	if (isset($_REQUEST['id'])) 
		$propertyid = $_REQUEST['id']; 
	else 
		$propertyid = '';

//current CRUD command		
	if (isset($_REQUEST["command"])) 
		$command = $_REQUEST["command"]; 
	else 
		$command = '';
		
//execute the respective function based on the command		
    switch ($command) {
	//operations access through the URL	
		case 'view':
			property_view($propertyid);
		break;
		
		case 'edit':
			$msg = property_form('update', $propertyid); //notice the $propertyid passed for the form for an update/edit
		break;

		case 'new':
		    //notice that no 'id' is passed from 'new' to the form. 
			//WAD_faq_form will use 'null' as the default 'id' - refer to WAD_faq_form for more details
			$msg = property_form('insert');
		break;
		
    //operations performing the various database tasks based on the previous CRUD command
		case 'delete':
			$msg = property_delete($propertyid); //remove a faq entry
			$command = '';
		break;

		case 'update':
			$msg = property_update($propertydata); //update an existing faq
			$command = '';
		break;

		case 'insert':	
			$msg = property_insert($propertydata); //prepare a blank form for adding a new faq entry
			$command = '';
		break;
	}
	
//a simple catchall if the command is not found in the switch selector
	if (empty($command)) property_list(); //display a list of the faqs if no command issued

//show any information messages	
	if (!empty($msg)) {
      echo '<p><a href="?page=add_property"> back to the FAQ list </a></p> Message: '.$msg;      
	}
	echo '</div>';
}

function property_view($id) {
    global $wpdb;
 
    //https://developer.wordpress.org/reference/classes/wpdb/#protect-queries-against-sql-injection-attacks
    //safer preferred method of passing values to an SQL query this is not a substitute for data validation
    //this method merely reduces the likelyhood of SQL injections
    $qry = $wpdb->prepare("SELECT * FROM properties WHERE propertyID = %s",$id);
    
    //$qry = $wpdb->prepare("SELECT * FROM WAD_faq WHERE id = %s",array($id)); //alternative using an array
 //pr($qry); //uncomment this line to see the prepared query
    $row = $wpdb->get_row($qry);
    
    //popular unsafe method
    //$row = $wpdb->get_row("SELECT * FROM WAD_faq WHERE id = '$id'");

    echo $row->address;
    echo $row->price;
    echo $row->city;
    echo $row->specifications;
    echo $row->images;

    echo '<p><a href="?page=add_property">&laquo; Return to property list</p>';
}

function property_update($data) {
    global $wpdb, $current_user;

    if (isset($_POST['submit'])) {
        $targetDirectory = "images/"; // Specify the directory where you want to store the uploaded files
        $targetFile = $targetDirectory . basename($_FILES["images"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    }

    if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFile)) {
        echo "The file " . basename($_FILES["images"]["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $wpdb->update( 'properties',
            array(
            'address' => stripslashes_deep($data['address']),
            'price' => $data['price'],
            'city' => stripslashes_deep($data['city']),
            'specifications' => stripslashes_deep($data['specifications']),
            'images' => $targetFile),
		  array( 'propertyID' => $data['propertyEdit']));
    $msg = "Property ".$data['propertyEdit']." has been updated";
    return $msg;
}

function property_insert($data) {
    global $wpdb, $current_user;

    if (isset($_POST['submit'])) {
        $targetDirectory = "images/"; // Specify the directory where you want to store the uploaded files
        $targetFile = $targetDirectory . basename($_FILES["images"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    }

    if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFile)) {
        echo "The file " . basename($_FILES["images"]["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $wpdb->insert( 'properties',
		  array(
			'address' => stripslashes_deep($data['address']),
            'price' => $data['price'],
            'city' => stripslashes_deep($data['city']),
            'specifications' => stripslashes_deep($data['specifications']),
            'images' => $targetFile),
		  array( '%s', '%s', '%s', '%s', '%s') );
    $msg = "Property has been added to the database.";
    return $msg;
}

function property_delete($id) {
    global $wpdb;
    $results = $wpdb->query($wpdb->prepare("DELETE FROM properties WHERE propertyID=%s", $id));
    if ($results) {
        $msg = "Property $id was successfully deleted.";
        return $msg;
    }
}

function property_list() {
    global $wpdb, $current_user;
 
    //prepare the query for retrieving the FAQ's from the database
    $query = "SELECT propertyID, address, price, city, specifications, images FROM properties";
    $allfaqs = $wpdb->get_results($query);
 
    //prepare the table and use a default WP style - wp-list-table widefat and manage-column
    echo '<table class="wp-list-table widefat">
         <thead>
         <tr>
            <th scope="col" class="manage-column">Address</th>
            <th scope="col" class="manage-column">Price</th>
            <th scope="col" class="manage-column">City</th>
            <th scope="col" class="manage-column">Specifications</th>
            <th scope="col" class="manage-column">Images</th>
         </tr>
         </thead>
         <tbody>';
     
     foreach ($allfaqs as $faq) {
        
 //prepare the URL's for some of the CRUD - note again the use of the menu slug to maintain page location between operations	   
        $edit_link = '?page=add_property&id=' . $faq->propertyID . '&command=edit';
        $view_link ='?page=add_property&id=' . $faq->propertyID . '&command=view';
        $delete_link = '?page=add_property&id=' . $faq->propertyID . '&command=delete';
 
        //use some inbuilt WP CSS to perform the hover effect for the edit/view/delete links	   
        echo '<tr>';
        echo '<td><strong><a href="'.$edit_link.'" title="Edit question">' . $faq->address . '</a></strong>';
        echo '<div class="row-actions">';
        echo '<span class="edit"><a href="'.$edit_link.'" title="Edit this item">Edit</a></span> | ';
        echo '<span class="view"><a href="'.$view_link.'" title="View this item">View</a></span> | ';
        echo '<span class="trash"><a href="'.$delete_link.'" title="Move this item to Trash" onclick="return doDelete();">Trash</a></span>';
        echo '</div>';
        echo '</td>';
        echo '<td>$' . $faq->price . '</td>';
        echo '<td>' . $faq->city . '</td>';
        echo '<td>' . $faq->specifications . '</td>';
        echo '<td>' . $faq->images . '</td>';
         
     }
    echo '</tbody></table>';
     
 //small piece of javascript for the delete confirmation	
     echo "<script type='text/javascript'>
             function doDelete() { if (!confirm('Are you sure?')) return false; }
           </script>";
}

function property_form($command, $id = null) {
	global $wpdb; 

//if the current command was 'edit' then retrieve the FAQ record based on the id pased to this function
//!!this SQL querey is open to potential injection attacks
	if ($command == 'update') {
        $property = $wpdb->get_row("SELECT * FROM properties WHERE propertyID = '$id'");
	}

//if the current command is insert then clear the form variables to ensure we have a blank
//form before starting	
	if(empty($property)) { // This happens for 'new' also if get_row fails
		$property = (object) array('address' => '', 'price' => '', 'city' => '', 'specifications' => '', 'images' => '' );
	}
	
//prepare the HTML form	
    echo '<form name="PropertyForm" method="post" enctype="multipart/form-data" action="?page=add_property">
		<input type="hidden" name="propertyEdit" value="'.$id.'"/>
		<input type="hidden" name="command" value="'.$command.'"/>

		<p>Address:<br/>
		<input type="text" name="address" value="'.$property->address.'" size="20" class="large-text" required/>    

        <p>Price:<br/>
		<input type="number" name="price" value="'.$property->price.'" size="20" class="large-text" required/>

        <p>City:<br/>
		<input type="text" name="city" value="'.$property->city.'" size="20" class="large-text" required/>

        <p>Specifications:<br/>
		<input type="text" name="specifications" value="'.$property->specifications.'" size="20" class="large-text" required/>

        <p>Images:<br/>
		<input type="file" accept="image/png, image/jpeg" name="images" value="'.$property->images.'" size="20" class="large-text"/>
		</p><hr />

		<p class="submit"><input type="submit" name="submit" value="Save Changes" class="button-primary" /></p>
		</form>';
   echo '<p><a href="?page=add_property">&laquo; Return to property list</p>';		
}





?>
