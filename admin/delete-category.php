<?php
//Include constants file
include('../config/constants.php');


//echo "Delete Page";
//Check whether the id image_name value is set or not
if(isset($_GET['id']) AND isset($_GET['image_name']))
{
    //Get the value and Delete
    //echo "Get Value and Delete";
   $id = $_GET['id'];
   $image_name = $_GET['image_name'];
   //Remove the physical image file is available
   if($image_name !="")
   {
       //Image is Available. So remove it
       $path = "../images/category/".$image_name;
       //Remove the image
       $remove = unlink($path);
       //IF Failed to remove image then add an error message and stop the process
       if($remove==false)
       {
           //Set the session message 
           $_SESSION['remove'] = "<div class='error'>Failed to Remove Category Image.</div>";
           //Redirect to Manage-Category page
           header('location:'.SITEURL. 'admin/manage-category.php');
           // stop the process
           die();
       }
       
   }
   //Delete Data from Database
   //SQL Query to Delete Data from Database
$sql = "DELETE FROM tbl_category WHERE id=$id";

//Execute the Query
$res = mysqli_query($conn, $sql);

//Check whether the data is deleted from database or not
if($res==true)
{
    // Set Success Message and Redirects
    $_SESSION['delete'] = "<div class='success'>Category Deleted Succesfully.</div>";
    //Redirect to Manage Category
    header('location:'.SITEURL.'admin/manage-category.php');
}
else 
{
    //Set fail Message and Redirects
    $_SESSION['delete'] = "<div class='error'>Failed to Delete Category.</div>";
    //Redirect to Manage Category
    header('location:'.SITEURL.'admin/manage-category.php');
}
}   
else 
{
    //redirect to Manage Category Page
    header('location:'.SITEURL.'admin/manage-category.php');
}
?>