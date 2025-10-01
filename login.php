<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']];
        header('Location: dashboard.php');
        exit;
    } else {
        $errors[] = 'بيانات الدخول غير صحيحة';
    }
}

include __DIR__ . '/includes/header.php';
?>
<h1 class="h4 mb-3">تسجيل الدخول</h1>
<?php if (isset($_GET['registered'])): ?>
    <div class="alert alert-success">تم إنشاء الحساب بنجاح، سجّل الدخول.</div>
<?php endif; ?>
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
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" required>
    </div>
    <div class="col-12">
        <label class="form-label">كلمة المرور</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">دخول</button>
        <a href="signup.php" class="btn btn-link">إنشاء حساب</a>
    </div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>