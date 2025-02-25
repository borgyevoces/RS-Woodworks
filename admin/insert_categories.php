<?php include('../includes/connection.php'); 
if(isset($_POST['insert_cat'])){
  $category_title=$_POST['cat_title'];

  //select data form database
  $select_query="Select * from `categories` where category_title='$category_title'";
  $result_select=mysqli_query($con,$select_query);
  $number=mysqli_num_rows($result_select);
  if($number>0){
    echo "<script>alert('This category already exist')</script>";
  }else{

  $insert_query="insert into `categories`(category_title) values ('$category_title')";
  $result=mysqli_query($con,$insert_query);
  if($result){
    echo "<script>alert('Successfully added category')</script>";
  }
}}
?>
<div class="content">
    <div class="form-container">
      <h2>Add Product Category</h2>
      <form action="" method="post">
        <input type="text" name="cat_title" placeholder="Category Name" required>
        <button type="submit" name="insert_cat">Add Category</button>
      </form>
    </div>
  </div>