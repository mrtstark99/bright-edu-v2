</div><!-- /.admin-content -->
</div><!-- /#admin-main -->

<footer style="margin-left:var(--sidebar-w);background:#fff;border-top:1px solid #e2e8f0;padding:0 28px;height:var(--footer-h);font-size:12px;color:#94a3b8;display:flex;align-items:center;justify-content:space-between;gap:12px;">
    <span>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</span>
    <a href="/" target="_blank" style="color:#cbd5e1;text-decoration:none;font-size:11px;display:flex;align-items:center;gap:4px;">
        <i class="bi bi-globe2"></i> Xem trang web
    </a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
</body>
</html>
