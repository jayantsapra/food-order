<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>
        <br><br>
        <?php
        if(isset($_SESSION['upload']))
        {
            echo $_SESSION['upload'];
            unset ($_SESSION['upload']);
        }
        ?>
 <!-- Add food Form Starts -->
 <form action="" method="POST" enctype="multipart/form-data">
        <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td> 
                        <input type="text" name="title" placeholder="Food-Title">
                    </td>
                </tr>     
                
                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description-Food"></textarea>
                    </td>
                </tr>
                
                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price">  
                    </td>
                </tr>

                <tr>
             <td>Select Image: </td>
              <td>
            <input type="file" name="image">
                </td>
            </tr> 
           
           
            <tr> 
    <td>Category:</td>
    <td>
        <select name="category">
<?php 
//create php code to display categories from database
//1.Create SQL to get all active categories from database
$sql = "SELECT * FROM tbl_category WHERE active='Yes'";

$res =mysqli_query($conn, $sql);

//Count Rows to check whether we have categories or not
$count = mysqli_num_rows($res);
//2. display on dropdown


//if count is greater than zero, we have categories else we do-not have categories
if($count>0)
{
    //we have categories
    while($row=mysqli_fetch_assoc($res))
    {
        //get the details of categories
        $id = $row['id'];
        $title = $row['title'];

        ?>

        <option value="<?php echo $id; ?>"><?php echo $title; ?></option>
        <?php
    }
}
else 
{
    //we do not have category
    ?>
    <option value ="0">No Category Found</option>
    <?php
}

//2.Display on Dropdown
?>

    </select>
    </td>
        </tr>

<tr> 
    <td>Featured:</td>
    <td>
        <input type="radio" name="featured" value="yes"> yes
        <input type="radio" name="featured" value="no"> no
</td>
</tr>


<tr>
<td>Active: </td>
    <td>
        <input type="radio" name="active" value="yes"> yes
        <input type="radio" name="active" value="no"> no
</td>
</tr>

<tr>
    <td colspan="2">
        <input type="submit" name="submit" value="Add-Food" class="btn-secondary">
    </td>
    </tr>
            </table>
        </form>
        <?php
        //Check whether the button is clicked or not
        if(isset($_POST['submit']))
        {
            // Add the food in Database
            //echo "Clicked";

        //1. Get the data from Form
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];


           //check whether radio button for featured and active are checked or not
           if(isset($_POST['featured']))
           {
               $featured = $_POST['featured'];
           }
           else 
               {
                $featured = "No"; // Setting the default value
               }

               if(isset($_POST['active']))
           {
               $active = $_POST['active'];
           }
           else
          {
            $active = "No"; // Setting the default value
           }

            //2  upload the image if selected 
            // check whether the select image is clicked or not and upload the image only if the image is selected 
            if(isset($_FILES['image']['name']))
            {
                // Get the details of selected image
                $image_name = $_FILES['image']['name'];
              
                //check whether the image is selected or not and upload image if selected
                if($image_name!="")
                {
                    //image is Selected
                    //A. Rename the Image
                    //Get the Extension of selected image(jpg,png,gif,etc.)curtain.jpg image jpg
                    $ext = end(explode('.', $image_name));
                    
                    //create New name for image
                    $image_name = "Food-Name-".rand(0000,9999).".".$ext; //New image name May be "food Name-657.jpg"
                    
                    
                    //B.Upload the image
                    //Get the src path and destination path
                    

                    // source path is the current location of the image
                    $src = $_FILES['image']['tmp_name'];
                    
                    //Destination Path for the image to be uploaded
                    $dst = "../images/food/".$image_name;
                    
                    //Finally upload the food image
                    $upload = move_uploaded_file($src, $dst);


                    //check whether image is uploaded or not
                    if($upload==false)
                    {
            
                    //Failed to upload the image
                    //Redirect to add Food page with error message
                    $_SESSION['upload'] = "<div class='error'>Failed to Upload Image. </div>";
                    //Redirect to add category page
                    header('location:'.SITEURL.'admin/add-food.php');
                    //Stop the process 
                    die();
                  }
                }

                //check whether the image is selected or not
            }
            else 
            {
                    $image_name = ""; //Setting deafault value as blank
            }
            //3. Get the data into Form
            //For Numerical we do not need to pass value inside quotes '' but for string value it is compulsory to add quotes ''
            //.3 Insert Into Database
            $sql2 = "INSERT INTO tbl_food SET 
            title ='$title',
            description = '$description',
            price = $price,
            image_name = '$image_name',
            category_id = $category,
            featured = '$featured',
            active = '$active'
               ";
            //Execute the Query
            $res2 = mysqli_query($conn, $sql2);
            //Check whether data inserted or not
            //4. Redirect with Message to Manage Food page

            if($res2 == true)
            {
                //Data inserted successfully
                $_SESSION['add'] = "<div class='success'>Food Added Succesfully.</div>";
                 //Redirect to manage food Page
                header('location:'.SITEURL.'admin/manage-food.php');
            }
            else 
            {
              //Failed to insert data  
              $_SESSION['add'] = "<div class='error'>Failed to Add Food. </div>";
              //Redirect to manage food Page
             header('location:'.SITEURL.'admin/manage-food.php');
            }

        }
?>
</div>
</div>
<?php include('partials/footer.php') ?>