<?php
    include "conn.php";
    include "library/lib.php";
    if(isset($_REQUEST["btnRegister"])){
        $date = date('Y-m-d H:i:s');
        if($_REQUEST['pwd'] == $_REQUEST['cpwd']){
            $pwd = password_hash($_REQUEST['pwd'],PASSWORD_DEFAULT);            
            $image = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
            $conn->query("INSERT INTO account VALUES('','$_REQUEST[username]','$pwd','$_REQUEST[name]',0,'$image','$date')");
            header("location: home.php");
        }else{
            echo "<script>alert(Password not the same)</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-pic {
            max-width: 200px;
            max-height: 200px;
            display: block;
        }
        .file-upload {
            display: none;
        }
        .circle {
            border-radius: 1000px !important;
            overflow: hidden;
            width: 128px;
            height: 128px;
            border: 8px solid rgba(255, 255, 255, 0.7);
            /* position: absolute; */
            top: 72px;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        .p-image {
            /* position: absolute; */
            color: #666666;
            transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
        }
        .p-image:hover {
            transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
        }
        .upload-button {
            font-size: 1.2em;
        }
        .upload-button:hover {
            transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
            color: #999;
        }
    </style>
    <script>
        $(document).ready(function() {
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
        $(".upload-button").on('click', function() {
            $(".file-upload").click();
        });
    });
    </script>
</head>
<body style="background-color:white">
    <?php include "header.php" ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col lg-3 col-sm-0 card bg-primary card bg-primary "></div>
            <div class="col-lg-9 col-sm-12 card bg-dark text-white">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <form action="" method="POST" enctype='multipart/form-data'>
                        <div class="row">
                            <div class="mx-auto">
                                <div class="circle">
                                    <img class="profile-pic" src="http://cdn.cutestpaw.com/wp-content/uploads/2012/07/l-Wittle-puppy-yawning.jpg">
                                </div>
                                <div class="p-image">
                                    <i class="fa fa-camera upload-button"></i>
                                    <input class="file-upload" type="file" accept="image/*" name="photo"/>
                                </div>
                            </div>
                        </div>
            
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password</label>
                            <input type="password" class="form-control" id="pwd" name="pwd">
                        </div>
                        <div class="form-group">
                            <label for="cpwd">Confirm Password</label>
                            <input type="password" class="form-control" id="cpwd" name="cpwd">
                        </div>
                        <button type="submit" name="btnRegister" class="btn btn-warning form-control">Register</button>
                    </form>
                    <br>
                    <div class="text-center">   
                        Already have an account ? <a href="login.php" class="text-warning"> Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php include "footer.php" ?>
</body>
</html>
