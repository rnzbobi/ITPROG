<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

if(isset($_GET['item_id'])) {
    $combo_id = $_GET['item_id'];
    $sql_combo = "SELECT * FROM combo_clothes WHERE combo_id = $combo_id";
    $result_combo = executeQuery($conn, $sql_combo);
    
    if(mysqli_num_rows($result_combo) > 0) {
        $combo = mysqli_fetch_assoc($result_combo);
        $item1 = $combo['item_id1'];
        $item2 = $combo['item_id2'];
        $item3 = $combo['item_id3'];
        $item4 = $combo['item_id4'];

        $comboquantity = $combo['available_quantity'];
        $comboprice = $combo['price'];

        $username = $_SESSION['username'];
        $sql_user = "SELECT * FROM user_id WHERE username = '$username'";
        $result_user = executeQuery($conn, $sql_user);

        if(mysqli_num_rows($result_user) > 0) {
            $user_row = mysqli_fetch_assoc($result_user);
            $userid = $user_row['userid'];
            $userbalance = $user_row['balance'];
        }

        if($item1 !== null) {
            $sql_item1 = "SELECT * FROM individual_clothes WHERE id = '$item1'";
            $result_item1 = executeQuery($conn, $sql_item1);

            if(mysqli_num_rows($result_item1) > 0) {
                $item1_row = mysqli_fetch_assoc($result_item1);
                $item1quantity = $item1_row['available_quantity'];
            }
        }

        if($item2 !== null) {
            $sql_item2 = "SELECT * FROM individual_clothes WHERE id = '$item2'";
            $result_item2 = executeQuery($conn, $sql_item2);

            if(mysqli_num_rows($result_item2) > 0) {
                $item2_row = mysqli_fetch_assoc($result_item2);
                $item2quantity = $item2_row['available_quantity'];
            }
        }

        if($item3 !== null) {
            $sql_item3 = "SELECT * FROM individual_clothes WHERE id = '$item3'";
            $result_item3 = executeQuery($conn, $sql_item3);

            if(mysqli_num_rows($result_item3) > 0) {
                $item3_row = mysqli_fetch_assoc($result_item3);
                $item3quantity = $item3_row['available_quantity'];
            }
        }

        if($item4 !== null) {
            $sql_item4 = "SELECT * FROM individual_clothes WHERE id = '$item4'";
            $result_item4 = executeQuery($conn, $sql_item4);

            if(mysqli_num_rows($result_item4) > 0) {
                $item4_row = mysqli_fetch_assoc($result_item4);
                $item4quantity = $item4_row['available_quantity'];
            }
        }

        if($comboquantity >= 1 &&
            $userbalance >= $comboprice &&
            (
            (($item1 !== null) + ($item2 !== null) + ($item3 !== null) + ($item4 !== null)) >= 2 &&
            (($item1 !== null && $item1quantity >= 1) || $item1 === null) &&
            (($item2 !== null && $item2quantity >= 1) || $item2 === null) &&
            (($item3 !== null && $item3quantity >= 1) || $item3 === null) &&
            (($item4 !== null && $item4quantity >= 1) || $item4 === null)
            )
        ) {
            $newcomboavailablequantity = $comboquantity - 1;
            $newcombosoldquantity = $combo['sold_quantity'] + 1;
            $newuserbalance = $userbalance - $comboprice;

            $updateCombo = "UPDATE combo_clothes SET available_quantity=?, sold_quantity=? WHERE combo_id=?";
            $combostmt = mysqli_prepare($conn, $updateCombo);
            mysqli_stmt_bind_param($combostmt, "iii", $newcomboavailablequantity, $newcombosoldquantity, $combo_id);

            mysqli_stmt_execute($combostmt);

            if($item1 !== null) {
                $item1newavailquantity = $item1quantity - 1;
                $item1newsoldquantity = $item1_row['sold_quantity'] + 1;
                $itemQuery1 = "UPDATE individual_clothes SET available_quantity=?, sold_quantity=? WHERE id=?";
                $itemstmt1 = mysqli_prepare($conn, $itemQuery1);
                mysqli_stmt_bind_param($itemstmt1, "iii", $item1newavailquantity, $item1newsoldquantity, $item1);

                mysqli_stmt_execute($itemstmt1);
            }
            
            if($item2 !== null) {
                $item2newavailquantity = $item2quantity - 1;
                $item2newsoldquantity = $item2_row['sold_quantity'] + 1;
                $itemQuery2 = "UPDATE individual_clothes SET available_quantity=?, sold_quantity=? WHERE id=?";
                $itemstmt2 = mysqli_prepare($conn, $itemQuery2);
                mysqli_stmt_bind_param($itemstmt2, "iii", $item2newavailquantity, $item2newsoldquantity, $item2);

                mysqli_stmt_execute($itemstmt2);
            }

            if($item3 !== null) {
                $item3ewavailquantity = $item3quantity - 1;
                $item3newsoldquantity = $item3_row['sold_quantity'] + 1;
                $itemQuery3 = "UPDATE individual_clothes SET available_quantity=?, sold_quantity=? WHERE id=?";
                $itemstmt3 = mysqli_prepare($conn, $itemQuery3);
                mysqli_stmt_bind_param($itemstmt3, "iii", $item3newavailquantity, $item3newsoldquantity, $item3);

                mysqli_stmt_execute($itemstmt3);
            }

            if($item4 !== null) {
                $item4newavailquantity = $item4quantity - 1;
                $item4newsoldquantity = $item4_row['sold_quantity'] + 1;
                $itemQuery4 = "UPDATE individual_clothes SET available_quantity=?, sold_quantity=? WHERE id=?";
                $itemstmt4 = mysqli_prepare($conn, $itemQuery4);
                mysqli_stmt_bind_param($itemstmt4, "iii", $item4newavailquantity, $item4newsoldquantity, $item4);

                mysqli_stmt_execute($itemstmt4);
            }

            $studQuery2 = "INSERT INTO orders (order_id, user_id, total_price, order_date) VALUES (NULL, ?, ?, NOW());";
            $stmt2 = mysqli_prepare($conn, $studQuery2);
            mysqli_stmt_bind_param($stmt2, "id", $userid, $comboprice);

            mysqli_stmt_execute($stmt2);
            $latestOrderId = mysqli_insert_id($conn);

            $studQuery3 = "INSERT INTO order_items (order_item_id, order_id, item_id, combo_id, quantity, price_per_unit, subtotal) VALUES (NULL, ?, NULL, ?, 1, ?, ?);";
            $stmt3 = mysqli_prepare($conn, $studQuery3);
            mysqli_stmt_bind_param($stmt3, "iidd", $latestOrderId, $combo_id, $comboprice, $comboprice);
            mysqli_stmt_execute($stmt3);

            $studQuery4 = "UPDATE user_id SET balance=? WHERE userid=?";
            $stmt4 = mysqli_prepare($conn, $studQuery4);
            mysqli_stmt_bind_param($stmt4, "di", $newuserbalance, $userid);

            mysqli_stmt_execute($stmt4);

            $studQuery5 = "INSERT INTO receipt (receiptid, item_id, combo_id, quantity, price, subtotal, receipt_date) VALUES (NULL, NULL, ?, 1, ?, ?, NOW());";
            $stmt5 = mysqli_prepare($conn, $studQuery5);
            mysqli_stmt_bind_param($stmt5, "idd", $combo_id, $comboprice, $comboprice);
    
            mysqli_stmt_execute($stmt5);

            $_SESSION['itemcheckout'] = true;

            header("Location: receipt.php");
            exit();
        } else {
            $_SESSION['checkouterror'] = true;
            header("Location: index.php");
            exit();
        }
    } else {
        // Item not found
        header("Location: index.php");
        exit();
    }
} else {
    // Item ID not provided
    header("Location: index.php");
    exit();
}
?>