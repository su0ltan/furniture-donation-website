<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['email'])) {
   $user_id = $_SESSION['email'];
} else {
   header('location:../f/user_login.php');
}

if (isset($_POST['request'])) {

   $id = rand(10, 100);
   $ownerID = $_POST['ownerID'];
   $asked = $user_id;
   $status = 1;
   $fid = $_POST['pid'];

   if($ownerID == $user_id){
      $message[] = 'You are the owner!';
   }else{
      
   $qu = $conn->prepare("select * from requests where fid= ? and status = 2");
   $qu->execute([$fid]);
   if ($qu > 0) {
      $message[] = " Sorry this furniture is taken by another person";
   } else {
      $insert_request = $conn->prepare("INSERT INTO `requests`(fid,id,ownerid,status,askedId) VALUES  (?,?,?,?,?)");
      $insert_request->execute([$fid, $id, $ownerID, $status, $asked]);
      $message[] = 'request sent successfuly!';
   }
   }









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

   <div class="home-bg">

      <section class="home">

         <div class="swiper home-slider">

            <div class="swiper-wrapper">


            </div>



         </div>

         <div class="swiper-pagination"></div>

   </div>

   </section>

   </div>

   <section class="category">

      <h1 class="heading">furniture category</h1>


      <div class="swiper-wrapper">

         <a href="myfurniture.php" class="swiper-slide slide">
            <img src="images/imgcat1.png" alt="">
            <h3>My furniture</h3>
         </a>
         <a href="products.php" class="swiper-slide slide">
            <img src="images/imgadd.png" alt="">
            <h3>Add furniture</h3>
         </a>
         <a href="myrequest.php" class="swiper-slide slide">
            <img src="images/request.png" alt="">
            <h3>My Requests</h3>
         </a>

         <a href="search_page.php" class="swiper-slide slide">
            <img src="images/imgcat2.png" alt="">
            <h3>Search furniture</h3>
         </a>


      </div>





   </section>

   <section class="home-products">

      <h1 class="heading">latest Furnitures</h1>

      <div class="swiper products-slider">

         <div class="swiper-wrapper">

            <?php
            $select_products = $conn->prepare("SELECT * 
            FROM furniture1
             where ownerID != ?
              and id not in (select fid from requests where status = 2)
              ORDER BY date DESC 
              LIMIT 10");
            $select_products->execute([$user_id]);
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <form action="" method="post" class="swiper-slide slide">
                     <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_product['title']; ?>">
                     <input type="hidden" name="description" value="<?= $fetch_product['description']; ?>">
                     <input type="hidden" name="ownerID" value="<?= $fetch_product['ownerID']; ?>">
                     <input type="hidden" name="image" value="<?= $fetch_product['img1']; ?>">

                     <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                     <img src="uploaded_img/<?= $fetch_product['img1']; ?>" alt="">
                     <div class="name">
                        <?= $fetch_product['title']; ?>
                     </div>
                     <div class="flex">
                        <div class="description">
                           <?= $fetch_product['description']; ?>
                        </div>

                     </div>
                     <input type="submit" value="Request for forniture" class="btn" name="request">
                  </form>
                  <?php
               }
            } else {
               echo '<p class="empty">no furnitures added yet!</p>';
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
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            0: {
               slidesPerView: 2,
            },
            650: {
               slidesPerView: 3,
            },

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