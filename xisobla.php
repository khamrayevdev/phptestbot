<?php
ob_start();
define('API_KEY', '5325039446:AAGDcCvIxvZk3mKU_Kc1jhGNbzng-UxnaE0');
$admin = "5037919078";
$mybot = "GroupMembbot";
include("connect.php");
include("keyboard.php");
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$text = $message->text;
$mid = $message->message_id;
$chat_id = $message->chat->id;
$user_id = $message->from->id;
$ufname = $update->message->from->first_name;
$type = $message->chat->type;
$title = $message->chat->title;
$repid = $message->reply_to_message->from->id;
$repmid = $message->reply_to_message->message_id;
$repufname = $message->reply_to_message->from->first_name;
$reply = $message->reply_to_message;
$left = $message->left_chat_member;
$new = $message->new_chat_member;
$leftid = $message->left_chat_member->id;
$newid = $message->new_chat_member->id;
$newufname = $message->new_chat_member->first_name;
$channel = $update->channel_post; 
$channel_text = $channel->text;
$channel_mid = $channel->message_id; 
$channel_id = $channel->chat->id; 
$channel_user = $channel->chat->username; 
$rek = file_get_contents('rek.txt');
$soat = date('H:i:s', strtotime('3 hour'));
$sana = date('d-m-Y',strtotime('3 hour'));

$result = mysqli_query($connect,"SELECT * FROM groups WHERE group_id = $chat_id");
$rew = mysqli_fetch_assoc($result);
$ad = $rew['ad'];

#keyboards
$add = json_encode([   
   'inline_keyboard'=>[
[["text"=>" ➕ Guruhga qo'shish","url"=>"https://t.me/$mybot?startgroup=new"]],
]   
]);

if(isset($text)){
if($type == "private"){
$result = mysqli_query($connect,"SELECT * FROM users WHERE user_id = $chat_id");
$rew = mysqli_fetch_assoc($result);
if($rew){
}else{
mysqli_query($connect,"INSERT INTO users(user_id) VALUES ('$chat_id')");
}
}
if($type == "group" or $type=="supergroup"){
$result = mysqli_query($connect,"SELECT * FROM `groups` WHERE group_id = '$chat_id'");
$rew = mysqli_fetch_assoc($result);
if($rew){
}else{
mysqli_query($connect,"INSERT INTO `groups`(group_id) VALUES ($chat_id)");
}
}
}

if($channel and $channel_id == "-1001567543534"){
file_put_contents('rek.txt',$channel_mid);
}

if($text== "/start" or $text== "/start@$mybot"){
    bot('forwardmessage',[
    'chat_id'=>$chat_id,
    'from_chat_id'=>"@Khcoder",
    'message_id'=>$rek,
        ]);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=> "<b>🤖 Botga xush kelibsiz, <a href='tg://user?id=$user_id'>$ufname</a> !

🌐 Men guruhga kim qancha odam qo'shganligini aytib beruvchi robotman. Meni admin qilib tayinlashga unutmang!
/help buyrugi orqali bot buyruqlari haqida ma'lumot olishingiz mumkin</b>",
'parse_mode' => 'html',
'disable_web_page_preview'=>true,
  'reply_markup'=>$add,
]);
exit();
}

if($left){
  bot('deletemessage',[
    'chat_id'=>$chat_id,
    'message_id'=>$mid,
 ]);
 }
 
if($new){
if($newid==$user_id){
  bot('deletemessage',[
    'chat_id'=>$chat_id,
    'message_id'=>$mid
  ]);
  bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"<b>👋Salom</b> <a href='tg://user?id=$newid'>$newufname</a> Gruppamizga xush kelibsiz😉",
    'parse_mode'=>'html',
  'reply_markup'=>$add,
   ]);
}else{
	bot('deletemessage',[
    'chat_id'=>$chat_id,
    'message_id'=>$mid
  ]);
bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"<b>👋Salom</b> <a href='tg://user?id=$newid'>$newufname</a> Gruppamizga sizni qoʻshishdi😉",
    'parse_mode'=>'html',
     'reply_markup'=>$add,
   ]);
$result = mysqli_query($connect,"SELECT * FROM sonlar WHERE qani = '$user_id=$chat_id'");
$rew = mysqli_fetch_assoc($result);
$soni = $rew['soni'];
$xa = $soni + 1;
if($rew){
mysqli_query($connect,"UPDATE sonlar SET  soni = '$xa' WHERE qani = '$user_id=$chat_id'");
}else{
mysqli_query($connect,"INSERT INTO sonlar(qani,gr_id,soni) VALUES ('$user_id=$chat_id','$chat_id','1')");
}
exit();
}
}


if($update->message){
	if($ad!=0){
	$resultsoni = mysqli_query($connect,"SELECT * FROM sonlar WHERE qani = '$user_id=$chat_id'");
$rewsoni = mysqli_fetch_assoc($resultsoni);
$soni = $rewsoni['soni'];
$resultad = mysqli_query($connect,"SELECT * FROM groups WHERE group_id = $chat_id");
$rewad = mysqli_fetch_assoc($resultad);
$ad = $rewad['ad'];
  $gett = bot('getChatMember', [
       'chat_id' => $chat_id,
        'user_id' => $user_id,
]);
$get = $gett->result->status;
if($get =="member"){
	if($soni < $ad){
		bot('deletemessage',[
    'chat_id'=>$chat_id,
    'message_id'=>$mid
  ]);
bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"<a href='tg://user?id=$user_id'>$ufname</a> Gruppamizga yozish uchun $ad ta odam qoʻshgan boʻlishiz kerak",
    'parse_mode'=>'html',
     'reply_markup'=>$add,
   ]);
exit(); 
	}
}
}
}



if($text == "/help" or $text == "/help@$mybot"){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=> "<b>🤖 Botimizning buyruqlari!

/mymembers - Siz qo'shgan odamlar soni!
/yourmembers - Reply qilingan odam qo'shgan odamlar soni!
/top - Eng ko'p odam qo'shgan 10 talik!
/all - Guruhga odam qo'shgab barcha obunachilar!
/delall - Guruhga qo'shganlarni barchasini tozalash!
/add -  buyrug'i Guruhingizga majburiy odam qo'shishni yoqadi!. Bu orqali Guruhingizga istagancha odam yigʻib olasiz.
_________________________
/add 10 - majburiy odam qo'shishni yoqish!

❗️eslatma: 10 soni o'rniga istagan raqamizni yozib jonatishiz mumkin!
_________________________
/add off  - majburiy odam qo'shishni o'chirib qoyish uchun!
@$mybot</b>",
'parse_mode' => 'html',
'disable_web_page_preview'=>true,
  'reply_markup'=>$add,
]);
exit();
}

if($text=="/stat"){
$res = mysqli_query($connect, "SELECT * FROM `users`");
$userlar = mysqli_num_rows($res);
$res = mysqli_query($connect, "SELECT * FROM `groups`");
$guruhlar = mysqli_num_rows($res);
$all = $guruhlar + $userlar;
bot('sendmessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$mid,
'text'=>"<b>📊Bot foydalanuvchilari:
👤 Foydalanuvchilar: $userlar ta
👥 Guruhlar: $guruhlar ta
🔃Hammasi: $all ta
📅 $sana $soat
❇️</b> <a href='http://t.me/$mybot?startgroup=new'>@$mybot</a>",
'parse_mode'=>"html",
'reply_markup'=>$kanal,
]);
exit();
} 
if($type=="group" or $type=="supergroup"){
if($text == "/mymembers" or $text == "/mymembers@$mybot"){
$result = mysqli_query($connect,"SELECT * FROM sonlar WHERE qani = '$user_id=$chat_id'");
$rew = mysqli_fetch_assoc($result);
$soni = $rew['soni'];
if($soni > 0){
  bot('sendmessage',[    
    'chat_id'=>$chat_id, 
    'reply_to_message_id'=>$mid,  
    'parse_mode'=>'html',   
    'text'=>"<a href='tg://user?id=$user_id'>$ufname</a> 
🔹Siz $soni ta odam qo'shgansiz!",
  'reply_markup'=>$add,
  ]);   
}else{
bot("sendMessage",[
"chat_id"=>$chat_id,
 'reply_to_message_id'=>$mid,  
    'parse_mode'=>'html',   
"text"=>"<a href='tg://user?id=$user_id'>$ufname</a> 
❌Siz hali odam qo'shmadingiz!",
  'reply_markup'=>$add,
]);
exit();
}
exit();
}
}else{
bot("sendMessage",[
"chat_id"=>$chat_id,
 'reply_to_message_id'=>$mid,  
 "text"=>"<b>❌Buyruq faqat guruhlarda ishlaydi!</b>",
    'parse_mode'=>'html',   
    'reply_markup'=>$add,
]);
exit();
}

if($text=="/yourmembers" or $text=="/yourmembers@$mybot"){
if($reply){
$result = mysqli_query($connect,"SELECT * FROM sonlar WHERE qani = '$repid=$chat_id'");
$rew = mysqli_fetch_assoc($result);
$soni = $rew['soni'];
if($soni > 0){
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'reply_to_message_id'=>$repmid,
	'text'=>"<b><a href='tg://user?id=$repid'>$repufname</a> $soni ta odam qo'shgan</b>",
	'parse_mode'=>'html',
	'reply_markup'=>$add,
]);
}else{
bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"<b><a href='tg://user?id=$repid'>$repufname</a> 
❌ Xozircha odam qo'shmagan</b>",
	'parse_mode'=>'html',
	'reply_markup'=>$add,
]);
}
}else{
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"<b>❌ Ushbu buyruqdan foydalanish uchun birorta odamning xabariga reply qiling!</b>",
	'parse_mode'=>'html',
	  'reply_markup'=>$add,
]);
}
}

if ($text == "/top" or $text == "/top@$mybot"){
$top = mysqli_query($connect,"SELECT * FROM `sonlar` WHERE `gr_id` = '$chat_id' ORDER BY soni DESC  LIMIT 10");
$i =1;
$text = "👥 <code>$title</code> <b>guruhiga odam qo'shgan TOP 10 talik:</b>
";
while($row = mysqli_fetch_array($top)){
$exx = $row["qani"];
$ex = explode("=",$exx);
$userid = $ex[0];
$soni = $row["soni"];
$nomi = bot ('getChatMember', [
'chat_id'=> $userid,
'user_id'=> $userid
])->result->user->first_name;
$nomi = str_replace(["[","]","(",")","*","_","`"],["","","","","","",""],$nomi);
$text.="<b>$i)</b> <a href='tg://user?id=$userid'>$nomi</a> - <b>$soni</b> \n";
$i++;}
if($i > 1){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"html",
'reply_to_message_id'=>$mid,
'text'=>"$text-----------------------------------------",
    'reply_markup'=>$add,
    'parse_mode'=>'html',
    ]);
exit(); 
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"markdown",
'reply_to_message_id'=>$mid,
'text'=>"Guruhingizga hali hech kim odam qo'shmagan",
    'reply_markup'=>$add,
    ]);
exit(); 
}
}

if ($text == "/all" or $text=="/all@$mybot"){
$top = mysqli_query($connect,"SELECT * FROM `sonlar` WHERE `gr_id` = '$chat_id' ORDER BY soni DESC");
$i =1;
$text = "👥 <code>$title</code> <b>guruhiga odam qo'shgan barcha foydalanuvchilar:</b>
";
while($row = mysqli_fetch_array($top)){
$exx = $row["qani"];
$ex = explode("=",$exx);
$userid = $ex[0];
$soni = $row["soni"];
$nomi = bot ('getChatMember', [
'chat_id'=> $userid,
'user_id'=> $userid
])->result->user->first_name;
$nomi = str_replace(["[","]","(",")","*","_","`"],["","","","","","",""],$nomi);
$text.="<b>$i)</b> <a href='tg://user?id=$userid'>$nomi</a> - <b>$soni</b> \n";
$i++;}
if($i > 0){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"html",
'reply_to_message_id'=>$mid,
'text'=>"$text-----------------------------------------",
    'reply_markup'=>$add,
    ]);
exit(); 
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"markdown",
'reply_to_message_id'=>$mid,
'text'=>"<b>Sizning guruhingizga odam qo'shishmagan</b>",
    'reply_markup'=>$add,
    ]);
exit(); 
}
}

if($text=="/delall" or $text=="/delall@$mybot"){
	  $gett = bot('getChatMember', [
       'chat_id' => $chat_id,
        'user_id' => $user_id,
]);
$get = $gett->result->status;
if($get =="creator" or $get=="administrator"){
$result = mysqli_query($connect,"DELETE FROM sonlar WHERE gr_id = $chat_id");
$rew = mysqli_fetch_assoc($result);
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"<code>$title</code> <b>guruhida barcha odam qo'shganlar soni tozalandi!</b>",
'parse_mode'=>'html',
  'reply_markup'=>$add,
]);
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"Ushbu buyruq faqatgina adminda ishlaydi !",
  'reply_markup'=>$add,
]);
}
}

if(mb_stripos($text,"/add")!== false){
$son = explode(" ",$text);
$son = $son[1];
	  $gett = bot('getChatMember', [
       'chat_id' => $chat_id,
        'user_id' => $user_id,
]);
$get = $gett->result->status;
if($get =="creator" or $get=="administrator"){
if($son > 0 and $son!=="off"){
mysqli_query($connect,"UPDATE groups SET  ad = '$son' WHERE group_id = '$chat_id'");
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"<b>Endi </b><code>$title</code> <b>guruhiga yozish uchun </b><code>$son</code> <b>ta odam qo'shgan bo'lishi kerak</b>",
'parse_mode'=>'html',
  'reply_markup'=>$add,
]); 
exit(); 
}elseif($son=="off"){
mysqli_query($connect,"UPDATE groups SET  ad = '0' WHERE group_id = '$chat_id'");
	bot('SendMessage',[
	'chat_id'=>$chat_id,
	'text' =>"Majburiy azolik toʻxtatildi",
	'parse_mode'=>'markdown',
	'reply_markup'=>$add,
	]);
	exit(); 
	}
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"Ushbu buyruq faqatgina adminda ishlaydi !",
  'reply_markup'=>$add,
]);
}
}














if($text == "!ro"){
$gett = bot('getChatMember', [
'chat_id' => $chat_id,
'user_id' => $user_id,
]);
$get = $gett->result->status;
if($get =="member"){
  bot('restrictChatMember', [
      'chat_id' => $chat_id,
      'user_id' => $user_id,
      'until_date' => $minut,
'permissions'=>json_encode([
'can_send_messages' => false,
'can_invite_users'=>true
])
  ]);
  bot('sendmessage', [
      'chat_id' =>$chat_id,
      'text' => "💝 <a href='tg://user?id=$id'>$from_user_first_name</a> siz <b>Read only</b> rejimiga tushirildingiz!",
      'parse_mode' => 'html'
  ]);
}
}
