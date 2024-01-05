<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['email'])) {
   $user_id = $_SESSION['email'];
} else {
   $user_id = '';
}
;

if(isset($_POST['request'])){
   $ownerID = $_POST['ownerID'];

   if($ownerID == $user_id){
      $message[] = 'You are the owner!';
   }else{
      
   $id = rand(10,100);
   $ownerID = $_POST['ownerID'];
   $asked =$user_id;
   $status = 1;
   $fid = $_POST['pid'];
  
   
   $insert_request = $conn->prepare("INSERT INTO `requests`(fid,id,ownerid,status,askedId) VALUES  (?,?,?,?,?)");
   $insert_request->execute([$fid, $id, $ownerID, $status, $asked]);
   $message[] = 'request sent successfuly!';


   }


  

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="quick-view">

      <h1 class="heading">quick view</h1>

      <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `furniture1` WHERE id = ?");
      $select_products->execute([$pid]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" method="post" class="box">
               <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
               <input type="hidden" name="title" value="<?= $fetch_product['title']; ?>">
               <input type="hidden" name="description" value="<?= $fetch_product['description']; ?>">
               <input type="hidden" name="ownerID" value="<?= $fetch_product['ownerID']; ?>">
               <input type="hidden" name="image" value="<?= $fetch_product['img1']; ?>">
               <div class="row">
                  <div class="image-container">
                     <div class="main-image">
                        <img src="uploaded_img/<?= $fetch_product['img1']; ?>" alt="">
                     </div>
                     <div class="sub-image">
                        <img src="uploaded_img/<?= $fetch_product['img1']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['img2']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['img3']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['img4']; ?>" alt="">
                     </div>
                  </div>
                  <div class="content">
                     <div class="name">
                        <?= $fetch_product['title']; ?>
                     </div>
                     <div class="flex">
                        
                     </div>
                     <div class="details">
                        <?= $fetch_product['description']; ?>
                     </div>
                     <div class="flex-btn">
                        <input type="submit" value="Request this furniture" class="btn" name="request">
                        
                     </div>
                  </div>
               </div>
            </form>
            <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>

   </section>













   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>