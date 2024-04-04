<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

$getData=mysqli_query($conn, 
            "SELECT ic.id, ic.name, ic.size, ic.image_URL, SUM(oi.quantity) AS 'amount_sold'
            FROM order_items oi
            JOIN individual_clothes ic ON oi.item_id = ic.id 
            GROUP BY oi.item_id
            ORDER BY amount_sold DESC");


$xml = new DOMDocument("1.0", "UTF-8");

$summary = $xml->createElement("summary_sold");
$xml->appendChild($summary);

/*$dtd = $xml->createDocumentType('summary_sold', '', 'summary_sold_clothes.dtd');
$xml->appendChild($dtd);*/

while($obtainSummary=mysqli_fetch_assoc($getData)){
    $item = $xml->createElement("item");
    $item->setAttribute("id", $obtainSummary['id']);

    $name = $xml->createElement("name", htmlspecialchars($obtainSummary['name']));
    $item->appendChild($name);

    $size = $xml->createElement("size", $obtainSummary['size']);
    $item->appendChild($size);

    $image = $xml->createElement("image", $obtainSummary['image_URL']);
    $item->appendChild($image);

    $amountSold = $xml->createElement("amount_sold", $obtainSummary['amount_sold']);
    $item->appendChild($amountSold);

    $summary->appendChild($item);
}

$xml->formatOutput = true;
$xml->save("summary_sold_clothes.xml");
$xml = simplexml_load_file('summary_sold_clothes.xml') or die ("Error cannot create object!");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary of Sold Clothes</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel='stylesheet' href='css/signupstyle.css' type='text/css' />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body class="bodystyle">
<?php
echo "<div class='sum_sold_interface'>";
    echo "<div class='sum_sold-header'>";
        echo "<div class='sum_sold_title-left'>";
            echo "Image";
        echo "</div>";
        echo "<div class='sum_sold_title-mid'>";
            echo "Item and Size";
        echo "</div>";
        echo "<div class='sum_sold_title-right'>";
            echo "Amount sold";
        echo "</div>";
        echo "<div class='sum_sold_title-rank'>";
            echo "Rank";
        echo "</div>";
    echo "</div>";

for($x=0; $x<5; $x++){
    echo "<div class='sum_sold_container'>";
       
        echo "<div class = 'sum_sold_image-holder'>";
            echo "<img src='".$xml->item[$x]->image."'style='width: 100px; height: 100px;'>";
        echo "</div>";
        echo "<div class='sum_sold-item-details'>";
            echo "<div class ='sum_sold-item-name'>";
                echo "<b>".$xml->item[$x]->name."</b>";
            echo "</div>";
            echo "<div class ='sum_sold-placeholder'>";
                echo "<b>".$xml->item[$x]->size."</b>";
            echo "</div>";
        echo "</div>";
        echo "<div class='sum_sold_amount-holder'>";
                echo "<b>".$xml->item[$x]->amount_sold."</b>";
        echo "</div>";
        echo "<div class='sum_sold-rank'>";
                echo "<b>#".($x+1)."</b>";
        echo "</div>";
    echo "</div>";
}
echo "<a class='sum-regbutton' href='sellermode.php'>Back to Seller Mode</a>";
echo "</div>";

?>
</body>
</html>