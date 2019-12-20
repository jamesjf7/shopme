<?php 
    include "conn.php";include "library/lib.php"; include "redirect.php";
?>
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
            $("#listchat").load('control.php?listchat=2&mode=1');
            
        })
        function activechat(chatroomid){
            //akun ke store
            //nampilin nama di atas chatcontent

            activechatroom = chatroomid;
            $('#chatactivity').load("control.php?chatactivity=1");
            $("#listchat").load('control.php?listchat=2&mode=1');
            $('#chatcontent').load("control.php?chatactive="+chatroomid+"&mode=1");
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
                    url: "control.php?addchat=1&mode=1&image=1",
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
                //biar bisa spasi dilempar
                chatmsg = chatmsg.split(' ').join('+');
                console.log(chatmsg);
                $('#chatcontent').load('control.php?addchat='+chatmsg+"&mode=1");
            }
            $("#chatmsg").val("")
            $("#image").val("")
            $("#listchat").load('control.php?listchat=2&mode=1',()=>{
                $('#chat'+activechatroom).click();
            });
            
        }
    </script>
 
</head>
<body>
    <?php include "header.php";?>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-12 bg-grey" style="height:600px">
                <div class="row container"><h2>Chat</h2></div>
                <div id="listchat" style="overflow:auto;" ></div>
            </div>
            <div class="col-lg-9 col-sm-12 bg-secondary" style="height:600px;padding-left:0;padding-right:0;">
                <div id="chatcontent" style="height:90%;margin-left:0;margin-right:0;">

                </div>
                <div id="chatactivity" class="row bg-secondary" style="height:10%;margin-left: 0px;margin-right: 0px;">
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"?>
    
</body>
</html>