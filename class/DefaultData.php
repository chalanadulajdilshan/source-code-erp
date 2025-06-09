<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DefaultData
 *
 * @author User™
 */
class DefaultData
{


    //get gender
    public function Years()
    {
        $currentYear = 2025;
        $years = [];

        for ($i = 0; $i < 5; $i++) {
            $year = $currentYear + $i;
            $years["$year"] = "$year";
        }

        return $years;
    }

    //get branches
    function getOrderByOptions()
    {
        return [
            "arn_number" => "ARN Number",
            "po_number" => "PO Number",
            "supplier_name" => "Supplier",
            "invoice_date" => "Invoice Date",
            "entry_date" => "Entry Date",
            "arn_status" => "ARN Status",
        ];
    }

    //pages sub category
    function pagesSubCategory()
    {
        return [
            1 => "Main",
            2 => "Sales",
            3 => "Customer",
            4 => "Stores",
            5 => "Others",
        ];
    }
    public function Days()
    {
        return [
            30 => '30 Days',
            60 => '90 Days',
            90 => '120 Days'
        ];
    }


    function DagType()
    {
        return array(
            "canvas" => "CANVAS",
            "radial" => "RADIAL",
        );
    }

    function DagMake()
    {
        return array(
            "arpico" => "Arpico",
            "ceat" => "Ceat",
        );
    }
}
