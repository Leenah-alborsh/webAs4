<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("UPDATE news SET status='deleted' WHERE id=?");
    $stmt->execute([$id]);
    header('Location: news_list.php?deleted=1');
    exit;
}

$rows = $pdo->query("
SELECT n.id, n.title, n.status, n.created_at, c.name AS category, u.name AS author
FROM news n
JOIN categories c ON c.id = n.category_id
JOIN users u ON u.id = n.user_id
WHERE n.status='active'
ORDER BY n.id DESC
")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<h1 class="h4 mb-3">جميع الأخبار</h1>
<?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success">تمت إضافة الخبر.</div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">تم حذف الخبر (حذف ناعم).</div><?php endif; ?>
<table class="table table-striped align-middle">
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
                <td><?php echo htmlspecialchars($r['title']); ?></td>
                <td><?php echo htmlspecialchars($r['category']); ?></td>
                <td><?php echo htmlspecialchars($r['author']); ?></td>
                <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                <td>
                    <a class="btn btn-sm btn-outline-primary"
                        href="edit_news.php?id=<?php echo (int) $r['id']; ?>">تعديل</a>
                    <a class="btn btn-sm btn-outline-danger" href="news_list.php?delete=<?php echo (int) $r['id']; ?>"
                        onclick="return confirm('تأكيد حذف الخبر؟ (سيصبح في المحذوفات)');">حذف</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/includes/footer.php'; ?>