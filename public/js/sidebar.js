document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggle-sidebar');
    const sidebar = document.getElementById('admin-sidebar');

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed'); // Toggle the 'collapsed' class
    });
});
