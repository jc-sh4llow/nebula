<?php
// Database connection configuration
define('DB_HOST', 'sql206.infinityfree.com'); // InfinityFree's MySQL server
define('DB_USER', 'if0_38911460'); // Your InfinityFree MySQL username 
define('DB_PASS', 'your_password_here'); // Replace with your actual password
define('DB_NAME', 'if0_38911460_nebula'); // Your database name

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to ensure proper handling of special characters
mysqli_set_charset($conn, "utf8mb4");

// Function to handle database queries and return results
function query($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    return $result;
}

// Function to fetch all rows from a query result
function fetch_all($result) {
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Function to fetch a single row from query result
function fetch_one($result) {
    return mysqli_fetch_assoc($result);
}

// Function to escape strings for database queries to prevent SQL injection
function escape_string($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

// Function to get last inserted ID
function last_id() {
    global $conn;
    return mysqli_insert_id($conn);
}
?>