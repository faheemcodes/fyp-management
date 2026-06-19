<?php
// SMTP Mail Settings Configuration

return [
    'smtp_host'     => 'smtp.gmail.com',         // SMTP server host (e.g. smtp.gmail.com for Gmail, smtp.office365.com for Outlook)
    'smtp_port'     => 587,                      // SMTP port (587 for TLS/STARTTLS, 465 for SSL)
    'smtp_auth'     => true,                     // Set to true to enable SMTP authentication
    'smtp_username' => 'your_email@gmail.com',   // Your SMTP email address
    'smtp_password' => 'your_app_password',      // Your SMTP password (or App Password if using Gmail/Outlook with 2FA)
    'smtp_secure'   => 'tls',                    // TLS or SSL security
    'from_email'    => 'noreply@fypmanagement.com', // Sender email address
    'from_name'     => 'FYP Management Portal'   // Sender name
];
