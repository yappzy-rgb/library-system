<?php

define('DB_HOST', 'sql303.infinityfree.com');    
define('DB_USER', 'if0_41581192');              
define('DB_PASS', 'LMSJULY262006');            
define('DB_NAME', 'if0_41581192_library_db');  


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>