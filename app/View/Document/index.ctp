<?php
$query = $this->request->query;
$queryString = http_build_query($query);
?>

<div id="document">
    <div class="document-header">
        <div class="document-title">
            <h1>
                Document List
            </h1>
        </div>
    </div>

    <div class="document-search-form">
        <form action="" id="formSearch">
            <div class="search-form-row">
                <div class="form-group search-form-row col-4">
                    <label for="searchName" class="form-label col-4">Name</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="searchName" value="<?= !empty($query['name']) ? $query['name'] : '' ?>" name="name" placeholder="Enter Name">
                    </div>
                </div>
                <div class="form-group search-form-row col-4">
                    <label for="searchSize" class="form-label col-4">Size (<?= $uploadUnits['default'] ?>)</label>
                    <div class="col-8">
                        <div class="search-form-row">
                            <div class="col-6">
                                <input type="number" class="form-control" id="searchSize" value="<?= !empty($query['size_min']) ? $query['size_min'] : '' ?>" name="size_min" placeholder="Enter Min">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" id="searchSize" value="<?= !empty($query['size_max']) ? $query['size_max'] : '' ?>" name="size_max" placeholder="Enter Max">
                            </div>
                        </div>
                        <p class="text-danger error-min-max"></p>
                    </div>
                </div>
                <div class="form-group search-form-row col-4">
                    <button class="btn btn-primary">Search</button>
                </div>
            </div>

            <div class="search-form-row">
                <div class="form-group search-form-row col-4">
                    <label for="search" class="form-label col-4">Type</label>
                    <div class="col-8">
                        <select class="form-control select2" name="types[]" multiple="multiple">
                            <option value="">All</option>
                            <?php foreach ($allType as $type) : ?>
                                <option <?= !empty($query['types']) && in_array($type, $query['types']) ? 'selected' : '' ?> value="<?= $type ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group search-form-row col-4">
                    <label for="searchUserUpload" class="form-label col-4">User Upload</label>
                    <div class="col-8">
                        <select class="form-control select2" name="user_upload">
                            <option value="">All</option>
                            <?php foreach ($usernames as $item) : ?>
                                <option <?= !empty($query['user_upload']) && $query['user_upload'] == $item['id'] ? 'selected' : '' ?> value="<?= $item['id'] ?>"><?= $item['username'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group search-form-row col-4">
                    <a href="<?= $this->Html->url(['controller' => 'document', 'action' => 'index']) ?>" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <div class="document-action search-form-row">
        <div class="btn-delete-all">
            <button class="btn btn-danger">Delete All</button>
        </div>
        <div class="btn-download-all">
            <button class="btn btn-warning">Download All</button>
        </div>
    </div>

    <div class="document-list">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 40px;"><input type="checkbox" id="checkAll"></th>
                    <th class="table-header">Id <span class="sort-by" data-value="id"><i class="fa fa-arrow-up"></span></th>
                    <th class="table-header">Name <span class="sort-by" data-value="id"><i class="fa fa-arrow-up"></span></th>
                    <th class="table-header">Type <span class="sort-by" data-value="id"><i class="fa fa-arrow-up"></span></th>
                    <th class="table-header">Size (<?= $uploadUnits['default'] ?>) <span class="sort-by" data-value="id"><i class="fa fa-arrow-up"></span></th>
                    <th class="table-header">User Upload <span class="sort-by" data-value="id"><i class="fa fa-arrow-up"></span></th>
                    <th class="table-header">Action <span class="sort-by" data-value="id"><i class="fa fa-arrow-up"></span></th>
                </tr>
            </thead>
            <tbody id="tableContent">
                <?php if ($totalItems > 0) : ?>
                    <?php foreach ($documents as $item) : ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="document-checkbox checkbox-item" name="items[]" value="<?= $item['doc']['id'] ?>" />
                            </td>
                            <td class="document-id">
                                <?php echo $item['doc']['id']; ?>
                            </td>
                            <td class="document-name">
                                <?php echo $item['doc']['name']; ?>
                            </td>
                            <td class="document-type">
                                <?php echo $item['doc']['type']; ?>
                            </td>
                            <td class="document-size">
                                <?php echo round($item['doc']['size'] / $uploadUnits['values'][$uploadUnits['default']], 3) ?>
                            </td>
                            <td class="document-user-upload">
                                <?php echo $item['users']['username']; ?>
                            </td>
                            <td class="">
                                <a href=""><i class="fa fa-trash-o text-danger"></i></a>
                                <a href=""><i class="fa fa-eye text-info"></i></a>
                                <a href=""><i class="fa fa-download text-warning"></i></a>
                            </td>
                        <tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    unset($query['page']);
    $queryString = http_build_query($query);
    ?>

    <?php if ($totalPages > 1) : ?>
        <div class="paginate text-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= $currentPage > 1 ? '' : 'disabled' ?>"><a class="page-link" href="?<?= $queryString . ($currentPage - 1) ?>">Previous</a></li>
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?> "><a class="page-link" href="?<?= $queryString . $i ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <li class="page-item <?= $currentPage < $totalPages ? '' : 'disabled' ?>"><a class="page-link" href="?<?= $queryString . ($currentPage + 1) ?>">Next</a></li>
                </ul>
            </nav>
        </div>
    <?php endif ?>
</div>

<?= $this->Html->script('script'); ?>