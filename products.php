<?php

include 'components/connect.php';

session_start();

$id = $_SESSION['email'];
if(!isset($id)){
   header('location:../f/user_login.php');
}


if (isset($_POST['add_furniture'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $owner = $id;
   $desription = $_POST['details'];
   $desription = filter_var($desription, FILTER_SANITIZE_STRING);
   $id = rand(1000,9999);
   
   $date = date("Y/m/d");
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = 'uploaded_img/' . $image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = 'uploaded_img/' . $image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = 'uploaded_img/' . $image_03;

   $image_04 = $_FILES['image_04']['name'];
   $image_04 = filter_var($image_04, FILTER_SANITIZE_STRING);
   $image_size_04 = $_FILES['image_04']['size'];
   $image_tmp_name_04 = $_FILES['image_04']['tmp_name'];
   $image_folder_04 = 'uploaded_img/' . $image_04;

   $select_products = $conn->prepare("SELECT * FROM `furniture1` WHERE id = ?");
   $select_products->execute([$id]);

   if ($select_products->rowCount() > 0) {
      $message[] = 'id already exist!';
   } else {

      $insert_products = $conn->prepare("INSERT INTO `furniture1`(id,ownerID,title,description, date,img1,img2,img3,img4) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_products->execute([$id, $owner, $name, $desription, $date, $image_01,$image_02, $image_03, $image_04]);

      if ($insert_products) {
         if ($image_size_01 > 2000000 or $image_size_02 > 2000000 or $image_size_03 > 2000000 or $image_size_04 > 2000000) {
            $message[] = 'image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            move_uploaded_file($image_tmp_name_04, $image_folder_04);
            $message[] = 'new furniture added!';
         }

      }

   }

}
;




?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/admin_style.css">
   
   <style>
      
.footer{
   background-color: var(--white);
   /* padding-bottom: 7rem; */
}

.footer .grid{
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(27rem, 1fr));
   gap:1.5rem;
   align-items: flex-start;
}

.footer .grid .box h3{
   font-size: 2rem;
   color:var(--black);
   margin-bottom: 2rem;
   text-transform: capitalize;
}

.footer .grid .box a{
   display: block;
   margin:1.5rem 0;
   font-size: 1.7rem;
   color:var(--light-color);
}

.footer .grid .box a i{
   padding-right: 1rem;
   color:var(--main-color);
   transition: .2s linear;
}

.footer .grid .box a:hover{
   color:var(--main-color);
}

.footer .grid .box a:hover i{
   padding-right: 2rem;
}

.footer .credit{
   text-align: center;
   padding: 2.5rem 2rem;
   border-top: var(--border);
   font-size: 2rem;
   color:var(--black);
}

.footer .credit span{
   color:var(--main-color);
}
</style>
  
</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="add-products">

      <h1 class="heading">add furniture</h1>

      <form action="" method="post" enctype="multipart/form-data">
         <div class="flex">
            <div class="inputBox">
               <span>furniture name (required)</span>
               <input type="text" class="box" required maxlength="100" placeholder="enter furniture name" name="name">
            </div>
            <div class="inputBox">
               <span>furniture details (required)</span>
               <textarea name="details" placeholder="enter furniture details" class="box" required maxlength="500"
                  cols="30" rows="10"></textarea>
            </div>
            <div class="inputBox">
               <span>image 01 (required)</span>
               <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>
             <div class="inputBox">
               <span>image 02 (required)</span>
               <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>
            <div class="inputBox">
               <span>image 03 (required)</span>
               <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>
            <div class="inputBox">
               <span>image 04 (required)</span>
               <input type="file" name="image_04" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>

         </div>

         <input type="submit" value="add furniture" class="btn" name="add_furniture">
      </form>

   </section>





   <?php
   include("components/footer.php"); ?>






</body>

</html>