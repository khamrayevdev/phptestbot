<?php
function inlineKeyboard($keyboard) {
    return json_encode([
        'inline_keyboard'=>$keyboard
    ]);
}

// Keyboard yaratish
$kanal = inlineKeyboard([
    [['text'=>"📣 ᴋᴀɴᴀʟ","url"=>"https://t.me/khcoder"]],
]);

