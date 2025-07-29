<div class="card height-on-mobile">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h5 class="fw-bold m-0">Holidays List</h5>
            <div>
                <button class="btn btn-sm btn-success" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight-Holiday"><i class="ri-add-circle-fill"></i> Add Holiday</button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table theme-text-color holiday-table table-striped">
            <thead>
                <tr>
                    <th class="hide-on-mobile">ID</th>
                    <th>Date</th>
                    <th>Holiday</th>
                    <th class="">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($holidays as $holiday): ?>
                    <tr>
                        <td class="hide-on-mobile"><?= $holiday->ID ?></td>
                        <td><?= $holiday->DATE ?></td>
                        <td><?= $holiday->HOLIDAY ?></td>
                        <td class="">
                            <dialog id="update-holiday-<?= $holiday->ID ?>" class="col-lg-4 border-0 rounded shadow p-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="align-content-center">
                                                <p class="fw-bold fs-6 m-0">Update Holiday</p>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="closeDialog()">X</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="/update-holiday" method="post">
                                            <input type="hidden" name="id" value="<?= $holiday->ID ?>" required>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="holiday" id="holiday"
                                                            value="<?= $holiday->HOLIDAY ?>" required>
                                                        <label for="holiday">Holiday Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-floating mb-3">
                                                        <input type="date" class="form-control" name="date" id="date"
                                                            value="<?= $holiday->DATE ?>" required>
                                                        <label for="date">Holiday Date</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-info">Update Holiday</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="openDialogModal('update-holiday-<?= $holiday->ID ?>');"><i class="ri-edit-2-fill"></i> Edit </button>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmBeforeAction('/delete-holiday/<?= $holiday->ID ?>','do you want to delete <?= $holiday->HOLIDAY ?> ?');"><i class="ri-close-circle-fill"></i> Delete</button>
                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight-Holiday">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Add New Holiday</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php include __DIR__ . '/../form/admin-holiday-add-form.php' ?>
        </div>
    </div>
</div>