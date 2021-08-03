<?php
//include constants page
include('../config/constants.php');
//echo "Delete Food Page"
if(isset($_GET['id']) && isset($_GET['image_name']))//Either use '&&' or 'AND' or &
{
    //Process to Delete
   // echo "process to delete";
   //1. Get ID and Image Name
$id = $_GET['id'];
$image_name = $_GET['image_name'];

   
   //2.Remove the image if available

   //Check whether image is available or not and delete only if available
   if($image_name != "")
   {
       //It has image and need to remove from folder
       //Get the Image Path
       $path = "../images/food/".$image_name;
   
//rename Image File from Folder
       $remove = unlink($path);
       
       //check whether the image is removed or not 
       if($remove==false)
       
    {
       // Failed to Remove Image
       $_SESSION['upload'] = "<div class='error'> Failed to remove Image File</div>";
       //Redirect to Manage Food
       header('location:'.SITEURL.'admin/manage-food.php');
       
       //stop the process of Deleting food
       die();
    }
}
       
       //3.Delete food from database using sql query

       $sql = "DELETE FROM tbl_food WHERE id=$id";
       //Execute the Query
       $res = mysqli_query($conn, $sql);

      // check whether the query executed and set the session message respectively
       //4.redirect to Manage food with Session Message
       if($res==true)
       {
           //Food Deleted
           $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
       }
        else 
        {
        $_SESSION['delete'] = "<div class='error'>Failed to delete food</div>";
        header('location:'.SITEURL.'admin/manage-food.php');
        }
    }
        else 
        {
        //redirect to manage food page
        //echo "Redirect";    
        $_SESSION['unauthorize'] = "<div class='error'>Unauthorized Access.</div>";
        header('location:'.SITEURL.'admin/manage-food.php');
        }
        
        ?>
