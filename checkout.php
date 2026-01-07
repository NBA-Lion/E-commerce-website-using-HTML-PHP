<?php
include 'components/connect.php';
session_start();

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
}

if(isset($_POST['order'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
   $address = 'flat no. '. filter_var($_POST['flat'], FILTER_SANITIZE_STRING) .', '. filter_var($_POST['street'], FILTER_SANITIZE_STRING) .', '. filter_var($_POST['city'], FILTER_SANITIZE_STRING) .', '. filter_var($_POST['state'], FILTER_SANITIZE_STRING) .', '. filter_var($_POST['country'], FILTER_SANITIZE_STRING) .' - '. filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   // Kiểm tra giỏ hàng
   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      // Thêm đơn hàng vào DB
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      // Xóa giỏ hàng
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $mail = new PHPMailer(true);
      try {
          $mail->Host = 'smtp.gmail.com';
          $mail->isSMTP();
          $mail->SMTPAuth = true;
          $mail->Username = 'binhyb13c@gmail.com';        // your Gmail address
          $mail->Password = 'dumv fisn eeru ejvb';        // your Gmail app password
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port = 587;

          $mail->setFrom('your_email@gmail.com', 'Electronics Store');
          $mail->addAddress($email, $name); // send to customer

          $mail->addEmbeddedImage('images/logo.png', 'logo_cid'); // Attach logo image

          $mail->isHTML(true);
          $mail->Subject = 'Order Confirmation from Electronics Store';
         $mail->Body = '
<div style="font-family: Arial, sans-serif; color: #222; background: #f4f6fb; padding: 32px 0;">
   <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); overflow: hidden;">
      <div style="background: #007BFF; padding: 24px 0; text-align: center;">
         <img src="cid:logo_cid" alt="Store Logo" style="width: 90px; margin-bottom: 8px;">
         <h1 style="color: #fff; margin: 0; font-size: 2rem;">Thank You for Your Order!</h1>
      </div>
      <div style="padding: 32px 28px 24px 28px;">
         <p style="font-size: 1.1rem; margin-bottom: 18px;">Hello <strong>' . htmlspecialchars($name) . '</strong>,</p>
         <p style="margin-bottom: 18px;">We appreciate your trust in <strong>Electronics Store</strong>! Your order has been received and is now being processed. Below are your order details:</p>
         <table style="width: 100%; border-collapse: collapse; margin-bottom: 18px;">
            <tr>
               <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Full Name:</strong></td>
               <td style="padding: 8px; border-bottom: 1px solid #eee;">' . htmlspecialchars($name) . '</td>
            </tr>
            <tr>
               <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Phone Number:</strong></td>
               <td style="padding: 8px; border-bottom: 1px solid #eee;">' . htmlspecialchars($number) . '</td>
            </tr>
            <tr>
               <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Email:</strong></td>
               <td style="padding: 8px; border-bottom: 1px solid #eee;">' . htmlspecialchars($email) . '</td>
            </tr>
            <tr>
               <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Payment Method:</strong></td>
               <td style="padding: 8px; border-bottom: 1px solid #eee;">' . htmlspecialchars($method) . '</td>
            </tr>
            <tr>
               <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Address:</strong></td>
               <td style="padding: 8px; border-bottom: 1px solid #eee;">' . htmlspecialchars($address) . '</td>
            </tr>
            <tr>
               <td style="padding: 8px; border-bottom: 1px solid #eee; vertical-align: top;"><strong>Products:</strong></td>
               <td style="padding: 8px; border-bottom: 1px solid #eee;">' . htmlspecialchars($total_products) . '</td>
            </tr>
            <tr>
               <td style="padding: 10px 8px; font-weight: bold; background: #f7fafd; border-top: 2px solid #007BFF;">Total Amount:</td>
               <td style="padding: 10px 8px; font-weight: bold; background: #f7fafd; border-top: 2px solid #007BFF; color: #007BFF;">' . number_format($total_price, 0, ',', '.') . ' $</td>
            </tr>
         </table>
         <div style="background: #eaf6ff; border-left: 4px solid #007BFF; padding: 14px 18px; border-radius: 6px; margin-bottom: 18px;">
            <p style="margin: 0; color: #007BFF; font-size: 1rem;">
               Our sales team will contact you soon to confirm your order and arrange delivery.
            </p>
         </div>
         <p style="margin-bottom: 0;">If you have any questions, feel free to reply to this email or contact our support team.</p>
         <p style="margin-top: 28px; color: #888;">Best regards,<br><strong>Electronics Store Team</strong></p>
      </div>
      <div style="background: #f7fafd; text-align: center; padding: 14px 0; color: #aaa; font-size: 0.95rem;">
         &copy; ' . date('Y') . ' Electronics Store. All rights reserved.
      </div>
   </div>
</div>
';


          $mail->send();
          $message[] = 'Đơn hàng đã được đặt thành công và email xác nhận đã được gửi!';
      } catch (Exception $e) {
          $message[] = "Đơn hàng đã đặt thành công nhưng không gửi được email. Lỗi: {$mail->ErrorInfo}";
      }

   }else{
      $message[] = 'Giỏ hàng của bạn đang trống!';
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Your Orders</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch()){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= '$'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
         }else{
            echo '<p class="empty">your cart is empty!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <div class="grand-total">Grand Total : <span>$.<?= $grand_total; ?>/-</span></div>
      </div>

      <h3>place your orders</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Name:</span>
            <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Your Number :</span>
            <input type="number" name="number" placeholder="enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>Your Email :</span>
            <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>How would you like to pay? :</span>
            <select name="method" class="box" required>
               <option value="Cash on delivery">Cash On Delivery</option>
               <option value="Credit card">Credit Card</option>
               <option value="Paypal">PayPal</option>
               <option value="Bank transfer">Bank Transfer</option>
               <option value="Momo">MoMo</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address line 01 :</span>
            <input type="text" name="flat" placeholder="e.g. Flat number" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Address line 02 :</span>
            <input type="text" name="street" placeholder="Street name" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" placeholder="Hanoi" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Province:</span>
            <input type="text" name="state" placeholder="Thanh xuan" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Country :</span>
            <input type="text" name="country" placeholder="Hanoi" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>ZIP CODE :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 56400" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>