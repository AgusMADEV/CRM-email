<?php

// 1. Fuerza cabecera JSON
header('Content-Type: application/json; charset=UTF-8');

// 2. Desactiva la “display_errors” de PHP para que no meta HTML
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING);

$config = json_decode(file_get_contents("config.json"), true);

$hostname = $config['hostname'];
$username = $config['username'];
$password = $config['password']; 
/*
$root = preg_replace('/\}[^\}]+$/', '}', $hostname); 
// extrae sólo "{servidor:puerto/flags}"
$imap = imap_open($root . 'INBOX', $username, $password);
$all = imap_list($imap, $root, '*');
imap_close($imap);
// Graba el listado en error_log para que lo mires:
error_log("Carpetas IMAP disponibles: " . print_r($all, true));*/
// Function to fetch emails from a given mailbox folder
function fetch_emails($mailbox, $folder, &$uniqueEmails) {
    $correos = [];
    $inbox = imap_open($mailbox . $folder, $GLOBALS['username'], $GLOBALS['password']) 
        or die('Cannot connect to mail server: ' . imap_last_error());

    $emails = imap_search($inbox, 'ALL');
    
    if ($emails) {
        foreach ($emails as $email_number) {
            $cabecera = imap_headerinfo($inbox, $email_number);
            $asunto = isset($cabecera->subject) ? imap_utf8($cabecera->subject) : "(No Subject)";
            
            $from = isset($cabecera->from[0]->mailbox, $cabecera->from[0]->host) ? 
                    $cabecera->from[0]->mailbox . "@" . $cabecera->from[0]->host : "(Unknown Sender)";
            
            $to = "(Unknown Recipient)";
            if (isset($cabecera->to[0]->mailbox, $cabecera->to[0]->host)) {
                $to = $cabecera->to[0]->mailbox . "@" . $cabecera->to[0]->host;
            }

            $fecha = isset($cabecera->date) ? date("Y-m-d H:i:s", strtotime($cabecera->date)) : "(No Date)";
            
            // Collect emails, avoiding duplicates and excluding the main account email
            if ($from !== "agusmadev@evo-luciona.es" && $from !== "(Unknown Sender)") {
                $uniqueEmails[$from] = true;
            }
            if ($to !== "agusmadev@evo-luciona.es" && $to !== "(Unknown Recipient)") {
                $uniqueEmails[$to] = true;
            }

            $correos[] = [
                "carpeta" => $folder,
                "from" => $from,
                "to" => $to,
                "asunto" => $asunto,
                "fecha" => $fecha
            ];
        }
    } else {
        error_log("back.php: carpeta '$folder' sin correos"); 
    }
    
    imap_close($inbox);
    return $correos;
}

// Fetch emails from both INBOX and Sent folder
$supercorreos = [];
$uniqueEmails = []; // Array to store unique emails

foreach (fetch_emails($hostname, "INBOX", $uniqueEmails) as $correo) {
    $supercorreos[] = $correo;
}

foreach (fetch_emails($hostname, "Elementos enviados", $uniqueEmails) as $correo) {
    $supercorreos[] = $correo;
}

// Convert email list to array
$distinctEmails = array_keys($uniqueEmails);

// Prepare final JSON output
$output = [
    "emails" => $supercorreos,
    "unique_contacts" => $distinctEmails
];

echo json_encode($output,JSON_PRETTY_PRINT);

?>
