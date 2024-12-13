<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
include('chatbotdatabase.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <!-- show normal on the phone -->
    <title>Hayden's chatbot learning</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="style.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Bootstrap relies on jquery -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script> 
    <!-- interactive components -->
</head>
<body>
<div class="container">
        <div class="row justify-content-md-center mb-4">
            <div class="col-md-6">
    <div class="card">  
        <div class="card-body messages-box">
            <ul class="list-unstyled messages-list">
                <!-- create list -->
                <?php
                    $res=mysqli_query($con,"select * from message");
                    if(mysqli_num_rows($res)>0){
                        $html='';
                        while($row=mysqli_fetch_assoc($res)){
                            // for every row
                            $message=$row['message'];
                            $date=$row['date'];
                            $strtotime=strtotime($date);
                            $time=date('h:i A',$strtotime);
                            $type=$row['type'];
                            if($type=='user'){
                                $msg="msg-me";
                                $img="user_avatar.png";
                                $name="Me";
                            }else{
                                $msg="msg-you";
                                $img="bot_avatar.png";
                                $name="Corgi";
                            }
                            $html.='<li class="'.$msg.' clearfix"><span class="message-img"><img src="image/'.$img.'" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">'.$name.'</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">'.$time.'</span></small> </div><p class="messages-p">'.$message.'</p></div></li>';
                            // .= is concatenation(in for loop)
                            // more than one class
                            //span: does not start a new line
                            //not sure what to add
                        }
                        echo $html; //show the list in css format! //show chat history 
                    }else{ ?>
                        <li class="messages-me clearfix start_chat">
                        Anything to ask?
                        </li>
                        <?php
                    } //can do like this?
                    ?> 
            </ul>
        </div>
        <div class="card-header">
            <div class="input-group">
                <input id="input-me" type="text" name="messages" class="form-control input-sm" placeholder="Type your message here..." />
                <span class="input-group-append">
                <input type="button" class="btn btn-primary" value="Send" onclick="send_msg()">
                </span>
            </div> 
        </div>
    </div>
    </div>
        </div>
    </div> 
    <script type="text/javascript">
        function getCurrentTime(){
            var now = new Date();
            var hh = now.getHours();
            var min = now.getMinutes();
            var ampm = (hh>=12)?'PM':'AM';
            hh = hh%12;
            hh = hh?hh:12; //if hh is zero
            hh = hh<10 ? '0'+hh : hh; //if hh<10
            min = min<10?'0'+min:min;
			var time = hh+":"+min+" "+ampm;
            return time;
        }
        function send_msg(){
			jQuery('.start_chat').hide(); //hide the start chat button
			var txt=jQuery('#input-me').val(); //get user input
			var html='<li class="messages-me clearfix"><span class="message-img"><img src="image/user_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Me</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">'+getCurrentTime()+'</span></small> </div><p class="messages-p">'+txt+'</p></div></li>';
			jQuery('.messages-list').append(html);//append to element with class messages-list
			jQuery('#input-me').val('');//clear input field
			if(txt){
				jQuery.ajax({ //send POST request to get_bot_msg
					url:'get_bot_message.php',
					type:'post',
					data:'txt='+txt,
					success:function(result){ //call back function, it runs after result has a value
						var html='<li class="messages-you clearfix"><span class="message-img"><img src="image/bot_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Chatbot</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">'+getCurrentTime()+'</span></small> </div><p class="messages-p">'+result+'</p></div></li>';
						jQuery('.messages-list').append(html); //append bot message
						jQuery('.messages-box').scrollTop(jQuery('.messages-box')[0].scrollHeight); //scroll to bottom
					}
				});
			}
		 }
    </script>
</body>
</html>