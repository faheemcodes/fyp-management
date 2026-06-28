<?php
$c=ftp_connect('ftpupload.net');
if(!$c) die("no connect");
if(!ftp_login($c,'if0_42230840','rHZkhphFhy2')) die("no login");
ftp_pasv($c,true);
ftp_chdir($c,'htdocs');

// Delete htaccess
@ftp_delete($c, '.htaccess');

// Upload a clean hello world
$hello = "<?php echo 'HELLO WORLD - THIS IS A TEST'; ?>";
file_put_contents('hello_test.php', $hello);
ftp_put($c, 'index.php', 'hello_test.php', FTP_BINARY);

ftp_close($c);
echo "done";
