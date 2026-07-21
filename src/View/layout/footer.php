    </div> <!-- Close container-fluid -->
</div> <!-- Close #content -->
</div> <!-- Close d-flex -->

<!-- Bootstrap Bundle with Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Custom script app.js -->
<?php
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($basePath === '/') {
    $basePath = '';
}
?>
<script>
    window.appBasePath = '<?php echo $basePath; ?>';
    window.csrfToken = '<?php echo $_SESSION['csrf_token'] ?? ''; ?>';
</script>
<script src="<?php echo $basePath; ?>/js/app.js?v=<?php echo time(); ?>"></script>
<?php
// Include Chatbot Widget for students (except on the live chat page)
$isChatPage = strpos($_SERVER['REQUEST_URI'] ?? '', '/student/chat') !== false;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'student' && !$isChatPage) {
    $chatbotFile = __DIR__ . '/../student/chatbot_widget.php';
    if (file_exists($chatbotFile)) {
        include $chatbotFile;
    }
}
?>
</body>
</html>
