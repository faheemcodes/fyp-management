<?php
$c=ftp_connect('ftpupload.net');
if(!$c) die("no connect");
if(!ftp_login($c,'if0_42230840','rHZkhphFhy2')) die("no login");
ftp_pasv($c,true);
ftp_chdir($c,'htdocs');
$files = ftp_nlist($c, '.');
print_r($files);
ftp_close($c);
