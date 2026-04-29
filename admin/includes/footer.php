        </div><!-- /.admin-content -->
    </main>
</div><!-- /.admin-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar toggle
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    document.querySelector('.admin-wrapper').classList.toggle('sidebar-collapsed');
});

// Auto-close alerts
document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
    setTimeout(function() { alert.style.display = 'none'; }, 5000);
});
</script>
</body>
</html>
