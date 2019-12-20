<?php
	include_once "library/lib.php";
	include_once "conn.php";
	include_once "redirect.php";
	$itemid = $_REQUEST["Item_ID"];
	$waktu = date("Y-m-d H:i:s");
	if(isset($_SESSION['active'])){ 
		$userid = $_SESSION["active"]["Account_ID"];
		$storeid = $conn->query("SELECT * FROM store WHERE Account_ID = '$userid'")->fetch_assoc()["Store_ID"];
		foreach ($conn->query("SELECT * FROM item WHERE Store_ID = '$storeid'") as $item) {
			if($item['Item_ID'] == $itemid){
				header('location: home.php');
			}
		}
		$conn->query("INSERT INTO view VALUES('','$userid','$itemid','$waktu')");
	}else{$conn->query("INSERT INTO view VALUES('','0','$itemid','$waktu')");}
	
	//Masukin ke view
	$count=0; $ctr=0;
	$q="SELECT * FROM item i LEFT OUTER JOIN store s on i.store_id=s.store_id where Item_ID = $itemid ";
	$hasil=mysqli_query($conn,$q);
	$tampil=mysqli_fetch_assoc($hasil);
	
	if(isset($_POST["wish"])){
		//wishlist sudah di edit
		if(isset($userid)){
			$storeid = $tampil["Store_ID"];
			if($conn->query("SELECT * FROM wishlist WHERE Item_ID = '$itemid' AND Account_ID = '$userid'")->num_rows > 0){
				$conn->query("DELETE FROM wishlist WHERE Item_ID = '$itemid'");
				echo "<script>alert('Barang di remove')</script>";
			}else{
				$q2="INSERT INTO `wishlist`(`Account_ID`, `Item_ID`, `Wishlist_Date`) VALUES ('$userid','$itemid','$waktu')";
				$hasil2=mysqli_query($conn,$q2);
				echo "<script>alert('Barang berhasil ditambahkan')</script>";
			}
		}else{
			echo "<script>alert('Login terlebih dahulu')</script>";
		}
	}
	if(isset($_POST["shop"])){
		$exist = false;
		$itemstock = $conn->query("SELECT * FROM item WHERE Item_ID = '$itemid'")->fetch_assoc()["Item_Stock"];
		if(isset($_SESSION["Item"]))
			foreach ($_SESSION["Item"] as $key => $item) {
				if($item["Item_ID"] == $itemid){
					$exist = true;
					if($itemstock-(int)$item["Item_Qty"]-(int)$_POST["jumlah"] >= 0){
						$_SESSION["Item"][$key]["Item_Qty"] = (int)$item["Item_Qty"]+(int)$_POST["jumlah"];
					}else{
						echo "<script>alert('Stock tidak cukup')</script>";
					}
				}
			}
		if(!$exist){
			//Kalo barang g ad di dalam shopping cart
			if($itemstock-(int)$_POST["jumlah"] >= 0)
				$_SESSION["Item"][]=[
					"Item_ID"=> $itemid,
					"Item_Image"=> $tampil["Item_Image"],
					"Item_Name"=> $tampil["Item_Name"],
					"Item_Qty"=> (int)$_POST["jumlah"],
					"Item_Price"=> (int)$tampil["Item_Price"],
					"Store_ID" => $tampil['Store_ID']
				];
			else
				echo "<script>alert('Stock tidak cukup')</script>";
		}
	}
?>
<html>

<head>
	<title>ShopMe | Marketplace</title>
	<script>
		function gotoitem(id){
            window.location = "item.php?Item_ID="+id;
        }
	</script>
</head>
<body>
	<?php include_once("header.php");?>
	<!-- product -->
	<section class="product-section">
		<div class="container">
			<div>
				<a href='storepage.php?Store_ID=<?=$tampil['Store_ID']?>'>
					<h3>Toko <?= $tampil['Store_Name']?></h3>
				</a>	
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="product-pic-zoom">
					<?php
						if($tampil['Item_Image'] != ""){
							echo '<img class="product-big-img" src="data:image/jpeg;base64,'.base64_encode($tampil['Item_Image']).'" >';
						}
						else{echo '<img src="image/luigi.png" width=100/>'; }
					?> 
						<!-- <img class="product-big-img" src="data:image/jpeg;base64,<?= base64_encode($tampil['Item_Image']) ?>"  onerror="this.src='image/luigi.png';" >						 -->
					</div>
					<div class="product-thumbs" tabindex="1" style="overflow: hidden; outline: none;">
						<div class="product-thumbs-track">
							<div class="pt active" data-imgbigurl="image/luigi.png"><img src="data:image/jpeg;base64,<?= base64_encode($tampil['Item_Image']) ?>" alt="" onerror="this.src='image/luigi.png';">
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 product-details">
					<h2 class="p-title"><?=$tampil["Item_Name"]?></h2>
					<h3 class="p-price">Rp.<?=$tampil["Item_Price"]?>,00</h3>
					<h4 class="p-stock">Available:<?=$tampil["Item_Stock"]?></h4>
					<div class="p-rating">
						<?php foreach ($conn->query("SELECT * FROM review where Item_ID = '$itemid'") as $key => $value) {
							$count+=(int)$value["Review_Rating"];$ctr++;
						}
						if($ctr == 0){
							echo "0/0";
							for ($i=0; $i < 5; $i++) { 
								echo "<i class='far fa-star'></i>";
							}
						}else{
							echo $count/$ctr;
							for ($i=0; $i < floor($count/$ctr); $i++) { ?>
								<i class="fa fa-star"></i>
						<?php }
							for ($i=0; $i < 5-floor($count/$ctr); $i++) { ?>
								<i class="far fa-star"></i>
						<?php }}?>
					</div>
					<form action="" method="post">
					<div class="quantity">
						<p>Quantity</p>
							<div><input type="number" name="jumlah" class="pro-qty" min=1 max=<?= $tampil["Item_Stock"]?> value="1"></div>
					</div>
					<button name="shop" class="site-btn">BUY NOW</button>
					<?php 
					//ngubah dari add jadi remove
						if(isset($userid))
							$wl = $conn->query("SELECT * FROM wishlist WHERE Item_ID = '$itemid' AND Account_ID = '$userid'")->num_rows == 0 ? "ADD TO WISHLIST" : "REMOVE FROM WISHLIST";
						else $wl = "ADD TO WISHLIST";
						echo "<button name='wish' class='site-btn'><i class='far fa-heart'></i>$wl</button>";
					?>
					</form>
					<div id="accordion" class="accordion-area">
						<div class="panel">
							<div class="panel-header" id="headingOne">
								<button class="panel-link active" data-toggle="collapse" data-target="#collapse1"
									aria-expanded="true" aria-controls="collapse1">information</button>
							</div>
							<div id="collapse1" class="collapse show" aria-labelledby="headingOne"
								data-parent="#accordion">
								<div class="panel-body">
									<p>
										<?=$tampil["Item_Description"]?>
									</p>
								</div>
							</div>
						</div>
						<!-- <div class="panel">
							<div class="panel-header" id="headingTwo">
								<button class="panel-link" data-toggle="collapse" data-target="#collapse2"
									aria-expanded="false" aria-controls="collapse2">care details </button>
							</div>
							<div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="panel-body">
									<p>Pembayaran dapat dilakukan via</p>
									<img src="image/cards.png" alt="">
								</div>
							</div>
						</div> -->
						<div class="panel">
							<div class="panel-header" id="headingTwo">
								<button class="panel-link" data-toggle="collapse" data-target="#collapse3"
									aria-expanded="false" aria-controls="collapse3">Comments</button>
							</div>
							<div id="collapse3" class="collapse" aria-labelledby="headingThree"
								data-parent="#accordion">
								<div class="panel-body">
									<?php foreach ($conn->query("SELECT * FROM review r
										left outer join Htransaction h on r.Htransaction_ID=h.Htransaction_ID
										LEFT outer join item i on r.Item_ID=i.Item_ID
										LEFT outer join account a on a.Account_ID=h.Account_ID
										where i.Item_ID=$itemid") as $key => $value) {
										if($value["Review_Comment"]!=NULL){
									?>
									<!-- src='data:image/jpeg;base64' -->
										<div class="container">
											<div class="row">
												<div class="col-2">
													<?= "<img src='data:image/jpeg;base64,".base64_encode($value['Account_Image'])."' style='border-radius:100%;height:40px;width:40px;'/>"?>
												</div>
												<div class="col-10">
													<?=$value["Account_Username"]?>
													<?php 
														for ($i=0; $i < 5; $i++) { 
															if($i < $value["Review_Rating"]){
																echo "<i class='fas fa-star'></i>";
															}else{
																echo "<i class='far fa-star'></i>";
															}
														}
													?><br>
													<span><?=$value["Review_Comment"]?></span>
												</div>
											</div>
											<hr>
										</div>
										<?php }?>
									<?php }?>
								</div>
							</div>
						</div>
					</div>
					<div class="social-sharing">
						<a href=""><i class="fab fa-google-plus"></i></a>
						<a href=""><i class="fab fa-pinterest"></i></a>
						<a href=""><i class="fab fa-facebook"></i></a>
						<a href=""><i class="fab fa-twitter"></i></a>
						<a href=""><i class="fab fa-youtube"></i></a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="container">
   	<h3>Recommended</h3>
   	<div id="listbarang" class="row">
	<?php 
		$temp = [];
		foreach ($conn->query("SELECT DISTINCT * FROM item i left outer JOIN item_tag it on i.Item_ID=it.Item_ID left OUTER JOIN tag t on t.Tag_ID=it.Tag_ID WHERE i.Item_ID = $itemid AND i.Item_Status = 0") as $value) {
			foreach ($conn->query("SELECT DISTINCT * FROM item i left outer JOIN item_tag it on i.Item_ID=it.Item_ID left OUTER JOIN tag t on t.Tag_ID=it.Tag_ID WHERE NOT i.Item_ID = $itemid and Tag_Name='$value[Tag_Name]' AND i.Item_Status = 0") as $item) {
				if(!in_array($item["Item_ID"],$temp)){
					$temp[] = $item["Item_ID"];
	?>
					<div class="col-sm-12 col-md-4 col-lg-4 mt-3">
						<div class="card" onclick='gotoitem(<?= $item["Item_ID"] ?>)'>
							<div class="card-body">
								<div class="card-image"><img src="data:image/jpeg;base64,<?=base64_encode($item['Item_Image'])?>" alt=""></div>
								<div class="card-title"><?= $item['Item_Name']?></div>
								<div class="text-left">Rp.<?= $item['Item_Price']?> ,00-</div>
								<div class="text-right">Sisa : <?= $item['Item_Stock']?></div>
								<div><?= $item['Item_Description']?></div>
							</div>
						</div>
					</div>
	<?php 		}
			};	 
		}
	?>
   </div>
  </div>
	<?php include_once("footer.php");?>

</body>

</html>