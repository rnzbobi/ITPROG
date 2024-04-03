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
$sql = "SELECT receipt.*, 
        receipt.price AS priceperitem,
        individual_clothes.*, 
        combo_clothes.* 
        FROM receipt
        LEFT JOIN individual_clothes ON individual_clothes.id = receipt.item_id 
        LEFT JOIN combo_clothes ON combo_clothes.combo_id = receipt.combo_id 
        ORDER BY receiptid;";
        $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .receipt {
            margin-top: 20px;
        }
        .item {
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item p {
            margin: 5px 0;
            color: #666;
        }
        .item:hover {
            transform: scale(1.02);
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
            color: #333;
        }
        .ok-button {
            display: block;
            width: 100%;
            margin-top: 40px;
            padding: 15px 0;
            background-color: #000000;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }
        .ok-button:hover {
            background-color: #444;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Purchase Receipt</h1>
        <div class="receipt">
            <?php while ($purchases = mysqli_fetch_assoc($result)): ?>
                <?php $comboid = $purchases['combo_id']; ?>
                <div class="item">
                    <?php if($comboid !== null) { ?>
                        <p><strong>Date:</strong> <?php echo $purchases['receipt_date']; ?></p>
                        <p><strong>Product Name:</strong> <?php echo $purchases['combo_name']; ?></p>
                        <p><strong>Quantity:</strong> <?php echo $purchases['quantity']; ?></p>
                        <p><strong>Price:</strong> $<?php echo number_format($purchases['quantity'] * $purchases['priceperitem'], 2); ?></p>
                    <?php } else { ?>
                        <p><strong>Date:</strong> <?php echo $purchases['receipt_date']; ?></p>
                        <p><strong>Product Name:</strong> <?php echo $purchases['name']; ?></p>
                        <p><strong>Brand:</strong> <?php echo $purchases['brand']; ?></p>
                        <p><strong>Category:</strong> <?php echo $purchases['category']; ?></p>
                        <p><strong>Color:</strong> <?php echo $purchases['color']; ?></p>
                        <p><strong>Size:</strong> <?php echo $purchases['size']; ?></p>
                        <p><strong>Quantity:</strong> <?php echo $purchases['quantity']; ?></p>
                        <p><strong>Price:</strong> $<?php echo number_format($purchases['quantity'] * $purchases['priceperitem'], 2); ?></p>
                    <?php } ?>
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
        <p class="footer">Thank you for shopping with us!</p>
    </div>
</body>
</html>