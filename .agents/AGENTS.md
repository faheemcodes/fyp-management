# Security Guidelines

When editing the FYP Management Portal, strictly adhere to the following security rules:
1. **XSS Protection:** Never output user or database data raw (`echo $var`). ALWAYS use `htmlspecialchars($var ?? '', ENT_QUOTES, 'UTF-8')`.
2. **CSRF Protection:** Every state-changing action (POST requests) must validate the CSRF token. All forms must include `<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">`. AJAX requests must include the `X-CSRF-TOKEN` header.
3. **File Uploads:** Never trust the client-provided file extension. Validate the MIME type securely (e.g. using `finfo`), and map the MIME type to a hardcoded safe extension. Ensure flawed logic like `&&` isn't bypassing checks.
4. **Information Exposure:** Do not expose raw database exceptions or error messages (`$e->getMessage()`) to the user. Log them if necessary, but return generic error messages.
5. **Session Fixation:** When authenticating a user, always generate a new session ID with `session_regenerate_id(true)`.
6. **SQL Injection:** Continue strictly using PDO prepared statements. Do not concatenate strings into SQL queries.
