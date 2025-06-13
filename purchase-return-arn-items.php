<?php
require_once 'class/ArnItem.php';
require_once 'class/ItemMaster.php';
require_once 'class/Database.php';

if (!isset($_GET['arn_id'])) {
    echo "<div class='alert alert-danger'>Invalid ARN ID</div>";
    exit;
}

$arn_id = $_GET['arn_id'];

$db = new Database();
$query = "SELECT ai.*, 
                 im.code AS item_code,
                 im.id AS item_id, im.name, im.brand, im.size, im.pattern, im.group, im.category,
                 im.cost, im.list_price, im.whole_sale_price, im.retail_price
          FROM arn_items ai
          LEFT JOIN item_master im ON ai.item_code = im.id
          WHERE ai.arn_id = '$arn_id'";
$result = $db->readQuery($query);
?>
<input type="hidden" id="arn_id_hidden" value="<?= $arn_id; ?>">

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Code</th>
            <th>Name</th>
            <th>Size</th>
            <th>Pattern</th>
            <th>Order Qty</th>
            <th>Received Qty</th>
            <th>Commercial Cost</th>
            <th>Discount 1</th>
            <th>Discount 2</th>
            <th>Discount 3</th>
            <th>Final Cost</th>
            <th>Cost</th>
            <th>Return</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $index = 1;
        while ($item = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?= $index++; ?></td>
                <td><?= htmlspecialchars($item['item_code']); ?></td>
                <td><?= htmlspecialchars($item['name']); ?></td>
                <td><?= htmlspecialchars($item['size']); ?></td>
                <td><?= htmlspecialchars($item['pattern']); ?></td>
                <td><?= htmlspecialchars($item['order_qty']); ?></td>
                <td><?= htmlspecialchars($item['received_qty']); ?></td>
                <td><?= htmlspecialchars($item['commercial_cost']); ?></td>
                <td><?= htmlspecialchars($item['discount_1']); ?></td>
                <td><?= htmlspecialchars($item['discount_2']); ?></td>
                <td><?= htmlspecialchars($item['discount_3']); ?></td>
                <td><?= htmlspecialchars($item['final_cost']); ?></td>
                <td><?= htmlspecialchars($item['cost']); ?></td>
                <td>
                    <input type="number"
                        class="form-control return-qty"
                        name="return_qty[<?= $item['item_id']; ?>]"
                        max="<?= $item['received_qty']; ?>"
                        min="0"
                        data-received="<?= $item['received_qty']; ?>">
                </td>

            </tr>
        <?php
        }
        ?>
    </tbody>
</table>



<div class="row d-none">
    <div class="col-md-6">
        <button class="btn btn-success" id="makeReturnBtnOld">Make Return</button>
    </div>
</div>


<div id="returnModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border: 1px solid #000;">
            <div class="modal-header">
                <h5 class="modal-title">Return Details</h5>
            </div>
            <div class="modal-body">
                <label>Reference No:</label>
                <input type="text" id="refNo" class="form-control" required>

                <label>Return Reason:</label>
                <textarea id="returnReason" class="form-control" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="submitPurchaseReturn" class="btn btn-primary">Save ARN Return</button>
                <button type="button" class="btn btn-danger" onclick="$('#returnModal').hide();">Cancel</button>
            </div>
        </div>
    </div>
</div>