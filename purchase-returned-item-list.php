<?php
require_once 'class/Database.php';
require_once 'class/ItemMaster.php';

if (!isset($_GET['return_id'])) {
    echo "<div class='alert alert-danger'>Invalid Return ID.</div>";
    exit;
}

$return_id = $_GET['return_id'];
$db = new Database();

$query = "SELECT pri.*, 
                 im.name AS item_name, 
                 im.code AS item_code,
                 pr.ref_no
          FROM purchase_return_items pri
          LEFT JOIN item_master im ON pri.item_id = im.id
          LEFT JOIN purchase_return pr ON pri.return_id = pr.id
          WHERE pri.return_id = '$return_id'";

$result = $db->readQuery($query);

if (mysqli_num_rows($result) === 0) {
    echo "<div class='alert alert-warning'>No items found for this return.</div>";
    exit;
}

$item = mysqli_fetch_assoc($result);
$ref_no = $item['ref_no'];

mysqli_data_seek($result, 0);
?>

<h5><strong>Return Reference No: <?= htmlspecialchars($ref_no); ?></strong></h5>

<table class="table table-bordered mt-2">
    <thead>
        <tr>
            <th>#</th>
            <th>Item Code</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Net Amount</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        while ($item = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($item['item_code']); ?></td>
                <td><?= htmlspecialchars($item['item_name']); ?></td>
                <td><?= htmlspecialchars($item['quantity']); ?></td>
                <td><?= htmlspecialchars(number_format($item['unit_price'], 2)); ?></td>
                <td><?= htmlspecialchars(number_format($item['net_amount'], 2)); ?></td>
                <td><?= htmlspecialchars($item['created_at']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
