<?php include "conn.php";include "library/lib.php";  include "redirect.php"; ?>
<?php include "header.php"?>
<script>
    var angka = 5;
    function reset(){
        for (let i = 1; i <= 5; i++) {
            $("#"+i).addClass('far');
            $("#"+i).removeClass('fas');
        }
    }
    function hov(bintang){
        reset();
        for (let i = 1; i <= bintang; i++) {
            $("#"+i).toggleClass('far');
            $("#"+i).toggleClass('fas');
        }
    }
    function nohov(){
        reset();
        for (let i = 1; i <= 5; i++) {
            if(i <= angka){
                $("#"+i).addClass('fas');
                $("#"+i).removeClass('far');
            }else{
                $("#"+i).addClass('far');
                $("#"+i).removeClass('fas');
            }
        }
    }
    function rate(jumlah) {
        angka = jumlah;
        reset();
        for (let i= 1; i <= jumlah; i++) {
            $("#"+i).toggleClass('far');
            $("#"+i).toggleClass('fas');
        }
    }
    function ids(hid,itemid) {
        htrans=hid;
        item_id=itemid;
    }
    $(()=>{
        angka = 5;
        $("#submitreview").click(()=>{
            const desc = $('#desc').val();
            if(angka!=0){
                $.post("control.php?rate="+angka+"&comment="+desc+"&htransid="+htrans+"&itemid="+item_id);
                location = 'history.php';
            }else{
                alert("Pilih jumlah bintang");
            }
        })
        $('#reviewModal').on('hidden.bs.modal', function (e) {
            angka = 5;
            nohov();
            $('#desc').val("");
        })
    });
</script>
<div id="content">
    <div id="accordion" class="container">
        <!-- Foreach htrans lalu dtrans -->
        <?php
        $id = $_SESSION['active']['Account_ID'];$i = 0;
        foreach ($conn->query("SELECT * FROM htransaction WHERE Account_ID = '$id'") as $htrans) {
            $i++;
    ?>
        <div class="card">
            <div class="card-header">
                <a class="card-link" data-toggle="collapse" href="#collapse<?= $i ?>" style="text-decoration:none">
                    No Nota : <?= $htrans['Htransaction_ID']?><br>
                    Store Name : <?= $conn->query("SELECT * FROM store WHERE Store_ID = '$htrans[Store_ID]'")->fetch_assoc()["Store_Name"];?><br>
                    Transaction Date : <?= $htrans['Htransaction_OrderDate']?><br>
                    Status : <?php 
                        if($htrans['Htransaction_Status']==-1){echo "Ditolak";}
                        elseif($htrans['Htransaction_Status']==1){ echo "Berhasil";}
                        else{ echo "Belum ada kabar";}
                    ?><br>
                    Total : <?= $htrans['Htransaction_Total']?><br>
                </a>
            </div>
            <div id="collapse<?= $i ?>" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <?php foreach($conn->query("SELECT * FROM dtransaction WHERE Htransaction_ID = '$htrans[Htransaction_ID]'") as $dtrans){
                        $item = $conn->query("SELECT * FROM item WHERE Item_ID = $dtrans[Item_ID]")->fetch_assoc();
                    ?>
                    <div class="row">
                        <div class='col-3'>
                            <?= "<img src='data:image/jpeg;base64,".base64_encode($item['Item_Image'])."'\>" ?>
                        </div>
                        <div class="col-9">
                            <h5><?= $item['Item_Name']."<br>" ?></h5>
                            Harga : Rp.<?= $dtrans['Dtransaction_Price']?>,00<br>
                            Jumlah : <?= $dtrans['Dtransaction_Qty']?><br>
                            Subtotal : Rp.<?= $dtrans['Dtransaction_Subtotal']?>,00<br>
                            <?php if($htrans['Htransaction_Status']==1){?>
                                <?php if($dtrans["Dtransaction_Status"]==0){?>
                                    <button class="btn btn-dark" type="submit" id="submit" onclick="ids('<?=$htrans['Htransaction_ID']?>','<?=$dtrans['Item_ID']?>')" data-toggle="modal" data-target="#reviewModal"><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;Rate Me</button>
                            <?php }else{?>
                                    <button class="btn btn-dark" disabled>Thank you for rating!</button>
                            <?php }echo "<br>";}

                                }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!--  -->
    </div>
    <?php 
    if($conn->query("SELECT * FROM htransaction WHERE Account_ID = '$id'")){}
        echo 
        "<div class='container text-center'>
            <h5 class='text-secondary my-5'>You Don't Have Any History</h5>
            <form action='home.php' method='post'>
                <button type='submit' class='btn btn-dark form-control'>Lanjutkan Belanja</button>
            </form>
        </div>"
        ;
    ?>
</div>
<?php include "footer.php"?>
<!-- modals review -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-title text-center mb-5">
                    <h4 class="text-secondary">Review</h4>
                    <br>
                    <h5 style="font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif ">What do you think about this product?</h5>
                </div>
                <div class="d-flex flex-column text-center">
                    <div class="rate form-group">
                        <i id="1" class="fas fa-star b" onclick="rate(1)" onmouseenter="hov(1)" onmouseleave="nohov(1)" style="cursor:pointer;"></i>
                        <i id="2" class="fas fa-star b" onclick="rate(2)" onmouseenter="hov(2)" onmouseleave="nohov(2)" style="cursor:pointer;"></i>
                        <i id="3" class="fas fa-star b" onclick="rate(3)" onmouseenter="hov(3)" onmouseleave="nohov(3)" style="cursor:pointer;"></i>
                        <i id="4" class="fas fa-star b" onclick="rate(4)" onmouseenter="hov(4)" onmouseleave="nohov(4)" style="cursor:pointer;"></i>
                        <i id="5" class="fas fa-star b" onclick="rate(5)" onmouseenter="hov(5)" onmouseleave="nohov(5)" style="cursor:pointer;"></i>
                    </div>
                    <div>
                        <textarea class='form-control' placeholder="Comment" name="desc" id="desc" rows="5" style="resize:none" required></textarea>
                    </div>
                    <br>
                    <button type="submit" id="submitreview" class="btn btn-secondary">Submit</button>
                </div>
                            
            </div>
        </div>
    </div>
    <!-- <button class="btn btn-info btn-block btn-round" name="addbalance" onclick="review()">Submit</button> -->
</div>
