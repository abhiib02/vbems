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
        <div class=" p-2">

            <p>Multiple Value Must be comma seperated</p>
            <table class="table  table-striped">
                <thead>
                    <th>Option Name</th>
                    <th>Option Value</th>
                </thead>
                <?php foreach ($options as $index => $option) : ?>
                    <tr>
                        <td><?= $option->NAME ?></td>
                        <td>
                            <form action="/option/<?= $option->NAME ?>" method="post" class="d-flex">
                                <input type="text" name="<?= $option->NAME ?>" value="<?= $option->VALUE ?>" onchange="this.form.submit()" class="form-control" required>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

</div>