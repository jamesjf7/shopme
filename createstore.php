<?php include 'library/lib.php';require 'conn.php'; include "redirect.php"; ?>
<?php
    $user_id = $_SESSION['active']['Account_ID'];
    $user = $conn->query("SELECT * FROM account a WHERE account_id = $user_id")->fetch_assoc();
    $exist = $conn->query("SELECT * FROM store s WHERE s.account_id = $user_id")->num_rows > 0;
    if($exist)
        header("Location: mystore.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Store</title>
    <?php require 'library/lib.php'?>
</head>
<body>
    <!-- <pre>
        <?=$user['Account_ID']?><br>
        <?=$user['Account_Username']?><br>
        <?=$user['Account_Password']?><br>
        <?=$user['Account_Name']?><br>
        <?=$user['Account_Balance']?><br>
        <?=$user['Account_RegisterDate']?><br>
    </pre>  -->
    <?php include "header.php"?>
    <div class="container">
        
        <div class="row">
            <div class="col-sm-12 col-md-6 " style="background-color:lightgrey">
                <img id="preview" src="image/default.jpg" alt="" width=100%>
            </div>
            <div class="col-sm-12 col-md-6">
                <h2>Create Store</h2>
                <form id="myform" action="#" method="post">
                    <div class="from-group mb-2">
                        <label for="name">Store Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="from-group mb-2">
                        <label for="desc">Store Description</label>
                        <textarea name="desc" id="desc" rows="5" class="form-control" required></textarea>
                    </div>
                    <div>
                        Image : <input type="file" name="image" id="image" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])" >
                    </div>
                    <br>
                    <div class="from-group">
                        <button class="btn btn-lg btn-block btn-dark">Create</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
    <?php include "footer.php"?>

    <script>
        $(()=>{
            $('#myform').submit(function(){
                const name = $('#name').val();
                const desc = $('#desc').val();
                // const data = {
                //     table : 'store',
                //     state : 'insert',
                //     package : { 
                //         'Account_ID' : '<?=$user_id?>',
                //         'Store_Name' : `'${name}'`,
                //         'Store_Description' : `'${desc}'`,
                //         'Store_RegisterDate' : 'now()'
                //     }
                // }
                // $.post("control.php",data,function(e){
                //     alert('masuk!' + e);
                //     location = 'mystore.php';
                // });
                let data = new FormData();
                data.append('image',$('#image')[0].files[0]);
                $.ajax({
                    url: "control.php?addstore=1&StoreName="+name+"&StoreDesc="+desc,
                    type: "POST",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(e){
                        
                        location = 'mystore.php';
                    }
                });
                

                return false;
            });
        })
    </script>
</body>
</html>