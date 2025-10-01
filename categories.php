<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';

$cats = $pdo->query("SELECT id, name, created_at FROM categories ORDER BY id DESC")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<h1 class="h4 mb-3">جميع الفئات</h1>
<?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success">تمت إضافة الفئة.</div><?php endif; ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>تاريخ الإنشاء</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cats as $c): ?>
            <tr>
                <td><?php echo (int) $c['id']; ?></td>
                <td><?php echo htmlspecialchars($c['name']); ?></td>
                <td><?php echo htmlspecialchars($c['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/includes/footer.php'; ?>