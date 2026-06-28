<?php
$ftp_server = "ftpupload.net";
$ftp_username = "if0_42230840";
$ftp_userpass = "rHZkhphFhy2";

echo "Connecting to $ftp_server...\n";
$conn_id = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
$login_result = ftp_login($conn_id, $ftp_username, $ftp_userpass) or die("Could not login");
ftp_pasv($conn_id, true);

echo "Connected successfully. Changing directory to htdocs...\n";
if (@ftp_chdir($conn_id, "htdocs")) {
    echo "Changed to htdocs\n";
} else {
    echo "Could not change to htdocs. Uploading to root.\n";
}

$ignore = ['.', '..', '.git', 'upload_ftp.php', 'database.sqlite', 'schema.sql', 'list_tables.php', 'find_tables.php'];

function upload_dir($conn_id, $local_dir, $remote_dir, $ignore) {
    $files = scandir($local_dir);
    foreach ($files as $file) {
        if (in_array($file, $ignore) && $local_dir === '.') continue;
        if ($file === '.' || $file === '..') continue;

        $local_file = $local_dir . '/' . $file;
        $remote_file = $remote_dir === '.' ? $file : $remote_dir . '/' . $file;

        if (is_dir($local_file)) {
            @ftp_mkdir($conn_id, $remote_file);
            upload_dir($conn_id, $local_file, $remote_file, $ignore);
        } else {
            if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
                echo "Uploaded $local_file -> $remote_file\n";
            } else {
                echo "Failed to upload $local_file\n";
            }
        }
    }
}

upload_dir($conn_id, '.', '.', $ignore);

ftp_close($conn_id);
echo "Upload Complete!\n";
