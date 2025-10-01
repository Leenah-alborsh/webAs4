</div> 
</main>
</div> 

<div class="overlay" id="overlay"></div>

<script>
    (function initTheme() {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark' || (!saved && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.body.classList.add('dark');
        }
    })();

    document.getElementById('toggleTheme')?.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
    });

    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('overlay');
    document.getElementById('toggleSidebar')?.addEventListener('click', () => {
        sb.classList.toggle('open');
        ov.style.display = sb.classList.contains('open') ? 'block' : 'none';
    });
    ov?.addEventListener('click', () => {
        sb.classList.remove('open');
        ov.style.display = 'none';
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>