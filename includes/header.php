<?php
// includes/header.php (نسخة Light فقط)
if (session_status() === PHP_SESSION_NONE)
    session_start();
?>
<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لوحة إدارة الأخبار</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f7fb;
            --text: #0f172a;
            --muted: #64748b;
            --card: #ffffff;
            --border: #e5e7eb;
            --ring: #cbd5e1;

            --sidebar-bg: #0b1222;
            --sidebar-text: #cbd5e1;
            --sidebar-hover: #0f172a;

            --accent: #2563eb;
            --accent-2: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;

            --shadow: 0 6px 20px rgba(0, 0, 0, .08);
            --radius: 16px;
            --glass: rgba(255, 255, 255, .75);
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            background: var(--bg);
            color: var(--text);
        }

        .app-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 270px 1fr;
            background: var(--bg);
        }

        @media (max-width:992px) {
            .app-shell {
                grid-template-columns: 1fr
            }

            .sidebar {
                position: fixed;
                inset-inline-start: -280px;
                width: 270px;
                transition: .25s;
                z-index: 1040
            }

            .sidebar.open {
                inset-inline-start: 0
            }

            .overlay {
                display: block
            }
        }

        .sidebar {
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            border-inline-end: 1px solid var(--border)
        }

        .brand {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px
        }

        .brand .logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent))
        }

        .nav-link {
            color: var(--sidebar-text);
            border-radius: 10px;
            padding: 10px 12px;
            margin: 6px 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: .2s
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--sidebar-hover);
            color: #fff
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            background: var(--glass);
            backdrop-filter: blur(8px)
        }

        .container-page {
            padding: 24px
        }

        .stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: var(--shadow)
        }

        .stat .k {
            font-size: 28px;
            font-weight: 800
        }

        .badge-soft {
            background: transparent;
            border: 1px solid var(--ring);
            color: var(--muted);
            border-radius: 999px;
            padding: .25rem .6rem;
            font-size: .8rem
        }

        .card {
            background: var(--card);
            border-color: var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow)
        }

        .btn {
            border-radius: 12px
        }

        .btn-outline-primary {
            color: var(--accent);
            border-color: var(--accent)
        }

        .btn-outline-primary:hover {
            background: var(--accent);
            color: #fff
        }

        .btn-outline-success {
            color: var(--accent-2);
            border-color: var(--accent-2)
        }

        .btn-outline-success:hover {
            background: var(--accent-2);
            color: #fff
        }

        .btn-outline-danger {
            color: var(--danger);
            border-color: var(--danger)
        }

        .btn-outline-danger:hover {
            background: var(--danger);
            color: #fff
        }

        .btn-outline-warning {
            color: var(--warning);
            border-color: var(--warning)
        }

        .btn-outline-warning:hover {
            background: var(--warning);
            color: #111827
        }

        .table {
            color: var(--text)
        }

        .table thead th {
            border-bottom: 1px solid var(--border);
            color: var(--muted)
        }

        .table td,
        .table th {
            border-color: var(--border)
        }

        .table-hover tbody tr:hover {
            background: rgba(148, 163, 184, .08)
        }

        .form-control,
        .form-select,
        .form-check-input {
            background: var(--card);
            color: var(--text);
            border-color: var(--border)
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 .2rem rgba(56, 189, 248, .25)
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--sidebar-hover);
            color: #cbd5e1;
            border: 1px solid var(--border)
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: #0006;
            z-index: 1030
        }
    </style>
</head>

<body>
    <div class="app-shell">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="logo"></div>
                <div>
                    <div class="fw-bold text-white">News CMS</div>
                    <small class="text-muted">لوحة الإدارة</small>
                </div>
            </div>
            <nav class="mt-2">
                <a class="nav-link <?php echo str_contains($_SERVER['PHP_SELF'], 'dashboard.php') ? 'active' : ''; ?>"
                    href="dashboard.php"><i class="bi bi-speedometer2"></i> لوحة التحكم</a>
                <div class="px-3 mt-2 mb-1 text-uppercase small text-muted">الفئات</div>
                <a class="nav-link" href="add_category.php"><i class="bi bi-plus-circle"></i> إضافة فئة</a>
                <a class="nav-link" href="categories.php"><i class="bi bi-grid"></i> عرض الفئات</a>

                <div class="px-3 mt-3 mb-1 text-uppercase small text-muted">الأخبار</div>
                <a class="nav-link" href="add_news.php"><i class="bi bi-file-plus"></i> إضافة خبر</a>
                <a class="nav-link" href="news_list.php"><i class="bi bi-newspaper"></i> عرض جميع الأخبار</a>
                <a class="nav-link" href="deleted_news.php"><i class="bi bi-trash"></i> الأخبار المحذوفة</a>
            </nav>

            <div class="p-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="chip w-100 justify-content-between">
                        <span><i class="bi bi-person"></i> <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                        <a class="btn btn-sm btn-outline-light" href="logout.php">خروج</a>
                    </div>
                <?php else: ?>
                    <div class="d-grid gap-2">
                        <a class="btn btn-light" href="login.php">دخول</a>
                        <a class="btn btn-success" href="signup.php">إنشاء حساب</a>
                    </div>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Main -->
        <main>
            <div class="topbar">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary d-lg-none" id="toggleSidebar"><i
                            class="bi bi-list"></i></button>
                    <span class="fw-semibold">مرحبًا 👋</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a class="btn btn-sm btn-outline-primary" href="add_news.php"><i class="bi bi-plus-lg"></i> خبر
                        جديد</a>
                </div>
            </div>
            <div class="container-page">