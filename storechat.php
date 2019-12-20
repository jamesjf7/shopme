<?php include "conn.php";include "library/lib.php"; include "redirect.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <title>Chat</title>
    <style>
        .activechat{
            background-color: lightcyan;
            border-radius: 10px; 
        }
        .bg-grey{
            background-color: #999;
        }
        .bg-lightblue{
            background-color: lightskyblue;
        }
        .bg-lightgreen{
            background-color: lightgreen;
        }
    </style>
    <script>
        var activechatroom = -1;
        $(()=>{
            
            $("#listchat").load('control.php?listchat=1&mode=2');
        })
        function activechat(chatroomid){
            //store ke akun
            activechatroom = chatroomid;
            $('#chatcontent').load("control.php?chatactive="+chatroomid+"&mode=2");
            $('#chatactivity').load("control.php?chatactivity=2");
            $("#listchat").load('control.php?listchat=1&mode=2');
            setTimeout(()=>{
                for (let i = 0; i < 10000; i++) {
                    $('#chat'+i).addClass('bg-white');
                    $('#chat'+i).removeClass('activechat');
                }
                $('#chat'+chatroomid).removeClass('bg-white');
                $('#chat'+chatroomid).addClass('activechat');
                $('#messages').scrollTop($('#messages')[0].scrollHeight);
            },50)
            
        }
        function addChat(type){
            let chatmsg = $("#chatmsg").val(); 
            if(type == 2){
                let data = new FormData();
                data.append('image', $('#image')[0].files[0]);
                $.ajax({
                    url: "control.php?addchat=&mode=2&image=1",
                    type: "POST",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data){ 
                        $('#chatcontent').html(data);
                    }
                });
            }
            else if($('#chatmsg').val() != ""){
                //biar spasi bisa di lempar
                chatmsg = chatmsg.split(' ').join('+');
                console.log(chatmsg);
                $('#chatcontent').load('control.php?addchat='+chatmsg+"&mode=2");
            }
            // $('#chatcontent').load('control.php?addchat='+chatmsg+"&mode=2");
            $("#chatmsg").val("")
            $("#image").val("")
            $("#listchat").load('control.php?listchat=1&mode=2',()=>{
                $('#chat'+activechatroom).click();
            });
        }
    </script>
</head>
<body>
    <?php include "header.php";?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12 bg-grey pt-2" style="height:600px">
                <div class="row container"><h2>Chat</h2></div>
                <div id="listchat" style="overflow:auto"></div>
            </div>
            <div class="col-lg-8 col-sm-12 bg-secondary" style="height:600px;padding:0;">
                <div id="chatcontent" style="height:90%;overflow:auto;"></div>
                <div class="row bg-secondary" style="height:10%;margin-left: 0px;margin-right: 0px;" id="chatactivity">
                    <!-- <input type="text" name="chatmsg" id="chatmsg" class="col-8" placeholder="Enter your text"/>
                    <button class="col-2 btn btn-success" onclick="addChat(1)"><i class="fa fa-send"></i></button>
                    <div class="col-2 btn btn-primary">
                        <label for="image" class="w-100 text-center pt-3"><i class="fa fa-file"></i></label>
                    </div>
                    <input id="image" name=image type="file" onchange="addChat(2)" style="display:none;"/> -->
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"?>
    
</body>
</html>