<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Page
if (isset($_POST['create'])) {

    $PAGES = new Pages(NULL); // Create a new page

    // Set the page details
    $PAGES->page_category = $_POST['page_category'];
    $PAGES->sub_page_category = $_POST['sub_page_category'];
    $PAGES->page_name = $_POST['page_name'];
    $PAGES->page_url = $_POST['page_url'];

    // Attempt to create the page
    $res = $PAGES->create();

    if ($res) {
        $result = [
            "status" => 'success'
        ];
        echo json_encode($result);
        exit();
    } else {
        $result = [
            "status" => 'error'
        ];
        echo json_encode($result);
        exit();
    }
}

// Update Page details
if (isset($_POST['update'])) {

    $PAGES = new Pages($_POST['page_id']); // Retrieve page by ID

    // Update Page details
    $PAGES->page_category = $_POST['page_category'];
    $PAGES->sub_page_category = $_POST['sub_page_category'];
    $PAGES->page_name = $_POST['page_name'];
    $PAGES->page_url = $_POST['page_url'];

    // Attempt to update the page
    $result = $PAGES->update();

    if ($result) {
        $result = [
            "status" => 'success'
        ];
        echo json_encode($result);
        exit();
    } else {
        $result = [
            "status" => 'error'
        ];
        echo json_encode($result);
        exit();
    }
}
