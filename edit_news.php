<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM news WHERE id=?");
$stmt->execute([$id]);
$news = $stmt->fetch();
if (!$news) {
    header('Location: news_list.php');
    exit;
}

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$errors = [];
$title = $news['title'];
$body = $news['body'];
$category_id = (int) $news['category_id'];
$image_path = $news['image_path'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $body = trim($_POST['body'] ?? '');

    if ($title === '')
        $errors[] = 'العنوان مطلوب';
    if ($category_id <= 0)
        $errors[] = 'يرجى اختيار الفئة';
    if ($body === '')
        $errors[] = 'التفاصيل مطلوبة';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $safeName = 'img_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        $dest = __DIR__ . '/uploads/' . $safeName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            $image_path = 'uploads/' . $safeName;
        } else {
            $errors[] = 'فشل رفع الصورة';
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE news SET title=?, category_id=?, body=?, image_path=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([$title, $category_id, $body, $image_path, $id]);
        header('Location: news_list.php?updated=1');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>
<h1 class="h4 mb-3">تعديل خبر</h1>
<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method="post" enctype="multipart/form-data" class="row g-3 col-lg-8">
    <div class="col-12">
        <label class="form-label">العنوان</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">الفئة</label>
        <select name="category_id" class="form-select" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo (int) $cat['id']; ?>" <?php if ($category_id == $cat['id'])
                        echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">التفاصيل</label>
        <textarea name="body" rows="6" class="form-control" required><?php echo htmlspecialchars($body); ?></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">استبدال الصورة </label>
        <input type="file" name="image" accept="image/*" class="form-control">
        <?php if ($image_path): ?>
            <div class="mt-2">
                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="" style="max-height:120px">
            </div>
        <?php endif; ?>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">تحديث</button>
        <a class="btn btn-secondary" href="news_list.php">رجوع</a>
    </div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>