<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['email'])) {
   $user_id = $_SESSION['email'];
} else {
   header('location:../f/user_login.php');
}

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
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="search-form">
      <form action="" method="post">
         <input type="text" name="search_box" placeholder="search here..." maxlength="100" class="box" required>
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>
   </section>

   <section class="products" style="padding-top: 0; min-height:100vh;">

      <div class="box-container">

         <?php

         if (isset($_POST['search_box']) or isset($_POST['search_btn'])) {
            $search_box = $_POST['search_box'];


            $select_products = $conn->prepare("

          SELECT * FROM `furniture1`
          WHERE 
          (title LIKE '%{$search_box}%'
         OR description LIKE '%{$search_box}%')
         and id not in (select fid from requests where status = 2)");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <form action="" method="post" class="box">
                     <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_product['title']; ?>">
                     <input type="hidden" name="ownerID" value="<?= $fetch_product['ownerID']; ?>">
                     <input type="hidden" name="description" value="<?= $fetch_product['description']; ?>">
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
                     <input type="submit" value="Request" class="btn" name="request">
                  </form>
                  <?php
               }
            } else {
               echo '<p class="empty">no products found!</p>';
            }
         }
         ?>

      </div>

   </section>















   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>