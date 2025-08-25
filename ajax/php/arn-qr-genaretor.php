<?php
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

// Function to generate a unique barcode ID
function generateBarcodeId($arnId, $itemId, $sequence) {
    $prefix = 'BRC'; // Barcode prefix
    $arnPart = str_pad($arnId, 5, '0', STR_PAD_LEFT);
    $itemPart = str_pad($itemId, 5, '0', STR_PAD_LEFT);
    $seqPart = str_pad($sequence, 4, '0', STR_PAD_LEFT);
    $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
    
    return $prefix . $arnPart . $itemPart . $seqPart . $random;
}

// Save barcodes in bulk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_barcodes') {
    $response = ['status' => 'error', 'message' => 'Invalid request'];
    
    try {
        if (!isset($_POST['barcodes']) || !is_array($_POST['barcodes'])) {
            throw new Exception('No barcodes data provided');
        }

        $savedCount = 0;
        $errors = [];
        $barcodes = $_POST['barcodes'];

        foreach ($barcodes as $barcodeData) {
            // Validate required fields
            if (empty($barcodeData['arn_id']) || empty($barcodeData['item_id']) || empty($barcodeData['item_code']) || 
                !isset($barcodeData['commercial_cost']) || empty($barcodeData['barcode_id'])) {
                $errors[] = 'Missing required fields for barcode';
                continue;
            }

            try {
                // Create new barcode record
                $barcode = new stdClass();
                $barcode->arn_id = (int)$barcodeData['arn_id'];
                $barcode->item_id = (int)$barcodeData['item_id'];
                $barcode->item_code = $barcodeData['item_code'];
                $barcode->barcode_id = $barcodeData['barcode_id'];
                $barcode->commercial_cost = (float)$barcodeData['commercial_cost'];
                $barcode->created_at = date('Y-m-d H:i:s');
                $barcode->updated_at = date('Y-m-d H:i:s');

                // Save to database using the ArnQrGenaretor class
                $barcodeObj = new ArnQrGenaretor();
                $barcodeObj->arn_id = (int)$barcodeData['arn_id'];
                $barcodeObj->item_id = (int)$barcodeData['item_id'];
                $barcodeObj->item_code = $barcodeData['item_code'];
                $barcodeObj->barcode_id = $barcodeData['barcode_id'];
                $barcodeObj->commercial_cost = (float)$barcodeData['commercial_cost'];
                
                if ($barcodeObj->create()) {
                    $savedCount++;
                } else {
                    $errors[] = 'Failed to save barcode: ' . $barcodeData['barcode_id'];
                }
            } catch (Exception $e) {
                $errors[] = 'Error saving barcode: ' . $e->getMessage();
            }
        }

        if ($savedCount > 0) {
            $response = [
                'status' => 'success',
                'message' => "Successfully saved $savedCount barcode(s)",
                'saved_count' => $savedCount
            ];
            
            if (!empty($errors)) {
                $response['warning'] = count($errors) . ' barcode(s) failed to save';
                $response['errors'] = $errors;
            }
        } else {
            throw new Exception('Failed to save any barcodes. ' . implode('; ', $errors));
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
    }

    echo json_encode($response);
    exit;
}

// Get barcodes by ARN ID
if (isset($_GET['action']) && $_GET['action'] === 'get_barcodes_by_arn' && !empty($_GET['arn_id'])) {
    try {
        $arnId = (int)$_GET['arn_id'];
        $db = new Database();
        $query = "SELECT bd.*, ai.description as item_description 
                 FROM barcode_details bd
                 JOIN arn_items ai ON bd.item_id = ai.id
                 WHERE bd.arn_id = :arn_id
                 ORDER BY bd.id DESC";
        
        $barcodes = $db->query($query, [':arn_id' => $arnId])->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'data' => $barcodes
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch barcodes: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Get next barcode sequence
if (isset($_GET['action']) && $_GET['action'] === 'get_next_sequence' && !empty($_GET['arn_id']) && !empty($_GET['item_id'])) {
    try {
        $arnId = (int)$_GET['arn_id'];
        $itemId = (int)$_GET['item_id'];
        
        $db = new Database();
        $query = "SELECT COUNT(*) as count FROM barcode_details 
                 WHERE arn_id = :arn_id AND item_id = :item_id";
        
        $result = $db->query($query, [
            ':arn_id' => $arnId,
            ':item_id' => $itemId
        ])->fetch(PDO::FETCH_ASSOC);
        
        $nextSequence = $result ? (int)$result['count'] + 1 : 1;
        
        echo json_encode([
            'status' => 'success',
            'next_sequence' => $nextSequence
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to get next sequence: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Default response for invalid requests
echo json_encode([
    'status' => 'error',
    'message' => 'Invalid request'
]);