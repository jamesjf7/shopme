<?php
    include_once("conn.php");
    include "redirect.php";
    $id = $_SESSION['active']['Account_ID'];
    $hasil = $conn->query("SELECT * FROM account where Account_ID = $id");    
    $tampil = mysqli_fetch_assoc($hasil);
    if(isset($_REQUEST['btnEdit'])){
        $username = $_REQUEST['editusername'];
        $password = password_hash($_REQUEST['editpwd'],PASSWORD_DEFAULT);  
        if($_FILES['image']['name'] != ""){
            //CARA INSERT IMAGE HRS DIGINIIN DULU
            $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
            $conn->query("UPDATE account SET Account_Username = '$username', Account_Password = '$password', Account_Image = '$image' WHERE Account_ID = '$id'");
        }else 
            $conn->query("UPDATE account SET Account_Username = '$username', Account_Password = '$password' WHERE Account_ID = '$id'");
            header("location: account.php");
    }
?>
<?php include_once("library/lib.php");?>
<script>
    function addbalance(){
        $jumlahuang = $("#jml").val();
        $('#bal').load("control.php?addbalance="+$jumlahuang);
        $('#topupModal').modal('toggle');
    }
    $(()=>{
        //gambar di modal
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
    });
</script>
<html>
<?php include_once "header.php";?>
<div class="row">
    <!-- <div class="col-lg-2">
        <?php 
        // echo '<div class="product-pic-zoom"> <img class="product-big-img" src="data:image/jpeg;base64,'.base64_encode($tampil['Account_Image']).'"/></div>'
        ?>
    </div> -->
    <div class="product-details container">
        <div class="row container">
            <div class="col-sm-12 col-lg-2 mt-5">
            <?php
                if($tampil['Account_Image'] != "")
                    echo '<img src="data:image/jpeg;base64,'.base64_encode($tampil['Account_Image']).'" width=100 height=100 style="border-radius:100%;"/>';   
                else{
                    echo '<img src="image/luigi.png" width=100/>';   
                }
            ?>
            </div>
            <div class="col-sm-12 col-lg-10">
                &nbsp;&nbsp;<h1><?=$tampil["Account_Name"]?></h1>
                <h5 class="p-price" id="bal">Balance : Rp.<?=$tampil["Account_Balance"]?>,00</h5>
                <button name="shop" class="site-btn" data-toggle="modal" data-target="#topupModal"><i class="fa fa-plus"></i>TOP UP NOW</button>
            </div>
            
            
        </div>
        
        
        <div id="accordion" class="accordion-area">
            <div class="panel">
                <div class="panel-header" id="headingOne">
                    <button class="panel-link active" data-toggle="collapse" data-target="#collapse1"
                        aria-expanded="true" aria-controls="collapse1">Detail Information <i class="fa fa-money-check"></i></button>
                </div>
                <div id="collapse1" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="panel-body">
                        <p>
                            Username:<?=$tampil["Account_Username"]?> <br>
                            Name:<?=$tampil["Account_Name"]?> <br>
                            Join Date:<?=$tampil["Account_RegisterDate"]?>
                            <br>
                            <input type="submit" class="btn btn-primary btn-round" data-toggle="modal" data-target="#editModal" value="Edit Account">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
				<div class="form-title text-center mb-5">
					<h4>Edit Account</h4>
				</div>
				<div class="d-flex flex-column text-center">
                    <!-- WAJIB enctype='multipart/form-data' -->
					<form action="" method="POST" enctype='multipart/form-data'>
                        <div style="border-radius:100%;width:100px;height:100px;" class="mx-auto ">
                            <?= '<img class="profile-pic" src="data:image/jpeg;base64,'.base64_encode($tampil['Account_Image']).'" style="border-radius:100%;width:100;height:100;"/>'?>
                        </div>
                        <label for="image"><i class="fa fa-camera"></i></label>
                        <input class="file-upload" type="file" name="image" id="image" style="display:none;" accept="image/*">
						<div class="form-group">
							<input type="text" class="form-control" id="editusername" name="editusername" placeholder="Your username" required>
						</div>
						<div class="form-group">
							<input type="password" class="form-control" id="editpassword" name="editpwd" placeholder="Your password" required>
						</div>
						<button type="submit" class="btn btn-info btn-block btn-round" name="btnEdit">Accept</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once("footer.php");?>

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
                    <h4>Top-Up</h4>
                </div>
                <div class="d-flex flex-column text-center">
                    <div class="form-group">
                        <input type="number" min=10000 name="jumlah" id="jml" class="form-control">
                    </div>
                    <button class="btn btn-info btn-block btn-round" name="addbalance" onclick="addbalance()">Add To Balance</button>
                </div>
            </div>
        </div>
    </div>
</div>

</html>