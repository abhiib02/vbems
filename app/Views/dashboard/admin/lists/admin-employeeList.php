<div class="card height-on-mobile">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h5 class="fw-bold m-0">Employees List</h5>
            <div>
                <button class="btn btn-sm btn-success" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight-Employee"><i class="ri-add-circle-fill"></i> Add Employee</button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table theme-text-color employees-table table-striped">
            <thead>
                <tr>
                    <th class="hide-on-mobile">ID</th>
                    <th>Name</th>
                    <th class="hide-on-mobile">Email</th>
                    <th class="">Contact</th>
                    <th>Designation</th>
                    <th>Department Name</th>
                    <th>Base Salary</th>
                    <th>Status</th>
                    <th class="">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td class="hide-on-mobile"><?= $employee->ID ?></td>
                        <td><?= $employee->NAME ?></td>
                        <td class="hide-on-mobile"><?= $employee->EMAIL ?></td>
                        <td class=""><?= $employee->CONTACT ?></td>
                        <td><?= $employee->DESIGNATION ?></td>
                        <td><?= $employee->DEPARTMENT_NAME ?></td>
                        <td><?= $employee->BASIC_SALARY ?></td>
                        <td><?= ($employee->DEACTIVATE) ? '<span class="badge text-bg-danger">Deactived</span>' : '<span class="badge text-bg-success">Active</span>' ?></td>
                        <td class="">


                            <div class="dropstart">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    ••• Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item"
                                            onclick="openDialogModal('emp-profile-form-<?= $employee->ID ?>');"><i
                                                class="ri-file-user-fill"></i>Edit Employee Profile</button>
                                    </li>
                                    <li><button class="dropdown-item"
                                            onclick="openDialogModal('salary-form-<?= $employee->ID ?>');"><i
                                                class="ri-money-rupee-circle-fill"></i>Salary</button></li>
                                    <li><button class="dropdown-item"
                                            onclick="openDialogModal('leave-form-<?= $employee->ID ?>');"><i
                                                class="ri-user-minus-fill"></i>Create Paid Leave</button></li>
                                    <li> <a class="dropdown-item" href="/employee-attendance/<?= $employee->ID ?>"><i
                                                class="ri-file-copy-fill"></i> Attendance</a></li>
                                    <?php if ($employee->DEACTIVATE == 1): ?>
                                        <li><button class="dropdown-item text-success"
                                                form="toggle-user-activation-<?= $employee->ID ?>"><i
                                                    class="ri-checkbox-circle-fill"></i> Activate</button></li>
                                    <?php else: ?>
                                        <li><button class="dropdown-item text-danger"
                                                form="toggle-user-activation-<?= $employee->ID ?>"><i
                                                    class="ri-close-circle-fill"></i> Deactivate</button></li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                            <dialog id="emp-profile-form-<?= $employee->ID ?>" class="col-lg-4 border-0 rounded shadow p-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="align-content-center">
                                                <p class="fw-bold fs-6 m-0">Update Employee Profile</p>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="closeDialog()">X</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php include __DIR__ . '/../form/admin-update-employee-profile-form.php' ?>
                                    </div>
                                </div>
                            </dialog>


                            <dialog id="salary-form-<?= $employee->ID ?>" class="col-lg-4 border-0 rounded shadow p-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="align-content-center">
                                                <p class="fw-bold fs-6 m-0">Edit Salary</p>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="closeDialog()">X</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php include __DIR__ . '/../form/admin-update-employee-salary-form.php' ?>
                                    </div>
                                </div>
                            </dialog>

                            <dialog id="leave-form-<?= $employee->ID ?>" class="col-lg-4 border-0 rounded shadow p-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="align-content-center">
                                                <p class="fw-bold fs-6 m-0">Create Paid Leave</p>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="closeDialog()">X</button>
                                        </div>
                                    </div>
                                    <div class="card-body pb-5 mb-5">
                                        <?php include __DIR__ . '/../form/admin-create-paid-leave-form.php' ?>
                                    </div>
                                </div>

                            </dialog>

                            <form action="/deactivate-user" method="POST" id="toggle-user-activation-<?= $employee->ID ?>">
                                <input type="hidden" name="id" value="<?= $employee->ID ?>">
                                <?php if ($employee->DEACTIVATE == 1): ?>
                                    <input type="hidden" name="set" value="0">
                                <?php else: ?>
                                    <input type="hidden" name="set" value="1">
                                <?php endif; ?>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight-Employee">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Add New Employee</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php include __DIR__ . '/../form/admin-add-employee-form.php' ?>
        </div>
    </div>
</div>
<!-- for create paid leave date picker -->
<script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>
<script>
    function initializeDatePicker() {

        const dateInputs = document.querySelectorAll('.datepicker');
        dateInputs.forEach((input) => {
            const picker = new easepick.create({
                element: input,
                format: 'YYYY-MM-DD',
                setup(picker) {
                    picker.on('select', () => {
                        input.value = picker.options.element.value
                    });
                },
                css: [
                    "/css/easepick.css",
                ],
                RangePlugin: {
                    delimiter: "/"
                },
                LockPlugin: {
                    minDays: 0,
                    inseparable: true,
                    filter: (date) => {
                        return (date.getDay() === 0);
                    },
                    selectForward: true,
                    minDate: new Date()
                },
                plugins: [
                    "RangePlugin",
                    "LockPlugin"
                ],
            });
            input.addEventListener('click', function() {
                picker.show();
            });

        });
    }
    initializeDatePicker()
</script>