<?php 
    if(!isset($_SESSION['active']) && !strpos($_SERVER['REQUEST_URI'],"item.php")){
        header("location: home.php");
        // echo "a";
    }
    else{
        if(isset($_SESSION['active']) ){
            $accountid = $_SESSION['active']['Account_ID'];
            if($conn->query("SELECT * FROM store s WHERE s.Account_ID = '$accountid'")->num_rows == 0){
                if(strpos($_SERVER['REQUEST_URI'],"storechat")||strpos($_SERVER['REQUEST_URI'],"additem")||strpos($_SERVER['REQUEST_URI'],"mystore"))
                    header("location: home.php");
            }
        }
        // echo "b";
    }
    if(isset($_REQUEST['Item_ID'])){
        $item = $conn->query("SELECT * FROM item WHERE Item_ID = '$_REQUEST[Item_ID]'")->fetch_assoc();
        if($item['Item_Status'] == -1)
            header("location: home.php");
    }
    // echo "coba";
?>