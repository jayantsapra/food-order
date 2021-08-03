<?php include('partials/menu.php'); ?>

<?php
//check whether id is set or not
if(isset($_GET['id']))
{
    //Get all the details
    $id = $_GET['id'];

//SQL Query to Get the Selected Food
$sql2 = "SELECT * FROM tbl_food WHERE id=$id";

//Execute the Query
$res2 = mysqli_query($conn, $sql2);

//Get the value based on query executed
$row2 = mysqli_fetch_assoc($res2);

//Get the individual values of selected Food

//Get the Individual values of Selected Food
$title = $row2['title'];
$description = $row2['description'];
$price = $row2['price'];
$current_image = $row2['image_name'];
$current_category = $row2['category_id'];
$featured = $row2['featured'];
$active = $row2['active'];
}
else {
    
    //Redirect to Manage food
    header('location:'.SITEURL.'admin/manage-food.php');    
}
?>

<div class="main-content">
    <div class="wrapper">
        <h2>Update Food</h2>
        <br><br>

<form action="" method="POST" enctype="multipart/form-data">
        <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td> 
                        <input type="text" name="title" value="<?php echo $title; ?>">
                    </td>
                </tr>     
                
                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                    </td>
                </tr>
                
                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price" value="<?php echo $price; ?>" >
                    </td>
                </tr>

                <tr>
             <td>Current Image: </td>
              <td>
           <?php
           if($current_image == "")
           {
               //Image not Available
               echo "<div class='error'> Image not available</div>";
           }
           else {
               // Image available
               ?>
               <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="100px">
               <?php
           }
           ?>
                </td>
            </tr> 
           <tr>
               <td>select New Image: </td>
            <td>
                <input type="file" name="image"> 
            </td>          
          </tr> 

     <tr>
    <td>Category:</td>
    <td>
        <select name="category">
<?php
//Query to get Acive Categories
$sql = "SELECT * FROM tbl_category WHERE active='yes'";
//Execute the Query
$res = mysqli_query($conn, $sql);
//Count Rows
$count = mysqli_num_rows($res);

// check whether category available or not 
if($count>0)
{
    //Category available
    while($row=mysqli_fetch_assoc($res))
    {
        $category_title = $row['title'];
        $category_id = $row['id'];
        //echo "<option value ='$category_id'>$category_title</option>";
        ?>
        <option <?php if($current_category==$category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
        <?php
    }
}
else {
// category not available
echo "<option value='0'>Category not Available</option>";
}
  ?>
</select>
</td>
</tr> 

<tr>
    <td>Featured:</td>
        <td>
            <input <?php if($featured=="yes") {echo "checked";} ?> type="radio" name="featured" value="yes"> Yes
            <input <?php if($featured=="no") {echo "checked";} ?> type="radio" name="featured" value="no"> No
        </td>
</tr>

<tr>
    <td>Active:</td>
        <td>
            <input <?php if($active=="yes") {echo "checked";} ?> type = "radio" name="active" value="yes"> Yes
            <input <?php if($active=="no") {echo "checked";} ?> type = "radio" name="active" value="no"> No
        </td>
</tr>
<tr>
    <td>
        <td>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
            <input type="submit" name="submit" value="Update Food" class="btn-secondary">
        </td>
</tr>

</table>
</form>
<?php

if(isset($_POST['submit']))
{
   // echo "Button is Clicked";
//1. Get all the details from the form
$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];
$current_image = $_POST['current_image'];
$category = $_POST['category'];

$featured = $_POST['featured'];
$active = $_POST['active'];
//2. Upload the image if selected 

//Check whether upload button is clicked or not
if(isset($_FILES['image']['name']))
{
  //Upload button clicked 
  $image_name = $_FILES['image']['name']; //new image name

 //check whether the image or file is available or not 
 if($image_name!="")
 {
     //Image is Available
     //A. Upload the New Image
    //Auto Rename our Image

$ext = end(explode('.', $image_name)); //Get the Extension of our image(jpg, png, gif, etc e.g. "food1.jpg, "special food1.jpg")

//Rename the Image
$image_name = "Food-Name-".rand(0000, 9999).'.'.$ext; //Rename the Image e.g. Food_Category_734.jpg

//get the source path and destination path
$src_path = $_FILES['image']['tmp_name'];  //source path 

$dest_path = "../images/food/".$image_name; //destination path

//Finally Upload the image
$upload = move_uploaded_file($src_path, $dest_path);

//check whether the image is uploaded or not
//and if the image is not uploaded then we will stop the process and redirect with error message.
if($upload==false)
{
        //set message that failed to upload
        $_SESSION['upload'] = "<div class='error'>Failed to Upload new Image. </div>";
        //Redirect to manage food page
        header('location:'.SITEURL.'admin/manage-food.php');
        //Stop the Process
       die();
    }
       //3.Remove the image if new image is uploaded and current image exists
       //B. Remove current Image if Available
       if($current_image!="")
       {
        //Current image is available
        //Remove the image
        $remove_path = "../images/food/".$current_image;
    
           $remove = unlink($remove_path);
           
           //Check whether the image is removed or not
           //if failed to remove then display message and stop the process
           if($remove==false)
           {
               //Failed to remove current image 
               $_SESSION['remove-failed'] = "<div class='error'> Failed to remove current image. </div>";
               //redirect to manage food 
               header('location:'.SITEURL. 'admin/manage-food.php');
              die(); //stop the process
          }      
       }
    }
    else 
    {
        $image_name = $current_image; //default image when image is not selected
    }

}
    else 
    {
        $image_name = $current_image; // deafult image when button is not clicked.
    }


    //4. Update the food in Database  (Update the database)
$sql3 = "UPDATE tbl_food SET
title = '$title',
description = '$description',
price = $price,
image_name = '$image_name',
category_id='$category',
featured = '$featured',
active = '$active'
WHERE id = $id
";
//Execute the SQL Query
$res3 = mysqli_query($conn, $sql3);

//check whether the query is executed or not
if($res3==true)
{
    //Query Executed and food updated
    $_SESSION['update']= "<div class='success'>Food Updated Successfully.</div>";
    // Redirect to manage admin page
    header('location:'.SITEURL.'admin/manage-food.php');
}
else 
{
    //failed to update the message
    $_SESSION['update']= "<div class='error'>Failed to Update Food.</div>";
    // Redirect to manage admin page
    header('location:'.SITEURL.'admin/manage-food.php');
}

}
?>
</div>
</div>
<?php include('partials/footer.php') ?>