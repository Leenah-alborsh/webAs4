<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';

if (isset($_GET['restore'])) {
    $id = (int) $_GET['restore'];
    $stmt = $pdo->prepare("UPDATE news SET status='active' WHERE id=?");
    $stmt->execute([$id]);
    header('Location: deleted_news.php?restored=1');
    exit;
}

$rows = $pdo->query("
SELECT n.id, n.title, n.created_at, c.name AS category, u.name AS author
FROM news n
JOIN categories c ON c.id = n.category_id
JOIN users u ON u.id = n.user_id
WHERE n.status='deleted'
ORDER BY n.id DESC
")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<h1 class="h4 mb-3">الأخبار المحذوفة</h1>
<?php if (isset($_GET['restored'])): ?>
    <div class="alert alert-success">تم استرجاع الخبر.</div>
<?php endif; ?>

<?php if (!$rows): ?>
    <div class="alert alert-secondary">لا توجد أخبار محذوفة حالياً.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>العنوان</th>
                    <th>الفئة</th>
                    <th>الكاتب</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?php echo (int) $r['id']; ?></td>
                        <td class="fw-semibold"><?php echo htmlspecialchars($r['title']); ?></td>
                        <td><?php echo htmlspecialchars($r['category']); ?></td>
                        <td><?php echo htmlspecialchars($r['author']); ?></td>
                        <td class="text-muted small"><?php echo htmlspecialchars($r['created_at']); ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-success"
                                href="deleted_news.php?restore=<?php echo (int) $r['id']; ?>"
                                onclick="return confirm('استرجاع هذا الخبر؟');">
                                <i class="bi bi-arrow-counterclockwise"></i> استرجاع
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>