<?php include('partials/menu.php'); ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>
        <br><br> 

        <?php
        if(isset($_SESSION['add'])) //checking whether the session is set or Not 
        {
            echo $_SESSION['add']; //Displaying session messsage
            unset($_SESSION['add']);//Removing session message
        }
        ?>
        
        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>full name: </td>
                    <td>
                        <input type="text" name="full_name" placeholder="Enter your Name">
                </td>
            </tr>
            <tr>
                    <td>username: </td>
                    <td><input type="text" name="username" placeholder="Your Username">
                </td>
            </tr>
            <tr>
                    <td>Password: </td>
                    <td><input type="password" name="password" placeholder="your password">
                </td>
            </tr>
            <tr>
                <td colspan="2">
<input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                </td>
            </tr>
            
            <table>
        </form>

    </div>
</div>
<br><br>
<?php include('partials/footer.php'); ?>

<?php 
//process the value from form and save it in database.

//check whether the submit button is clicked or not 
if(isset($_POST['submit']))
{
    //Button clicked
   //echo "Button Clicked";
    
   //1.Get the Data from form
  $full_name = $_POST['full_name'];
  $username = $_POST['username'];
  $password = md5($_POST['password']);  //Password Encryption with MD5

  //2.SQL query to save the data into database.
$sql = "INSERT INTO tbl_admin SET 
full_name='$full_name',
username='$username',
password='$password'
";

//3. Execute Query and Save Data in Database
$conn = mysqli_connect('localhost','root') or die(mysqli_error());//Database connection
$db_select = mysqli_select_db($conn, 'food-order') or die(mysql_error());// Selecting database

//3.Executing Query and saving Data into Database.
$res = mysqli_query($conn, $sql) or die(mysql_error());

//4. Check whether the (Query is Executed) data is inserted or not and display appropriate message
if($res==TRUE)
{
    //Data Inserted
//echo "Data Inserted";
//create a session variable to display message
$_SESSION['add'] = "Admin Added Sucessfully";
//Redirect page to Mange Admin
header("location:".SITEURL.'admin/manage-admin.php');
}
else
{
//Failed to insert data
   // echo "Failed to Insert Data";
   //create a session variable to display message
$_SESSION['add'] = "Failed to Add Addmin";
//Redirect page to Mange Admin
header("location:",SITEURL,'admin/add-admin.php');
}
}

?> 