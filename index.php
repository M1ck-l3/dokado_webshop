<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome to the home page. ";
if ($_SESSION['user_role'] == 1) {
    echo "You are an admin. <a href='admin_panel.php'>Go to admin panel</a>";
}
echo " <a href='logout.php'>Logout</a>";
?>
