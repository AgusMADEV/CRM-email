<?php 

$config = json_decode(file_get_contents("config.json"), true);

$hostname = $config['hostname'];
$username = $config['username'];
$password = $config['password']; 

$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail server: ' .imap_last_error());

$emails = imap_search($inbox, 'ALL');

    foreach($emails as $email_number){
        $cabecera = imap_headerinfo($inbox, $email_number);
        $asunto = isset($cabecera->subject) ? imap_utf8($cabecera->subject) : "(No Subject)";
        $from = isset($cabecera->from[0]->mailbox, $cabecera->from[0]->host) ?
        $cabecera->from[0]->mailbox . "@" . $cabecera->from[0]->host : "(Unknown Sender)";
        $fecha = isset($cabecera->date) ? date("Y-m-d H:i:s", strtotime($cabecera->date)) : "(No Date)";
        echo "From: $from<br>";
        echo "Asunto: $asunto<br>";
        echo "Fecha: $fecha<br>";
        echo "<hr>";
    }
imap_close($inbox);
?>