<?php
// ... injecting into AdminController.php
$file = 'src/Controller/AdminController.php';
$content = file_get_contents($file);

$methods = <<<'EOD'

    public function batches() {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM academic_batches ORDER BY created_at DESC");
        $batches = $stmt->fetchAll();
        $this->render('admin/batches', ['batches' => $batches]);
    }

    public function createBatch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $this->flash('error', 'Batch name is required.');
                redirect('/admin/batches');
            }
            $db = \Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO academic_batches (name, is_active, is_registration_open) VALUES (?, 1, 0)");
            try {
                $stmt->execute([$name]);
                $this->flash('success', 'Batch created successfully.');
            } catch (\Exception $e) {
                $this->flash('error', 'Failed to create batch. Ensure the name is unique.');
            }
        }
        redirect('/admin/batches');
    }

    public function toggleBatch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['batch_id'] ?? 0;
            $action = $_POST['action'] ?? '';
            $db = \Database::getInstance()->getConnection();

            if ($action === 'set_registration') {
                // Only one can be open for registration
                $db->exec("UPDATE academic_batches SET is_registration_open = 0");
                $stmt = $db->prepare("UPDATE academic_batches SET is_registration_open = 1 WHERE id = ?");
                $stmt->execute([$id]);
                $this->flash('success', 'Registration batch updated.');
            } elseif ($action === 'toggle_active') {
                $stmt = $db->prepare("UPDATE academic_batches SET is_active = NOT is_active WHERE id = ?");
                $stmt->execute([$id]);
                $this->flash('success', 'Batch active status toggled.');
            }
        }
        redirect('/admin/batches');
    }
EOD;

// Insert before the last closing brace
$pos = strrpos($content, '}');
if ($pos !== false) {
    $content = substr_replace($content, $methods . "\n}", $pos, 1);
    file_put_contents($file, $content);
    echo "Methods added to AdminController.php\n";
} else {
    echo "Failed to find end of class.\n";
}
