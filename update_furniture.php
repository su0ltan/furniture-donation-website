<?php

include 'components/connect.php';

   session_start();

   if (isset($_SESSION['email'])) {
      $user_id = $_SESSION['email'];
   } else {
      $user_id = '';
   }
   if (isset($_POST['update'])) {
      
      

      $query = $conn->prepare('update furniture1 set title= ?, description= ? where id=?');
      $query->execute([$_POST['name'], $_POST['details'] , $_GET['id']]);
      if($query)
      $message[] = "Update successfully";
   }

   if (isset($_GET['id'])) {

      $id = $_GET['id'];



 
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update furniture</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->   <link rel="stylesheet" href="css/admin_style.css">


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

      <h1 class="heading">Update furniture</h1>

      <?php

     
      $select_products = $conn->prepare("SELECT * FROM `furniture1` WHERE id = ? ");
      $select_products->execute([$id]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" method="post" class="box">
               <div class="flex">
                  <div class="inputBox">
                     <span>furniture name (required)</span>
                     <input type="text" class="box" required maxlength="100" placeholder="Enter title"  name="name" value="<?=$fetch_product['title'];?>">
                  </div>
                  <div class="inputBox">
                     <span>furniture details (required)</span>
                     <textarea name="details" class="box" required maxlength="500" cols="30"
                        rows="10"><?=$fetch_product['description'];?></textarea>
                  </div>
                 
                  <input type="submit" value="update" class="btn" name="update">


               </div>


            </form>
            <?php
         }
      } else {
         echo '<p class="empty">no furnitures added yet!</p>';
      }
      ?>

   </section>













   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>