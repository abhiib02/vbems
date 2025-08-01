<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js">
</script>

<section class="mobile-nav hide-on-desktop">
    <div class="d-flex justify-content-evenly bg-dark h-100">
        <a href="/account" class="m-1 dashboard-link text-center align-content-center" data-linkName="Dashboard">
            <i class="ri-layout-masonry-fill"></i>
        </a>
        <a href="/profile" class="m-1 dashboard-link text-center align-content-center" data-linkName="Profile">
            <i class="ri-user-2-fill"></i>
        </a>
        <a href="/my-leaves" class="m-1 dashboard-link text-center align-content-center" data-linkName="Leaves">
            <i class="ri-user-minus-fill"></i>
        </a>
        <a href="/leaveRequestForm" class="m-1 dashboard-link text-center align-content-center"
            data-linkName="Leave Request">
            <i class="ri-user-voice-fill"></i>
        </a>
        <a href="/attendanceInfo" class="m-1 dashboard-link text-center align-content-center"
            data-linkName="Attendance">
            <i class="ri-user-follow-fill"></i>
        </a>
    </div>
</section>


<div class="">
    <div class="d-flex ">
        <div class="">
            <aside class="px-1 bg-dark text-light hide-on-tab hide-on-mobile sidebar position-sticky"
                style="z-index: 0;top:0%;">
                <div class="vh100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="text-light pt-2">
                            <img src="<?= env('appLogoPath') ?>" style="width:52px;">
                        </div>
                        <br>
                        <div class="row px-3">

                            <a href="/account" class="btn mb-1 dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Dashboard">
                                <i class="ri-layout-masonry-fill"></i>
                            </a>
                            <a href="/profile" class="btn mb-1 dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Profile">
                                <i class="ri-user-2-fill"></i>
                            </a>
                            <a href="/my-leaves" class="btn mb-1 dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Leaves">
                                <i class="ri-user-minus-fill"></i>
                            </a>
                            <a href="/leaveRequestForm" class="btn mb-1 dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Leave Request Form">
                                <i class="ri-user-voice-fill"></i>
                            </a>
                            <a href="/attendanceInfo" class="btn mb-1 dashboard-link btn-outline-light fs-3 p-0 mb-1"
                                data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-title="Attendance Information">
                                <i class="ri-calendar-view"></i>
                            </a>

                        </div>
                    </div>
                    <div class="row px-3">

                        <?php if ($ShowPunchOutButton): ?>
                            <form action="/punch-out" method="post" id="punchoutform">
                                <input type="hidden" name="user_id" value="<?= $id ?>">
                            </form>
                            <button type="submit" class="btn btn-outline-light fs-3 p-0 mb-1 hide-on-mobile"
                                form="punchoutform" onclick="confirmBeforeAction('','Do You Really Want to Punchout ?');"
                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Punch Out"><i
                                    class="ri-logout-box-r-line"></i></button>
                        <?php endif; ?>

                        <button onclick="changeTheme()" id="themeChangerBtn"
                            class="btn btn-outline-light fs-3 p-0 mb-1 hide-on-mobile"> <i
                                class="ri-sun-fill"></i></button>
                        <button onclick="confirmBeforeAction('/logout','Do You Want to Logout ?');" type="button"
                            class="btn btn-danger fs-3 p-0 mb-1 hide-on-mobile" data-bs-toggle="tooltip"
                            data-bs-placement="right" data-bs-title="Logout"> <i
                                class="ri-logout-circle-r-line"></i></button>
                    </div>
                </div>
            </aside>
        </div>
        <div class="w-100 ">
            <section class=" bg-secondary-subtle p-0">
                <nav class="navbar bg-dark " data-bs-theme="dark">
                    <div class="container-fluid">
                        <button onclick="history.back()" class="btn btn-outline-light"><i
                                class="ri-arrow-left-line"></i> Back</button>
                        <span class="navbar-brand mb-0 h1"><?= $title ?></span>
                        <div>
                            <?php if ($ShowPunchOutButton): ?>
                                <button type="submit" class="btn btn-outline-light mb-1 hide-on-desktop"
                                    form="punchoutform" onclick="confirmBeforeAction('','Do You Really Want to Punchout ?');">
                                    <i class="ri-logout-box-r-line"></i> Punchout
                                </button>
                            <?php endif; ?>
                            <button onclick="changeTheme()" id="themeChangerBtn-2"
                                class="btn btn-outline-light mb-1 hide-on-desktop"> <i class="ri-sun-fill"></i></button>
                            <button onclick="confirmBeforeAction('/logout','Do You Want to Logout ?');" type="button"
                                class="btn btn-danger mb-1 hide-on-desktop"> <i class="ri-logout-circle-r-line"></i>
                                Logout</button>
                        </div>

                    </div>

                </nav>
                <section class="vh100-sub-navbar p-3 container-fluid">
                    <div class="">