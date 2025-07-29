<div class="row">
    <div class="col-lg-12">
        <div class="card height-on-mobile">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="fw-bold m-0">Your Leaves </h5>
                    <div>
                        <h5 class="fw-bold m-0">Leave Credit
                            <?= ($leavecredit > 0) ? "<span class='text-success'>($leavecredit Days)</span>" : "<span class='text-danger'>($leavecredit Days)</span>" ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table  hide-on-mobile">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Leave Applied On</th>
                            <th>Leave Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaves as $leave): ?>

                            <tr>
                                <td><?= $leave->FROM_DATE ?></td>
                                <td><?= $leave->TO_DATE ?></td>
                                <td><?= $leave->DAYS ?></td>
                                <td><?= $leave->TYPE ?></td>
                                <td><?= $leave->REASON ?></td>
                                <td><?= $leave->CREATED_ON ?></td>
                                <td class="<?= $leave->STATUS ?>"><?= $leave->STATUS ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="overflow-scroll hide-on-tab hide-on-desktop ">
                    <?php foreach ($leaves as $leave): ?>
                        <div class="card p-3 my-3">
                            <table>

                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= $leave->NAME ?></div>
                                        <small>Requested For <?= $leave->DAYS ?> Days Leave</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">Type</div>
                                        <small><?= $leave->TYPE ?></small>
                                    </td>
                                </tr>
                            </table>

                            <hr>
                            <table>
                                <tr>
                                    <td><b>From : </b> <?= $leave->FROM_DATE ?></td>
                                    <td><b>To : </b> <?= $leave->TO_DATE ?></td>
                                </tr>
                            </table>
                            <br>
                            <div class="fw-bold">Reason</div>
                            <small><?= $leave->REASON ?></small>
                            <hr>
                            <div class="alert <?= $leave->STATUS ?> m-0" role="alert">
                                <?= $leave->STATUS ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>