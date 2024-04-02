<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

$sql_user = "SELECT * FROM user_id WHERE username = '$username'";
$result_user = executeQuery($conn, $sql_user);

if(mysqli_num_rows($result_user) > 0) {
    $user_row = mysqli_fetch_assoc($result_user);
    $userid = $user_row['userid'];
    $userbalance = $user_row['balance'];
}

//Gets all rows in cart that matches current user_id
$getuserCart = mysqli_query($conn, "SELECT * FROM carts WHERE user_id = '$userid'");

//Starting from first row until last 
while ($checkout=mysqli_fetch_assoc($getuserCart) )
{
    if(isset($checkout['item_id'])) {
        $item_id = $checkout['item_id'];
        $sql_item = "SELECT * FROM individual_clothes WHERE id = $item_id";
        $result_item = executeQuery($conn, $sql_item);
        
        if(mysqli_num_rows($result_item) > 0) {
            $item = mysqli_fetch_assoc($result_item);
            $itemquantity = $item['available_quantity'];
            $itemprice = $item['price'];
    
            if( ($itemquantity >= 1 && $itemquantity >= $checkout['quantity']) && $userbalance >= $itemprice * $checkout['quantity']) {

                $sql_user = "SELECT * FROM user_id WHERE username = '$username'";
                $result_user = executeQuery($conn, $sql_user);
                
                if(mysqli_num_rows($result_user) > 0) {
                    $user_row = mysqli_fetch_assoc($result_user);
                    $userid = $user_row['userid'];
                    $userbalance = $user_row['balance'];
                }

                $newavailablequantity = $itemquantity - $checkout['quantity'];
                $newsoldquantity = $item['sold_quantity'] + $checkout['quantity'];
                $newuserbalance = $userbalance - ($itemprice * $checkout['quantity']);
    
                $studQuery = "UPDATE individual_clothes SET available_quantity=?, sold_quantity=? WHERE id=?";
                $stmt = mysqli_prepare($conn, $studQuery);
                mysqli_stmt_bind_param($stmt, "iii", $newavailablequantity, $newsoldquantity, $item_id);
    
                mysqli_stmt_execute($stmt);
    
                $studQuery2 = "INSERT INTO orders (order_id, user_id, total_price, order_date) VALUES (NULL, ?, ?, NOW());";
                $stmt2 = mysqli_prepare($conn, $studQuery2);
                mysqli_stmt_bind_param($stmt2, "id", $userid, $itemprice);
    
                mysqli_stmt_execute($stmt2);
                $latestOrderId = mysqli_insert_id($conn);
    
                $studQuery3 = "INSERT INTO order_items (order_item_id, order_id, item_id, quantity, price_per_unit, subtotal) VALUES (NULL, ?, ?, 1, ?, ?);";
                $stmt3 = mysqli_prepare($conn, $studQuery3);
                mysqli_stmt_bind_param($stmt3, "iidd", $latestOrderId, $item_id, $itemprice, $itemprice);
                mysqli_stmt_execute($stmt3);
    
                $studQuery4 = "UPDATE user_id SET balance=? WHERE userid=?";
                $stmt4 = mysqli_prepare($conn, $studQuery4);
                mysqli_stmt_bind_param($stmt4, "di", $newuserbalance, $userid);
    
                mysqli_stmt_execute($stmt4);

                $studQuery4 = "UPDATE user_id SET balance=? WHERE userid=?";
                $stmt4 = mysqli_prepare($conn, $studQuery4);
                mysqli_stmt_bind_param($stmt4, "di", $newuserbalance, $userid);
    
                mysqli_stmt_execute($stmt4);

                $studQuery5 = "DELETE FROM carts WHERE item_id=?";
                $stmt5 = mysqli_prepare($conn, $studQuery5);
                mysqli_stmt_bind_param($stmt5, "i", $item_id);
    
                mysqli_stmt_execute($stmt5);

            } 
            //ELSE INVALID QUANTITY OR PRICE
            else {
                $_SESSION['checkouterror'] = true;
                header("Location: index.php");
                exit();
            }
        }
        //ELSE WALANG STOCK YUNG TINRY BILHIN NI USER 
        else {
            $_SESSION['checkouterror'] = true;
            header("Location: index.php");
            exit();
        } 
    }
    //ITEM DOES NOT EXIST
    else {
        header("Location: index.php");
        exit();
    } 
}
    
    $_SESSION['itemcheckout'] = true;
    //CHECKOUT NA MISMO
    
    header("Location: index.php");
    exit();
?>
