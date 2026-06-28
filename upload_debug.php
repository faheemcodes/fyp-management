<?php
$c=ftp_connect('ftpupload.net');
ftp_login($c,'if0_42230840','rHZkhphFhy2');
ftp_pasv($c,true);
ftp_chdir($c,'htdocs');
ftp_put($c,'index.php','root_index_debug.php',FTP_BINARY);
ftp_delete($c, 'index2.html'); // Remove index2.html just in case
ftp_close($c);
echo 'done';
