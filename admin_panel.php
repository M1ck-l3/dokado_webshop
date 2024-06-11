<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: index.php");
    exit();
}

$host = "localhost";
$user = "#";
$password = "#";
$dbname = "dokado_db";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $picture = $_POST['picture'];
        
        $sql = "INSERT INTO products (name, price, picture) VALUES ('$name', '$price', '$picture')";
        if ($conn->query($sql) === TRUE) {
            echo "Product added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['edit_product'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $picture = $_POST['picture'];
        
        $sql = "UPDATE products SET name='$name', price='$price', picture='$picture' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Product updated successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['delete_product'])) {
        $id = $_POST['id'];
        
        $sql = "DELETE FROM products WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Product deleted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <h2>Add Product</h2>
    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required><br>
        <label for="picture">Picture URL:</label>
        <input type="text" id="picture" name="picture" required><br>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h2>Edit Products</h2>
    <?php while ($product = $result->fetch_assoc()) { ?>
        <form action="" method="post" style="margin-bottom: 20px;">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required><br>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo $product['price']; ?>" required><br>
            <label for="picture">Picture URL:</label>
            <input type="text" id="picture" name="picture" value="<?php echo $product['picture']; ?>" required><br>
            <button type="submit" name="edit_product">Edit Product</button>
            <button type="submit" name="delete_product">Delete Product</button>
        </form>
    <?php } ?>

    <a href="index.php">Back to Home</a>
</body>
</html>

<?php
$conn->close();
?>
