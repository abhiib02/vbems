<div class="card height-on-mobile">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h5 class="fw-bold m-0">Department List</h5>
            <div>
                <button class="btn  btn-sm btn-success" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight-Department"><i class="ri-add-circle-fill"></i> Add Department</button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-striped theme-text-color department-table">
            <thead>
                <tr>
                    <th class="hide-on-mobile">ID</th>
                    <th>Name</th>
                    <th>Max Person Can Take Leave</th>
                    <th class="">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $department): ?>
                    <tr>
                        <td class="hide-on-mobile"><?= $department->ID ?></td>
                        <td><?= $department->NAME ?></td>
                        <td><?= $department->LEAVE_PERSON_COUNT ?></td>
                        <td class="">
                            <dialog id="update-department-<?= $department->ID ?>" class="col-lg-4 border-0 rounded shadow p-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="align-content-center">
                                                <p class="fw-bold fs-6 m-0">Update Department</p>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="closeDialog()">X</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="/update-department" method="post">
                                            <input type="hidden" name="id" value="<?= $department->ID ?>" required>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="department_name"
                                                            id="department_name" value="<?= $department->NAME ?>" required>
                                                        <label for="department_name">Department Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-floating mb-3">
                                                        <input type="number" class="form-control" name="leave_person_count" id="lpc"
                                                            value="<?= $department->LEAVE_PERSON_COUNT ?>">
                                                        <label for="lpc">How Many Employees Can Take Leave @ Same time ?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-info">Update Department</button>
                                        </form>
                                    </div>
                                </div>

                            </dialog>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="openDialogModal('update-department-<?= $department->ID ?>');"><i class="ri-edit-2-fill"></i> Edit </button>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmBeforeAction('/delete-department/<?= $department->ID ?>','do you want to delete <?= $department->NAME ?> ?');"><i class="ri-close-circle-fill"></i> Delete</button>
                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight-Department">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Add New Department</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php include __DIR__ . '/../form/admin-department-add-form.php' ?>
        </div>
    </div>
</div>