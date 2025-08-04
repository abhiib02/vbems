<script>
    (function setTheme() {
        let currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', currentTheme);
        let newTheme = currentTheme === 'light' ? 'dark' : 'light';
        const btn1 = document.getElementById('themeChangerBtn');
        const btn2 = document.getElementById('themeChangerBtn-2');

        const iconHTML = newTheme === 'light' ? '<i class="ri-moon-fill"></i>' : '<i class="ri-sun-fill"></i>';

        if (btn1) btn1.innerHTML = iconHTML;
        if (btn2) btn2.innerHTML = iconHTML;
    })();

    function changeTheme() {
        let currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'light') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
        document.getElementById('themeChangerBtn').innerHTML = (localStorage.getItem('theme') === 'light') ? '<i class="ri-moon-fill"></i>' : '<i class="ri-sun-fill"></i>';
        document.getElementById('themeChangerBtn-2').innerHTML = (localStorage.getItem('theme') === 'light') ? '<i class="ri-moon-fill"></i>' : '<i class="ri-sun-fill"></i>';
    }
</script>
<script>
    let links = document.querySelectorAll('.dashboard-link');
    const currentPath = new URL(window.location.href).pathname;

    links.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (linkPath === currentPath) {
            link.classList.add('active');
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<script src="/js/ezToast.js?v=1.2"></script>
<script>
    function openDialogModal(id) {
        let dialog = document.getElementByID(id);
        dialog.showModal();
    }

    function closeDialog() {
        document.querySelectorAll('dialog').forEach((dialog) => {
            dialog.close();
        })
    }

    function toastonFormSubmit(ID, message, status, time) {
        const form = document.getElementById(ID);
        if (!form) return;
        form.addEventListener('submit', () => {
            runToast(message, status, time);
        });
    }

    function toastonLinkClick(message, status, time) {
        runToast(message, status, time);
    }

    function confirmBeforeAction(url, message) {
        if (confirm(message)) {
            window.location.href = url;
        }
    }
</script>