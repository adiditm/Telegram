<?php
//include_once("src/Autoloader.php");
//$bot = new Telegram\Bot("1008718966:AAE6WLYElQWQ-r9ecuu1dnSyZOZ3VF9LVpg", "thecath_bot", "thecath_bot");
//$tg  = new Telegram\Receiver($bot);


    include '../classes/Telegram.php';
    include '../server/config.php';
    $bot_token = '1008718966:AAE6WLYElQWQ-r9ecuu1dnSyZOZ3VF9LVpg';
    $telegram = new Telegram($bot_token);
    $text = $telegram->Text();
    $chat_id = $telegram->ChatID();
    $vTextRes="";
    if (preg_match("/\/help/",$text)) {
                $vSQL="select * from botcommand where factive ='1' ";
                $db->query($vSQL);
                while($db->next_record()){
                    $vTextRes .= $db->f('fcommand')." - ".$db->f('fdesc')."\n";
                }
    
    } else if (substr($text,0,1)=='/') {
                $vSQL="select * from botcommand where '$text' like concat(fcommand,'%')";
                $db->query($vSQL);
                $db->next_record();
                $vTextRes = $db->f('ftext'); 
    } 
    
    $content = array('chat_id' => $chat_id, 'text' => $vTextRes);
    $telegram->sendMessage($content);

?>
