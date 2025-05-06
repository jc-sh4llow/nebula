<?php
// Include database connection
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
// Include authentication functions
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/auth_functions.php');
// Include utility functions
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php');

// Get categories for dropdown menu
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = query($categories_query);
$categories = fetch_all($categories_result);

// Get cart item count if user is logged in
$cart_count = 0;
if (is_logged_in() && isset($_SESSION['cart_id'])) {
    $cart_id = $_SESSION['cart_id'];
    $cart_query = "SELECT SUM(quantity) as total FROM cart_items WHERE cart_id = $cart_id";
    $cart_result = query($cart_query);
    $cart_data = fetch_one($cart_result);
    $cart_count = $cart_data['total'] ?: 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous"
  >
  <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Nebulus Bookstore</title>
  <link rel="stylesheet" href="/assets/css/main.css">
  <?php if (isset($additional_css)): ?>
    <link rel="stylesheet" href="<?php echo $additional_css; ?>">
  <?php endif; ?>
</head>
<body>
  <header>
    <div class="nav-left">
      <button>☰</button>
      <div class="dropdown">
        <button class="dropbtn">Genre ▾</button>
        <div class="dropdown-content">
          <?php foreach ($categories as $category): ?>
          <a href="/books/category.php?slug=<?php echo $category['slug']; ?>"><?php echo $category['name']; ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <a href="/index.php"><button>Home</button></a>
    </div>
    <div class="search-bar">
      <form action="/books/index.php" method="GET">
        <input type="text" name="search" placeholder="Search books" />
      </form>
    </div>
    <div class="nav-right">
      <a href="/books/index.php"><button>Books</button></a>
      <a href="/cart/index.php"><button>🛒 <?php if ($cart_count > 0): ?><span class="badge"><?php echo $cart_count; ?></span><?php endif; ?></button></a>
      <?php if (is_logged_in()): ?>
        <div class="dropdown">
          <button class="dropbtn"><?php echo $_SESSION['username']; ?> ▾</button>
          <div class="dropdown-content">
            <a href="/auth/profile.php">My Profile</a>
            <?php if (is_admin()): ?>
            <a href="/admin/index.php">Admin Panel</a>
            <?php endif; ?>
            <a href="/auth/logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="/auth/login.php"><button>Login</button></a>
      <?php endif; ?>
    </div>
  </header>