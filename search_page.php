<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search page</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="search-form">
   <form>
      <input type="text" id="search-box" placeholder="Search here..." maxlength="10000" class="box" autocomplete="off">
      <button type="submit" class="fas fa-search"></button>
   </form>
   <div id="search-result" class="box-container"></div>
</section>

<section class="products" style="padding-top: 0; min-height:100vh;">

   <div class="box-container">

   <?php
     if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
     $search_box = $_POST['search_box'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch()){
   ?>
<div class="search-result-item">
   <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
   <div class="search-result-content">
      <div class="product-name"><?= $fetch_product['name']; ?></div>
      <div class="price-row">
         <span class="price"><?= number_format($fetch_product['price'], 0, ',', '.'); ?>đ</span>
         <?php if ($fetch_product['old_price'] > $fetch_product['price']) { ?>
            <span class="old-price"><?= number_format($fetch_product['old_price'], 0, ',', '.'); ?>đ</span>
            <span class="discount">
               -<?= round(100 - ($fetch_product['price'] / $fetch_product['old_price']) * 100); ?>%
            </span>
         <?php } ?>
      </div>
      <?php if (!empty($fetch_product['gift'])) { ?>
         <div class="gift">Quà <?= number_format($fetch_product['gift'], 0, ',', '.'); ?>đ</div>
      <?php } ?>
   </div>
</div>


   <?php
         }
      }else{
         echo '<p class="empty">no products found!</p>';
      }
   }
   ?>

   </div>

</section>



<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>

<script>
document.querySelector("#search-box").addEventListener("input", function() {
   let query = this.value.trim();
   let resultBox = document.querySelector("#search-result");

   if(query.length > 0){
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "search_ajax.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onload = function(){
         resultBox.innerHTML = this.responseText;
      };
      xhr.send("search=" + encodeURIComponent(query));
   } else {
      resultBox.innerHTML = "";
   }
});
</script>

</html>