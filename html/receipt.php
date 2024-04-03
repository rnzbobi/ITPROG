<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

$purchases = array();
$sql = "SELECT * FROM receipt JOIN individual_clothes ON individual_clothes.id=receipt.item_id ORDER BY receiptid";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        h1 {
            text-align: center;
        }
        .receipt {
            margin-top: 20px;
        }
        .item {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .item p {
            margin: 5px 0;
        }
        .total {
            margin-top: 20px;
            text-align: right;
        }
        .ok-button {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Purchase Receipt</h1>
        <div class="receipt">
            <?php
            while ($purchases = mysqli_fetch_assoc($result)): ?>
                <div class="item">
                    <p><strong>Date:</strong> <?php echo $purchases['receipt_date']; ?></p>
                    <p><strong>Product Name:</strong> <?php echo $purchases['name']; ?></p>
                    <p><strong>Brand:</strong> <?php echo $purchases['brand']; ?></p>
                    <p><strong>Category:</strong> <?php echo $purchases['category']; ?></p>
                    <p><strong>Color:</strong> <?php echo $purchases['color']; ?></p>
                    <p><strong>Size:</strong> <?php echo $purchases['size']; ?></p>
                    <p><strong>Quantity:</strong> <?php echo $purchases['quantity']; ?></p>
                    <p><strong>Price:</strong> $<?php echo number_format($purchases['quantity'] * $purchases['price'], 2); ?></p>
                </div>
                <?php $total = $purchases['subtotal']; ?>
            <?php endwhile; ?>
            <div class="total">
                <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
            </div>
        </div>
        <form method="POST" action="delete_receipt.php">
            <button type="submit" class="ok-button" name="delete_receipt">OK</button>
        </form>
    </div>
</body>
</html>
