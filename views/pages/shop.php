<?php
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$brands = getAll('manufacturers');
$colors = getAll('colors');

$selectedBrands = isset($_GET['brands']) ? $_GET['brands'] : [];
$selectedColors = isset($_GET['colors']) ? $_GET['colors'] : [];

$perPage = 6;
$currentShopPage = isset($_GET['shop_page']) && is_numeric($_GET['shop_page']) && $_GET['shop_page'] > 0 ? intval($_GET['shop_page']) : 1;
$offset = ($currentShopPage - 1) * $perPage;

$sortOption = isset($_GET['sort']) ? $_GET['sort'] : '';

if (!empty($searchTerm)) {
    $phoneData = searchPhones($searchTerm, $selectedBrands, $selectedColors, $perPage, $offset, $sortOption);
    $totalPhones = getTotalPhoneCount($searchTerm, $selectedBrands, $selectedColors);
} else {
    $phoneData = getPhoneDataForPage($selectedBrands, $selectedColors, $perPage, $offset, $sortOption);
    $totalPhones = getTotalPhoneCount(null, $selectedBrands, $selectedColors);   
}

$totalPages = ceil($totalPhones / $perPage);
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Sidebar for Filters -->
        <div class="col-lg-3">
            <div class="d-flex flex-column">
                <p class="font-weight-bold">Choose price between</p>
                <p class="font-weight-bold mt-3">Brands</p>
                <div class="d-flex flex-column pb-3 border-bottom">
                <form action="" method="GET" id="filterForm">
                    <input type="hidden" name="page" value="shop">
                    <?php 
                    foreach ($brands as $brand) : ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input chbCSS" type="checkbox" name="brands[]" 
                            value="<?= $brand->ID_manufacturer ?>" <?= in_array($brand->ID_manufacturer, $selectedBrands) ? 'checked' : '' ?>>
                            <label class="form-check-label ml-3"><?= $brand->name ?></label>
                        </div>
                    <?php endforeach; ?>
                    <p class="font-weight-bold mt-3">Choose color</p>
                    <div class="d-flex flex-column">
                        <?php 
                        foreach ($colors as $color) : ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input chbCSS" type="checkbox" name="colors[]" 
                                value="<?= $color->ID_color ?>" <?= in_array($color->ID_color, $selectedColors) ? 'checked' : '' ?>>
                                <label class="form-check-label ml-3"><?= $color->name ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Search and Sort Form -->
            <div class="row justify-content-between mt-5">
                <div class="col-9">
                    <form action="" method="GET" class="mb-4">
                        <input type="hidden" name="page" value="shop">
                        <div class="input-group">
                            <input type="text" class="form-control w-75" name="search" placeholder="Search..." value="<?= htmlspecialchars($searchTerm) ?>">
                            <button class="btn btn-secondary ml-2" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3">
                    <form action="" method="GET" class="mb-4">
                        <input type="hidden" name="page" value="shop">
                        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                        <select name="sort" class="form-select selectSortLista w-100 form-select-lg mb-3" onchange="this.form.submit()">
                            <option value="" <?= !$sortOption ? 'selected' : '' ?>>Sort :</option>
                            <option value="price_desc" <?= $sortOption == 'price_desc' ? 'selected' : '' ?>>Price high to low</option>
                            <option value="price_asc" <?= $sortOption == 'price_asc' ? 'selected' : '' ?>>Price low to high</option>
                            <option value="name_desc" <?= $sortOption == 'name_desc' ? 'selected' : '' ?>>Name high to low</option>
                            <option value="name_asc" <?= $sortOption == 'name_asc' ? 'selected' : '' ?>>Name low to high</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Phone Data Display -->
            <div class="row">
                <?php
                if (!empty($phoneData)) {
                    foreach ($phoneData as $phone) {
                        ?>
                        <!-- Product Card -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4 mt-4">
                            <div class="card card-header h-100">
                                <div class="aspect-ratio-container">
                                    <img src="assets/images/<?= $phone->Image; ?>" class="card-img-top cardImage img-fluid" alt="image">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title namePhone"><?= $phone->name; ?></h5>
                                    <p class="card-text">Price: <?= number_format($phone->Price, 2); ?>(RSD)</p>
                                    <div>
                                        <?php
                                        $phoneColors = explode(', ', $phone->colors);
                                        foreach ($phoneColors as $color) {
                                            echo '<span class="color-dot mt-2 mb-2" style="background-color: ' . $color . '; border:1px solid black;"></span>';
                                        }
                                        ?>
                                    </div>
                                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { ?>
                                <button class="btn btn-secondary btn-block buy-now"
                                        data-name="<?= $phone->name; ?>"
                                        data-image="<?= $phone->Image; ?>"
                                        data-price="<?= $phone->Price; ?>"
                                        data-colors="<?= $phone->colors; ?>"
                                >Buy now</button>
                            <?php } else { ?>
                                <button class="btn btn-secondary btn-block" onclick="alert('You need to be logged in to buy this phone.');">Buy now</button>
                            <?php } ?>
                                    <div class="dropdown">
                                        <button class="btn btn-dark w-100 mt-2 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Description
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <p class="full-description"><?= $phone->Description; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Product Card -->
                        <?php
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </div>
            <!-- End Phone Data Display -->

            <!-- Modal -->
            <div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labelledby="phoneModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="phoneModalLabel">Phone Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="phone-details">
                                <!-- Phone details -->
                            </div>
                            <div class="color-options">
                                <!-- Color options -->
                            </div>
                            <label for="address">Your address:</label>
                            <input type="text" name="address" >
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary finish-shopping">Finish Shopping</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination Links -->
            <div class="row">
                <div class="col-md-12">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?= ($currentShopPage == $i) ? 'active' : ''; ?>">
                            <a class="page-link rounded <?= ($currentShopPage == $i) ? 'bg-secondary' : 'bg-light text-dark'; ?>" href="?page=shop&shop_page=<?= $i ?>&sort=<?= htmlspecialchars($sortOption) ?>&search=<?= htmlspecialchars($searchTerm) ?><?php if (!empty($selectedBrands)) { ?>&brands=<?= implode(',', $selectedBrands) ?><?php } ?><?php if (!empty($selectedColors)) { ?>&colors=<?= implode(',', $selectedColors) ?><?php } ?>" style="margin: 0 3px;"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
                </div>
            </div>
        </div>
    </div>
</div>