<script>
    (function setTheme() {
        let currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', currentTheme);

        const btn1 = document.getElementById('themeChangerBtn');
        const btn2 = document.getElementById('themeChangerBtn-2');

        if (btn1) {
            btn1.innerHTML = (currentTheme === 'light') ? '<i class="ri-moon-fill"></i>' : '<i class="ri-sun-fill"></i>';
        }

        if (btn2) {
            btn2.innerHTML = (currentTheme === 'light') ? '<i class="ri-moon-fill"></i>' : '<i class="ri-sun-fill"></i>';
        }
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
    let current_url = window.location.href;
    let current_arr = current_url.split("/");

    links.forEach((link) => {
        if (!link.href) return;
        let link_arr = link.href.split("/");
        if (current_arr[3] && link_arr[3] && current_arr[3] === link_arr[3]) {
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
        let dialog = document.getElementById(id);
        dialog.showModal();
    }

    function closeDialog() {
        document.querySelectorAll('dialog').forEach((dialog) => {
            dialog.close();
        })
    }

    function toastonFormSubmit(ID, message, status, time) {
        document.getElementByID(ID).addEventListener('submit', () => {
            runToast(message, status, time);
        })
    }

    function toastonLinkClick(message, status, time) {
        runToast(message, status, time);
    }

    function confirmBeforeAction(url, message) {
        if (confirm(message)) {
            window.location.href = url;
        } else {
            return 0;
        }

    }
</script>