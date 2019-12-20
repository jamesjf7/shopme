<script>
	function search(){
		let search = $("#search").val();		
		let alltag = [];
		$('.tag:checked').each((e,v)=>{alltag.push(v.value);});
		if(!(location+"").includes("search.php")){
			location = "search.php?search="+search+"&tag="+JSON.stringify(alltag);
		}
		else{
			
			let orderby = $("input[name=order]:checked").val();
			let search = $("#search").val();
			let min = $( "#slider-range" ).slider( "values", 0 )
			let max = $( "#slider-range" ).slider( "values", 1 )
			history.pushState({}, null, "search.php?search="+search+"&tag="+JSON.stringify(alltag));
			$("#listbarang").load("control.php?search="+search+"&tag="+JSON.stringify(alltag)+"&orderby="+orderby+"&min="+min+"&max="+max,(e)=>{});
		}
	}
</script>
<?php
	if(isset($_REQUEST['btnLogin'])){
		foreach($conn->query("SELECT * FROM account") as $account){
			if($account['Account_Username'] == $_REQUEST['username'] && password_verify($_REQUEST['pwd'],$account['Account_Password'])){
				$_SESSION['active'] = $account;
				// TAMBAH SUPAYA BARANG DARI TOKONYA TIDAK MASUK
				$accountid = $account['Account_ID'];
				$store = $conn->query("SELECT * FROM store WHERE Account_ID = '$accountid'")->fetch_assoc();
				// var_dump($_SESSION['Item']);
				foreach ($_SESSION['Item'] as $key => $value) {
					foreach ($conn->query("SELECT * FROM item WHERE Store_ID = '$store[Store_ID]'") as $item) {
						if($value['Item_ID'] == $item['Item_ID']){
							unset($value);
						}	
					}
				}
				header("location: home.php");
			}
		}echo "<script>alert('Username/ Password salah')</script>";
	}
?>
<style>
	.tag{
		/* box-sizing: border-box; */
		color:black;
		margin:0;
		padding:0;
	}
	.tag:hover{
		color: brown;
		cursor: pointer;
	}
</style>
<div id="preloder">
	<div class="loader"></div>
</div>
<header class="header-section">
	<div class="header-top">
		<div class="container">
			<div class="row">
				<div class="col-lg-2 text-center text-lg-left">
					<!-- logo -->
					<a href="home.php" class="site-logo">
						<img src="image/logo.png" alt="">
					</a>
				</div>
				<div class="col-xl-5 col-lg-5">
					<div class="header-search-form">
						<input type="text" placeholder="Search on ShopMe" id="search">
						<button onclick="search()"><i class="fa fa-search" aria-hidden="true"></i></button>
					</div>
					<div class="row container mt-3 ml-3">
					<!-- tag yang terkenal sering dikunjungi -->
					<?php 
						foreach($conn->query("SELECT * FROM tag t INNER JOIN item_tag it on it.Tag_ID = t.Tag_ID INNER JOIN item i on i.Item_ID = it.Item_ID LEFT JOIN view v on v.Item_ID = i.Item_ID GROUP BY t.Tag_ID ORDER BY count(t.Tag_ID) DESC LIMIT 5") as $tag){
					?>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="checkbox" class="tag" class="form-check-input" value="<?= $tag['Tag_ID']?>">&nbsp;<?= $tag['Tag_Name']?>
							</label>
						</div>
					<?php } ?>
					</div>
				</div>
				<div class="col-xl-5 col-lg-5">
					<div class="user-panel">
						<div class="up-item tag">
							<i class="fas fa-sign-in-alt"></i>
							<?php if(isset($_SESSION['active'])){ ?>
								<a href="account.php">Welcome, <?= $_SESSION['active']['Account_Name']?></a>
							<?php }else{ ?>
								<a data-toggle="modal" data-target="#loginModal">Login or Sign Up</a>
							<?php } ?>
						</div>
						<div class="up-item tag">
							<div class="shopping-card">
								<i class="fa fa-shopping-cart" aria-hidden="true"></i>
								<span><?= isset($_SESSION['Item'])?count($_SESSION['Item']):0 ?></span>
							</div>
							<?php if(isset($_SESSION['active'])){ ?>
								<a href="shoppingcart.php">Cart</a>
							<?php }else{ ?>
								<a data-toggle="modal" data-target="#loginModal">Shopping Cart</a>
							<?php } ?>
						</div>
						<?php if(isset($_SESSION['active'])){ ?>
							<div class="up-item tag">
								<i class="fa fa-shopping-bag" aria-hidden="true"></i>
								<a href="mystore.php">Me Shop</a>
							</div>
							<div class="up-item tag">
								<i class="fa fa-history" aria-hidden="true"></i>
								<a href="history.php">History</a>
							</div>
							<div class="up-item tag" style="margin-left:10px;" onclick="$.get('logout.php');window.location = 'home.php';">
								<i class="fas fa-sign-out-alt" aria-hidden="true"></i>Logout
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<nav class="main-navbar">
		<div class="container">
			<!-- menu -->
			<ul class="main-menu">
				<li><a href="home.php">Home</a></li>
				<?php if(isset($_SESSION['active'])){ ?>
					<li><a href="wishlist.php">Wishlist</a></li>
				<?php } ?>
				<?php if(preg_match("/mystore/i",  $_SERVER['REQUEST_URI'])) { ?>
					<li>
						<a href="myorder.php">Confirmation<span class="new">new</span></a>
					</li>
					<li>
						<a href="storechat.php">Chat</a>
					</li>
				<?php } ?>
	
			</ul>
		</div>
	</nav>
</header>
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
					<h4>Login</h4>
				</div>
				<div class="d-flex flex-column text-center">
					<form action="" method="POST">
						<div class="form-group">
							<input type="text" class="form-control" id="username" name="username" placeholder="Your username">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" id="password" name="pwd" placeholder="Your password">
						</div>
						<button type="submit" class="btn btn-info btn-block btn-round" name="btnLogin">Login</button>
					</form>

					<div class="text-center text-muted delimiter">or use a social network</div>
					<div class="d-flex justify-content-center social-buttons">
						<button type="button" class="btn btn-secondary btn-round" data-toggle="tooltip" data-placement="top"
							title="Twitter">
							<i class="fab fa-twitter"></i>
						</button>
						<button type="button" class="btn btn-secondary btn-round" data-toggle="tooltip" data-placement="top"
							title="Facebook">
							<i class="fab fa-facebook"></i>
						</button>
						<button type="button" class="btn btn-secondary btn-round" data-toggle="tooltip" data-placement="top"
							title="Linkedin">
							<i class="fab fa-linkedin"></i>
						</button>
						</di>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-center">
				<div class="signup-section">Not a member yet? <a href="register.php" class="text-info"> Sign Up</a>.</div>
			</div>
		</div>
	</div>
</div>