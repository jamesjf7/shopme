<?php
    require_once "conn.php";
    include "redirect.php"; 
    $acc_id = $_REQUEST['account_id'];
    $store = $conn->query(
        "SELECT * FROM store s
        INNER JOIN account a ON s.account_id = a.account_id 
        WHERE s.account_id = $acc_id
    ")->fetch_assoc();

    $items = $conn->query(
        "SELECT * FROM item i 
        INNER JOIN store s ON i.store_ID = s.store_ID
        WHERE i.store_ID = {$store['Store_ID']} AND i.Item_Status = 0
        ORDER BY i.item_registerdate DESC
    ")->fetch_all(MYSQLI_ASSOC);


    if(isset($items[0])){
        $orders = $conn->query(
            "SELECT * FROM dtransaction d INNER JOIN htransaction h ON d.Htransaction_ID = h.Htransaction_ID
            WHERE d.Item_ID = {$items[0]['Item_ID']} AND '{$items[0]['Item_RegisterDate']}' < h.Htransaction_OrderDate
            ORDER BY h.Htransaction_OrderDate ASC"
        )->fetch_all(MYSQLI_ASSOC);
    
        $time = strtotime($items[0]['Item_RegisterDate']);
        foreach ($orders as $key => $order) {
            $n = strtotime($order['Htransaction_OrderDate']);
            if($n - $time < 10000)
                $time = $n + 10000;
        }
        $value = strtotime("now") - $time;
        $valid = $value < 10000;
        if(!$valid){
            $conn->query(
                "UPDATE item i SET item_status = -1 WHERE item_id = {$items[0]['Item_ID']}"
            );
            header("Location: store.php?acount_id=" . $acc_id);
        }
        echo $value;
    }
    // echo strtotime($items[0]['Item_RegisterDate']);
?>

<h1><?=$store['Store_Name']?></h1>
<p><?=$store['Store_Description']?></p>
<div class="row">
    <div class="card-columns">  
    <?php foreach ($items as $key => $item) {?>
        <div class="card bg-dark">
            <div class="card-header text-light">
                <h3><?=$item['Item_Name']?></h3>
            </div>
            <div class="card-body bg-light">
                Description: 
                <p><?=$item['Item_Description']?></p>
                <p>Price: <?=$item['Item_Price']?></p>
                <p>Stock: <?=$item['Item_Stock']?></p>
                <div class="btn-block btn-group">
                    <button class="btn btn-primary" onclick="editItem(<?=$item['Item_ID']?>)">Edit</button>
                    <button class="btn btn-danger" onclick="deleteItem(<?=$item['Item_ID']?>)">Delete</button>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
    <?php
        //kalo g ada item yang dijual
        if(count($items) == 0){
            echo "<h5 class='text-secondary mx-auto my-5'>You Don't Sell Any Item</h5>";    
        }
    ?>
</div>
<div class="row">
    <div class="col-12">
        <a href="additem.php" class="btn btn-block btn-success">ADD ITEM</a>
        <br>
    </div>
</div>