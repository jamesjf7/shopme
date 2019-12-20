<?php include "conn.php";include "library/lib.php";?>
<?php 
    $lowestprice = $conn->query("SELECT MIN(Item_Price) FROM item")->fetch_assoc()["MIN(Item_Price)"];
    $highestprice = $conn->query("SELECT MAX(Item_Price) FROM item")->fetch_assoc()["MAX(Item_Price)"];
    echo "<script>
        
        var minprice = $lowestprice;
        var maxprice = $highestprice;
    </script>"
?>
<script>
    function gotoitem(id){
        window.location = "item.php?Item_ID="+id;
    }
    $(()=>{     
        $( "#slider-range" ).slider({
            range: true,
            min: minprice,
            max: maxprice,
            step: 10000,
            values: [minprice, maxprice ],
            slide: function( event, ui ) {
                $( "#amount" ).val( "Rp." + ui.values[ 0 ] + " - Rp." + ui.values[ 1 ] );
            }
        });
        $( "#amount" ).val( "Rp." + $( "#slider-range" ).slider( "values", 0 ) +
        " - Rp." + $( "#slider-range" ).slider( "values", 1 ) );
        
        $('#loginModal').modal('hide');        
        // $('[data-toggle="tooltip"]').tooltip()
        $("#search").val("<?= $_REQUEST['search']?>");
        let search = $("#search").val();
        
        let alltag = JSON.parse('<?= $_REQUEST['tag']?>');
        let orderby = $('input[name=order]:checked').val();
        $("#listbarang").load("control.php?search="+search+"&tag="+JSON.stringify(alltag)+"&orderby="+orderby+"&min=0&max=999999");
    });
</script>
<!-- <head>
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css"  media="screen">
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head> -->
<?php include "header.php"?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-6 mt-3 ml-5 mb-5 p-5 bg-secondary card">
        <h5>Filter</h5>
        <!-- $conn->query("SELECT * FROM item i INNER JOIN view v on v.Item_ID = i.Item_ID INNER JOIN Item_Tag it on it.Item_ID = i.Item_ID RIGHT JOIN tag t on t.Tag_ID = it.Tag_ID GROUP BY v.Item_ID ORDER BY COUNT(v.Item_ID) DESC") -->
        
        <?php 
            $accountid = isset($_SESSION['active']['Account_ID'])??'';
            // $q = "SELECT * FROM tag t INNER JOIN item_tag it on it.Tag_ID = t.Tag_ID INNER JOIN item i on i.Item_ID = it.Item_ID LEFT JOIN view v on v.Item_ID = i.Item_ID WHERE v.Account_ID = '$accountid' GROUP BY t.Tag_ID ORDER BY count(t.Tag_ID) DESC";
            $q = "SELECT * FROM tag";
            foreach($conn->query($q) as $row){?>
            <div>
                <input type="checkbox" class="tag" class="form-check-input" value="<?= $row['Tag_ID']?>"> <?= $row['Tag_Name']?>
                
            </div>
        <?php }?>
        Price Range :
        <p>
            <input type="text" id="amount" readonly style="border:0; color:black; font-weight:bold; width:100%; font-size:11px;" >
        </p>
        <div id="slider-range"></div>
        <hr>
        <h5>Order By</h5>
        <div><input type="radio" name="order" value="i.Item_Name" checked> A - Z</div>
        <div><input type="radio" name="order" value="i.Item_Name+DESC"> Z - A</div>
        <div><input type="radio" name="order" value="i.Item_Rating+DESC"> Rating</div>
        <div><input type="radio" name="order" value="i.Item_Price+DESC"> High Price</div>
        <div><input type="radio" name="order" value="i.Item_Price"> Low Price</div>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-12">
        <div id="listbarang" class="row">
        </div>
    </div>
</div>
<?php include "footer.php"?>