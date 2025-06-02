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
    public function Days()
    {
        return array(
            "30" => "30 Days",
            "60" => "60 Days",
            "90" => "90 Days",
            "120" => "120 Days",
        );
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
