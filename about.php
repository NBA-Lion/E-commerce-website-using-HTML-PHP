<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

   <style>
   /* Style cho nút điều hướng slider */
   .swiper-button-next,
   .swiper-button-prev {
      color: var(--main-color); /* màu bạn có thể điều chỉnh */
      font-size: 2.5rem;
   }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">
   <div class="row">
      <div class="image">
         <img src="images/25.png" alt="">
      </div>
      <div class="content">
         <h3>Developer's Message:</h3>
         <p>Hey There ! I'm Binh. A Student from HB university. I love designing websites and exploring new things. Learning new things is my hobby.</p>
         <p>I would like to thank my teacher for guiding me through the session and making me able to develop projects like this.</p>
         <a href="contact.php" class="btn">Contact Us</a>
      </div>
   </div>
</section>

<section class="reviews">
   <h1 class="heading">Client's Reviews.</h1>

   <div class="swiper reviews-slider">
      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <img src="images/user.png" alt="">
            <p>Been using their services for quite a bit...</p>
            <div class="stars">
               <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
            </div>
            <h3><a href="https://www.facebook.com/profile.php" target="_blank">Charlote</a></h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/profile.png" alt="">
            <p>It is the first online services in Nepal...</p>
            <div class="stars">
               <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
            </div>
            <h3><a href="https://www.facebook.com/profile.php" target="_blank">Hoa Pham</a></h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/panda.png" alt="">
            <p>Electronics store is great if you choose...</p>
            <div class="stars">
               <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
            </div>
            <h3><a href="https://www.facebook.com/" target="_blank">Panda</a></h3>
         </div>

      </div>

      <!-- Phân trang -->
      <div class="swiper-pagination"></div>

      <!-- Nút điều hướng -->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Script khởi tạo Swiper -->
<script>
var swiper = new Swiper(".reviews-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable: true,
   },
   navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
   },
   breakpoints: {
      0: {
         slidesPerView: 1,
      },
      768: {
         slidesPerView: 2,
      },
      991: {
         slidesPerView: 3,
      },
   },
});
</script>

<!-- JS của bạn -->
<script src="js/script.js"></script>

</body>
</html>
