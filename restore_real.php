<?php
$c=ftp_connect('ftpupload.net');
ftp_login($c,'if0_42230840','rHZkhphFhy2');
ftp_pasv($c,true);
ftp_chdir($c,'htdocs');

// The real root index.php
$index = "<?php\n\$_SERVER['SCRIPT_NAME'] = '/public/index.php';\nrequire __DIR__ . '/public/index.php';\n";
file_put_contents('real_index.php', $index);
ftp_put($c, 'index.php', 'real_index.php', FTP_BINARY);

// The real root .htaccess
$hta = "<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteCond %{REQUEST_FILENAME} !-f\n    RewriteCond %{REQUEST_FILENAME} !-d\n    RewriteRule ^(.*)$ index.php [QSA,L]\n</IfModule>\n";
file_put_contents('real_hta.txt', $hta);
ftp_put($c, '.htaccess', 'real_hta.txt', FTP_ASCII);

ftp_close($c);
echo 'done';
