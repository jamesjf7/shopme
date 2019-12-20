<?php
    include_once("conn.php");
    include_once("library/lib.php");
    include "redirect.php"; 
    if(isset($_POST["checkout"]) && isset($_SESSION['Item']) && count($_SESSION['Item'])){  
        $accountid = $_SESSION['active']['Account_ID'];
        $tot = 0;
        foreach($_SESSION['Item'] as $item){
            $conn->query("UPDATE item SET Item_Qty = Item_Qty - $item[Item_Qty] WHERE Item_ID = '$item[Item_ID]'");
            $total[$item['Store_ID']] = $total[$item['Store_ID']]??0;
            $total[$item['Store_ID']] +=  $item['Item_Price']*$item['Item_Qty'];
            $tot += $item['Item_Price']*$item['Item_Qty'];
        }
        if($_SESSION['active']['Account_Balance']-$tot >= 0){
            $conn->query("UPDATE account SET Account_Balance = Account_Balance-$tot WHERE Account_ID = '$accountid'");
            foreach ($total as $key=>$value) {
                $date = date("y-m-d H:i:s");
                $conn->query("INSERT INTO htransaction values('','$accountid','$key','$date','',0,'$value')");
                $htransid = $conn->insert_id;
                foreach ($_SESSION['Item'] as $item) {
                    if($item['Store_ID'] == $key){
                        $conn->query("INSERT INTO dtransaction VALUES('$htransid','$item[Item_ID]',$item[Item_Price],$item[Item_Qty],$item[Item_Price]*$item[Item_Qty],'0')");
                    }
                }
            }
            unset($_SESSION['Item']);
        }else{
            echo "<script>alert('Balance anda tidak cukup')</script>";
        }
        // echo "$conn->error";
        //dtransaction_status = 0 --> blm di rate , 1 --> udh di rate
    }
    // var_dump($_SESSION['Item']);
?>
<html>
    <script>
        //key punya $_SESSION['Item']
        function addqty(key){
            $("#listshoppingcart").load("control.php?addqty="+key);
        }
        function minqty(key){
            $("#listshoppingcart").load("control.php?minqty="+key);
        }
        function removeqty(key){
            $("#listshoppingcart").load("control.php?removeqty="+key);
        }
        $(()=>{
            $('#listshoppingcart').load("control.php?addqty=show");
        })
    </script>
    <?php include_once("header.php");?>
    <div class="container">
        <div id="listshoppingcart"></div>
        <?php 
            if(isset($_SESSION['Item'])){
                if(count($_SESSION['Item'])){
                    echo "
                    <form action='' method='post'>
                        <button type='submit' name='checkout' class='btn btn-dark form-control'>Checkout</button>
                    </form>";
                }else{
                    echo "
                    <form action='home.php' method='post'>
                        <button type='submit' class='btn btn-dark form-control'>Lanjutkan Belanja</button>
                    </form>";    
                }
            }else{
                echo "
                <form action='home.php' method='post'>
                    <button type='submit' class='btn btn-dark form-control'>Lanjutkan Belanja</button>
                </form>";
            }
        ?>
            
        
    </div>


    <?php include_once("footer.php");?>

</html>