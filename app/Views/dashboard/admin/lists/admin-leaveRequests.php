<div class="d-flex justify-content-between align-items-center mb-2">
    <div class="btn-group btn-group-sm" role="group">
        <a type="button" href="/leaveRequests/pending" class="btn btn-warning">Pending</a>
        <a type="button" href="/leaveRequests/approved" class="btn btn-success">Approved</a>
        <a type="button" href="/leaveRequests/rejected" class="btn btn-danger">Rejected</a>
    </div>
    <form method="get" class="d-flex">
        <?php
        $monthArr = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        ?>
        <select name="month" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="<?= $month ?>" selected><?= $monthArr[$month - 1] ?></option>
            <hr>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
        <select class="form-select form-select-sm" name="year" onchange="this.form.submit()">
            <option value="<?= $year ?>" selected><?= $year ?></option>
            <hr>
            <option value="<?= date('Y', strtotime("-5 years")) ?>"><?= date('Y', strtotime("-5 years")) ?></option>
            <option value="<?= date('Y', strtotime("-4 years")) ?>"><?= date('Y', strtotime("-4 years")) ?></option>
            <option value="<?= date('Y', strtotime("-3 years")) ?>"><?= date('Y', strtotime("-3 years")) ?></option>
            <option value="<?= date('Y', strtotime("-2 years")) ?>"><?= date('Y', strtotime("-2 years")) ?></option>
            <option value="<?= date('Y', strtotime("-1 years")) ?>"><?= date('Y', strtotime("-1 years")) ?></option>
            <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
        </select>

    </form>
</div>
<div class="card height-on-mobile">
    <div class="card-header">
        <h5 class="fw-bold m-0 text-capitalize"><?= $status ?> Leave Requests</h5>
    </div>
    <div class="card-body">
        <div class="overflow-y-scroll hide-on-tab hide-on-mobile">
            <table class="table theme-text-color leavesRequest-table table-striped ">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Days</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Request Applied On</th>
                        <?php if ($status == "pending" || $status == "Pending"): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaveRequests as $request): ?>
                        <tr>
                            <td><?= $request->NAME ?></td>
                            <td><?= $request->FROM_DATE ?></td>
                            <td><?= $request->TO_DATE ?></td>
                            <td><?= $request->DAYS ?></td>
                            <td><?= $request->TYPE ?></td>
                            <td>
                                <dialog id="reason-<?= $request->USER_ID ?>" class="col-lg-4 border-0 rounded shadow p-0">

                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="align-content-center">
                                                <p class="fw-bold fs-6 m-0">Leave Reason</p>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="closeDialog()">X</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <label for="Designation" class="form-label">Reason</label>
                                            <textarea class="form-control autosize" readonly><?= $request->REASON ?></textarea>
                                        </div>
                                    </div>
                                </dialog>
                                <button class="btn btn-sm btn-outline-secondary"
                                    onclick="openDialogModal('reason-<?= $request->USER_ID ?>');">See Reason</button>
                            </td>
                            <td class="<?= $request->STATUS ?>"><?= $request->STATUS ?></td>
                            <td><?= $request->CREATED_ON ?></td>
                            <?php if ($status == "pending" || $status == "Pending"): ?>
                                <td>
                                    <a href="/lr-approve/<?= $request->ID ?>"
                                        onclick="toastonLinkClick('Please Wait Approving Request...','Success','5s')"
                                        class='btn btn-sm btn-success'>Approve</a>
                                    <a href="/lr-reject/<?= $request->ID ?>"
                                        onclick="toastonLinkClick('Please Wait Rejecting Request...','Danger','5s')"
                                        class='btn btn-sm btn-danger'>Reject</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="overflow-y-scroll hide-on-desktop ">
            <?php foreach ($leaveRequests as $request): ?>
                <div class="card p-3 my-3 ">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-bold"><?= $request->NAME ?></div>
                            <small>Requested For <?= $request->DAYS ?> Days Leave</small>
                        </div>
                        <div>
                            <div class="fw-bold">Leave Type</div>
                            <small><?= $request->TYPE ?></small>
                        </div>
                    </div>

                    <hr>
                    <table>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Reason</th>
                        </tr>
                        <tr>
                            <td>
                                <?= $request->FROM_DATE ?>
                            </td>
                            <td>
                                <?= $request->TO_DATE ?>
                            </td>
                            <td>
                                <dialog id="reason-mob-<?= $request->USER_ID ?>" class="w-100 border-0 rounded shadow p-0">

                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="align-content-center">
                                                <p class="fw-bold fs-6 m-0">Leave Reason</p>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="closeDialog()">X</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <label for="Designation" class="form-label">Reason</label>
                                            <textarea class="form-control autosize" readonly><?= $request->REASON ?></textarea>
                                        </div>
                                    </div>
                                </dialog>
                                <button class="btn btn-sm btn-outline-secondary"
                                    onclick="openDialogModal('reason-mob-<?= $request->USER_ID ?>');">See Reason</button>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <?php if ($status == "pending" || $status == "Pending"): ?>
                        <div class="">
                            <a href="/lr-approve/<?= $request->ID ?>"
                                onclick="toastonLinkClick(`<div class='spinner-border spinner-border-sm text-light' role='status'></div> Please Wait Approving Request...`,'Success','5s')"
                                class='btn btn-sm btn-success'>Approve</a>
                            <a href="/lr-reject/<?= $request->ID ?>"
                                onclick="toastonLinkClick(`<div class='spinner-border spinner-border-sm text-light' role='status'></div> Please Wait Rejecting Request...`,'Danger','5s')"
                                class='btn btn-sm btn-danger'>Reject</a>
                        </div>
                    <?php else: ?>
                        <div class="alert <?= $request->STATUS ?> m-0" role="alert">
                            <?= $request->STATUS ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>