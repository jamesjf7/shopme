<?php
    include "conn.php";
    include "library/lib.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <style>
        #hashtag ul li
        {
            list-style-type: none;
            margin-top: 10px;
            padding: 10px;
            border-radius: 50px;
            border: 1px solid rgb(228, 207, 23);
            background-color: rgb(219, 157, 41);
            color:white;
            position: sticky;
            display: inline;   
            box-sizing: border-box;
        }
        #hashtag
        {
            padding-top:10px;
            vertical-align: center;
            overflow-x: scroll;
            white-space: nowrap;
        }
    </style>
    <script>
        function gotoitem(id){
            window.location = "item.php?Item_ID="+id;
        }
        $(document).ready(function() {     
            $('#loginModal').modal('hide');        
            // $('#loginModal').modal('show');
            
            // $('[data-toggle="tooltip"]').tooltip()
        
        });

    </script>
</head>
<body>
    <?php include "header.php" ?>
    <div class="container mt-5">
        <div class="row">        
            <div class="col-sm-12 col-lg-8">
                <h3>Popular</h3>
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol> 
                    <div class="carousel-inner">
                        <?php 
                            $i = 0;
                            foreach($conn->query("SELECT * FROM (SELECT DISTINCT(i.Item_ID) FROM item i LEFT JOIN view v on v.Item_ID = i.Item_ID LEFT JOIN item_tag it on it.Item_ID = i.Item_ID WHERE i.Item_Status = 0 GROUP BY it.Tag_ID ORDER BY count(it.Tag_ID) DESC LIMIT 3) AS items INNER JOIN item i on i.Item_ID = items.Item_ID") as $item){ 
                                if($i == 0){ 
                                    echo "<div class='carousel-item active'>
                                        <img class='d-block w-100' src='data:image/jpeg;base64,".base64_encode($item['Item_Image'])."' >
                                    </div>";
                                }else{
                                    echo "<div class='carousel-item'>
                                        <img class='d-block w-100' src='data:image/jpeg;base64,".base64_encode($item['Item_Image'])."' >
                                    </div>";
                                }
                                $i++;
                            }
                        ?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4">
                <h3>Recent</h3>
                <?php 
                    if(isset($_SESSION['active'])){
                        $accountid = $_SESSION['active']["Account_ID"];
                        $q = "SELECT * FROM (SELECT DISTINCT(i.Item_ID) FROM item i INNER JOIN view v on v.Item_ID = i.Item_ID INNER JOIN Account a on a.Account_ID = v.Account_ID WHERE a.Account_ID = '$accountid' AND i.Item_Status = 0 ORDER BY v.View_Date DESC LIMIT 3) AS items INNER JOIN item i on i.Item_ID = items.Item_ID";
                    }
                    else{$q = "SELECT * FROM (SELECT DISTINCT(i.Item_ID) FROM item i INNER JOIN view v on v.Item_ID = i.Item_ID WHERE i.Item_Status = 0 ORDER BY v.View_Date DESC LIMIT 3) AS items INNER JOIN item i on i.Item_ID = items.Item_ID";}
                    foreach($conn->query($q) as $item){
                ?>
                <div class="card" onclick="gotoitem(<?= $item['Item_ID']?>)">
                    <div class="row no-gutters py-2">
                        <div class="col-4 my-auto mx-2">
                            <div class="card-image"><img src="data:image/jpeg;base64,<?= base64_encode($item['Item_Image']) ?>"></div>
                        </div>
                        <div class="col-6">
                            <div class="card-body">
                                <div class="card-title"><?= $item['Item_Name']?></div>
                                <div class="text-truncate"><?= $item['Item_Description']?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <br>
        <h3>Feed</h3>
        <div id="listbarang" class="row">
        <!-- foreach post nya -->
        <?php 
            if(isset($_SESSION['active'])){
                $id = $_SESSION['active']['Account_ID'];
                $storeid = $conn->query("SELECT * FROM store WHERE Account_ID = '$id'")->fetch_assoc()["Store_ID"];
                if(isset($storeid)){
                    // $q = "SELECT * FROM item i LEFT OUTER JOIN view v on v.Item_ID = i.Item_ID WHERE NOT Store_ID = '$storeid' GROUP BY i.Item_ID ORDER BY count(i.Item_ID) DESC ";
                    $q = "SELECT * FROM view v RIGHT JOIN item i on i.Item_ID = v.Item_ID WHERE NOT Store_ID ='$storeid' AND i.Item_Status = 0 GROUP BY i.Item_ID ORDER BY count(i.Item_ID) DESC";
                }
                else{
                    // $q = "SELECT * FROM item i LEFT OUTER JOIN view v on v.Item_ID = i.Item_ID GROUP BY i.Item_ID ORDER BY count(v.Item_ID) DESC";
                    $q = "SELECT * FROM view v RIGHT JOIN item i on i.Item_ID = v.Item_ID WHERE i.Item_Status = 0 GROUP BY i.Item_ID ORDER BY count(i.Item_ID) DESC";
                }
            }else{
                // $q = "SELECT * FROM item i LEFT OUTER JOIN view v on v.Item_ID = i.Item_ID GROUP BY i.Item_ID ORDER BY count(v.Item_ID) DESC";
                $q = "SELECT * FROM view v RIGHT JOIN item i on i.Item_ID = v.Item_ID WHERE i.Item_Status = 0 GROUP BY i.Item_ID ORDER BY count(i.Item_ID) DESC";
            }       
            // $q = "SELECT * FROM view v LEFT JOIN item_tag it on it.Item_ID = v.Item_ID RIGHT JOIN item i on i.Item_ID = v.Item_ID  WHERE i.Item_Name like '%$search%' AND i.Item_Price <= $max AND i.Item_Price >= $min";
            foreach($conn->query($q) as $item){ 
        ?>
            <div class="col-sm-12 col-md-4 col-lg-4 mt-3">
                <div class="card" onclick="gotoitem(<?= $item['Item_ID']; ?>)">
                    <div class="card-body">
                        <div class="card-image"><img src="data:image/jpeg;base64,<?= base64_encode($item['Item_Image']) ?>"></div>
                        <div class="card-title"><?= $item['Item_Name']?></div>
                        <div class="text-left">
                            Rp.<?= $item['Item_Price']?> ,00-
                        </div>
                        <div class="text-right">
                            Sisa : <?= $item['Item_Stock']?> 
                        </div>
                        <div >
                            <?= $item['Item_Description']?> 
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!--  -->
        </div>
    </div>
    <?php include "footer.php" ?>
</body>
</html>