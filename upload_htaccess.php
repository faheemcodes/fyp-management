<?php
$c=ftp_connect('ftpupload.net');
ftp_login($c,'if0_42230840','rHZkhphFhy2');
ftp_pasv($c,true);
ftp_chdir($c,'htdocs');
ftp_put($c,'.htaccess','.htaccess',FTP_BINARY);
ftp_close($c);
echo 'done';
