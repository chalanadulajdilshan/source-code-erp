<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once("config.inc.php");
require_once("DBConnector.php");
$db = new DBConnector();

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

$MSHFlexGrid1 = array(array());
$MSHFlexGrid1_count = 0;
$gridchk = array(array());


if ($_GET["Command"] == "grnhistory") {
    $txtgrntot = "";

    $year = substr($_GET["dtMonth"], 0, 4);
    $month = substr($_GET["dtMonth"], 5, 2);

    $ii = 1;

    $sql_rsgen = "select * from s_crnma where CANCELL='0' and month(sdate1) =" . $month . " and   year(sdate1) =" . $year . " and SAL_EX='" . trim($_GET["cmbrep"]) . "'";
    $result_rsgen = $db->RunQuery($sql_rsgen);
    while ($row_rsgen = mysql_fetch_array($result_rsgen)) {

        $TypeGrid1[$ii][0] = "GRN";
        $TypeGrid1[$ii][1] = $row_rsgen["REF_NO"];
        $TypeGrid1[$ii][2] = $row_rsgen["GRAND_TOT"];
        $TypeGrid1[$ii][3] = $row_rsgen["INVOICENO"];


        $sql_rs_salm = "Select sdate1, GRAND_TOT,DUMMY_VAL from s_salma where Accname <> 'NON STOCK' AND (brand = 'CHENG SHING' OR brand = 'MAXXIS MC TYRE') and  REF_NO='" . $row_rsgen["INVOICENO"] . "' ";
        $result_rs_salm = $db->RunQuery($sql_rs_salm);
        if ($row_rs_salm = mysql_fetch_array($result_rs_salm)) {
            $TypeGrid1[$ii][4] = $row_rs_salm["sdate1"];
            $TypeGrid1[$ii][5] = $row_rs_salm["GRAND_TOT"];
            $TypeGrid1[$ii][6] = $row_rs_salm["DUMMY_VAL"];
            if (($row_rs_salm["DUMMY_VAL"] > 0) and ($row_rs_salm["GRAND_TOT"] > 0)) {
                $TypeGrid1[$ii][7] = ($row_rs_salm["DUMMY_VAL"] / $row_rs_salm["GRAND_TOT"]) * $row_rsgen["GRAND_TOT"];
            }
            $TypeGrid1[$ii][8] = $row_rsgen["DIS1"];
            $txtgrntot = $_GET["txtgrntot"] + $TypeGrid1[$ii][7] + $TypeGrid1[$ii][8];
            $ii = $ii + 1;
        }


        $sql_rsgen = "select * from cred where CANCELL='0' and month(sdate1) =" . $month . " and   year(sdate1) =" . $year . " and C_SALEX='" . trim($_GET["cmbrep"]) . "'  ";
        $result_rsgen = $db->RunQuery($sql_rsgen);
        while ($row_rsgen = mysql_fetch_array($result_rsgen)) {

            $sql_rsbal = "Select * from c_bal where REFNO = '" . $row_rsgen["C_REFNO"] . " ' and flag1 <> '1'";
            $result_rsbal = $db->RunQuery($sql_rsbal);
            if ($row_rsbal = mysql_fetch_array($result_rsbal)) {

                $TypeGrid1[$ii][0] = "CRN";
                $TypeGrid1[$ii][1] = $row_rsgen["C_REFNO"];
                $TypeGrid1[$ii][2] = $row_rsgen["C_PAYMENT"];
                $TypeGrid1[$ii][3] = $row_rsgen["C_INVNO"];

                $sql_rs_salm = "Select sdate1,GRAND_TOT,DUMMY_VAL from s_salma where Accname <> 'NON STOCK' AND (brand = 'CHENG SHING' OR brand = 'MAXXIS MC TYRE') and REF_NO='" . $row_rsgen["C_INVNO"] . "'";
                $result_rs_salm = $db->RunQuery($sql_rs_salm);
                if ($row_rs_salm = mysql_fetch_array($result_rs_salm)) {

                    $TypeGrid1[$ii][4] = $row_rs_salm["sdate1"];
                    $TypeGrid1[$ii][5] = $row_rs_salm["GRAND_TOT"];
                    $TypeGrid1[$ii][6] = $row_rs_salm["DUMMY_VAL"];
                    if ($row_rs_salm["DUMMY_VAL"] == 0) {
                        $TypeGrid1[$ii][7] = 0;
                    } else {
                        $TypeGrid1[$ii][7] = ($row_rs_salm["DUMMY_VAL"] / $row_rs_salm["GRAND_TOT"]) * $row_rsgen["C_PAYMENT"];
                    }
                }
                if (is_null($row_rsgen["SETTLED"]) == false) {
                    $TypeGrid1[$ii][8] = $row_rsgen["SETTLED"];
                }
                $txtgrntot = $txtgrntot + $TypeGrid1[$ii][7] + $TypeGrid1[$ii][8];
                $ii = $ii + 1;
            }
        }

        $TypeGrid1_count = $ii;

        $TypeGrid1[0][1] = "";
        $TypeGrid1[0][1] = "GRN/CRN NO";
        $TypeGrid1[0][2] = "Amount";
        $TypeGrid1[0][3] = "Invoice No";
        $TypeGrid1[0][4] = "IN.Date";
        $TypeGrid1[0][5] = "IN.Amount";
        $TypeGrid1[0][6] = "Paid";
        $TypeGrid1[0][7] = "Commi";
        $TypeGrid1[0][8] = "Comm.Manu";

        $ResponseXML = "";
        $ResponseXML .= "<salesdetails>";


        $ResponseXML .= "<TypeGrid1><![CDATA[ <table  border=1  cellspacing=0>      ";
        $r = 0;
        while ($TypeGrid1_count > $r) {

            $gtype = "gtype" . $r;
            $grnno = "grnno" . $r;
            $Commi = "Commi" . $r;
            $commman = "CommManu" . $r;


            $ResponseXML .= "<tr>
            <td><div id=\"" . $gtype . "\">" . $TypeGrid1[$r][0] . "</div></td>
            <td><div id=\"" . $grnno . "\">" . $TypeGrid1[$r][1] . "</div></td>
            <td>" . $TypeGrid1[$r][2] . "</td>
            <td>" . $TypeGrid1[$r][3] . "</td>
            <td>" . $TypeGrid1[$r][4] . "</td>
            <td>" . $TypeGrid1[$r][5] . "</td>
            <td>" . $TypeGrid1[$r][6] . "</td>
            <td><div id=\"" . $Commi . "\">" . $TypeGrid1[$r][7] . "</div></td>";
            if ($r != 0) {
                $ResponseXML .= "<td><input type=\"text\" name=\"" . $commman . "\" id=\"" . $commman . "\" value=\"" . $TypeGrid1[$r][8] . "\" /></td>
                </tr>";
            } else {
                $ResponseXML .= "<td>" . $TypeGrid1[$r][8] . "</td>
                </tr>";
            }
            $r = $r + 1;
        }

        $ResponseXML .= "   </table>]]></TypeGrid1>";
        $ResponseXML .= "<mcount><![CDATA[" . $r . "]]></mcount>";


        $ResponseXML .= " </salesdetails>";
        echo $ResponseXML;
    }
}



if ($_GET["Command"] == "savegrn") {
    include('connection.php');

    $r = 1;
    while ($_GET["grngrid"] > $r) {

        $gtype = "gtype" . $r;
        $grnno = "grnno" . $r;
        $Commi = "Commi" . $r;
        $commman = "CommManu" . $r;

        if ($_GET[$Commi] == "") {
            $Commi_val = 0;
        } else {
            $Commi_val = $_GET[$Commi];
        }

        if ($_GET[$commman] == "") {
            $commman_val = 0;
        } else {
            $commman_val = $_GET[$commman];
        }


        if ($_GET[$gtype] == "GRN") {

            $sql_inv = "update s_crnma set DUMMY_VAL=" . $Commi_val . " ,DIS1=" . $commman_val . "  where  REF_NO='" . $_GET[$grnno] . "'";
            //echo $sql_inv;
            $result_inv = mysql_query($sql_inv, $dbinv);
        } else {

            $sql_inv = "update cred set dummy_val=" . $Commi_val . " ,SETTLED=" . $commman_val . "  where  C_REFNO='" . $_GET[$grnno] . "'";
            //echo $sql_inv;
            $result_inv = mysql_query($sql_inv, $dbinv);
        }



        $r = $r + 1;
    }

    echo "Updated";
}


if ($_GET["Command"] == "com_lock") {

    include("connection.php");





    $mrefno = date("m/Y", strtotime($_GET["dtMonth"])) . "-" . $_GET["cmbrep"] . "-" . $_GET["cmbdev"];

    $sql_commadva = "select * from s_commadva where refno='" . $mrefno . "'";
    $result_commadva = mysql_query($sql_commadva, $dbinv);
    if ($row_commadva = mysql_fetch_array($result_commadva)) {

        $sql = "Update s_commadva set Lock1 ='1' where refno = '" . $mrefno . "' AND FLAG='ADV'";
        $result = mysql_query($sql, $dbinv);

        $sql = "Update s_commadva set appby = '" . $_SESSION["CURRENT_USER"] . "' where refno = '" . $mrefno . "' AND FLAG='ADV'";
        $result = mysql_query($sql, $dbinv);

        $sql = "Update s_commadva set appdate = '" . date("Y-m-d") . "' where refno = '" . $mrefno . "' AND FLAG='ADV'";
        $result = mysql_query($sql, $dbinv);

        echo "Records are Locked";
    } else {
        echo "No Records Found";
    }
}


if ($_GET["Command"] == "com_unlock") {

    include("connection.php");



    //$X = MsgBox("Are you sure that you want to unlock this entry?", vbYesNo, Warning)
//If X = vbNo Then Exit Sub

    $mrefno = date("m/Y", strtotime($_GET["dtMonth"])) . "-" . $_GET["cmbrep"] . "-" . $_GET["cmbdev"];
    $sql_commadva = "select * from s_commadva where refno='" . $mrefno . "'";
    $result_commadva = mysql_query($sql_commadva, $dbinv);
    if ($row_commadva = mysql_fetch_array($result_commadva)) {

        if ($row_commadva["chno"] == "0") {

            $sql = "Update s_commadva set lock1 ='0' where refno = '" . $mrefno . "' AND FLAG='ADV'";
            $result = mysql_query($sql, $dbinv);

            $sql = "Update s_commadva set appby = '' where refno = '" . $mrefno . "' AND FLAG='ADV'";
            $result = mysql_query($sql, $dbinv);

            $sql = "Update s_commadva set appdate = '' where refno = '" . $mrefno . "' AND FLAG='ADV'";
            $result = mysql_query($sql, $dbinv);

            echo "Records are UnLocked";
        } else {
            echo "Can not Unlocked, Cheque written";
        }
    }
}

if ($_GET["Command"] == "lock_advance") {

    $year = substr($_GET["dtMonth"], 0, 4);
    $month = substr($_GET["dtMonth"], 5, 2);

    $mrefno = $month . "/" . $year . "-" . substr($_GET["cmbrep"], 0, 2) . "-" . $_GET["cmbdev"];

    $sql_commadva = "select * from s_commadva where refno='" . $mrefno . "'";
    $result_commadva = $db->RunQuery($sql_commadva);
    if ($row_commadva = mysql_fetch_array($result_commadva)) {
        $sql = "Update s_commadva set Lock1 ='1' where refno = '" . $mrefno . "' AND FLAG='ADV'";
        $result = $db->RunQuery($sql);
        $sql = "Update s_commadva set Over60out = '" . $_GET["txtover60"] . "' where refno = '" . $mrefno . "' AND FLAG='ADV'";
        $result = $db->RunQuery($sql);
        $sql = "Update s_commadva set Returnchk = '" . $_GET["txtretcheq"] . "' where refno = '" . $mrefno . "' AND FLAG='ADV'";
        $result = $db->RunQuery($sql);

        $sql = "Update s_commadva set appby = '" . $_SESSION["CURRENT_USER"] . "' where refno = '" . $mrefno . "' AND FLAG='ADV'";
        $result = $db->RunQuery($sql);
        $sql = "Update s_commadva set appdate = '" . date("Y-m-d") . "' where refno = '" . $mrefno . "' AND FLAG='ADV'";
        $result = $db->RunQuery($sql);

        echo "Records are Locked";
    } else {
        echo "No Records Found";
    }
}

if ($_POST["Command"] == "calculate_ins") {

    include('connection.php');

    //$REFNO = trim($_GET["txt_cuscode"]) . date("m", strtotime($_GET["DTPicker1"])) . date("Y", strtotime($_GET["DTPicker1"])) . "Save";
    //Call cmd_cal_Click////////////////////////////////////////////////////////
    //Call Auto_cal///////////////////////////////////////

    $txttot1 = 0;
    $i = 1;
    $txttot1_W = 0;
    $txt_grn_w = 0;
    $txt_grn = str_replace(",", "", $_POST["txt_grn"]);

    $TypeGrid1_count = $_POST["mcount"];
    while ($i < $TypeGrid1_count) {
        $TypeGrid03 = "TypeGrid_" . $i . "_03";
        $TypeGrid08 = "TypeGrid_" . $i . "_08";
        $TypeGrid09 = "TypeGrid_" . $i . "_09";
        $TypeGrid10 = "TypeGrid_" . $i . "_10";
        $TypeGrid14 = "TypeGrid_" . $i . "_14";
        $TypeGrid16 = "TypeGrid_" . $i . "_16";
        $val_TypeGrid08 = str_replace(",", "", $_POST[$TypeGrid08]);
        $val_TypeGrid03 = str_replace(",", "", $_POST[$TypeGrid03]);
        
        if (($_POST[$TypeGrid14] == "INV") or ($_POST[$TypeGrid14] == "INV - TBR") or ($_POST[$TypeGrid14] == "INV - TBB")) {
            if ($_POST[$TypeGrid09] <= $_POST[$TypeGrid10]) {
                $txttot1 = $txttot1 + $val_TypeGrid08;
                $txttot1_W = $txttot1_W + ($val_TypeGrid08 / (1 + ($_POST[$TypeGrid16] / 100)));
            } else {
                $txttot1 = $txttot1;
            }
        } else {
            $txt_grn_w = $txt_grn_w + ($val_TypeGrid03 / (1 + ($_POST[$TypeGrid16] / 100)));
        }
        $i = $i + 1;
    }


    if (date("Y", strtotime($_POST["DTPicker1"])) > 2012) {

        $sql_rsper = "Select * from intper_goodyear where sdate <= '" . $_POST["DTPicker1"] . "' and traget < " . (($txttot1_W - $txt_grn_w)) . " and brand='" . $_POST["cmbtype"] . "' order by sdate desc,traget desc ";
        //echo $sql_rsper;

        $result_rsper = mysql_query($sql_rsper, $dbinv);
        if ($row_rsper = mysql_fetch_array($result_rsper)) {

            $txt_percentage = $row_rsper["per"];
            $ii = 1;
            while ($ii < $TypeGrid1_count) {

                $TypeGrid03 = "TypeGrid_" . $ii . "_03";
                $TypeGrid08 = "TypeGrid_" . $ii . "_08";
                $TypeGrid09 = "TypeGrid_" . $ii . "_09";
                $TypeGrid10 = "TypeGrid_" . $ii . "_10";
                $TypeGrid14 = "TypeGrid_" . $ii . "_14";
                $TypeGrid16 = "TypeGrid_" . $ii . "_16";
                $val_TypeGrid03 = str_replace(",", "", $_POST[$TypeGrid03]);
                $val_TypeGrid08 = str_replace(",", "", $_POST[$TypeGrid08]);

                if (($_POST[$TypeGrid14] == "INV") or ($_POST[$TypeGrid14] == "INV - TBR") or ($_POST[$TypeGrid14] == "INV - TBB")) {
                    if ($_POST[$TypeGrid09] <= $_POST[$TypeGrid10]) {
                        $TypeGrid[$ii][13] = ($val_TypeGrid08 / 100 * $txt_percentage) / ($_POST[$TypeGrid16] + 100) * 100;
                    } else {
                        $TypeGrid[$ii][13] = 0;
                    }
                } else {
                    $TypeGrid[$ii][13] = -1 * ($val_TypeGrid03 / 100 * $txt_percentage) / ($_POST[$TypeGrid16] + 100) * 100;
                }
                $ii = $ii + 1;
            }
        }
    }
    $txttot1_W = 0;
    $txt_grn_w = 0;
    ////////////////////////////////////////////////
    $txttot1 = 0;
    $txttot1_W = 0;
    $tinc = 0;
    $ii = 1;
    while ($ii < $TypeGrid1_count) {

        $TypeGrid03 = "TypeGrid_" . $ii . "_03";
        $TypeGrid08 = "TypeGrid_" . $ii . "_08";
        $TypeGrid09 = "TypeGrid_" . $ii . "_09";
        $TypeGrid10 = "TypeGrid_" . $ii . "_10";
        $TypeGrid13 = "TypeGrid_" . $ii . "_13";
        $TypeGrid14 = "TypeGrid_" . $ii . "_14";
        $TypeGrid16 = "TypeGrid_" . $ii . "_16";
        $val_TypeGrid08 = str_replace(",", "", $_POST[$TypeGrid08]);
        $val_TypeGrid03 = str_replace(",", "", $_POST[$TypeGrid03]);
        $val_TypeGrid13 = str_replace(",", "", $TypeGrid[$ii][13]);

        if (($_POST[$TypeGrid14] == "INV") or ($_POST[$TypeGrid14] == "INV - TBR") or ($_POST[$TypeGrid14] == "INV - TBB")) {
            if ($_POST[$TypeGrid09] <= $_POST[$TypeGrid10]) {
                $txttot1 = $txttot1 + $val_TypeGrid08;
                $txttot1_W = $txttot1_W + ($val_TypeGrid08 / (1 + ($_POST[$TypeGrid16] / 100)));
            }
        } else {
            $txt_grn_w = $txt_grn_w + ($val_TypeGrid03 / (1 + ($_POST[$TypeGrid16] / 100)));
        }
        if ($val_TypeGrid13 != "") {
            $tinc = $tinc + $val_TypeGrid13;
        }
        $ii = $ii + 1;
    }

    $txt_tot = $txttot1;
    $txttotal = ($txttot1_W - $txt_grn_w);
    $txttot_inc = $tinc;
    $txtnetin = $txttot_inc - $_POST["txtint"];
    ////////////////////////////////////////////////////////

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $ii = 1;
    while ($ii < $TypeGrid1_count) {
        $TypeGrid13 = "TypeGrid_" . $ii . "_13";

        $ResponseXML .= "<" . $TypeGrid13 . "><![CDATA[" . number_format($TypeGrid[$ii][13], 2, ".", ",") . "]]></" . $TypeGrid13 . ">";
        $ii = $ii + 1;
    }

    $ResponseXML .= "<txt_tot><![CDATA[" . number_format($txt_tot, 2, ".", ",") . "]]></txt_tot>";
    $ResponseXML .= "<txttotal><![CDATA[" . number_format($txttotal, 2, ".", ",") . "]]></txttotal>";
    $ResponseXML .= "<txttot_inc><![CDATA[" . number_format($txttot_inc, 2, ".", ",") . "]]></txttot_inc>";
    $ResponseXML .= "<txtnetin><![CDATA[" . number_format($txtnetin, 2, ".", ",") . "]]></txtnetin>";
    $ResponseXML .= "<txt_percentage><![CDATA[" . number_format($txt_percentage, 2, ".", ",") . "]]></txt_percentage>";
    $ResponseXML .= "<TypeGrid1_count><![CDATA[" . $TypeGrid1_count . "]]></TypeGrid1_count>";

    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}

if ($_POST["Command"] == "save_advance") {

    include('connection.php');
    echo "gfdgfd";
    //$REFNO = trim($_POST["txt_cuscode"]) . date("m", strtotime($_POST["DTPicker1"])) . date("Y", strtotime($_POST["DTPicker1"])) . "Save";
    //Call cmd_cal_Click////////////////////////////////////////////////////////
    //Call Auto_cal///////////////////////////////////////

    $txttot1 = 0;
    $i = 1;
    $TypeGrid1_count = $_POST["mcount"];
    while ($i < $TypeGrid1_count) {

        $TypeGrid08 = "TypeGrid_" . $i . "_08";
        $TypeGrid09 = "TypeGrid_" . $i . "_09";
        $TypeGrid10 = "TypeGrid_" . $i . "_10";
        $TypeGrid14 = "TypeGrid_" . $i . "_14";

        if (($_POST[$TypeGrid14] == "INV") or ($_POST[$TypeGrid14] == "INV - TBR") or ($_POST[$TypeGrid14] == "INV - TBB")) {
            if ($_POST[$TypeGrid09] <= $_POST[$TypeGrid10]) {
                $txttot1 = $txttot1 + $_POST[$TypeGrid08];
            } else {
                $txttot1 = $txttot1;
            }
        }
        $i = $i + 1;
    }
    if (date("Y", strtotime($_POST["DTPicker1"])) > 2012) {
        if ((intval(date("m", strtotime($_GET["DTPicker1"]))) >= 3) and (date("Y", strtotime($_GET["DTPicker1"])) >= 2017)) {
            $sql_rsper = "Select * from intper_goodyear where incen_year = 2017 and brand='" . $_POST["cmbtype"] . "' and traget < " . (($txttot1 - $_POST["txt_grn"]) / ($_POST["txt_vat"] + 100) * 100) . " order by traget desc ";
        } else {
            $sql_rsper = "Select * from intper_goodyear where incen_year = 2013 and brand='" . $_POST["cmbtype"] . "' and traget < " . (($txttot1 - $_POST["txt_grn"]) / ($_POST["txt_vat"] + 100) * 100) . " order by traget desc ";
        }

        $result_rsper = mysql_query($sql_rsper, $dbinv);
        if ($row_rsper = mysql_fetch_array($result_rsper)) {

            $txt_percentage = $row_rsper["per"];
            $ii = 1;
            while ($ii < $TypeGrid1_count) {

                $TypeGrid03 = "TypeGrid_" . $ii . "_03";
                $TypeGrid08 = "TypeGrid_" . $ii . "_08";
                $TypeGrid09 = "TypeGrid_" . $ii . "_09";
                $TypeGrid10 = "TypeGrid_" . $ii . "_10";
                $TypeGrid14 = "TypeGrid_" . $ii . "_14";

                if (($_POST[$TypeGrid14] == "INV") or ($_POST[$TypeGrid14] == "INV - TBR") or ($_POST[$TypeGrid14] == "INV - TBB")) {
                    if ($_POST[$TypeGrid09] <= $_POST[$TypeGrid10]) {
                        $TypeGrid13 = ($_POST[$TypeGrid08] / 100 * $_POST["txt_percentage"]) / ($_POST["txt_vat"] + 100) * 100;
                    } else {
                        $TypeGrid13 = 0;
                    }
                } else {
                    $TypeGrid13 = -1 * ($_POST[$TypeGrid03] / 100 * $_POST["txt_percentage"]) / ($_POST["txt_vat"] + 100) * 100;
                }
                $ii = $ii + 1;
            }
        }
    } else {
        if ((intval(date("m", strtotime($_GET["DTPicker1"]))) >= 3) and (date("Y", strtotime($_GET["DTPicker1"])) >= 2017)) {
            $sql_rsper = "Select * from intper_goodyear where incen_year = 2017 and brand='" . $_POST["cmbtype"] . "' and traget < '" . ($txttot1 - $_POST["txt_grn"]) / ($_POST["txt_vat"] + 100) * 100 . "' order by traget desc ";
        } else {
            $sql_rsper = "Select * from intper_goodyear where incen_year = 20101 and brand='" . $_POST["cmbtype"] . "' and traget < '" . ($txttot1 - $_POST["txt_grn"]) / ($_POST["txt_vat"] + 100) * 100 . "' order by traget desc ";
        }

        $result_rsper = mysql_query($sql_rsper, $dbinv);
        if ($row_rsper = mysql_fetch_array($result_rsper)) {

            $txt_percentage = $row_rsper["per"];
            $ii = 1;
            while ($ii < $TypeGrid1_count) {

                $TypeGrid03 = "TypeGrid_" . $ii . "_03";
                $TypeGrid08 = "TypeGrid_" . $ii . "_08";
                $TypeGrid09 = "TypeGrid_" . $ii . "_09";
                $TypeGrid10 = "TypeGrid_" . $ii . "_10";
                $TypeGrid14 = "TypeGrid_" . $ii . "_14";

                if (($_POST[$TypeGrid14] == "INV") or ($_POST[$TypeGrid14] == "INV - TBR") or ($_POST[$TypeGrid14] == "INV - TBB")) {
                    if ($_POST[$TypeGrid09] <= $_POST[$TypeGrid10]) {
                        $TypeGrid[$ii][13] = ($_POST[$TypeGrid08] / 100 * $txt_percentage) / ($_POST["txt_vat"] + 100) * 100;
                    } else {
                        $TypeGrid[$ii][13] = 0;
                    }
                } else {
                    $TypeGrid[$ii][13] = -1 * ($_POST[$TypeGrid03] / 100 * $txt_percentage) / ($_POST["txt_vat"] + 100) * 100;
                }
                $ii = $ii + 1;
            }
        }
    }
    ////////////////////////////////////////////////
    $txttot1 = 0;
    $tinc = 0;
    $ii = 1;
    while ($ii < $TypeGrid1_count) {

        $TypeGrid03 = "TypeGrid_" . $ii . "_03";
        $TypeGrid08 = "TypeGrid_" . $ii . "_08";
        $TypeGrid09 = "TypeGrid_" . $ii . "_09";
        $TypeGrid10 = "TypeGrid_" . $ii . "_10";
        $TypeGrid14 = "TypeGrid_" . $ii . "_14";

        if (($_POST[$TypeGrid14] == "INV") or ($_POST[$TypeGrid14] == "INV - TBR") or ($_POST[$TypeGrid14] == "INV - TBB")) {
            if ($_POST[$TypeGrid09] <= $_POST[$TypeGrid10]) {
                $txttot1 = $txttot1 + $_POST[$TypeGrid08];
            } else {
                $txttot1 = $txttot1;
            }
        }
        if ($TypeGrid[$ii][13] != "") {
            $tinc = $tinc + $TypeGrid[$ii][13];
        }
        $ii = $ii + 1;
    }
    $txt_tot = $txttot1;
    $txttotal = ($txt_tot - $_POST["txt_grn"]) / ($_POST["txt_vat"] + 100) * 100;
    $txttot_inc = $tinc;
    $txtnetin = $txttot_inc - $_POST["txtint"];
    ////////////////////////////////////////////////////////
//  $msg = MsgBox("Do You Want Save ", vbYesNo, "Warning")
//  if ($msg = vbYes Then
    /*      $ii = 1;
      while ($ii < $TypeGrid1_count){
      if ($TypeGrid1[$ii][11] == "YES") {
      }
      $ii = $ii + 1;
  } */

    $ii = 1;
    $aa = $TypeGrid1_count;
    while ($ii < $aa) {

        $TypeGrid00 = "TypeGrid_" . $ii . "_00";
        $TypeGrid03 = "TypeGrid_" . $ii . "_03";
        $TypeGrid08 = "TypeGrid_" . $ii . "_08";
        $TypeGrid09 = "TypeGrid_" . $ii . "_09";
        $TypeGrid10 = "TypeGrid_" . $ii . "_10";
        $TypeGrid13 = "TypeGrid_" . $ii . "_13";
        $TypeGrid14 = "TypeGrid_" . $ii . "_14";

        $mdays = $_POST[$TypeGrid10];
        $MAMO = str_replace(",", "", $_POST[$TypeGrid13]);
        $M_id = $_POST[$TypeGrid00];
        $sql = "uPDATE  s_sttr set deliin_days=" . $mdays . ", deliin_amo=" . $MAMO . " WHERE ID=" . $M_id;
        // echo $sql;
        $result = mysql_query($sql, $dbinv);

        $ii = $ii + 1;
    }


    $txt_cusname = str_replace("~", "&", $_POST["txt_cusname"]);
    $txtnetin = str_replace(",", "", $_POST["txtnetin"]);
    $txtint = str_replace(",", "", $_POST["txtint"]);

    if ($_POST["txt_chno"] == "") {
        $chno = 0;
    } else {
        $chno = $_POST["txt_chno"];
    }
    if ($_GET['monthwise'] == "nch") {
        $sql_rs_ins = "Select * from ins_payment where cusCode='" . trim($_POST["txt_cuscode"]) . "' and I_month ='" . intval(date("m", strtotime($_POST["DTPicker1"]))) . "' and I_year='" . date("Y", strtotime($_POST["DTPicker1"])) . "' and Type = '" . trim($_POST["cmbtype"]) . "'";
    } else {
        $sql_rs_ins = "Select * from ins_payment where cusCode='" . trim($_POST["txt_cuscode"]) . "' and I_month ='" . intval(date("m", strtotime($_POST["DTPicker1"]))) . "' and I_year='" . date("Y", strtotime($_POST["DTPicker1"])) . "' and Type = '" . trim($_POST["cmbtype"]) . "'";
    }

    $result_rs_ins = mysql_query($sql_rs_ins, $dbinv);
    if ($row_rs_ins = mysql_fetch_array($result_rs_ins)) {

        if ((trim($row_rs_ins["chno"]) == "0") or (trim($row_rs_ins["chno"]) == "X")) {
            //$sql_ins = "delete  from ins_payment where cusCode='" . trim($_POST["txt_cuscode"]) . "' and I_month ='" . intval(date("m", strtotime($_POST["DTPicker1"]))) . "' and I_year='" . date("Y", strtotime($_POST["DTPicker1"])) . "' and Type = '" . trim($_POST["cmbtype"]) . "'";
            $sql_ins = "delete  from ins_payment where cusCode='" . trim($_POST["txt_cuscode"]) . "' and I_month ='" . intval(date("m", strtotime($_POST["DTPicker1"]))) . "' and I_year='" . date("Y", strtotime($_POST["DTPicker1"])) . "' and Type = '" . trim($_POST["cmbtype"]) . "'";
            $result_ins = mysql_query($sql_ins, $dbinv);

            $mtxtPrepare = $_POST["txtPrepare"];
            $mtxtauth = $_POST["txtauth"];

            $sql_para = "select * from invpara";
            $result_para = mysql_query($sql_para, $dbinv);
            $row_para = mysql_fetch_array($result_para);

            $remark = trim($_POST["txtremark"]) . " " . trim($_POST["txtremark_new"]);
            if ($_POST["chq_ignore"] == "true") {
                $chno = "X";
            }

            $sql_ins_insert = "insert into ins_payment(Cuscode, CUSNAME, I_month, I_year, SDATE, remarks, pr_by, auth_by, amount, Interest,  Percentage, indate, REFNO, Type, chno) values('" . $_POST["txt_cuscode"] . "', '" . $txt_cusname . "', '" . intval(date("m", strtotime($_POST["DTPicker1"]))) . "', '" . date("Y", strtotime($_POST["DTPicker1"])) . "', '" . date("Y-m-d") . "', '" . $remark . "', '" . $mtxtPrepare . "', '" . $_SESSION["CURRENT_USER"] . "', '" . $txtnetin . "', '" . $txtint . "', '" . $_POST["txt_percentage"] . "', '" . $_POST["DTPicker1"] . "', '" . $row_para["delinno"] . "', '" . $_POST["cmbtype"] . "', '" . $chno . "')";
            echo $sql_ins_insert . "-1";
            $result_ins_insert = mysql_query($sql_ins_insert, $dbinv);

            $sql_inv = "update invpara set delinno=delinno+1";
            $result_inv = mysql_query($sql_inv, $dbinv);

            echo "Saved";
        } else {
            exit("This Insentive payment Already Saved");
        }
    } else {
        // $sql_ins = "delete  from ins_payment where cusCode='" . trim($_POST["txt_cuscode"]) . "' and I_month ='" . intval(date("m", strtotime($_POST["DTPicker1"]))) . "' and I_year='" . date("Y", strtotime($_POST["DTPicker1"])) . "' and Type = '" . trim($_POST["cmbtype"]) . "'";
        // $sql_ins = "delete  from ins_payment where cusCode='" . trim($_POST["txt_cuscode"]) . "' and I_month ='" . intval(date("m", strtotime($_POST["DTPicker1"]))) . "' and I_year='" . date("Y", strtotime($_POST["DTPicker1"])) . "' ";

        $result_ins = mysql_query($sql_ins, $dbinv);

        $mtxtPrepare = $_POST["txtPrepare"];
        $mtxtauth = $_POST["txtauth"];

        $sql_para = "select * from invpara";
        $result_para = mysql_query($sql_para, $dbinv);
        $row_para = mysql_fetch_array($result_para);

        $remark = trim($_POST["txtremark"]) . " " . trim($_POST["txtremark_new"]);
        if ($_POST["chq_ignore"] == "true") {
            $chno = "X";
        }

        $sql_ins_insert = "insert into ins_payment(Cuscode, CUSNAME, I_month, I_year, SDATE, remarks, pr_by, auth_by, amount, Interest,  Percentage, indate, REFNO, Type, chno) values('" . $_POST["txt_cuscode"] . "', '" . $txt_cusname . "', '" . intval(date("m", strtotime($_POST["DTPicker1"]))) . "', '" . date("Y", strtotime($_POST["DTPicker1"])) . "', '" . date("Y-m-d") . "', '" . $remark . "', '" . $mtxtPrepare . "', '" . $_SESSION["CURRENT_USER"] . "', '" . $txtnetin . "', '" . $txtint . "', '" . $_POST["txt_percentage"] . "', '" . $_POST["DTPicker1"] . "', '" . $row_para["delinno"] . "', '" . $_POST["cmbtype"] . "', '" . $chno . "')";
        echo $sql_ins_insert . "-2";
        $result_ins_insert = mysql_query($sql_ins_insert, $dbinv);

        $sql_inv = "update invpara set delinno=delinno+1";
        $result_inv = mysql_query($sql_inv, $dbinv);

        echo "Saved";
    }
}

if ($_GET["Command"] == "settlement") {

    $sql = "SELECT * FROM c_bal";
    $result = $db->RunQuery($sql);
    while ($row = mysql_fetch_array($result)) {

        $sql1 = "Select sum(C_PAYMENT) as paid  from s_ut where CRE_NO_NO='" . $row['REFNO'] . "'";
        $result1 = $db->RunQuery($sql1);
        if ($row1 = mysql_fetch_array($result1)) {
            if (is_null($row1["paid"])) {
                $mpaid = $row1["paid"];
            }

            $sql2 = "update c_bal set totpay ='" . $mpaid . "' where REF_NO='" . $row["REF_NO"] . "'";
            $result2 = $db->RunQuery($sql2);
        }
    }
}

if ($_GET["Command"] == "proces") {

    include('connection.php');

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $year = substr($_GET["DTPicker1"], 0, 4);
    $month = substr($_GET["DTPicker1"], 5, 2);

    $sql_rs = "select * from vatrate where  sdate <='" . trim($_GET["DTPicker1"]) . "' order by sdate desc";
    //    $sql_rs = "select * from vatrate where month(sdate)<='" . trim($month) . "' and  year(sdate)<='" . trim($year) . "' order by sdate desc";
    $result_rs = $db->RunQuery($sql_rs);
    if ($row_rs = mysql_fetch_array($result_rs)) {
        $txtvat_new = $row_rs['vatrate'];
        $txtvat = $row_rs['vatrate'];
        $txt_vat = $row_rs['vatrate'];
    }
    if ($_GET['monthwise'] == "nch") {
        $sql_rssslma .= "select * from s_salma where Accname != 'NON STOCK' and  month(sdate1)='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "' and  year(sdate1)='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and C_CODE = '" . trim($_GET["txt_cuscode"]) . "'  and CANCELL='0'";
    } else {
        $sql_rssslma .= "select * from s_salma where Accname != 'NON STOCK' and  ((month(sdate1)='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "' and  year(sdate1)='" . date("Y", strtotime($_GET["DTPicker1"])) . "') or (month(sdate1)='" . intval(date("m", strtotime($_GET["DTPicker2"]))) . "' and  year(sdate1)='" . date("Y", strtotime($_GET["DTPicker2"])) . "')) and C_CODE = '" . trim($_GET["txt_cuscode"]) . "' and  CANCELL='0'";
    }



    // ================prawee 25.06.08
    $brand1 = $_GET["cmbtype"];
    $sql_333 = "Select * from intper_goodyear where sdate <= '" . $_GET["DTPicker1"] . "'  and brand='" . $_GET["cmbtype"] . "' ORDER BY sdate DESC, traget DESC ";
    $result_333 = mysql_query($sql_333, $dbinv);
    if ($row_333 = mysql_fetch_array($result_333)) {
        $brand1 = $row_333['brand'];
        $brand2 = $row_333['brand1'];
        $brand3 = $row_333['brand2'];
        $brand4 = $row_333['brand3'];
    }

    if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
        $sql_rssslma .= " and Brand = '" . $brand1 . "' ";
    } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
        $sql_rssslma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' )";
    } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
        $sql_rssslma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' )";
    } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
        $sql_rssslma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' or Brand = '" . $brand4 . "' )";
    }


    // ===========prawee 25.06.08
    // echo $sql_rssslma;
//    two month 19.09.09

    $TXTTOT = 0;
    $totincen = 0;
    $totout = 0;
    $txttot_inc = "";
    $ii = 1;

    $result_rssslma = mysql_query($sql_rssslma, $dbinv);

    $xx = $count_rssslma;

    $result_rssslma = mysql_query($sql_rssslma, $dbinv);
    while ($row_rssslma = mysql_fetch_array($result_rssslma)) {

        $sql_rsbrand_mas = "select * from brand_mas where barnd_name = '" . trim($row_rssslma["Brand"]) . "'";

        $sql_rsVENDOR = "select * from vendor where CODE = '" . $row_rssslma["C_CODE"] . "' ";

        $result_rsVENDOR = mysql_query($sql_rsVENDOR, $dbinv);
        $row_rsVENDOR = mysql_fetch_array($result_rsVENDOR);

        if (($_GET["cmbtype"] == "GOODYEAR") or ($_GET["cmbtype"] == "MAXXIS MC TYRE")) {


            if ((intval(date("m", strtotime($_GET["DTPicker1"]))) < 11) and (date("Y", strtotime($_GET["DTPicker1"])) <= 2010)) {

                $result_rsbrand_mas = mysql_query($sql_rsbrand_mas, $dbinv);
                if ($row_rsbrand_mas = mysql_fetch_array($result_rsbrand_mas)) {
                    if ($row_rsbrand_mas["delinrate"] == 0) {


                        $TypeGrid1[$ii][1] = $row_rssslma["REF_NO"];
                        if ((is_null($row_rssslma["deli_date"]) == false) and ($row_rssslma["deli_date"] != "0000-00-00")) {
                            $TypeGrid1[$ii][2] = $row_rssslma["deli_date"];
                            $mdate = $row_rssslma["deli_date"];
                        } else {
                            $TypeGrid1[$ii][2] = $row_rssslma["sdate1"];
                            $mdate = $row_rssslma["sdate1"];
                        }
                        $TypeGrid1[$ii][3] = $row_rssslma["GRAND_TOT"];
                        $TypeGrid1[$ii][4] = $row_rssslma["TOTPAY"];
                        $TypeGrid1[$ii][5] = $row_rssslma["Brand"];



                        if ($row_rssslma["GRAND_TOT"] > $row_rssslma["TOTPAY"]) {
                            $totout = $totout + ($row_rssslma["GRAND_TOT"] - $row_rssslma["TOTPAY"]);
                        } else {
                            $totout = $totout + 0;
                        }

                        $sql_rssttr = "select * from s_sttr where ST_INVONO = '" . trim($row_rssslma["REF_NO"]) . "'";

                        $result_rssttr = mysql_query($sql_rssttr, $dbinv);

                        while ($row_rssttr = mysql_fetch_array($result_rssttr)) {

                            $TypeGrid1[$ii][0] = $row_rssttr["ID"];
                            $TypeGrid1[$ii][6] = $row_rssttr["ST_REFNO"];
                            $TypeGrid1[$ii][7] = $row_rssttr["ST_DATE"];

                            if ((is_null($row_rssttr["st_chdate"]) == false) and ($row_rssttr["st_chdate"] != "0000-00-00")) {
                                $TypeGrid1[$ii][7] = $row_rssttr["st_chdate"];

                                $diff = abs(strtotime($row_rssttr["st_chdate"]) - strtotime($mdate));
                                $days = dateDifference($row_rssttr["st_chdate"], $mdate, $differenceFormat = '%a');
                                $days = floor($diff / (60 * 60 * 24));
                                $TypeGrid1[$ii][9] = $days;
                            } else {
                                $TypeGrid1[$ii][7] = $row_rssttr["ST_DATE"];

                                $diff = abs(strtotime($row_rssttr["ST_DATE"]) - strtotime($mdate));
                                $days = dateDifference($row_rssttr["ST_DATE"], $mdate, $differenceFormat = '%a');
                                $days = floor($diff / (60 * 60 * 24));
                                $TypeGrid1[$ii][9] = $days;
                            }
                            if (is_null($row_rsVENDOR["incdays"]) == false) {
                                $TypeGrid1[$ii][10] = $row_rsVENDOR["incdays"];
                            }
                            $TypeGrid1[$ii][8] = $row_rssttr["ST_PAID"];

                            if ($row_rssttr["deliin_amo"] > 0) {
                                if ((is_null($row_rssttr["deliin_days"]) == false) and ($row_rssttr["deliin_days"] != "0000-00-00")) {
                                    $TypeGrid1[$ii][10] = $row_rssttr["deliin_days"];
                                }
                            }

                            if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                                $TypeGrid1[$ii][12] = ($row_rssttr["ST_PAID"] / 100 * $_GET["txt_percentage"]) / ($txt_vat + 100) * 100;
                            } else {
                                $TypeGrid1[$ii][12] = 0;
                            }
                            if (is_null($row_rssttr["deliin_amo"]) == false) {
                                $TypeGrid1[$ii][13] = $row_rssttr["deliin_amo"];
                                $txttot_inc = $txttot_inc + $row_rssttr["deliin_amo"];
                            }
                            if ($row_rssttr["deliin_lock"] == "1") {
                                $TypeGrid1[$ii][11] = "YES";
                            } else {
                                $TypeGrid1[$ii][11] = "NO";
                            }
                            $TypeGrid1[$ii][14] = "INV";
                            if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                                $TXTTOT = $TXTTOT + $row_rssttr["ST_PAID"];
                            } else {
                                $TXTTOT = $TXTTOT + 0;
                            }
                            $totincen = $totincen + $TypeGrid1[$ii][12];

                            $ii = $ii + 1;
                        }

                    }
                }

            } else {

                $result_rsbrand_mas = mysql_query($sql_rsbrand_mas, $dbinv);
                if ($row_rsbrand_mas = mysql_fetch_array($result_rsbrand_mas)) {

                    $TypeGrid1[$ii][1] = $row_rssslma["REF_NO"];
                    if ((is_null($row_rssslma["deli_date"]) == false) and ($row_rssslma["deli_date"] != "0000-00-00")) {
                        $TypeGrid1[$ii][2] = $row_rssslma["deli_date"];
                        $mdate = $row_rssslma["deli_date"];
                    } else {
                        $TypeGrid1[$ii][2] = $row_rssslma["sdate1"];
                        $mdate = $row_rssslma["sdate1"];
                    }
                    $TypeGrid1[$ii][3] = $row_rssslma["GRAND_TOT"];
                    $TypeGrid1[$ii][4] = $row_rssslma["TOTPAY"];
                    $TypeGrid1[$ii][5] = $row_rssslma["Brand"];


                    if ($row_rssslma["GRAND_TOT"] > $row_rssslma["TOTPAY"]) {
                        $totout = $totout + ($row_rssslma["GRAND_TOT"] - $row_rssslma["TOTPAY"]);
                    } else {
                        $totout = $totout + 0;
                    }


                    $sql_rssttr = "select * from s_sttr where ST_INVONO = '" . trim($row_rssslma["REF_NO"]) . "'";

                    $result_rssttr = mysql_query($sql_rssttr, $dbinv);
                    while ($row_rssttr = mysql_fetch_array($result_rssttr)) {


                        $TypeGrid1[$ii][0] = $row_rssttr["ID"];

                        $TypeGrid1[$ii][6] = $row_rssttr["ST_REFNO"];
                        $TypeGrid1[$ii][7] = $row_rssttr["ST_DATE"];
                        if ((is_null($row_rssttr["st_chdate"]) == false) and ($row_rssttr["st_chdate"] != "0000-00-00")) {
                            $TypeGrid1[$ii][7] = $row_rssttr["st_chdate"];


                            $diff = abs(strtotime($row_rssttr["st_chdate"]) - strtotime($mdate));
                            $days = dateDifference($row_rssttr["st_chdate"], $mdate, $differenceFormat = '%a');
                            $days = floor($diff / (60 * 60 * 24));
                            $TypeGrid1[$ii][9] = $days;
                        } else {
                            $TypeGrid1[$ii][7] = $row_rssttr["ST_DATE"];

                            $diff = abs(strtotime($row_rssttr["ST_DATE"]) - strtotime($mdate));
                            $days = dateDifference($row_rssttr["ST_DATE"], $mdate, $differenceFormat = '%a');
                            $days = floor($diff / (60 * 60 * 24));
                            $TypeGrid1[$ii][9] = $days;
                        }

                        // pppp
                        // $mdcou = $row_rsVENDOR["incdays"];


                        // $sqldays = "select * from br_trn where brand ='" . $row_rsbrand_mas["class"] . "' and cus_code = '" . $_GET["txt_cuscode"] . "' and Rep='" . $row_rssslma["SAL_EX"] . "' order by days desc";
                        // $result_days = mysql_query($sqldays, $dbinv);
                        // if ($row_days = mysql_fetch_array($result_days)) {
                        //     $mdcou = $row_days['days'];
                        // }

                        // $TypeGrid1[$ii][10] = $mdcou;

                        if ($row_rssslma["cre_pe"] != "") {
                            $TypeGrid1[$ii][10] = $row_rssslma["cre_pe"];
                        } else {
                            $TypeGrid1[$ii][10] = $row_rsVENDOR["incdays"];
                        }

                        //ppppppppppppppppppppp
                        $TypeGrid1[$ii][8] = $row_rssttr["ST_PAID"];

                        if ($row_rssttr["deliin_amo"] > 0) {
                            if ((is_null($row_rssttr["deliin_days"]) == false) and ($row_rssttr["deliin_days"] != "0000-00-00")) {
                                $TypeGrid1[$ii][10] = $row_rssttr["deliin_days"];
                            }
                        }
                        if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                            $TypeGrid1[$ii][12] = ($row_rssttr["ST_PAID"] / 100 * $_GET["txt_percentage"]) / ($txt_vat + 100) * 100;
                        } else {
                            $TypeGrid1[$ii][12] = 0;
                        }
                        if (is_null($row_rssttr["deliin_amo"]) == false) {
                            $TypeGrid1[$ii][13] = $row_rssttr["deliin_amo"];
                            $txttot_inc = $txttot_inc + $row_rssttr["deliin_amo"];
                        }
                        if ($row_rssttr["deliin_lock"] == "1") {
                            $TypeGrid1[$ii][11] = "YES";
                        } else {
                            $TypeGrid1[$ii][11] = "NO";
                        }
                        $sql_rstype = "Select * from view_inv_item where REF_NO = '" . $row_rssslma["REF_NO"] . "' order by id";
                        $result_rstype = mysql_query($sql_rstype, $dbinv);
                        $row_rstype = mysql_fetch_array($result_rstype);
                        if (trim($row_rstype["type"]) == "TBR") {
                            $TypeGrid1[$ii][14] = "INV - TBR";
                        } else {
                            if (trim($row_rstype["type"]) == "BIAS TYRES") {
                                $TypeGrid1[$ii][14] = "INV - TBB";
                            } else {
                                $TypeGrid1[$ii][14] = "INV";
                            }
                        }

                        $TypeGrid1[$ii][15] = $row_rssslma["GST"];
                        $TypeGrid1[$ii][16] = $row_rssslma["sdate1"];
                        if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                            $TXTTOT = $TXTTOT + $row_rssttr["ST_PAID"];
                        } else {
                            $TXTTOT = $TXTTOT + 0;
                        }
                        $totincen = $totincen + $TypeGrid1[$ii][12];

                        $ii = $ii + 1;
                    }
                    //                        }
//                    }pppppppppppppppppppppppppppppppppppp
                }
            }
        } else {
            if ((intval(date("m", strtotime($_GET["DTPicker1"]))) >= 1) and (date("Y", strtotime($_GET["DTPicker1"])) > 2010)) {

                $result_rsbrand_mas = mysql_query($sql_rsbrand_mas, $dbinv);
                if ($row_rsbrand_mas = mysql_fetch_array($result_rsbrand_mas)) {

                    if ($row_rsbrand_mas["delinrate"] == 3.5) {
                        if ($row_rsbrand_mas["delinrate"] >= 0) {

                            $TypeGrid1[$ii][1] = $row_rssslma["REF_NO"];
                            if ((is_null($row_rssslma["deli_date"]) == false) and ($row_rssslma["deli_date"] != "0000-00-00")) {
                                $TypeGrid1[$ii][2] = $row_rssslma["deli_date"];
                                $mdate = $row_rssslma["deli_date"];
                            } else {
                                $TypeGrid1[$ii][2] = $row_rssslma["sdate1"];
                                $mdate = $row_rssslma["sdate1"];
                            }
                            $TypeGrid1[$ii][3] = $row_rssslma["GRAND_TOT"];
                            $TypeGrid1[$ii][4] = $row_rssslma["TOTPAY"];
                            $TypeGrid1[$ii][5] = $row_rssslma["Brand"];


                            if ($row_rssslma["GRAND_TOT"] > $row_rssslma["TOTPAY"]) {
                                $totout = $totout + ($row_rssslma["GRAND_TOT"] - $row_rssslma["TOTPAY"]);
                            } else {
                                $totout = $totout + 0;
                            }



                            $sql_rssttr = "select * from s_sttr where ST_INVONO = '" . trim($row_rssslma["REF_NO"]) . "'";
                            $result_rssttr = mysql_query($sql_rssttr, $dbinv);
                            while ($row_rssttr = mysql_fetch_array($result_rssttr)) {

                                $TypeGrid1[$ii][0] = $row_rssttr["ID"];

                                $TypeGrid1[$ii][6] = $row_rssttr["ST_REFNO"];
                                $TypeGrid1[$ii][7] = $row_rssttr["ST_DATE"];
                                if ((is_null($row_rssttr["st_chdate"]) == false) and ($row_rssttr["st_chdate"] != "0000-00-00")) {
                                    $TypeGrid1[$ii][7] = $row_rssttr["st_chdate"];

                                    $diff = (strtotime($row_rssttr["st_chdate"]) - strtotime($mdate));

                                    $days = dateDifference($row_rssttr["st_chdate"], $mdate, $differenceFormat = '%a');
                                    $days = floor($diff / (60 * 60 * 24));

                                    $TypeGrid1[$ii][9] = $days;
                                } else {
                                    $TypeGrid1[$ii][7] = $row_rssttr["ST_DATE"];

                                    $diff = (strtotime($row_rssttr["ST_DATE"]) - strtotime($mdate));
                                    $days = dateDifference($row_rssttr["ST_DATE"], $mdate, $differenceFormat = '%a');
                                    $days = floor($diff / (60 * 60 * 24));

                                    $TypeGrid1[$ii][9] = $days;
                                }

                                // $TypeGrid1[$ii][10] = 75;

                                if ($row_rssslma["cre_pe"] != "") {
                                    $TypeGrid1[$ii][10] = $row_rssslma["cre_pe"];
                                }

                                $TypeGrid1[$ii][8] = $row_rssttr["ST_PAID"];

                                if ($row_rssttr["deliin_amo"] > 0) {
                                    if ((is_null($row_rssttr["deliin_days"]) == false) and ($row_rssttr["deliin_days"] != "0000-00-00")) {
                                        $TypeGrid1[$ii][10] = $row_rssttr["deliin_days"];
                                    }
                                }
                                if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                                    $TypeGrid1[$ii][12] = ($row_rssttr["ST_PAID"] / 100 * $_GET["txt_percentage"]) / ($txt_vat + 100) * 100;
                                } else {
                                    $TypeGrid1[$ii][12] = 0;
                                }
                                if (is_null($row_rssttr["deliin_amo"]) == false) {
                                    $TypeGrid1[$ii][13] = $row_rssttr["deliin_amo"];
                                    $txttot_inc = $txttot_inc + $row_rssttr["deliin_amo"];
                                }
                                if ($row_rssttr["deliin_lock"] == "1") {
                                    $TypeGrid1[$ii][11] = "YES";
                                } else {
                                    $TypeGrid1[$ii][11] = "NO";
                                }
                                $TypeGrid1[$ii][14] = "INV";
                                $TypeGrid1[$ii][15] = $row_rssslma["GST"];
                                $TypeGrid1[$ii][16] = $row_rssslma["sdate1"];

                                if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                                    $TXTTOT = $TXTTOT + $row_rssttr["ST_PAID"];
                                } else {
                                    $TXTTOT = $TXTTOT + 0;
                                }
                                $totincen = $totincen + $TypeGrid1[$ii][12];

                                $ii = $ii + 1;
                            }
                        }
                    }
                }
            }
        }
    }

    $txt_tot = $TXTTOT;

    $txt_out = $totout;

    $totgrn = 0;
    $tgrnince = 0;
    if ($_GET['monthwise'] == "nch") {
        $sql_RScbal .= "select * from c_bal where  month(sdate1)='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "' and  year(sdate1)='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and CANCELL='0'and CUSCODE='" . trim($_GET["txt_cuscode"]) . "' AND trn_type != 'ARN' and trn_type != 'REC' and trn_type != 'DGRN' and flag1 != '1' order by sdate1";
    } else {
        $sql_RScbal .= "select * from c_bal where  ((month(sdate1)='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "' and  year(sdate1)='" . date("Y", strtotime($_GET["DTPicker1"])) . "') or (month(sdate1)='" . intval(date("m", strtotime($_GET["DTPicker2"]))) . "' and  year(sdate1)='" . date("Y", strtotime($_GET["DTPicker2"])) . "') ) and CANCELL='0' and CUSCODE='" . trim($_GET["txt_cuscode"]) . "' AND trn_type != 'ARN' and trn_type != 'REC' and trn_type != 'DGRN' and flag1 != '1' order by sdate1";
    }



    // ================prawee 25.06.08
    // if($brand1 !=""){
    //      $sql_RScbal .=" and brand = '" . $brand1 . "'";
    // }else if($brand2 !=""){
    //      $sql_RScbal .=" and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' )";
    // }else if($brand3 !=""){
    //      $sql_RScbal .=" and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' )";
    // }else if($brand4 !=""){
    //      $sql_RScbal .=" and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' or brand = '" . $brand4 . "' )";
    // }

    // ===========prawee 25.06.08
    $brands = [];
    if ($brand1 != "")
        $brands[] = "'$brand1'";
    if ($brand2 != "")
        $brands[] = "'$brand2'";
    if ($brand3 != "")
        $brands[] = "'$brand3'";
    if ($brand4 != "")
        $brands[] = "'$brand4'";

    if (count($brands) > 0) {
        $sql_RScbal .= " AND brand IN (" . implode(",", $brands) . ")";
    }
    ////////////////////////////

    //    two month 19.09.09

    $result_RScbal = mysql_query($sql_RScbal, $dbinv);
    while ($row_RScbal = mysql_fetch_array($result_RScbal)) {

        $sql_rsbrand_mas = "select * from brand_mas where barnd_name = '" . trim($row_RScbal["brand"]) . "'";

        if (($_GET["cmbtype"] == "GOODYEAR") or ($_GET["cmbtype"] == "MAXXIS MC TYRE")) {

            if ((intval(date("m", strtotime($_GET["DTPicker1"]))) < 11) and (date("Y", strtotime($_GET["DTPicker1"])) <= 2010)) {

                $result_rsbrand_mas = mysql_query($sql_rsbrand_mas, $dbinv);
                if ($row_rsbrand_mas = mysql_fetch_array($result_rsbrand_mas)) {
                    if ($row_rsbrand_mas["delinrate"] == 0) {
                        //                        if ($row_rsbrand_mas["delinrate"] >= 0) {

                        $TypeGrid1[$ii][1] = $row_RScbal["REFNO"];
                        $TypeGrid1[$ii][2] = $row_RScbal["sdate1"];
                        $TypeGrid1[$ii][3] = $row_RScbal["AMOUNT"];
                        $TypeGrid1[$ii][5] = $row_RScbal["brand"];
                        $TypeGrid1[$ii][12] = -1 * ($TypeGrid1[$ii][3] / 100 * $_GET["txt_percentage"]) / ($txt_vat + 100) * 100;
                        $TypeGrid1[$ii][13] = -1 * ($TypeGrid1[$ii][3] / 100 * $_GET["txt_percentage"]) / ($txt_vat + 100) * 100;
                        $TypeGrid1[$ii][14] = $row_RScbal["trn_type"];
                        $totgrn = $totgrn + $row_RScbal["AMOUNT"];
                        $tgrnince = $tgrnince + $TypeGrid1[$ii][12];
                        $txttot_inc = $txttot_inc + $TypeGrid1[$ii][12];
                        $ii = $ii + 1;
                        //                        }
                    }
                }
            } else {

                if ((intval(date("m", strtotime($_POST["DTPicker1"]))) < 11) and (date("Y", strtotime($_POST["DTPicker1"])) <= 2010)) {
                    $result_rsbrand_mas = mysql_query($sql_rsbrand_mas, $dbinv);
                    if ($row_rsbrand_mas = mysql_fetch_array($result_rsbrand_mas)) {
                        if ($row_rsbrand_mas["delinrate"] == 2.5) {
                            if ($row_rsbrand_mas["delinrate"] >= 0) {

                                $TypeGrid1[$ii][1] = $row_RScbal["REFNO"];
                                $TypeGrid1[$ii][2] = $row_RScbal["sdate1"];
                                $TypeGrid1[$ii][3] = $row_RScbal["AMOUNT"];
                                $TypeGrid1[$ii][5] = $row_RScbal["brand"];
                                $TypeGrid1[$ii][12] = -1 * ($TypeGrid1[$ii][3] / 100 * $_POST["txt_percentage"]) / ($txt_vat + 100) * 100;
                                $TypeGrid1[$ii][13] = -1 * ($TypeGrid1[$ii][3] / 100 * $_POST["txt_percentage"]) / ($txt_vat + 100) * 100;
                                $TypeGrid1[$ii][14] = $row_RScbal["trn_type"];
                                $totgrn = $totgrn + $row_RScbal["AMOUNT"];
                                $tgrnince = $tgrnince + $TypeGrid1[$ii][12];
                                $txttot_inc = $txttot_inc + $TypeGrid1[$ii][12];
                                $ii = $ii + 1;
                            }
                        }
                    }
                }








                //                echo 'asdsa';  praewwwwww
//             $result_rsbrand_mas = mysql_query($sql_rsbrand_mas, $dbinv);
//             if ($row_rsbrand_mas = mysql_fetch_array($result_rsbrand_mas)) {
//                 if ($row_rsbrand_mas["delinrate"] == 0) {
//                     if ($row_rsbrand_mas["delinrate"] == 0) {
// //                    if ($row_rsbrand_mas["delinrate"] == 2.5) {
// //                        if ($row_rsbrand_mas["delinrate"] >= 0) {

                //                         $TypeGrid1[$ii][1] = $row_RScbal["REFNO"];
//                         $TypeGrid1[$ii][2] = $row_RScbal["sdate1"];
//                         $TypeGrid1[$ii][3] = $row_RScbal["AMOUNT"];
//                         $TypeGrid1[$ii][5] = $row_RScbal["brand"];
//                         $TypeGrid1[$ii][12] = -1 * ($TypeGrid1[$ii][3] / 100 * $_GET["txt_percentage"]) / ( $row_RScbal["vatrate"] + 100) * 100;
//                         $TypeGrid1[$ii][13] = -1 * ($TypeGrid1[$ii][3] / 100 * $_GET["txt_percentage"]) / ( $row_RScbal["vatrate"] + 100) * 100;
//                         $TypeGrid1[$ii][14] = $row_RScbal["trn_type"];
//                         $TypeGrid1[$ii][15] = $row_RScbal["vatrate"];
//                         $totgrn = $totgrn + $row_RScbal["AMOUNT"];
//                         $tgrnince = $tgrnince + $TypeGrid1[$ii][12];
//                         $txttot_inc = $txttot_inc + $TypeGrid1[$ii][12];
//                         $ii = $ii + 1;
//                     }
//                 }
//             }



            }
        } else {
            if ((intval(date("m", strtotime($_GET["DTPicker1"]))) >= 1) and (date("Y", strtotime($_GET["DTPicker1"])) >= 2010)) {
                $result_rsbrand_mas = mysql_query($sql_rsbrand_mas, $dbinv);
                if ($row_rsbrand_mas = mysql_fetch_array($result_rsbrand_mas)) {
                    if ($row_rsbrand_mas["delinrate"] == 3.5) {
                        if ($row_rsbrand_mas["delinrate"] >= 0) {

                            $TypeGrid1[$ii][1] = $row_RScbal["REFNO"];
                            $TypeGrid1[$ii][2] = $row_RScbal["sdate1"];
                            $TypeGrid1[$ii][3] = $row_RScbal["AMOUNT"];
                            $TypeGrid1[$ii][5] = $row_RScbal["brand"];
                            $TypeGrid1[$ii][12] = -1 * ($TypeGrid1[$ii][3] / 100 * $_GET["txt_percentage"]) / ($row_RScbal["vatrate"] + 100) * 100;
                            $TypeGrid1[$ii][13] = -1 * ($TypeGrid1[$ii][3] / 100 * $_GET["txt_percentage"]) / ($row_RScbal["vatrate"] + 100) * 100;
                            $TypeGrid1[$ii][14] = $row_RScbal["trn_type"];
                            $totgrn = $totgrn + $row_RScbal["AMOUNT"];
                            $TypeGrid1[$ii][15] = $row_RScbal["vatrate"];
                            $tgrnince = $tgrnince + $TypeGrid1[$ii][12];
                            $txttot_inc = $txttot_inc + $TypeGrid1[$ii][12];
                            $ii = $ii + 1;
                        }
                    }
                }
            }
        }
    }


    $txt_grn = $totgrn;


    $txttotal = ($txt_tot - $txt_grn) / ($txt_vat + 100) * 100;


    $txtremark = "";
    $txtint = "";
    $txtnetin = "";
    $remark = "";

    $xx = trim(intval(date("m", strtotime($_GET["DTPicker1"]))));


    // ========================
    if ($_GET['monthwise'] == "nch") {
        $sql_rsincen = "select * from ins_payment where  I_month ='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "'  and I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and cusCode = '" . trim($_GET["txt_cuscode"]) . "'    and Type = '" . trim($_GET["cmbtype"]) . "' order by id desc ";
    } else {
        $sql_rsincen = "select * from ins_payment where  ((I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "') or (I_year='" . date("Y", strtotime($_GET["DTPicker2"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker2"]))) . "')) and cusCode = '" . trim($_GET["txt_cuscode"]) . "'    and Type = '" . trim($_GET["cmbtype"]) . "' order by id desc ";
    }
    //    two month 19.09.09
// echo $sql_rsincen;
    $result_rsincen = mysql_query($sql_rsincen, $dbinv);
    if ($row_rsincen = mysql_fetch_array($result_rsincen)) {

        if (is_null($row_rsincen["type"]) == false) {
            if (trim($row_rsincen["type"]) == trim($_GET["cmbtype"])) {

                if (is_null($row_rsincen["remarks"]) == false) {
                    $txtremark = $row_rsincen["remarks"];
                }

                if (is_null($row_rsincen["remarks"]) == false) {
                    $remark = $row_rsincen["remarks"];
                }
                if (is_null($row_rsincen["remarks"]) == false) {
                    $mremark = trim($row_rsincen["remarks"]);
                }
                if (is_null($row_rsincen["Interest"]) == false) {
                    $txtint = $row_rsincen["Interest"];
                }
                if (is_null($row_rsincen["Percentage"]) == false) {
                    $txt_percentage = $row_rsincen["Percentage"];
                }
                if (is_null($row_rsincen["amount"]) == false) {
                    $txtnetin = $row_rsincen["amount"];
                }
                if (is_null($row_rsincen["chno"]) == false) {
                    $txt_chno = $row_rsincen["chno"];
                }
                if (trim($row_rsincen["chno"]) == "X") {
                    $chq_ignore = 1;
                }
            } else {
                //rsincen.MoveNext
            }
        } else {
            // =======================21.08.20
            if ($_GET['monthwise'] == "nch") {
                $sql_rsincen1 = "select * from ins_payment where  I_month ='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "'  and I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and cusCode = '" . trim($_GET["txt_cuscode"]) . "'    and Type = '" . trim($_GET["cmbtype"]) . "' order by id desc ";
            } else {
                $sql_rsincen1 = "select * from ins_payment where  ((I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "') or (I_year='" . date("Y", strtotime($_GET["DTPicker2"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker2"]))) . "')) and cusCode = '" . trim($_GET["txt_cuscode"]) . "'    and Type = '" . trim($_GET["cmbtype"]) . "' order by id desc ";
            }

            $result_rsincen1 = mysql_query($sql_rsincen1, $dbinv);
            $txtremarknew = "";
            while ($row_rsincen1 = mysql_fetch_array($result_rsincen1)) {
                if (is_null($row_rsincen1["remarks"]) == false) {
                    $txtremarknew = $txtremarknew . '--' . $row_rsincen1["remarks"];
                }

            }


            // 21.08.20==============

            if (is_null($row_rsincen["remarks"]) == false) {
                $txtremark = $row_rsincen["remarks"];
            }
            $txtremark = $txtremarknew;
            if (is_null($row_rsincen["remarks"]) == false) {
                $remark = $row_rsincen["remarks"];
            }
            $remark = $txtremarknew;
            if (is_null($row_rsincen["remarks"]) == false) {
                $mremark = trim($row_rsincen["remarks"]);
            }
            if (is_null($row_rsincen["Interest"]) == false) {
                $txtint = $row_rsincen["Interest"];
            }
            if (is_null($row_rsincen["Percentage"]) == false) {
                $txt_percentage = $row_rsincen["Percentage"];
            }
            if (is_null($row_rsincen["amount"]) == false) {
                $txtnetin = $row_rsincen["amount"];
            }
            if (is_null($row_rsincen["chno"]) == false) {
                $txt_chno = $row_rsincen["chno"];
            }
            if (trim($row_rsincen["chno"]) == "X") {
                $chq_ignore = 1;
            }
        }
    }

    //Call cmd_cal_Click////////////////////////////////////////////////////////
    //Call Auto_cal///////////////////////////////////////

    $txttot1 = 0;
    $i = 1;
    $TypeGrid1_count = $ii;
    while ($i < $TypeGrid1_count) {
        if (($TypeGrid1[$i][14] == "INV") or ($TypeGrid1[$i][14] == "INV - TBR") or ($TypeGrid1[$i][14] == "INV - TBB")) {
            if ($TypeGrid1[$i][9] <= $TypeGrid1[$i][10]) {
                $txttot1 = $txttot1 + $TypeGrid1[$i][8];
            } else {
                $txttot1 = $txttot1;
            }
        }
        $i = $i + 1;
    }

    if (date("Y", strtotime($_GET["DTPicker1"])) > 2012) {
        //echo $txttot1.'@'.$txt_grn.'@'.$txt_vat;
        $sql_rsper = "Select * from intper_goodyear where sdate <= '" . $_GET["DTPicker1"] . "'  and traget < " . (($txttot1 - $txt_grn) / ($txt_vat + 100) * 100) . " and brand='" . $_GET["cmbtype"] . "' ORDER BY sdate DESC, traget DESC ";
        echo
            $result_rsper = mysql_query($sql_rsper, $dbinv);
        if ($row_rsper = mysql_fetch_array($result_rsper)) {

            $txt_percentage = $row_rsper["per"];

            $ii = 1;
            while ($ii < $TypeGrid1_count) {
                if (($TypeGrid1[$ii][14] == "INV") or ($TypeGrid1[$ii][14] == "INV - TBR") or ($TypeGrid1[$ii][14] == "INV - TBB")) {
                    if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                        $TypeGrid1[$ii][13] = ($TypeGrid1[$ii][8] / 100 * $txt_percentage) / ($txt_vat + 100) * 100;
                    } else {
                        $TypeGrid1[$ii][13] = 0;
                    }
                } else {
                    $TypeGrid1[$ii][13] = -1 * ($TypeGrid1[$ii][3] / 100 * $txt_percentage) / ($txt_vat + 100) * 100;
                }
                $ii = $ii + 1;
            }
        }
    } else {
        if ((intval(date("m", strtotime($_GET["DTPicker1"]))) >= 3) and (date("Y", strtotime($_GET["DTPicker1"])) >= 2017)) {
            $sql_rsper = "Select * from intper_goodyear where incen_year = 2017 and brand='" . $_GET["cmbtype"] . "' and traget < '" . ($txttot1 - $txt_grn) / ($txt_vat + 100) * 100 . "' order by traget desc ";
        } else {
            $sql_rsper = "Select * from intper_goodyear where incen_year = 20101 and brand='" . $_GET["cmbtype"] . "' and traget < '" . ($txttot1 - $txt_grn) / ($txt_vat + 100) * 100 . "' order by traget desc ";
        }

        $result_rsper = mysql_query($sql_rsper, $dbinv);
        if ($row_rsper = mysql_fetch_array($result_rsper)) {

            $txt_percentage = $row_rsper["per"];
            $ii = 1;
            while ($ii < $TypeGrid1_count) {
                if (($TypeGrid1[$ii][14] == "INV") or ($TypeGrid1[$ii][14] == "INV - TBR") or ($TypeGrid1[$ii][14] == "INV - TBB")) {
                    if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                        $TypeGrid1[$ii][13] = ($TypeGrid1[$ii][8] / 100 * $txt_percentage) / ($txt_vat + 100) * 100;
                    } else {
                        $TypeGrid1[$ii][13] = 0;
                    }
                } else {
                    $TypeGrid1[$ii][13] = -1 * ($TypeGrid1[$ii][3] / 100 * $txt_percentage) / ($txt_vat + 100) * 100;
                }
                $ii = $ii + 1;
            }
        }
    }
    ////////////////////////////////////////////////
    $txttot1 = 0;
    $tinc = 0;
    $ii = 1;
    while ($ii < $TypeGrid1_count) {
        if (($TypeGrid1[$ii][14] == "INV") or ($TypeGrid1[$ii][14] == "INV - TBR") or ($TypeGrid1[$ii][14] == "INV - TBB")) {
            if ($TypeGrid1[$ii][9] <= $TypeGrid1[$ii][10]) {
                $txttot1 = $txttot1 + $TypeGrid1[$ii][8];
                $txttot1_w = $txttot1_w + ($TypeGrid1[$ii][8] / (1 + ($TypeGrid1[$ii][15] / 100)));
            }
        } else {

            $txttot1_w = $txttot1_w - ($TypeGrid1[$ii][3] / (1 + ($TypeGrid1[$ii][15] / 100)));
        }
        if ($TypeGrid1[$ii][13] != "") {
            $tinc = $tinc + $TypeGrid1[$ii][13];
        }
        $ii = $ii + 1;
    }
    $txt_tot = $txttot1;
    $txttotal = $txttot1_w; //($txt_tot - $txt_grn) / ($txt_vat + 100) * 100;
    $txttot_inc = $tinc;
    $txtnetin = $txttot_inc - $txtint;
    ////////////////////////////////////////////////////////
    //Call GRIDSET

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";




    $ResponseXML .= "<incen_table><![CDATA[<table width=\"735\" border=\"0\" class=\"form-matrix-table\">
    <tr>
    <td width=\"50\"  ><font color=\"#FFFFFF\">ID</font></td>
    <td width=\"200\"  ><font color=\"#FFFFFF\">Inv No</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Inv Date</font></td>
    <td width=\"200\"  ><font color=\"#FFFFFF\">Amount</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Paid</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\"></font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Rec.No</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Rec.Date</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Rec.Amount</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Days</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Apply Days</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Locked</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">Incentive</font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\"></font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\"></font></td>
    <td width=\"200\" ><font color=\"#FFFFFF\">VAT</font></td>
    </tr>";

    $i = 1;
    while ($i < $TypeGrid1_count) {

        $grid0 = "TypeGrid_" . $i . "_00";
        $grid1 = "TypeGrid_" . $i . "_01";
        $grid2 = "TypeGrid_" . $i . "_02";
        $grid3 = "TypeGrid_" . $i . "_03";
        $grid4 = "TypeGrid_" . $i . "_04";
        $grid5 = "TypeGrid_" . $i . "_05";
        $grid6 = "TypeGrid_" . $i . "_06";
        $grid7 = "TypeGrid_" . $i . "_07";
        $grid8 = "TypeGrid_" . $i . "_08";
        $grid9 = "TypeGrid_" . $i . "_09";
        $grid10 = "TypeGrid_" . $i . "_10";
        $grid11 = "TypeGrid_" . $i . "_11";
        $grid12 = "TypeGrid_" . $i . "_12";
        $grid13 = "TypeGrid_" . $i . "_13";
        $grid14 = "TypeGrid_" . $i . "_14";
        $grid15 = "TypeGrid_" . $i . "_15";
        $grid15 = "TypeGrid_" . $i . "_16";

        $color = "";
        $sql_TTR = "Select * from s_sttr where ID = '" . $TypeGrid1[$i][0] . "' and ST_FLAG='CHK' ";


        $result_TTR = mysql_query($sql_TTR, $dbinv);
        if ($row_TTR = mysql_fetch_array($result_TTR)) {

            $color = "orange";
            $sql_sch = "Select * from s_cheq where CR_CHNO = '" . $row_TTR['ST_CHNO'] . "'   and CR_C_CODE = '" . $_GET['txt_cuscode'] . "'   and CR_CHEVAL = '" . str_replace(',', '', $TypeGrid1[$i][8]) . "' and CR_BANK = '" . $row_TTR['st_chbank'] . "' and CR_FLAG='0'      ";

            $result_sch = mysql_query($sql_sch, $dbinv);
            if ($row_sch = mysql_fetch_array($result_sch)) {
                $color = 'red';
            } else {
                $sql_invch = "Select * from s_invcheq where cus_code = '" . $_GET['txt_cuscode'] . "'  and cheque_no = '" . $row_TTR['ST_CHNO'] . "' and refno='" . $row_TTR['ST_REFNO'] . "' and   che_date='" . $row_TTR['st_chdate'] . "' and bank = '" . $row_TTR['st_chbank'] . "' order by che_date desc    ";

                $result_invch = mysql_query($sql_invch, $dbinv);
                if ($row_invch = mysql_fetch_array($result_invch)) {
                    if (($row_invch['realizedate'] != "0000-00-00") and ($row_invch['realizedate'] != "")) {
                        $color = 'green';
                    }

                }
            }

        } else {
            $color = "green";
        }

        $ResponseXML .= " <tr bgcolor='" . $color . "'>
        <td width=\"50\"  ><div id=\"" . $grid0 . "\">" . $TypeGrid1[$i][0] . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid1 . "\">" . $TypeGrid1[$i][1] . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid2 . "\">" . $TypeGrid1[$i][2] . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid3 . "\">" . $TypeGrid1[$i][3] . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid4 . "\">" . $TypeGrid1[$i][4] . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid5 . "\">" . $TypeGrid1[$i][5] . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid6 . "\">" . $TypeGrid1[$i][6] . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid7 . "\">" . $TypeGrid1[$i][7] . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid8 . "\">" . number_format($TypeGrid1[$i][8], 2, ".", ",") . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid9 . "\">" . $TypeGrid1[$i][9] . "</div></td>
        <td width=\"121\"  ><input type=\"text\"  class=\"text_purchase3\" name=\"" . $grid10 . "\" id=\"" . $grid10 . "\" size=\"15\"  value=\"" . $TypeGrid1[$i][10] . "\"  onblur=\"calc1_table('" . $i . "');\"   /></td>
        <td width=\"121\"  ><div id=\"" . $grid11 . "\">" . $TypeGrid1[$i][11] . "</div></td>
        <td width=\"121\" align=right ><div id=\"" . $grid12 . "\">" . number_format($TypeGrid1[$i][12], 2, ".", ",") . "</div></td>
        <td width=\"121\" align=right ><div id=\"" . $grid13 . "\">" . number_format($TypeGrid1[$i][13], 2, ".", ",") . "</div></td>
        <td width=\"121\"  ><div id=\"" . $grid14 . "\">" . $TypeGrid1[$i][14] . "</td>
        <td width=\"121\"  ><div id=\"" . $grid15 . "\">" . $TypeGrid1[$i][15] . "</td>
        <td width=\"121\"  ><div id=\"" . $grid16 . "\">" . $TypeGrid1[$i][16] . "</td></tr>";

        $i = $i + 1;
    }







    $ResponseXML .= " </table>]]></incen_table>";

    $txtremark = str_replace("&nbsp;", " ", $txtremark);

    $ResponseXML .= "<txtremark><![CDATA[" . $txtremark . "]]></txtremark>";
    $ResponseXML .= "<txtint><![CDATA[" . $txtint . "]]></txtint>";
    $ResponseXML .= "<txt_percentage><![CDATA[" . $txt_percentage . "]]></txt_percentage>";
    $ResponseXML .= "<txtnetin><![CDATA[" . number_format($txtnetin, 2, ".", ",") . "]]></txtnetin>";
    $ResponseXML .= "<txt_chno><![CDATA[" . $txt_chno . "]]></txt_chno>";
    $ResponseXML .= "<chq_ignore><![CDATA[" . $chq_ignore . "]]></chq_ignore>";
    $ResponseXML .= "<txttot_inc><![CDATA[" . number_format($txttot_inc, 2, ".", ",") . "]]></txttot_inc>";
    $ResponseXML .= "<txt_tot><![CDATA[" . number_format($txt_tot, 2, ".", ",") . "]]></txt_tot>";
    $ResponseXML .= "<txt_grn><![CDATA[" . number_format($txt_grn, 2, ".", ",") . "]]></txt_grn>";
    $ResponseXML .= "<txttotal><![CDATA[" . number_format($txttotal, 2, ".", ",") . "]]></txttotal>";
    $ResponseXML .= "<txt_out><![CDATA[" . number_format($txt_out, 2, ".", ",") . "]]></txt_out>";
    $ResponseXML .= "<TypeGrid1_count><![CDATA[" . $TypeGrid1_count . "]]></TypeGrid1_count>";
    $ResponseXML .= "<txt_vat><![CDATA[" . $txt_vat . "]]></txt_vat>";

    $sql = "select * from dealer_incen_rmk where d_code = '" . $_GET["txt_cuscode"] . "'";
    $result = mysql_query($sql, $dbinv);
    $row = mysql_fetch_array($result);

    $ResponseXML .= "<incenRemark><![CDATA[" . $row["rmk"] . "]]></incenRemark>";
    $ResponseXML .= "</salesdetails>";

    echo $ResponseXML;

    mysql_close($dbinv);
}

if ($_GET["Command"] == "lock_data") {
    include('connection.php');

    $ii = 1;
    while ($ii < $_GET["mcount"]) {

        $cell_name_00 = "TypeGrid_" . $ii . "_00";

        $sql = "uPDATE s_sttr set deliin_lock='1' WHERE id=" . $_GET[$cell_name_00];
        echo $sql;
        $result = mysql_query($sql, $dbinv);

        $ii = $ii + 1;
    }

    $sql_rsincen = "select * from ins_payment where  I_month ='" . intval(date("m", strtotime($_GET["dtMonth"]))) . "'  and I_year='" . date("Y", strtotime($_GET["dtMonth"])) . "' and cusCode = '" . trim($_GET["txt_cuscode"]) . "'    and Type = '" . trim($_GET["cmbtype"]) . "' order by id desc ";

    $result_rsincen = mysql_query($sql_rsincen, $dbinv);
    if ($row_rsincen = mysql_fetch_array($result_rsincen)) {
        if ($row_rsincen['chno'] == "0") {
            $hide = 0;
        } else {
            $hide = 1;
        }

        $sql_inv = "update ins_payment set hide='" . $hide . "'  where  id='" . $row_rsincen['id'] . "'";
        $result_inv = mysql_query($sql_inv, $dbinv);
    }

    echo "Locked";
}

if ($_POST["Command"] == "print_advance") {
    include('connection.php');

    $sql_inv = "delete from dealer_inc_summery";
    $result_inv = mysql_query($sql_inv, $dbinv);


    $mRow = 1;
    while ($mRow < $_POST["mcount"]) {

        $cell_name_01 = "TypeGrid_" . $mRow . "_01";
        $cell_name_02 = "TypeGrid_" . $mRow . "_02";
        $cell_name_03 = "TypeGrid_" . $mRow . "_03";
        $cell_name_05 = "TypeGrid_" . $mRow . "_05";
        $cell_name_07 = "TypeGrid_" . $mRow . "_07";
        $cell_name_08 = "TypeGrid_" . $mRow . "_08";
        $cell_name_09 = "TypeGrid_" . $mRow . "_09";
        $cell_name_10 = "TypeGrid_" . $mRow . "_10";
        $cell_name_13 = "TypeGrid_" . $mRow . "_13";
        $cell_name_14 = "TypeGrid_" . $mRow . "_14";

        $val_col_03 = str_replace(",", "", $_POST[$cell_name_03]);
        $val_col_08 = str_replace(",", "", $_POST[$cell_name_08]);
        $val_col_13 = str_replace(",", "", $_POST[$cell_name_13]);

        if ($_POST[$cell_name_01] != "") {
            $Inv_no = $_POST[$cell_name_01];
        }
        if ($_POST[$cell_name_02] != "") {
            $SDATE = $_POST[$cell_name_02];
        }
        if ($_POST[$cell_name_03] != "") {
            $amount = ($val_col_03 / (1 + ($_POST["txt_vat"] / 100)));
        }
        if ($_POST[$cell_name_05] != "") {
            $brand = $_POST[$cell_name_05];
        }
        if ($_POST[$cell_name_08] != "") {
            $paid = ($val_col_08 / (1 + ($_POST["txt_vat"] / 100)));
        }
        if ($_POST[$cell_name_09] != "") {
            $days = $_POST[$cell_name_09];
        }
        if ($_POST[$cell_name_10] != "") {
            $apdays = $_POST[$cell_name_10];
        }
        $incentive = $val_col_13;
        if (($val_col_08 != "") and ($val_col_08 != "0")) {
            $per = ($val_col_13 / ($val_col_08 / (1 + ($_POST["txt_vat"] / 100)))) * 100;
        }
        if ($_POST[$cell_name_07] != "") {
            $rec_date = $_POST[$cell_name_07];
        }
        $Type = $_POST[$cell_name_14];

        $sql = "insert into dealer_inc_summery(inv_no, sdate, amount, brand, paid, days, apdays, incentive, per, rec_date, type) values ('" . $Inv_no . "', '" . $SDATE . "', " . $amount . ", '" . $brand . "', " . $paid . ", " . $days . ", " . $apdays . ", " . $incentive . ", " . $per . ", '" . $rec_date . "', '" . $Type . "')";
        echo $sql;
        $result = mysql_query($sql, $dbinv);

        $mRow = $mRow + 1;
    }
}

if ($_GET["Command"] == "updateRemark") {
    include('connection.php');
    $sql = "delete from dealer_incen_rmk where d_code = '" . $_GET["txt_cuscode"] . "'";
    $result = mysql_query($sql, $dbinv);
    $sql = "insert into dealer_incen_rmk (d_code,rmk) values ('" . $_GET["txt_cuscode"] . "','" . $_GET["txtRemarkUpdate"] . "')";
    $result = mysql_query($sql, $dbinv);
    echo "Remark updated!";
}


if ($_GET["Command"] == "setTotal") {
    $r = 1;
    $chtotal = 0;
    $total = 0;

    while ($GLOBALS[$gridchk[$r][1]] != "") {
        $GLOBALS[$gridchk[$r][7]] = "";
        $chtotal = $chtotal + $GLOBALS[$gridchk[$r][6]];
        $r = $r + 1;
    }

    while ($GLOBALS[$Gridinv[$r][1]] != "") {
        $GLOBALS[$Gridinv[$r][7]] = "";
        $total = $total + $GLOBALS[$Gridinv[$r][6]];
        $r = $r + 1;
    }
    //$re = Val(Format(txtcrnamount.Text, General)) - (total + chtotal + Val(Format(txtcash, General)))
}

function dateDifference($date_1, $date_2, $differenceFormat = '%a')
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);
}

?>