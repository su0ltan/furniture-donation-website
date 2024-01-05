<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['email'])) {
   $user_id = $_SESSION['email'];
} else {
   header('location:../f/user_login.php');
}



if (isset($_POST['cancel'])) {


   $id = $_POST['pid'];
   $select_products = $conn->prepare("DELETE FROM `requests` WHERE id = ?");
   $select_products->execute([$id]);


   if ($select_products)
      $message[] = 'request canceled successfully!';


}
if (isset($_POST['accept'])) {


   $id = $_POST['pid'];
   $select_products = $conn->prepare("UPDATE `requests` SET status = ? WHERE id= ?");
   $select_products->execute([2, $id]);


   if ($select_products)
      $message[] = 'request accepted successfully!';


}
if (isset($_POST['reject'])) {


   $id = $_POST['pid'];
   $select_products = $conn->prepare("UPDATE `requests` SET status = ? WHERE id= ?");
   $select_products->execute([3, $id]);


   if ($select_products)
      $message[] = 'request rejected successfully!';


}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>




   </section>

   <section class="home-products">

      <h1 class="heading">My Requests</h1>

      <div class="swiper products-slider">

         <div class="swiper-wrapper">

            <?php
            $select_products = $conn->prepare("SELECT * FROM `requests` WHERE askedId = ?");
            $select_products->execute([$user_id]);
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                  $fid = $fetch_product['fid'];
                  $st = $fetch_product['status'];



                  $fur_query = $conn->prepare("SELECT * FROM `furniture1` WHERE id = ?");
                  $fur_query->execute([$fid]);
                  $select_fur = $fur_query->fetch(PDO::FETCH_ASSOC);


                  $u_query = $conn->prepare("SELECT * FROM `user` WHERE email= ?");
                  $u_query->execute([$fetch_product['ownerid']]);
                  $select_user = $u_query->fetch(PDO::FETCH_ASSOC);
                  $ph = "";
                  if ($st == 1)
                     $st = "is pending";
                  else if ($st == 2) {
                     $st = nl2br("is accepted \r\n the owner phone number is");
                     $ph = $select_user['phoneNumber'];
                  } else if ($st == 3)
                     $st = "sorry this furniture unavailable";
                  ?>
                  <form action="" method="post" class="swiper-slide slide">
                     <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="ownerID" value="<?= $fetch_product['ownerid']; ?>">
                     <input type="hidden" name="status" value="<?= $fetch_product['status']; ?>">



                     <div class="name">your request for a furniture
                        <div>ID:
                        <?= $select_fur['id']; ?></div>
                         <div>title: <?= $select_fur['title'];?></div>
                     </div>
                     <div class="name">
                        <?= $st; ?>
                     </div>
                     <div class="name">
                        <?=

                           $ph
                           ?>
                     </div>
                     <input type="submit" value="remove" class="delete-btn" name="cancel">
                  </form>
                  <?php
               }
            } else {
               echo '<p class="empty">no requests yet</p>';
            }
            ?>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>




   </section>

   <section class="home-products">

      <h1 class="heading">received Requests</h1>

      <div class="swiper products-slider">

         <div class="swiper-wrapper">

            <?php
            $select_products = $conn->prepare("SELECT * FROM `requests` WHERE ownerID = ? AND status = 1");
            $select_products->execute([$user_id]);
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                  $st = $fetch_product['status'];
                  $select_f = $conn->prepare("SELECT * FROM `furniture1` WHERE id = ?");
                  $select_f->execute([$fetch_product['fid']]);





                  $fur_query = $conn->prepare("SELECT * FROM `furniture1` WHERE id = ?");
                  $fur_query->execute([$fetch_product['fid']]);
                  $select_fur = $fur_query->fetch(PDO::FETCH_ASSOC);


                  

                  ?>
                  <form action="" method="post" class="swiper-slide slide">
                     <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="ownerID" value="<?= $fetch_product['ownerid']; ?>">
                     <input type="hidden" name="status" value="<?= $fetch_product['status']; ?>">
                     <input type="hidden" name="asked" value="<?= $fetch_product['askedId']; ?>">

                     
                     <div class="name">this request concern with a 
                        <?= $select_fur['title']; ?>
                     </div>

                     <div class="name">from:
                        <?= $fetch_product['askedId']; ?>
                     </div>
                     <input type="submit" value="Accept request" class="btn" name="accept">
                     <input type="submit" value="reject request" class="delete-btn" name="reject">
                  </form>
                  <?php
               }
            } else {
               echo '<p class="empty">no requests received!</p>';
            }
            ?>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>









   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <script src="js/script.js"></script>

   <script>

      var swiper = new Swiper(".home-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });

     
      var swiper = new Swiper(".category-slider", {
      slidesPerView: 3,
      
      slidesPerColumn: 2,
      spaceBetween: 20,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },


      });

      var swiper = new Swiper(".products-slider", {
      slidesPerView: 3,
      
      slidesPerColumn: 2,
      spaceBetween: 20,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },


      });

   </script>

</body>

</html>