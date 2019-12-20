<?php
    require_once "conn.php";
    //Davin
    if(isset($_REQUEST['table'])){
        $table = $_REQUEST['table'];
        $state = $_REQUEST['state'];
        if($state == 'insert'){
            $package = $_REQUEST['package'];
            echo insert($table,$package);
        }
    }
    if(isset($_REQUEST['additem'])){
        $storeid = $_REQUEST['StoreID'];
        $itemName = $_REQUEST['ItemName'];
        $itemPrice = $_REQUEST['ItemPrice'];
        $itemStock = $_REQUEST['ItemStock'];
        $itemDesc = $_REQUEST['ItemDescription'];
        $itemImage = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $registerDate = date("Y-m-d H:i:s");
        $conn->query("INSERT INTO item VALUES('','$storeid','$itemName','$itemPrice','$itemStock','$itemDesc','$itemImage','$registerDate','0','0')");
        echo $conn->insert_id;
    }
    if(isset($_REQUEST['addstore'])){
        $accountid = $_SESSION['active']['Account_ID'];
        $storename = $_REQUEST['StoreName'];
        $storedesc = $_REQUEST['StoreDesc'];
        $storeImage = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $registerDate = date("Y-m-d H:i:s");
        $conn->query("INSERT INTO store VALUES('','$accountid','$storename','$storedesc','$storeImage','$registerDate')");
        echo "$conn->error";
    }
    if(isset($_REQUEST['deleteitem'])){
        $itemid=$_REQUEST["itemid"];
        $conn->query("UPDATE htransaction h left OUTER JOIN dtransaction d on d.Htransaction_ID=h.Htransaction_ID LEFT OUTER JOIN item i on i.Item_ID=d.Item_ID SET h.Htransaction_Status=-1 WHERE i.Item_ID=$itemid AND h.Htransaction_Status=0;");
        $conn->query("UPDATE item set Item_Status=-1 where Item_ID = $itemid");
        header("location: mystore.php");
    }
    if(isset($_REQUEST['edititem'])){
        $itemid=$_REQUEST["itemid"];
        $_SESSION['activeitem'] = $conn->query("SELECT * FROM item WHERE Item_ID = '$itemid'")->fetch_assoc();
?>
                <div class="form-title text-center mb-5">
                    <h4>Edit Item</h4>
                </div>
                <div class="d-flex flex-column text-center">
                    <form action="" method="POST" enctype='multipart/form-data'>
                        <div style="border-radius:100%;width:100px;height:100px;" class="mx-auto ">
                            <?= '<img class="profile-pic" src="data:image/jpeg;base64,'.base64_encode($_SESSION['activeitem']['Item_Image']).'" style="border-radius:100%;width:100;height:100;"/>'?>
                        </div>
                        <label for="image"><i class="fa fa-camera"></i></label>
                        <input class="file-upload" type="file" name="image" id="image" style="display:none;" accept="image/*">
                        <div class="form-group">
                            <input type="text" class="form-control" id="editname" name="editname" placeholder="Item name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="editdesc" name="editdesc" placeholder="Description" required>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" id="editpassword" name="editprice" placeholder="Price" required>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" id="editstock" name="editstock" placeholder="Stock" required>
                        </div>
                        <button type="submit" class="btn btn-info btn-block btn-round" name="btnEdit">Accept</button>
                    </form>
                </div>
<?php
    }
    //James CHAT 
    //nampilin tombol button send, textbox ,dll
    if(isset($_REQUEST['chatactivity'])){
        echo "
        <input type='text' name='chatmsg' id='chatmsg' class='col-8' placeholder='Enter your text'/>
        <button class='col-2 btn btn-success' onclick='addChat(1)''><i class='fa fa-send'></i></button>
        <div class='col-2 btn btn-primary'>
            <label for='image' class='w-100 text-center pt-3'><i class='fa fa-file'></i></label>
        </div>
        <input id='image' name=image type='file' onchange='addChat(2)' style='display:none;'/>    ";
    }
    if(isset($_REQUEST['addchatroom'])){
        //na
        $storeid = $_REQUEST["addchatroom"];
        $accountid = $_SESSION["active"]["Account_ID"];
        $registerDate = date("Y-m-d H:i:s");
        if($conn->query("SELECT * FROM chat_room WHERE Account_ID = '$accountid' AND Store_ID = '$storeid'")->num_rows == 0){
            $conn->query("INSERT INTO chat_room VALUES('','$accountid','$storeid','$registerDate')");
        };
        header('location: chat.php');
    }
    //munculin chat di chat content
    if(isset($_REQUEST["chatactive"])){
        $_SESSION['chatactive'] = $_REQUEST["chatactive"];
        $storeid = $conn->query("SELECT * FROM chat_room WHERE Chat_Room_ID = '$_REQUEST[chatactive]'")->fetch_assoc()['Store_ID'];
        $store = $conn->query("SELECT * FROM store WHERE Store_ID = '$storeid'")->fetch_assoc();
        if($_REQUEST['mode']==1) $conn->query("UPDATE chat_message SET Chat_Status = '-2' WHERE Chat_Room_ID = '$_REQUEST[chatactive]' AND Chat_Status = '-1'");
        else $conn->query("UPDATE chat_message SET Chat_Status = '2' WHERE Chat_Room_ID = '$_REQUEST[chatactive]' AND Chat_Status = '1'");
        if($_REQUEST['mode']==1){ // akun ke store
            echo "
            <div style='height:15%;' class='w-100 bg-dark pl-3 pt-1' >
                <img style='height:70px;width:70px;border-radius:100%;' src='data:image/jpeg;base64,".base64_encode($store['Store_Image'])."' >  <span class='ml-2' style='color:white'><b>$store[Store_Name]</b></span>
            </div>";
        }
        else{ // store ke akun
            $account = $conn->query("SELECT * FROM account a INNER JOIN Chat_Room cr on cr.Account_ID = a.Account_ID WHERE cr.Chat_Room_ID = '$_REQUEST[chatactive]'")->fetch_assoc();
            echo "
            <div style='height:15%;' class='w-100 bg-dark pl-3 pt-1' >
                <img style='height:70px;width:70px;border-radius:100%;' src='data:image/jpeg;base64,".base64_encode($account['Account_Image'])."'>  <span class='ml-2' style='color:white'><b>$account[Account_Name]<b></span>
            </div>
            ";
        }
        echo "<div style='overflow:scroll;overflow-x:hidden;height:85%' class='w-100' id='messages'>";
        foreach ($conn->query("SELECT * FROM chat_message WHERE Chat_Room_ID = '$_REQUEST[chatactive]'") as $chat) {
            if(($_REQUEST['mode']==1 && $chat['Chat_Status']>=1) || ($_REQUEST['mode']==2 && $chat['Chat_Status']<=-1)){
                if(strlen($chat['Chat_Message'])){
                    echo "<div class='float-right p-3 bg-lightgreen m-1 ml-3' style='clear:both;border-radius:10px;'>$chat[Chat_Message]</div>";
                }else{
                    echo "<div class='float-right p-3 bg-lightgreen m-1 ml-3' style='clear:both;border-radius:10px;'>
                            <img src='data:image/jpeg;base64,".base64_encode($chat['Chat_Image'])."' width=100 />
                        </div>";
                }
            }else{
                if(strlen($chat['Chat_Message'])){
                    echo "<div class='float-left p-3 bg-lightblue m-1 ml-3' style='clear:both;border-radius:10px;'>$chat[Chat_Message]</div>";
                }else{
                    echo "<div class='float-left p-3 bg-lightblue m-1 ml-3' style='clear:both;border-radius:10px;'>
                            <img src='data:image/jpeg;base64,".base64_encode($chat['Chat_Image'])."' width=100 />
                        </div>";
                }
            }
        }
        echo "</div>";
        // foreach($conn->query("SELECT * FROM chat_message") as $chatmsg){
        //     $date = date("Y-m-d H:i:s");
        //     $to_time = strtotime("$date");
        //     $from_time = strtotime("$chatmsg[Chat_Date]");
        //     if(round(abs($to_time - $from_time))>15){
        //         $conn->query("DELETE FROM chat_message WHERE Chat_Date = '$chatmsg[Chat_Date]'");
        //     }
        // }
    }
    if(isset($_REQUEST['addchat'])){
        $date = date('Y-m-d H:i:s');
        // SEBAGAI ACCOUNT 1 // SEBAGAI STORE 2
        //$status = 1 --> user // $status = -1 --> store
        
        $status = $_REQUEST['mode']==1 ? 1 : -1;
        if(isset($_SESSION['chatactive'])){
            $img = '';
            if(!strlen($_REQUEST['addchat']))
                $img = addslashes(file_get_contents($_FILES['image']['tmp_name']));
            $conn->query("INSERT INTO chat_message VALUES('$_SESSION[chatactive]','$_REQUEST[addchat]','$img','$date','$status')");
            foreach ($conn->query("SELECT * FROM chat_message WHERE Chat_Room_ID = '$_SESSION[chatactive]'") as $chat) {
                if($_REQUEST['mode']==1 && $chat['Chat_Status']>=1 || $_REQUEST['mode']==2 && $chat['Chat_Status']<=-1){ 
                    if(strlen($chat['Chat_Message'])){
                        echo "<div class='float-right p-3 bg-lightgreen m-1 ml-3' style='clear:both;border-radius:10px;'>$chat[Chat_Message]</div>";
                    }else{
                        echo "<div class='float-right p-3 bg-lightgreen m-1 ml-3' style='clear:both;border-radius:10px;'>
                                <img src='data:image/jpeg;base64,".base64_encode($chat['Chat_Image'])."' width=100 />
                            </div>";
                    }
                }else{
                    if(strlen($chat['Chat_Message'])){
                        echo "<div class='float-left p-3 bg-lightblue m-1 ml-3' style='clear:both;border-radius:10px;'>$chat[Chat_Message]</div>";
                    }else{
                        echo "<div class='float-left p-3 bg-lightblue m-1 ml-3' style='clear:both;border-radius:10px;'>
                                <img src='data:image/jpeg;base64,".base64_encode($chat['Chat_Image'])."' width=100 />
                            </div>";
                    }
                }   
            }
        }
       
    }
    //nampilin list chat room
    if(isset($_REQUEST['listchat'])){
        $accountid = $_SESSION['active']['Account_ID'];
        //SEBAGAI ACCOUNT (1) //SEBAGAI STORE (2)
        $q = " WHERE Account_ID = '$accountid'";
        if($_REQUEST['mode']==2){
            $storeid = $conn->query("SELECT * FROM store WHERE Account_ID = '$accountid'")->fetch_assoc()['Store_ID'];   
            $q = " WHERE Store_ID = '$storeid'";
        }
        foreach ($conn->query("SELECT * FROM chat_room".$q) as $chatroom) {
            //jumlah notif
            if($_REQUEST['mode']!=2)
                $notifcount = $conn->query("SELECT COUNT(*) FROM chat_message WHERE Chat_Room_ID = '$chatroom[Chat_Room_ID]' AND Chat_Status = -1")->fetch_array()[0];
            else
                $notifcount = $conn->query("SELECT COUNT(*) FROM chat_message WHERE Chat_Room_ID = '$chatroom[Chat_Room_ID]' AND Chat_Status = 1")->fetch_array()[0];
            $recentmsg = $conn->query("SELECT * FROM chat_message WHERE Chat_Room_ID = '$chatroom[Chat_Room_ID]' ORDER BY 4 DESC")->fetch_assoc();
            //recent msg -> message terbaru
            if($recentmsg['Chat_Message'] == "" && $recentmsg['Chat_Image'] == ""){$recentmsg['Chat_Message'] = "";}
            else if($recentmsg['Chat_Message']==''){$recentmsg['Chat_Message']="Photo";}
            //TOKO KE ORANG
            $store = $conn->query("SELECT * FROM store WHERE Store_ID = '$chatroom[Store_ID]'")->fetch_assoc();
            if($_REQUEST['listchat']==2){
                //notifcount == 0 --> berarti tidak ada notif yang masuk
                $notif =  $notifcount == 0 ? "" : "<div class='float-right btn btn-success rounded-circle disabled mt-1'>$notifcount</div>";
                echo "
                    <div id='chat$chatroom[Chat_Room_ID]' onclick='activechat($chatroom[Chat_Room_ID])' class='bg-white clearfix m-1 p-3' >
                        <img class='float-left' style='border-radius:100%;width:50;height:50;' src='data:image/jpeg;base64,".base64_encode($store['Store_Image'])."'>
                        <div class='float-left ml-4'>
                            <div>$store[Store_Name]</div>
                            <div>$recentmsg[Chat_Message]</div>
                        </div>
                        $notif
                    </div>
                ";
            //ORANG KE TOKO
            }else{
                $account = $conn->query("SELECT * FROM account  WHERE Account_ID = '$chatroom[Account_ID]'")->fetch_assoc();
                $notif =  $notifcount ==0 ? "" : "<div class='float-right btn btn-success rounded-circle disabled mt-1'>$notifcount</div>";
                echo "
                    <div id='chat$chatroom[Chat_Room_ID]' onclick='activechat($chatroom[Chat_Room_ID])' class='bg-white clearfix m-1 p-3' >
                        <img class='float-left' style='border-radius:100%;width:50;height:50;' src='data:image/jpeg;base64,".base64_encode($account['Account_Image'])."'>
                        <div class='float-left ml-4'>
                            <div>$account[Account_Name]</div>
                            <div>$recentmsg[Chat_Message]</div>
                        </div>
                        $notif
                    </div>
                ";
            }
        }
    }
    //Chat diatas kak
    // JAMES SEARCH 
    if(isset($_REQUEST['search'])){
        $search = $_REQUEST['search'];
        $alltag = json_decode($_REQUEST['tag'],true);
        $min = $_REQUEST['min'];
        $max = $_REQUEST['max'];
        if(isset($_SESSION['active'])){
            $id = $_SESSION['active']['Account_ID'];
            $storeid = $conn->query("SELECT * FROM store WHERE Account_ID = '$id'")->fetch_assoc()["Store_ID"];
            $q = "SELECT * FROM view v LEFT JOIN item_tag it on it.Item_ID = v.Item_ID RIGHT JOIN item i on i.Item_ID = v.Item_ID  WHERE i.Item_Name like '%$search%' AND i.Item_Status = 0 AND i.Item_Price <= $max AND i.Item_Price >= $min AND NOT Store_ID = '$storeid'";
        }else{
            $q = "SELECT * FROM view v LEFT JOIN item_tag it on it.Item_ID = v.Item_ID RIGHT JOIN item i on i.Item_ID = v.Item_ID  WHERE i.Item_Name like '%$search%' AND i.Item_Status = 0 AND i.Item_Price <= $max AND i.Item_Price >= $min";
        }
        foreach ($alltag as $i=>$tag) {   
            if($i==0) $q .= " AND (it.Tag_ID = '$tag'";
            else $q .= " OR it.Tag_ID = '$tag'";
            if(count($alltag)-1 == $i) $q .= ")"; 
        }
        
        // 
        
        $orderby = $_REQUEST['orderby']??"COUNT(v.Item_ID) DESC";
        $q .= " GROUP BY i.Item_ID ORDER BY $orderby";
        // echo $q;
        foreach($conn->query($q) as $item){
            echo "
            <div class='col-sm-12 col-md-4 col-lg-4 mt-3'>
                <div class='card' onclick=gotoitem($item[Item_ID])>
                    <div class='card-body'>
                        <div class='card-image'><img src='data:image/jpeg;base64,".base64_encode($item['Item_Image'])."' alt='' ></div>
                        <div class='card-title'>$item[Item_Name]</div>
                        <div class='text-left'>Rp.$item[Item_Price],00-</div>
                        <div class='text-right'>Sisa : $item[Item_Stock]</div>
                        <div>$item[Item_Description]</div>
                    </div>
                </div>
            </div>";
        }
    } 
    //James Accept Reject(myorder.php) 
    if(isset($_REQUEST['reject'])){
        $accountid = $_SESSION['active']['Account_ID'];
        $htransid = $_REQUEST['reject'];
        $conn->query("UPDATE htransaction SET Htransaction_Status = -1 WHERE Htransaction_ID = '$htransid'");
        $htrans = $conn->query("SELECT * FROM htransaction WHERE Htransaction_ID = '$htransid'")->fetch_assoc();
        $conn->query("UPDATE Account SET Account_Balance = Account_Balance + $htrans[Htransaction_Total] WHERE Account_ID = '$htrans[Account_ID]'");
        foreach($conn->query("SELECT * FROM dtransaction WHERE Htransaction_ID = '$htransid'") as $dtrans){
            $conn->query("UPDATE item SET Item_Stock = Item_Stock + $dtrans[Dtransaction_Qty] WHERE Item_ID = '$dtrans[Item_ID]'");
        }
    }
    //accept transaksi oleh toko(myorder.php)
    if(isset($_REQUEST['accept'])){
        $htransid = $_REQUEST['accept'];
        $accountid = $_SESSION['active']['Account_ID'];
        $conn->query("UPDATE htransaction SET Htransaction_Status = 1 WHERE Htransaction_ID = '$htransid'");
        $balance = $conn->query("SELECT * from htransaction WHERE Htransaction_ID = '$htransid'")->fetch_assoc()["Htransaction_Total"];
        $conn->query("UPDATE Account SET Account_Balance = Account_Balance + $balance WHERE Account_ID = '$accountid'");
    }
    //tampilin order di toko(myorder.php)
    if(isset($_REQUEST['myorder'])){
        $accountid = $_SESSION['active']['Account_ID'];
        $store = $conn->query("SELECT * FROM store WHERE Account_ID = '$accountid'")->fetch_assoc();
        $i = 0;
        foreach($conn->query("SELECT * FROM htransaction WHERE Store_ID = '$store[Store_ID]'") as $htrans) { 
            $i++;
?>
        <div class="card">
            <div class="card-header">
                <a class="card-link" data-toggle="collapse" href="#collapse<?= $i ?>" style="text-decoration:none">
                    No Trans : <?= $htrans['Htransaction_ID']?><br>
                    Date : <?= $htrans['Htransaction_OrderDate']?><br>
                    Total : <?= $htrans['Htransaction_Total']?><br>
                </a>
                <?php if($htrans['Htransaction_Status']!=0){ 
                        if($htrans['Htransaction_Status']==1){
                            echo "<div class='btn btn-dark disabled'>Transaction Succeed!</div>";
                        }else{
                            echo "<div class='btn btn-dark disabled'>Transaction Cancelled!</div>";
                        }    
                }else{ ?>
                    <button class="btn btn-primary" onclick="accept(<?= $htrans['Htransaction_ID']?>)" >Accept</button>
                    <button class="btn btn-danger" onclick="reject(<?= $htrans['Htransaction_ID']?>)" >Reject</button>
                <?php } ?>
                
            </div>
            <div id="collapse<?= $i ?>" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <?php foreach($conn->query("SELECT * FROM dtransaction WHERE Htransaction_ID = '$htrans[Htransaction_ID]'") as $dtrans){
                            $item = $conn->query("SELECT * FROM item WHERE Item_ID = $dtrans[Item_ID]")->fetch_assoc();
                    ?>
                    <div class="row">
                        <div class="col-3">
                            <?= "<img src='data:image/jpeg;base64,".base64_encode($item['Item_Image'])."' >" ?>
                        </div>
                        <div class="col-9">
                            <h3><?= $item['Item_Name'] ?></h3>
                            Harga : <?= $dtrans['Dtransaction_Price']?><br>
                            Jumlah : <?= $dtrans['Dtransaction_Qty']?><br>
                            Subtotal : <?= $dtrans['Dtransaction_Subtotal']?><br>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php
    }}
    //Ivan insert Balance
    if(isset($_REQUEST["addbalance"])){
        $accountid = $_SESSION['active']['Account_ID'];
        $conn->query("UPDATE account set Account_Balance = Account_Balance + $_REQUEST[addbalance] where Account_ID= '$accountid'");
        $account = $conn->query("SELECT * FROM account where Account_ID = '$accountid'")->fetch_assoc();
        $_SESSION['active'] = $account;
        echo "Rp.".$account['Account_Balance'].",00";
    }
    //Tambah Tag ke tag create item
    if(isset($_REQUEST["newtag"])){
        if($_REQUEST['newtag']!="show" && $conn->query("SELECT * FROM tag WHERE Tag_Name = '$_REQUEST[newtag]'")->num_rows == 0){
            $conn->query("INSERT INTO tag VALUES('','$_REQUEST[newtag]')");
        }
        foreach ($conn->query("SELECT * FROM tag") as $key => $value) {
           echo "<div class=tags>
                <input type=checkbox name=listTag[] value=$value[Tag_ID]
                    id=$value[Tag_ID]>
                <label for=$value[Tag_ID]>$value[Tag_Name]</label>
            </div>";
        }
    }
    //Tambah Tag ke item_tag
    if(isset($_REQUEST["addtag"])){
        $itemid = $_REQUEST["addtag"];
        $listtag = json_decode($_REQUEST['listtag'],true);
        $waktu=date('Y-m-d H:i:s');
        foreach ($listtag as $key => $val ) {
            $conn->query("INSERT INTO item_tag VALUES('$itemid','$val','$waktu')");
        }
    }
    //add review
    if(isset($_REQUEST["rate"])){
        $jumlah = $_REQUEST["rate"];
        $comment = $_REQUEST["comment"];
        $htransid = $_REQUEST["htransid"];
        $itemid = $_REQUEST["itemid"];
        $waktu=date('Y-m-d H:i:s');
        $conn->query("INSERT INTO review VALUES('$htransid','$itemid','$jumlah','$comment','$waktu')");
        $conn->query("UPDATE dtransaction set Dtransaction_Status = 1 WHERE Htransaction_ID=$htransid AND Item_ID=$itemid");
        $count = $ctr = 0;
        foreach ($conn->query("SELECT * FROM review where Item_ID = '$itemid'") as $key => $value) {
            $count+=(int)$value["Review_Rating"];$ctr++;
        }
        $conn->query("UPDATE item SET Item_Rating = '$count/$ctr' WHERE Item_ID = '$itemid'");
    }
    //tampilin item di wishlist
    if(isset($_REQUEST['wishlist'])){
        $accountid = $_SESSION['active']['Account_ID'];
        $allfilter = json_decode($_REQUEST['filter'],true);
        $min = $_REQUEST['min'];
        $max = $_REQUEST['max'];
        $orderby = $_REQUEST['orderby']??"1 DESC";
        $q = "SELECT * FROM (SELECT DISTINCT w.Item_ID FROM Wishlist w LEFT JOIN item_tag it on it.Item_ID = w.Item_ID WHERE w.Account_ID = $accountid";
        foreach ($allfilter as $i=>$filter) {   
            if($i==0) $q .= " AND (it.Tag_ID = '$filter'";
            else $q .= " OR it.Tag_ID = '$filter'";
            if(count($allfilter)-1 == $i) $q .= ")"; 
        }
        $q .= ") as items INNER JOIN item i on i.Item_ID = items.Item_ID WHERE i.Item_Status = 0 ORDER BY ".$orderby;  
        // echo $q;
        
        foreach($conn->query($q) as $i=>$wishlist){
            if($i == 0) echo "<div class='card-columns col-lg-8 col-sm-12 mt-3'>";
            echo " 
            <div class='card bg-dark'>
                <div class='card-header text-light'>
                    <h3>$wishlist[Item_Name]</h3>
                </div>
                <div class='card-body bg-light'>
                    Description:
                    <p>$wishlist[Item_Description]</p>
                    <p>Price: $wishlist[Item_Price]</p>
                    <p>Stock: $wishlist[Item_Stock]</p>
                    <div class='btn-block btn-group'>
                        <button class='btn btn-primary' onclick=gotoitem($wishlist[Item_ID])>Buy</button>
                        <button class='btn btn-danger' onclick=deletewishlist($wishlist[Item_ID])>Remove</button>
                    </div>'
                </div>
            </div>";
            if($i-1 == $conn->query($q)->num_rows) echo "</div>";
        }
        if($conn->query($q)->num_rows == 0){
            echo "
            <div class='text-center'>
                <h5 class='text-secondary my-5'>You Don't Have Any Wishlist</h5>
                <form action='home.php' method='post'>
                    <button type='submit' class='btn btn-dark form-control'>Lanjutkan Belanja</button>
                </form>
            </div>
            "
            ;
        }
        // echo "$conn->error";
    }
    //hapus item di wishlist
    if(isset($_REQUEST['deletewishlist'])){
        
        $itemid = $_REQUEST['deletewishlist'];
        $accountid = $_SESSION['active']['Account_ID'];
        echo "$itemid";
        echo "$accountid";
        $conn->query("DELETE FROM wishlist WHERE Item_ID = '$itemid' AND Account_ID = '$accountid'");
        
        echo $conn->error;
    }
    //SHOPPING CART
    //Buat nambah sm ngurangin jumlah barang di shoppingcart.php
    //addqty bkn item_id tapi adalah index yang ada di dalam session 
    if(isset($_REQUEST['addqty']) || isset($_REQUEST['minqty']) || isset($_REQUEST['removeqty'])){
        if(isset($_REQUEST['addqty'])){
            if($_REQUEST['addqty']!="show"){
                $itemid = $_SESSION['Item'][$_REQUEST['addqty']]['Item_ID'];
                $_SESSION['Item'][$_REQUEST['addqty']]['Item_Qty']+=1;
                $itemstock = $conn->query("SELECT * FROM item WHERE Item_ID = '$itemid'")->fetch_assoc()["Item_Stock"];
                $_SESSION['Item'][$_REQUEST['addqty']]['Item_Qty'] = $_SESSION['Item'][$_REQUEST['addqty']]['Item_Qty'] > $itemstock ? $itemstock : $_SESSION['Item'][$_REQUEST['addqty']]['Item_Qty'];
            }
        }
        if(isset($_REQUEST['minqty'])){
            $_SESSION['Item'][$_REQUEST['minqty']]['Item_Qty']-=1;
            if($_SESSION['Item'][$_REQUEST['minqty']]['Item_Qty']==0){
                unset($_SESSION['Item'][$_REQUEST['minqty']]);
            }
        }
        if(isset($_REQUEST['removeqty'])){
            unset($_SESSION['Item'][$_REQUEST['removeqty']]);
        }
        if(isset($_SESSION['Item'])){
            foreach ($_SESSION["Item"] as $key => $value) {
                $subtotal = $value['Item_Qty']*$value['Item_Price'];
                echo "<div class='card'>
                    <div class='card-body row'>
                        <div class='col-3'>
                            <img src='data:image/jpeg;base64,".base64_encode($value['Item_Image'])."' alt=''>
                        </div>
                        <div class='col-9'>
                            <div class='float-right' onclick='removeqty($key)' style='cursor:pointer;'><i class='fas fa-minus-square'></i></div>
                            <h3>$value[Item_Name]</h3><br>
                            <button class='btn btn-secondary btn-md' onclick='minqty($key)'>-</button>
                            Jumlah : $value[Item_Qty]
                            <button class='btn btn-secondary btn-md' onclick='addqty($key)'>+</button>
                            <br><br>
                            Harga  : $value[Item_Price] <br><br>
                            Subtotal : $subtotal
                        </div>
                    </div>
                </div>";
            }
            if(!count($_SESSION['Item'])){
                echo "<div class='col-12 text-center mt-5 mb-5'><h3 class='text-secondary'>Empty Cart</h3></div>";
            }
        }else{
            echo "<div class='col-12 text-center mt-5 mb-5'><h3 class='text-secondary'>Empty Cart</h3></div>";
        }
    }
?>