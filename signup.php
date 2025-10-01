<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($name === '')
        $errors[] = 'الاسم مطلوب';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'بريد إلكتروني غير صالح';
    if (strlen($password) < 6)
        $errors[] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    if ($password !== $confirm)
        $errors[] = 'تأكيد كلمة المرور غير متطابق';

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'البريد الإلكتروني مستخدم مسبقاً';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users(name,email,password) VALUES (?,?,?)");
            $stmt->execute([$name, $email, $hash]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<h1 class="h4 mb-3">إنشاء حساب</h1>
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
        <label class="form-label">الاسم</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" required>
    </div>
    <div class="col-12">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">كلمة المرور</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">تأكيد كلمة المرور</label>
        <input type="password" name="confirm" class="form-control" required>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">تسجيل</button>
        <a href="login.php" class="btn btn-link">لدي حساب؟ دخول</a>
    </div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>