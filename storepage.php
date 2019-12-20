<?php
    include_once("library/lib.php");
    include_once("conn.php");
    $storeid = $_REQUEST["Store_ID"];
    $store = $conn->query("SELECT * FROM store where Store_ID=$storeid")->fetch_assoc(); 
?>
<script>
    function chat(id) {
        window.location="control.php?addchatroom="+id;
    }
    function gotoitem(id){
        window.location = "item.php?Item_ID="+id;
    }
</script>
<?php include_once("header.php")?>
<div class="container">
    <div class="row d-flex">
        <div class="col-3">
            <img src="data:image/jpeg;base64,<?=base64_encode($store["Store_Image"])?>" >

        </div>
        <div class="col-8 pt-2">
            <h1>Toko <?= $store["Store_Name"]?></h1><br>
            <p><?=$store["Store_Description"]?></p>
            <button onclick="chat(<?=$store['Store_ID']?>)" class="btn btn-success form-control"><i class="fa fa-comments"></i>&nbsp;&nbsp;chat</button>    
        </div>
        
        
    </div>
    
    <div class="row">
<?php foreach ($conn->query("SELECT * FROM item where Store_ID = $storeid AND Item_Status = 0") as $value) {?>
        <div class="col-sm-12 col-md-4 col-lg-4 mt-3">
            <div class="card" onclick='gotoitem(<?= $value["Item_ID"] ?>)'>
                <div class="card-body">
                    <div class="card-image"><img src="data:image/jpeg;base64,<?=base64_encode($value["Item_Image"])?>" alt="" ></div>
                    <div class="card-title"><?= $value['Item_Name']?></div>
                    <div class="text-left">
                        Rp.<?= $value['Item_Price']?> ,00-
                    </div>
                    <div class="text-right">
                        Sisa : <?= $value['Item_Stock']?> 
                    </div>
                    <div >
                        <?= $value['Item_Description']?> 
                    </div>
                </div>
            </div>
        </div>
<?php }?>
    </div>
</div>
<?php
    include_once("footer.php");
?>