<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
include('chatbotdatabase.php');
$txt=mysqli_real_escape_string($con,$_POST['txt']); //prevent SQL injection, elimate special characters
$sql="select reply from chatbot_hints where question like '%$txt%'";
$res=mysqli_query($con,$sql);
if(mysqli_num_rows($res)>0){
	$row=mysqli_fetch_assoc($res);
	$html=$row['reply'];
}else{
	$html="Sorry I'm a dog, I could not understand much of ur language ;(";
}
//store message
$date=date('Y-m-d h:i:s');
mysqli_query($con,"insert into message(message,date,type) values('$txt','$date','user')");
$date=date('Y-m-d h:i:s');
mysqli_query($con,"insert into message(message,date,type) values('$html','$date','bot')");
echo $html; //gives response
?>