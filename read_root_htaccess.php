<?php
$c=ftp_connect('ftpupload.net');
if(!$c) die("no connect");
if(!ftp_login($c,'if0_42230840','rHZkhphFhy2')) die("no login");
ftp_pasv($c,true);

$content = file_get_contents('ftp://if0_42230840:rHZkhphFhy2@ftpupload.net/.htaccess');
echo "ROOT HTACCESS:\n" . $content;
ftp_close($c);
