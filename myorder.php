<?php   
    include "conn.php";include "library/lib.php";  include "redirect.php"; 
    // $htransaction=$conn->query("SELECT * FROM htransaction")->fetch_all();
    // $time=strtotime("now");
    // foreach ($conn->query("SELECT * FROM htransaction") as $htrans) {
    //     if($time-strtotime($htrans["Htransaction_OrderDate"])>=30){
    //         $id=$htrans["Htransaction_ID"];
    //         $conn->query("UPDATE htransaction set Htransaction_Status=-1 where Htransaction_ID=$id");
    //     }
    // }
?>
<script>
    $(()=>{
        $("#accordion").load('control.php?myorder=1')
    })
    function accept(htransid){
        $('#accordion').load('control.php?myorder=1&accept='+htransid);}
    function reject(htransid){
        $('#accordion').load('control.php?myorder=1&reject='+htransid);}
</script>
<?php include "header.php"?>
<div id="content" class="container">
    <div id="accordion"> 
    </div>        
</div>
<?php include "footer.php"?>

