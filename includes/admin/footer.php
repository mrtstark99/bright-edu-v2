</div><!-- /.admin-content -->
</div><!-- /#admin-main -->

<footer id="admin-footer">
  <div class="admin-footer-inner">
      <span>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</span>
      <a href="/" target="_blank" class="admin-footer-link">
          <i class="bi bi-globe2"></i> Xem trang web
      </a>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var body = document.body;
        var html = document.documentElement;
        var sidebar = document.getElementById('admin-sidebar');
        var mobileToggle = document.getElementById('sidebar-toggle');
        var desktopToggle = document.getElementById('sidebar-collapse');
        var themeToggle = document.getElementById('theme-toggle');
        var scrim = document.getElementById('sidebar-scrim');

        function setMobileMenu(open) {
            sidebar.classList.toggle('open', open);
            body.classList.toggle('mobile-menu-open', open);
            mobileToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        mobileToggle.addEventListener('click', function() {
            setMobileMenu(!sidebar.classList.contains('open'));
        });
        scrim.addEventListener('click', function() { setMobileMenu(false); });

        var sidebarCollapsed = false;
        try { sidebarCollapsed = localStorage.getItem('admin-sidebar-collapsed') === 'true'; } catch (e) {}
        body.classList.toggle('sidebar-collapsed', sidebarCollapsed);
        desktopToggle.setAttribute('aria-expanded', sidebarCollapsed ? 'false' : 'true');
        desktopToggle.setAttribute('aria-label', sidebarCollapsed ? 'Mở rộng thanh điều hướng' : 'Thu gọn thanh điều hướng');
        desktopToggle.title = sidebarCollapsed ? 'Mở rộng thanh điều hướng' : 'Thu gọn thanh điều hướng';
        desktopToggle.addEventListener('click', function() {
            var collapsed = body.classList.toggle('sidebar-collapsed');
            desktopToggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            desktopToggle.setAttribute('aria-label', collapsed ? 'Mở rộng thanh điều hướng' : 'Thu gọn thanh điều hướng');
            desktopToggle.title = collapsed ? 'Mở rộng thanh điều hướng' : 'Thu gọn thanh điều hướng';
            try { localStorage.setItem('admin-sidebar-collapsed', collapsed ? 'true' : 'false'); } catch (e) {}
        });

        document.querySelectorAll('.sidebar-link').forEach(function(link) {
            if (!link.title) link.title = link.textContent.replace(/\s+/g, ' ').trim();
        });

        document.querySelectorAll('.sidebar-label').forEach(function(label, index) {
            var group = document.createElement('div');
            var inner = document.createElement('div');
            var key = 'admin-sidebar-group-' + index;
            var next = label.nextSibling;

            group.className = 'sidebar-group';
            inner.className = 'sidebar-group-inner';
            group.appendChild(inner);
            label.parentNode.insertBefore(group, next);

            while (next && !(next.nodeType === 1 && (next.classList.contains('sidebar-divider') || next.classList.contains('sidebar-label')))) {
                var current = next;
                next = next.nextSibling;
                inner.appendChild(current);
            }

            label.setAttribute('role', 'button');
            label.setAttribute('tabindex', '0');
            label.setAttribute('data-sidebar-group', key);
            label.insertAdjacentHTML('beforeend', '<i class="bi bi-chevron-down" aria-hidden="true"></i>');

            var collapsed = false;
            try { collapsed = localStorage.getItem(key) === 'collapsed'; } catch (e) {}
            if (inner.querySelector('.sidebar-link.active')) collapsed = false;

            function setGroupCollapsed(value) {
                group.classList.toggle('is-collapsed', value);
                label.setAttribute('aria-expanded', value ? 'false' : 'true');
                try { localStorage.setItem(key, value ? 'collapsed' : 'expanded'); } catch (e) {}
            }
            setGroupCollapsed(collapsed);

            label.addEventListener('click', function() {
                setGroupCollapsed(!group.classList.contains('is-collapsed'));
            });
            label.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    label.click();
                }
            });
        });

        function syncThemeIcon() {
            var dark = html.classList.contains('dark');
            themeToggle.querySelector('i').className = dark ? 'bi bi-sun' : 'bi bi-moon-stars';
            themeToggle.setAttribute('aria-label', dark ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối');
        }
        syncThemeIcon();
        themeToggle.addEventListener('click', function() {
            html.classList.toggle('dark');
            syncThemeIcon();
            try { localStorage.setItem('admin-theme', html.classList.contains('dark') ? 'dark' : 'light'); } catch (e) {}
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) setMobileMenu(false);
        });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') setMobileMenu(false);
        });

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
