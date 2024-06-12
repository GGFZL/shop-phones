<?php
    $tablePhoneData = getPhoneData();
    $tableColors = getAll('colors');
    $tableManufacturer = getAll('manufacturers');
?>
<div class="container">
    <!-- ispis -->
    <div class="row mt-5 mb-5">
        <div class="col-12">
            <h2>Phone Management</h2>
            <button class="btn btn-secondary mb-3" data-toggle="modal" data-target="#addPhoneModal">Add New Phone</button>
            <table class="table responsive-table">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Description</th>
                        <th scope="col">Colors</th>
                        <th scope="col">Featured</th>
                        <th scope="col">Manufacturer</th>
                        <th scope="col">Actions</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($tablePhoneData as $phone): ?>
                    <tr id="phone-row-<?= $phone->ID_phone; ?>" data-colors="<?= htmlspecialchars(json_encode($phone->colors)); ?>">
                        <td data-label="Image"><img src="assets/images/<?= $phone->Image; ?>" alt="<?= $phone->image ?>" style="max-width: 100px;"></td>
                        <td data-label="ID"><?= $phone->ID_phone; ?></td>
                        <td data-label="Name"><?= $phone->name; ?></td>
                        <td data-label="Price"><?= $phone->Price; ?></td>
                        <td data-label="Description"><?= $phone->Description; ?></td>
                        <td data-label="Colors"><?= $phone->colors; ?></td>
                        <td data-label="Featured"><?= ($phone->Featured == 1) ? 'Yes' : 'No'; ?></td>
                        <td data-label="Manufacturer"><?= $phone->manufacturer_name; ?></td>
                        <td data-label="Actions">
                            <button class="btn btn-warning btn-sm update-phone-btn" data-phone="<?= htmlspecialchars(json_encode($phone)); ?>" data-toggle="modal" data-target="#updatePhoneModal">Update</button>
                        </td>
                        <td data-label="Delete">
                            <button class="btn btn-danger btn-sm" onclick="deletePhone(<?= $phone->ID_phone; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- endIspis -->
</div>

<!-- Add Phone Modal -->
<div class="modal fade" id="addPhoneModal" tabindex="-1" aria-labelledby="addPhoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPhoneModalLabel">Add New Phone</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']);
                    }
                ?>
            </div>
            <div class="modal-body">
                <form id="addPhoneForm" method="POST" action="models/phones/addPhone.php" enctype="multipart/form-data">
                    <!-- Form fields for phone details -->
                    <div class="form-group">
                        <label for="phoneName">Name</label>
                        <input type="text" class="form-control" name="phoneName" required>
                    </div>
                    <div class="form-group">
                        <label for="phonePrice">Price</label>
                        <input type="number" class="form-control" name="phonePrice" required>
                    </div>
                    <div class="form-group">
                        <label for="phoneImage">Image</label>
                        <input type="file" class="form-control-file" name="phoneImage" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="phoneDescription">Description</label>
                        <textarea class="form-control" name="phoneDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Colors</label><br>
                        <?php foreach ($tableColors as $color): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="phoneColors[]" value="<?= $color->ID_color; ?>">
                                <label class="form-check-label"><?= $color->name; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group">
                        <label for="phoneFeatured">Featured</label>
                        <select class="form-control" name="phoneFeatured">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phoneManufacturer">Manufacturer</label>
                        <select class="form-control" name="phoneManufacturer">
                            <?php foreach ($tableManufacturer as $manufacturer): ?>
                                <option value="<?= $manufacturer->ID_manufacturer; ?>"><?= $manufacturer->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="submit" class="btn btn-secondary w-100">Add Phone</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Add Phone Modal -->

<!-- Update Phone Modal -->
<div class="modal fade" id="updatePhoneModal" tabindex="-1" aria-labelledby="updatePhoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePhoneModalLabel">Update Phone</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="updatePhoneForm" method="POST" action="models/phones/updatePhone.php" enctype="multipart/form-data">
                    <input type="hidden" id="updatePhoneId" name="phoneID">
                    <div class="form-group">
                        <label for="updatePhoneName">Name</label>
                        <input type="text" class="form-control" id="updatePhoneName" name="phoneName" required>
                    </div>
                    <div class="form-group">
                        <label for="updatePhonePrice">Price</label>
                        <input type="number" class="form-control" id="updatePhonePrice" name="phonePrice" required>
                    </div>
                    <div class="form-group">
                        <label for="updatePhoneDescription">Description</label>
                        <textarea class="form-control" id="updatePhoneDescription" name="phoneDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Colors</label><br>
                        <?php foreach ($tableColors as $color): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input update-phone-color" type="checkbox" name="phoneColors[]" value="<?= $color->ID_color; ?>">
                                <label class="form-check-label"><?= $color->name; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group">
                        <label for="updatePhoneFeatured">Featured</label>
                        <select class="form-control" id="updatePhoneFeatured" name="phoneFeatured">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="updatePhoneManufacturer">Manufacturer</label>
                        <select class="form-control" id="updatePhoneManufacturer" name="phoneManufacturer">
                            <?php foreach ($tableManufacturer as $manufacturer): ?>
                                <option value="<?= $manufacturer->ID_manufacturer; ?>"><?= $manufacturer->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Update Phone</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Update Phone Modal -->
<form action="models/generateThumb.php" method="post" class="mb-3 ml-3">
    <button type="submit" class="btn btn-secondary">Generiši Thumbnale za Sve Telefone</button>
</form>