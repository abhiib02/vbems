<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js">
</script>

<section class="mobile-nav hide-on-desktop">
    <div class="d-flex justify-content-evenly bg-dark h-100">
        <a href="/dashboard" class="m-1 dashboard-link text-center align-content-center" data-linkName="Dashboard"><i
                class="ri-layout-masonry-fill"></i></a>
        <a href="/employee-list" class="m-1 dashboard-link text-center align-content-center"
            data-linkName="Employees"><i class="ri-user-2-fill"></i></a>
        <a href="/departments-list" class="m-1 dashboard-link text-center align-content-center"
            data-linkName="Departments"><i class="ri-group-2-fill"></i></a>
        <a href="/holidays-list" class="m-1 dashboard-link text-center align-content-center" data-linkName="Holidays"><i
                class="ri-bard-fill"></i></a>
        <a href="/leaveRequests" class="m-1 dashboard-link text-center align-content-center" data-linkName="Requests"><i
                class="ri-user-minus-fill"></i></a>
        <a href="/attendance" class="m-1 dashboard-link text-center align-content-center" data-linkName="Attendance"><i
                class="ri-calendar-view"></i></a>
    </div>
</section>

<div class="">
    <div class="d-flex ">
        <div class="">
            <aside class="px-1 bg-dark text-light hide-on-tab hide-on-mobile sidebar position-sticky" style="top:0%;">
                <div class="vh100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="text-light pt-2">
                            <img src="<?= env('appLogoPath') ?>" style="width:52px;">
                        </div>
                        <br>
                        <div class="row px-3">

                            <a href="/dashboard" class="btn  dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Dashboard"><i
                                    class="ri-layout-masonry-fill"></i></a>
                            <a href="/employee-list" class="btn  dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Employees"><i
                                    class="ri-user-2-fill"></i>
                            </a>
                            <a href="/departments-list" class="btn  dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Departments"><i
                                    class="ri-group-2-fill"></i>
                            </a>

                            <a href="/holidays-list" class="btn  dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Holidays"><i
                                    class="ri-bard-fill"></i>
                            </a>
                            <a href="/leaveRequests"
                                class="position-relative btn  dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Leave Requests"><i
                                    class="ri-user-minus-fill"></i>

                                <?php if ($leaveRequestsCount > 0): ?>
                                    <span class="badge text-bg-danger position-absolute fs-6"
                                        style="top: 7px;left: 35px"><?= $leaveRequestsCount ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="/attendance" class="btn  dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Attendance"><i
                                    class="ri-calendar-view"></i></a>
                            <a href="/options-list" class="btn  dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Options & Flags"><i class="ri-flag-2-line"></i></a>

                        </div>
                    </div>


                    <div class="row px-3 ">
                        <button onclick="changeTheme()" id="themeChangerBtn" class="btn btn-outline-light fs-3 p-0 mb-1 hide-on-mobile"> <i class="ri-sun-fill"></i></button>
                        <button onclick="confirmBeforeAction('/logout','Do You Want to Logout ?');" type="button"
                            class="btn btn-danger fs-3 p-0 mb-1 hide-on-mobile" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Logout"> <i class="ri-logout-circle-r-line"></i></button>
                    </div>
                </div>

            </aside>
        </div>
        <div class="w-100 ">
            <section class=" bg-secondary-subtle p-0">
                <nav class="navbar bg-dark" data-bs-theme="dark">
                    <div class="container-fluid">
                        <button onclick="history.back()" class="btn btn-outline-light"><i class="ri-arrow-left-line"></i> Back</button>
                        <span class="navbar-brand mb-0 h1"><?= $title ?></span>
                        <div>
                            <button onclick="changeTheme()" id="themeChangerBtn-2" class="btn btn-outline-light mb-1 hide-on-desktop"> <i class="ri-sun-fill"></i></button>
                            <button onclick="confirmBeforeAction('/logout','Do You Want to Logout ?');" type="button"
                                class="btn btn-danger mb-1 hide-on-desktop"> <i class="ri-logout-circle-r-line"></i> Logout</button>
                        </div>


                    </div>

                </nav>
                <section class="vh100-sub-navbar pt-3 container-fluid">
                    <div class="">