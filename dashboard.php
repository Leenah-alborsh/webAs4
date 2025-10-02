<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

// إحصائيات سريعة
$totalCats = (int) $pdo->query("SELECT COUNT(*) AS c FROM categories")->fetch()['c'];
$totalNews = (int) $pdo->query("SELECT COUNT(*) AS c FROM news WHERE status='active'")->fetch()['c'];
$deletedNews = (int) $pdo->query("SELECT COUNT(*) AS c FROM news WHERE status='deleted'")->fetch()['c'];
$lastNews = $pdo->query("
  SELECT n.id, n.title, c.name AS category, DATE_FORMAT(n.created_at,'%Y-%m-%d %H:%i') as created_at
  FROM news n JOIN categories c ON c.id=n.category_id
  ORDER BY n.id DESC LIMIT 6
")->fetchAll();
?>
<div class="mb-4">
    <h1 class="h4 fw-bold">لوحة التحكم</h1>
    <p class="text-muted mb-0">إدارة الفئات والأخبار بسرعة مع نظرة عامة.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">إجمالي الفئات</div>
                    <div class="k"><?php echo $totalCats; ?></div>
                </div>
                <div class="display-6 text-success"><i class="bi bi-grid"></i></div>
            </div>
            <a class="btn btn-sm btn-outline-success mt-3" href="add_category.php"><i class="bi bi-plus-circle"></i>
                إضافة فئة</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">الأخبار النشطة</div>
                    <div class="k"><?php echo $totalNews; ?></div>
                </div>
                <div class="display-6 text-primary"><i class="bi bi-newspaper"></i></div>
            </div>
            <a class="btn btn-sm btn-outline-primary mt-3" href="add_news.php"><i class="bi bi-file-plus"></i> إضافة
                خبر</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">المحذوفات </div>
                    <div class="k"><?php echo $deletedNews; ?></div>
                </div>
                <div class="display-6 text-danger"><i class="bi bi-trash"></i></div>
            </div>
            <a class="btn btn-sm btn-outline-danger mt-3" href="deleted_news.php"><i class="bi bi-eye"></i> عرض
                المحذوفة</a>
        </div>
    </div>
</div>

<div class="card border-0" style="border-radius:16px">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h6 mb-0">آخر الأخبار</h2>
            <a class="btn btn-sm btn-outline-secondary" href="news_list.php">عرض الكل</a>
        </div>
        <?php if (!$lastNews): ?>
            <div class="text-muted">لا توجد أخبار بعد. ابدأ بإضافة أول خبر.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الفئة</th>
                            <th>التاريخ</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lastNews as $n): ?>
                            <tr>
                                <td><?php echo (int) $n['id']; ?></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($n['title']); ?></td>
                                <td><span class="badge text-bg-secondary"><?php echo htmlspecialchars($n['category']); ?></span>
                                </td>
                                <td class="text-muted small"><?php echo htmlspecialchars($n['created_at']); ?></td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="edit_news.php?id=<?php echo (int) $n['id']; ?>"><i class="bi bi-pencil"></i>
                                        تعديل</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
