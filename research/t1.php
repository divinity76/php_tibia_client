<?php 
declare (strict_types = 1);
require_once('hhb_.inc.php'); // https://github.com/divinity76/hhb_.inc.php/blob/master/hhb_.inc.php
require_once('hhb_datatypes.inc.php'); // https://github.com/divinity76/hhb_.inc.php/blob/master/hhb_datatypes.inc.php
require_once('../XTEA.class.php'); // https://github.com/divinity76/php-xtea/blob/master/src/xtea.class.php
require_once('../Tibia_client.class.php');

echo "hi!\n";
sleep(1);
//for ($i = 0; $i < 5; ++$i) {
echo "logging in..";
$tc = new Tibia_client('192.168.204.187', 7172, '166666', 'password', 'Php', true);
echo ". done!\n";
$tc->say("hello from PHP! will read packets.");
for (;; ) {
    $xtea_decrypted = null;
    $adler_removed = null;
    $packet = $tc->internal->read_next_packet(true, true, true, true, $adler_removed, $xtea_decrypted);
    $parsed = $tc->internal->parse_packet($packet);
    $tc->ping();
    if ($parsed->type === 0x17) {
        foreach ($parsed->data['welcome_messages'] as $message) {
            echo "server message: {$message}\n";
        }
    }
    if ($parsed->type === $parsed::TYPE_SAY) {
        $name = $parsed->data['speaker_name'];
        if ($name === 'Php') {
            continue;
        }
        $text = $parsed->data['text'];
        echo "{$name}: {$text}\n";
        if ($text === "go up") {
            $tc->say("yes sir!");
            $tc->walk_up();
        } elseif ($text === "go down") {
            $tc->say("yes sir!");
            $tc->walk_down();
        } elseif ($text === "go right") {
            $tc->say("yes sir!");
            $tc->walk_right();
        } elseif ($text === "go left") {
            $tc->say("yes sir!");
            $tc->walk_left();
        } elseif ($text === "logout") {
            $tc->say("yes, goodbye sir!");
            die();
        } else {
            $tc->say("sorry sir, i do not understand the command \"{$text}\"");
        }
       // var_dump($parsed);
    }
    continue;
    //$packet = bin2hex($packet);
    hhb_var_dump(
        $adler_removed,
        $xtea_decrypted,
        bin2hex($packet),
        $packet,
        $parsed
    );
}
//    die();
//}

 