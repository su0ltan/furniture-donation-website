<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['email'])) {
   $user_id = $_SESSION['email'];
} else {
   header('location:../f/user_login.php');
}


if (isset($_POST['delete'])) {






   $s_query = $conn->prepare("DELETE from furniture1 where id = ?");
   $s_query->execute([$_POST['id']]);
   $message[] = "Furniture deleted successfully";



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

      <h1 class="heading">My Furnitures</h1>

      <div class="swiper products-slider">

         <div class="swiper-wrapper">

            <?php
            $select_products = $conn->prepare("SELECT * FROM `furniture1` WHERE ownerID = ?");
            $select_products->execute([$user_id]);
            if ($select_products->rowCount() > 0) {

               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {


                  ?>
                  <form action="" method="post" class="swiper-slide slide">
                     <input type="hidden" name="id" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_product['title']; ?>">
                     <input type="hidden" name="description" value="<?= $fetch_product['description']; ?>">
                     <input type="hidden" name="img1" value="<?= $fetch_product['img1']; ?>">
                     <input type="hidden" name="img2" value="<?= $fetch_product['img2']; ?>">
                     <input type="hidden" name="img3" value="<?= $fetch_product['img3']; ?>">
                     <input type="hidden" name="img4" value="<?= $fetch_product['img4']; ?>">



                     <img src="uploaded_img/<?= $fetch_product['img1']; ?>" alt="">
                     <div class="name">
                        <?= $fetch_product['title']; ?>
                     </div>



                     <a href="update_furniture.php?id=<?= $fetch_product['id'];?> "class="btn"> Update</a>
                     <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Are you sure?');"
                        name="delete">


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