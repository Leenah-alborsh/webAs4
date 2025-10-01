<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';

$errors = [];
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $errors[] = 'اسم الفئة مطلوب';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories(name) VALUES (?)");
            $stmt->execute([$name]);
            header('Location: categories.php?added=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'تعذر إضافة الفئة (قد يكون الاسم مكرراً)';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<h1 class="h4 mb-3">إضافة فئة</h1>
<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method="post" class="row g-3 col-lg-6">
    <div class="col-12">
        <label class="form-label">اسم الفئة</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" required>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">حفظ</button>
        <a class="btn btn-secondary" href="categories.php">رجوع</a>
    </div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>