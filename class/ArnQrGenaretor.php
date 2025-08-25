<?php

class ArnQrGenaretor
{
    public $id;
    public $arn_id;
    public $item_id;
    public $item_code;
    public $barcode_id;
    public $commercial_cost;
    public $created_at;
    public $updated_at;

    public function __construct($id = null)
    {
        if ($id) {
            $this->id = (int)$id;
            $this->load();
        }
    }

    private function load()
    {
        $db = new Database();
        $query = "SELECT * FROM `barcode_details` WHERE `id` = " . $this->id;
        $result = $db->readQuery($query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            foreach ($row as $key => $value) {
                $this->$key = $value;
            }
            return true;
        }
        return false;
    }

    public static function generateBarcodeId($arnId, $itemId, $sequence)
    {
        $prefix = 'BRC';
        $arnPart = str_pad($arnId, 5, '0', STR_PAD_LEFT);
        $itemPart = str_pad($itemId, 5, '0', STR_PAD_LEFT);
        $seqPart = str_pad($sequence, 4, '0', STR_PAD_LEFT);
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        
        return $prefix . $arnPart . $itemPart . $seqPart . $random;
    }

    public function create()
    {
        $db = new Database();
        
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO `barcode_details` (
            `arn_id`, `item_id`, `item_code`, `barcode_id`, 
            `commercial_cost`, `created_at`, `updated_at`
        ) VALUES (
            '" . $db->escapeString($this->arn_id) . "',
            '" . $db->escapeString($this->item_id) . "',
            '" . $db->escapeString($this->item_code) . "',
            '" . $db->escapeString($this->barcode_id) . "',
            " . (float)$this->commercial_cost . ",
            '" . $this->created_at . "',
            '" . $this->updated_at . "'
        )";

        $result = $db->readQuery($query);
        if ($result) {
            $this->id = mysqli_insert_id($db->DB_CON);
            return $this->id;
        }
        return false;
    }

    public function update()
    {
        $db = new Database();
        $this->updated_at = date('Y-m-d H:i:s');

        $query = "UPDATE `barcode_details` SET
            `arn_id` = '" . $db->escapeString($this->arn_id) . "',
            `item_id` = '" . $db->escapeString($this->item_id) . "',
            `item_code` = '" . $db->escapeString($this->item_code) . "',
            `barcode_id` = '" . $db->escapeString($this->barcode_id) . "',
            `commercial_cost` = " . (float)$this->commercial_cost . ",
            `updated_at` = '" . $this->updated_at . "'
            WHERE `id` = " . $this->id;

        return $db->readQuery($query);
    }

    public function delete()
    {
        $db = new Database();
        $query = "DELETE FROM `barcode_details` WHERE `id` = " . $this->id;
        return $db->readQuery($query);
    }

    public static function getNextSequence($arnId, $itemId)
    {
        $db = new Database();
        $query = "SELECT COUNT(*) as count FROM `barcode_details` 
                 WHERE `arn_id` = " . (int)$arnId . " 
                 AND `item_id` = " . (int)$itemId;
        
        $result = $db->readQuery($query);
        $row = mysqli_fetch_assoc($result);
        
        return $row ? (int)$row['count'] + 1 : 1;
    }

    public static function getBarcodesByArn($arnId)
    {
        $db = new Database();
        $query = "SELECT bd.*, ai.description as item_description 
                 FROM `barcode_details` bd
                 JOIN `arn_items` ai ON bd.item_id = ai.id
                 WHERE bd.arn_id = " . (int)$arnId . "
                 ORDER BY bd.id DESC";
        
        $result = $db->readQuery($query);
        $barcodes = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $barcodes[] = $row;
            }
        }
        
        return $barcodes;
    }

    public static function saveBulkBarcodes($barcodes)
    {
        if (!is_array($barcodes) || empty($barcodes)) {
            return false;
        }

        $db = new Database();
        $values = [];
        $now = date('Y-m-d H:i:s');

        foreach ($barcodes as $barcode) {
            if (!empty($barcode['arn_id']) && !empty($barcode['item_id']) && 
                !empty($barcode['item_code']) && !empty($barcode['barcode_id'])) {
                
                $values[] = "(" . implode(',', [
                    (int)$barcode['arn_id'],
                    (int)$barcode['item_id'],
                    "'" . $db->escapeString($barcode['item_code']) . "'",
                    "'" . $db->escapeString($barcode['barcode_id']) . "'",
                    (float)$barcode['commercial_cost'],
                    "'" . $now . "'",
                    "'" . $now . "'"
                ]) . ")";
            }
        }

        if (empty($values)) {
            return false;
        }

        $query = "INSERT INTO `barcode_details` 
                 (`arn_id`, `item_id`, `item_code`, `barcode_id`, `commercial_cost`, `created_at`, `updated_at`)
                 VALUES " . implode(',', $values);

        return $db->readQuery($query);
    }

    public static function getBarcodeById($barcodeId)
    {
        $db = new Database();
        $query = "SELECT * FROM `barcode_details` WHERE `barcode_id` = '" . $db->escapeString($barcodeId) . "' LIMIT 1";
        $result = $db->readQuery($query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public static function getBarcodesByItem($arnId, $itemId)
    {
        $db = new Database();
        $query = "SELECT * FROM `barcode_details` 
                 WHERE `arn_id` = " . (int)$arnId . " 
                 AND `item_id` = " . (int)$itemId . "
                 ORDER BY `id` DESC";
        
        $result = $db->readQuery($query);
        $barcodes = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $barcodes[] = $row;
            }
        }
        
        return $barcodes;
    }
}