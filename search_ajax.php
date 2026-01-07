<?php
include 'components/connect.php';

$search = $_POST['search'] ?? '';

if (!empty($search)) {
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ?");
   $search_term = "%$search%";
   $select_products->execute([$search_term]);

   if ($select_products->rowCount() > 0) {
      while ($fetch_product = $select_products->fetch()) {
         ?>
         <div class="search-result-item" style="display: flex; border: 1px solid #ccc; border-radius: 8px; margin: 10px 0; padding: 10px; align-items: center;">
            <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" style="flex-shrink: 0;">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="" style="width: 120px; height: auto; border-radius: 6px; object-fit: cover;">
            </a>
            <div class="search-result-content" style="margin-left: 15px;">
               <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="product-name" style="font-size: 18px; font-weight: bold; color: #333; text-decoration: none;">
                  <?= $fetch_product['name']; ?>
               </a>
               <div class="price-row" style="margin-top: 8px; display: flex; gap: 10px; align-items: center;">
                  <div class="price" style="color: green; font-weight: bold;">$<?= $fetch_product['price']; ?></div>
                  <?php if (!empty($fetch_product['old_price'])): ?>
                     <div class="old-price" style="text-decoration: line-through; color: #999;">$<?= $fetch_product['old_price']; ?></div>
                  <?php endif; ?>
                  <?php if (!empty($fetch_product['discount'])): ?>
                     <div class="discount" style="color: red;">-<?= $fetch_product['discount']; ?>%</div>
                  <?php endif; ?>
               </div>
               <?php if (!empty($fetch_product['gift'])): ?>
                  <div class="gift" style="margin-top: 5px; color: #555;">🎁 <?= $fetch_product['gift']; ?></div>
               <?php endif; ?>
            </div>
         </div>
         <?php
      }
} else {
   ?>
   <div class="search-result-item" style="display: flex; border: 1px solid #ccc; border-radius: 8px; margin: 10px 0; padding: 15px; align-items: center; background-color: #f9f9f9; color: #555;">
      <div style="margin: 0 auto; font-size: 18px;">No products found!</div>
   </div>
   <?php
}
}
?>
