    </div> <!-- Close container-fluid -->
</div> <!-- Close #content -->
</div> <!-- Close d-flex -->

<!-- Bootstrap Bundle with Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom script app.js -->
<?php
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($basePath === '/') {
    $basePath = '';
}
?>
<script>
    window.appBasePath = '<?php echo $basePath; ?>';
</script>
<script src="<?php echo $basePath; ?>/js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>
