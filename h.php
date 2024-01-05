<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['email'])) {
   $user_id = $_SESSION['email'];

   $select_products = $conn->prepare("SELECT * 
   FROM furniture1
    where ownerID != ?
     and id not in (select fid from requests where status = 2)
     ORDER BY date DESC 
     LIMIT 10");

   $select_products->execute([$user_id]);
   $i = 0;


   $dyn = '<table border="1" cellpadding="10"> ';

   while ($row = $select_products->fetch(PDO::FETCH_ASSOC)) {



      $title = $row['title'];
      $desciption = $row['description'];
      $img1 = $row['img1'];
      $img2 = $row['img2'];
      $img3 = $row['img3'];
      $img4 = $row['img4'];


      if ($i % 3 == 0) {
         $dyn = '<tr><td class="box"><div class="content"> ' . $title . '</div></h1></td';

      } else {

         $dyn = '<td><h1><div class="content"> ' . $title . '</div></h1></td';

      }





      $i++;

   }
   $dyn = '</tr></table>';
  
} else {
   header('location:../f/user_login.php');
}

if (isset($_POST['request'])) {

   $id = rand(10, 100);
   $ownerID = $_POST['ownerID'];
   $asked = $user_id;
   $status = 1;
   $fid = $_POST['pid'];

   if ($ownerID == $user_id) {
      $message[] = 'You are the owner!';
   } else {

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


   if (isset($_SESSION['email'])) {




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


   <h1 class="heading">latest Furnitures</h1>


   <section>
      <div class="swiper products-slider">

         <div class="swiper-wrapper">
            <ph? echo $dyn; ?>
         </div>
      </div>
   </section>











   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <script src="js/script.js"></script>

</body>

</html>