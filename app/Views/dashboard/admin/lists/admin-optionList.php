<div class="card height-on-mobile">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h5 class="fw-bold m-0">Options & Flags List</h5>
            <div>
                <button class="btn  btn-sm btn-success" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight-option"><i class="ri-add-circle-fill"></i> Add Option</button>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight-option">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Add New Option</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <?php include __DIR__ . '/../form/admin-add-option-form.php' ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <th>Option Name</th>
                <th>Option Value</th>
            </thead>
            <?php foreach ($options as $index => $option) : ?>
                <tr>
                    <td><?= $option->NAME ?></td>
                    <td>
                        <form action="/option/<?= $option->NAME ?>" method="POST" class="d-flex">
                            <?php if ($option->TYPE == 1): ?>
                                <input type="text" name="<?= $option->NAME ?>" value="<?= $option->VALUE ?>" onchange="this.form.submit()" class="form-control" required>
                            <?php else: ?>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="<?= $option->NAME ?>" onchange="this.form.submit()" id="switchCheckChecked<?= $index?>" <?= ($option->VALUE == 1) ? 'checked':''?>>
                                </div>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</div>