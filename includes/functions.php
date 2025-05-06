<?php
// Format price with PHP sign and proper formatting
function format_price($price) {
    return '₱' . number_format($price, 2) . ' PHP';
}

// Check if a book is in stock
function is_in_stock($stock_quantity) {
    return $stock_quantity > 0;
}

// Get all books
function get_all_books($limit = null, $offset = 0) {
    $sql = "SELECT books.*, categories.name as category_name 
            FROM books 
            LEFT JOIN categories ON books.category_id = categories.category_id 
            ORDER BY books.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT $offset, $limit";
    }
    
    $result = query($sql);
    return fetch_all($result);
}

// Get featured books
function get_featured_books($limit = 8) {
    $sql = "SELECT books.*, categories.name as category_name 
            FROM books 
            LEFT JOIN categories ON books.category_id = categories.category_id 
            WHERE books.is_featured = 1 
            ORDER BY books.created_at DESC 
            LIMIT $limit";
    
    $result = query($sql);
    return fetch_all($result);
}

// Get new releases
function get_new_releases($limit = 3) {
    $sql = "SELECT books.*, categories.name as category_name 
            FROM books 
            LEFT JOIN categories ON books.category_id = categories.category_id 
            WHERE books.is_new_release = 1 
            ORDER BY books.created_at DESC 
            LIMIT $limit";
    
    $result = query($sql);
    return fetch_all($result);
}

// Get bestsellers
function get_bestsellers($limit = 3) {
    $sql = "SELECT books.*, categories.name as category_name 
            FROM books 
            LEFT JOIN categories ON books.category_id = categories.category_id 
            WHERE books.is_bestseller = 1 
            ORDER BY books.created_at DESC 
            LIMIT $limit";
    
    $result = query($sql);
    return fetch_all($result);
}

// Get book by ID
function get_book_by_id($book_id) {
    $book_id = (int) $book_id;
    $sql = "SELECT books.*, categories.name as category_name 
            FROM books 
            LEFT JOIN categories ON books.category_id = categories.category_id 
            WHERE books.book_id = $book_id";
    
    $result = query($sql);
    
    if (mysqli_num_rows($result) == 0) {
        return null;
    }
    
    return fetch_one($result);
}

// Get books by category
function get_books_by_category($category_slug, $limit = null, $offset = 0) {
    $category_slug = escape_string($category_slug);
    
    $sql = "SELECT books.*, categories.name as category_name 
            FROM books 
            LEFT JOIN categories ON books.category_id = categories.category_id 
            WHERE categories.slug = '$category_slug' 
            ORDER BY books.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT $offset, $limit";
    }
    
    $result = query($sql);
    return fetch_all($result);
}

// Get category by slug
function get_category_by_slug($slug) {
    $slug = escape_string($slug);
    
    $sql = "SELECT * FROM categories WHERE slug = '$slug'";
    $result = query($sql);
    
    if (mysqli_num_rows($result) == 0) {
        return null;
    }
    
    return fetch_one($result);
}

// Search books
function search_books($search_term, $limit = null, $offset = 0) {
    $search_term = escape_string($search_term);
    
    $sql = "SELECT books.*, categories.name as category_name 
            FROM books 
            LEFT JOIN categories ON books.category_id = categories.category_id 
            WHERE books.title LIKE '%$search_term%' 
            OR books.description LIKE '%$search_term%' 
            ORDER BY books.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT $offset, $limit";
    }
    
    $result = query($sql);
    return fetch_all($result);
}

// Add book to cart
function add_to_cart($book_id, $quantity = 1) {
    if (!is_logged_in()) {
        return false;
    }
    
    $cart_id = $_SESSION['cart_id'];
    $book_id = (int) $book_id;
    $quantity = (int) $quantity;
    
    // Check if book exists and is in stock
    $book = get_book_by_id($book_id);
    if (!$book || !is_in_stock($book['stock_quantity'])) {
        return false;
    }
    
    // Check if book is already in cart
    $check_sql = "SELECT * FROM cart_items WHERE cart_id = $cart_id AND book_id = $book_id";
    $check_result = query($check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update quantity
        $item = fetch_one($check_result);
        $new_quantity = $item['quantity'] + $quantity;
        
        // Make sure we don't exceed stock
        if ($new_quantity > $book['stock_quantity']) {
            $new_quantity = $book['stock_quantity'];
        }
        
        $update_sql = "UPDATE cart_items SET quantity = $new_quantity WHERE item_id = {$item['item_id']}";
        query($update_sql);
    } else {
        // Add new item
        $sql = "INSERT INTO cart_items (cart_id, book_id, quantity) VALUES ($cart_id, $book_id, $quantity)";
        query($sql);
    }
    
    return true;
}

// Get cart items
function get_cart_items() {
    if (!is_logged_in() || !isset($_SESSION['cart_id'])) {
        return [];
    }
    
    $cart_id = $_SESSION['cart_id'];
    
    $sql = "SELECT cart_items.*, books.title, books.price, books.image_path, books.stock_quantity 
            FROM cart_items 
            JOIN books ON cart_items.book_id = books.book_id 
            WHERE cart_items.cart_id = $cart_id";
    
    $result = query($sql);
    return fetch_all($result);
}

// Calculate cart total
function calculate_cart_total() {
    $items = get_cart_items();
    $total = 0;
    
    foreach ($items