<?php require 'conn.php';  include "redirect.php"; ?>
<?php
    $user_id = $_SESSION['active']['Account_ID'];
    $data = $conn->query(
        "SELECT * FROM store s
        INNER JOIN account a ON s.account_id = a.account_id
        WHERE s.account_id = $user_id"
    );
    if($data->num_rows <= 0){
        header("Location: createstore.php");
    }else{
        $store = $data->fetch_assoc();
    }
    $items = $conn->query(
        "SELECT * FROM item i 
        INNER JOIN store s ON i.store_ID = s.store_ID
        WHERE i.store_ID = {$store['Store_ID']}
        ORDER BY i.item_registerdate DESC
    ");
    if(isset($_REQUEST['btnEdit'])){
        $id = $_SESSION['activeitem']['Item_ID'];
        $name = $_REQUEST['editname'];
        $desc = $_REQUEST['editdesc'];
        $price = $_REQUEST['editprice'];
        $stock = $_REQUEST['editstock'];
        echo $username ;
        echo $password;
        if($_FILES['image']['name'] != ""){
            $image= addslashes(file_get_contents($_FILES['image']['tmp_name']));
            $conn->query("UPDATE item SET Item_Name = '$name', Item_Price = '$price', Item_Stock = '$stock', Item_Description = '$desc' ,Item_Image = '$image' WHERE Item_ID = '$id'");
        }else 
            $conn->query("UPDATE item SET Item_Name = '$name', Item_Price = '$price', Item_Stock = '$stock', Item_Description = '$desc' WHERE Item_ID = '$id'");
        echo "$conn->error";    
        header("location: mystore.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Store</title>
    <?php require 'library/lib.php'?>
    <script>
        function deleteItem(id) {
            $.post('control.php?deleteitem=1&itemid='+id);
            location = "mystore.php";
            // $("items").load('control.php?deleteitem=1&itemid='+id);
        }
        function editItem(id){
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.profile-pic').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".file-upload").on('change', function(){
                readURL(this);
            });
            $(".modal-body").load('control.php?edititem=1&itemid='+id);
            $("#editModal").modal();
        }
    </script>
</head>
<body>
    <div id="modalcontainer">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "header.php";?>
    <div class="container" id="cont"></div>
    <script>
        $(()=>{
            $('#cont').load("store.php",{account_id : '<?=$user_id?>'})
        })
    </script>
    <?php include "footer.php";?>
</body>
</html>