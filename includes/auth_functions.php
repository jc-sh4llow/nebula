<?php
session_start();

// Function to register a new user
function register_user($username, $email, $password, $first_name = '', $last_name = '') {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $username = escape_string($username);
    $email = escape_string($email);
    $first_name = escape_string($first_name);
    $last_name = escape_string($last_name);
    
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = query($check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        return false; // User already exists
    }
    
    $sql = "INSERT INTO users (username, email, password, first_name, last_name) 
            VALUES ('$username', '$email', '$hashed_password', '$first_name', '$last_name')";
    
    query($sql);
    return true;
}

// Function to log in a user
function login_user($username_or_email, $password) {
    $username_or_email = escape_string($username_or_email);
    
    // Check if login is by username or email
    $field = strpos($username_or_email, '@') ? 'email' : 'username';
    
    $sql = "SELECT * FROM users WHERE $field = '$username_or_email'";
    $result = query($sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = fetch_one($result);
        
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            // Initialize or load cart
            initialize_cart($user['user_id']);
            
            return true;
        }
    }
    
    return false;
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return is_logged_in() && $_SESSION['is_admin'] == 1;
}

// Function to log out a user
function logout_user() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to home page
    header("Location: /index.php");
    exit;
}

// Function to initialize cart for a logged-in user
function initialize_cart($user_id) {
    // Check if user already has a cart
    $sql = "SELECT * FROM carts WHERE user_id = $user_id";
    $result = query($sql);
    
    if (mysqli_num_rows($result) == 0) {
        // Create a new cart
        $sql = "INSERT INTO carts (user_id) VALUES ($user_id)";
        query($sql);
        
        // Get the cart ID
        $_SESSION['cart_id'] = last_id();
    } else {
        $cart = fetch_one($result);
        $_SESSION['cart_id'] = $cart['cart_id'];
    }
}

// Function to get current user
function get_current_user() {
    if (!is_logged_in()) {
        return null;
    }
    
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = $user_id";
    $result = query($sql);
    
    return fetch_one($result);
}
?>