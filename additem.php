<?php require 'conn.php';include 'library/lib.php'; include "redirect.php"; ?>
<?php
    $user_id = $_SESSION['active']['Account_ID'];
    $store = $conn->query(
        "SELECT * FROM store s 
        INNER JOIN account a ON s.account_id = a.account_id
        WHERE s.account_id = $user_id
    ")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<script>
    $(()=>{
        $('#alltags').load("control.php?newtag=show");
        $('#myform').submit(function () {
            const name = $('#name').val();
            const desc = $('#desc').val();
            const price = $('#price').val();
            const stock = $('#stock').val();
            let data = new FormData();
            data.append('image',$('#image')[0].files[0]);
            // const data = {
            //     table: 'item',
            //     state: 'insert',
            //     package: {
            //         'Store_ID': '<?=$store['Store_ID']?>',
            //         'Item_Name': `'${name}'`,
            //         'Item_Price': `${price}`,
            //         'Item_Stock': `${stock}`,
            //         'Item_Description': `'${desc}'`,
            //         'Item_Image': `'${img}'`,
            //         'Item_RegisterDate': 'now()'
            //     }
            // }
            // $.post("control.php?additem=1&StoreID="+<?=$store['Store_ID']?>+"&ItemName="+name+"&ItemPrice="+price+"&ItemStock="+stock+"&ItemDescription="+desc,img,()=>{
            //     alert('masuk!');
            //     let alltag = [];
		    //     $('input:checked').each((e,v)=>{alltag.push(v.value);});
            //     $.post("control.php?addtag="+e+"&listtag="+JSON.stringify(alltag));
            //     location = 'mystore.php';
            // });
            $.ajax({
                url: "control.php?additem=1&StoreID="+<?=$store['Store_ID']?>+"&ItemName="+name+"&ItemPrice="+price+"&ItemStock="+stock+"&ItemDescription="+desc,
                type: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(e){
                    alert('Masuk');
                    let alltag = [];
		            $('input:checked').each((e,v)=>{alltag.push(v.value);});
                    $.post("control.php?addtag="+e+"&listtag="+JSON.stringify(alltag));
                    location = 'mystore.php';
                }
            });
            // $.post("control.php", data, function (e) {
            //     alert('masuk!' + e);
            //     let alltag = [];
		    //     $('input:checked').each((e,v)=>{alltag.push(v.value);});
            //     $.post("control.php?addtag="+e+"&listtag="+JSON.stringify(alltag));
            //     location = 'mystore.php';
            // });
            return false;
        });
    });
    //Tag yang sudah ada blm dicheck 
    function addtag() {
        var tagname = $("#newtag").val();
        alert(tagname);
        $('#alltags').load("control.php?newtag=" + tagname);
    }
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Store</title>
</head>
<body>
    <?php include "header.php";?>
    <div class="container">
        <div class="row">
            <div id="" class="col-sm-12 col-md-6 bg-secondary">
                <img id="preview" src="" alt="No Image" class="w-100 h-100">
            </div>
            <div class="col-sm-12 col-md-6">
                <h2>Add Item</h2>
                <form id="myform" action="#" method="post">
                    <div class="from-group mb-2">
                        <label for="name">Item Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="price">Item Price</label>
                        <input type="number" name="price" id="price" class="form-control" min=0 required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="stock">Item Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control" min=1 required>
                    </div>
                    <div class="from-group mb-2">
                        <label for="desc">Item Description</label>
                        <textarea name="desc" id="desc" rows="5" class="form-control" required></textarea>
                    </div>
                    Image : <input type="file" name="image" id="image" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])"> 
                    <div id="alltags"></div>
                    <div>Not Available Above?<a href="" data-toggle="modal" data-target="#topupModal">add new</a>
                    </div>
                    <button class="btn btn-lg btn-block btn-primary" name="submit">Create</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="topupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <h4>New Tag</h4>
                    </div>
                    <div class="d-flex flex-column text-center">
                        <div class="form-group">
                            <input type="text" name="" id="newtag">
                        </div>
                        <button name="" onclick="addtag()" class="btn btn-info btn-block btn-round">Add Tag</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php";?>
</body>

</html>


<!-- feed itu dr user sendiri -->
<!-- home berasal barang yg liat dr smua user -->
<!-- history barang yang dilihat -->
<!-- filter -->
<!-- sort -->
<!-- chat -->