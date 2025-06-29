.
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


if ($_GET["Command"] == "view") {
    //if ($_SESSION['company']=="THT"){
    //  calrepo2014();
    //} else    if ($_SESSION['company']=="BEN"){
    calrepo();
    //} 
}

function calrepo()
{

    require_once("config.inc.php");
    require_once("DBConnector.php");
    $db = new DBConnector();

    $rs = "delete from monsales where user_id='" . $_SESSION["CURRENT_USER"] . "'";
    $result = $db->RunQuery($rs);

    $year = substr($_GET["DTPicker1"], 0, 4);
    $month = substr($_GET["DTPicker1"], 5, 2);

    $sql_tmp = "select * from monsales";

    $insert = "";
    $i = 0;
    if ($_GET['cmbtype'] == "TYRE") {
        if ($_GET['tardealer'] == "ch") {
            if ($_GET['monthwise'] == "nch") {
                $sql_rsVENDOR = "select C_CODE,SAL_EX,Brand, CUS_NAME, incdays, sum(GRAND_TOT) as tot,sum(GRAND_TOT/(1+(gst/100))) as totsal1  from  view_salma_vendor_brand where year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "' and CANCELL = '0' and (Brand='CHENG SHING' OR Brand='ETERNOPRESA')   and delinrate = '2.5'  and insentarget>0    ";

            } else {
                $sql_rsVENDOR = "select C_CODE,SAL_EX,Brand, CUS_NAME, incdays, sum(GRAND_TOT) as tot,sum(GRAND_TOT/(1+(gst/100))) as totsal1  from  view_salma_vendor_brand where ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "')) and CANCELL = '0' and (Brand='CHENG SHING' OR Brand='ETERNOPRESA')  and delinrate = '2.5'  and insentarget>0     ";

            }

        } else {
            if ($_GET['monthwise'] == "nch") {
                $sql_rsVENDOR = "select C_CODE, SAL_EX,Brand,CUS_NAME, incdays, sum(GRAND_TOT) as tot,sum(GRAND_TOT/(1+(gst/100))) as totsal1  from  view_salma_vendor_brand where year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "' and CANCELL = '0' and (Brand='CHENG SHING' OR Brand='ETERNOPRESA')   and delinrate = '2.5'   and insentarget=0    ";
                // 
            } else {
                $sql_rsVENDOR = "select C_CODE, SAL_EX,Brand,CUS_NAME, incdays, sum(GRAND_TOT) as tot,sum(GRAND_TOT/(1+(gst/100))) as totsal1  from  view_salma_vendor_brand where ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "')) and CANCELL = '0' and (Brand='CHENG SHING' OR Brand='ETERNOPRESA')   and delinrate = '2.5'   and insentarget=0   ";
            }

        }
    } else {
        if ($_GET['monthwise'] == "nch") {
            $sql_rsVENDOR .= "select C_CODE, SAL_EX,CUS_NAME, incdays, sum(GRAND_TOT) as tot,sum(GRAND_TOT/(1+(gst/100))) as totsal1  from  view_salma_vendor_brand where year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "' and CANCELL = '0'   ";
        } else {
            $sql_rsVENDOR .= "select C_CODE, SAL_EX,CUS_NAME, incdays, sum(GRAND_TOT) as tot,sum(GRAND_TOT/(1+(gst/100))) as totsal1  from  view_salma_vendor_brand where ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "')) and CANCELL = '0'  ";
        }

        // $sql_rsVENDOR .=" and brand = '" . $_GET['cmbtype'] . "' "; 
        // ================prawee 25.06.08
        $brand1 = $_GET["cmbtype"];
        $sql_333 = "Select * from intper_goodyear where sdate <= '" . $_GET["DTPicker1"] . "'  and brand='" . $_GET["cmbtype"] . "' ORDER BY sdate DESC, traget DESC ";
        $result_333 = $db->RunQuery($sql_333);
        if ($row_333 = mysql_fetch_array($result_333)) {
            $brand1 = $row_333['brand'];
            $brand2 = $row_333['brand1'];
            $brand3 = $row_333['brand2'];
            $brand4 = $row_333['brand3'];
        }

        if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
            $sql_rsVENDOR .= " and brand = '" . $brand1 . "' ";
        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
            $sql_rsVENDOR .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' )";
        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
            $sql_rsVENDOR .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' )";
        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
            $sql_rsVENDOR .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' or brand = '" . $brand4 . "' )";
        }
        // ====
    }

    $sql_rsVENDOR .= " and (C_CODE='A002' or C_CODE='M167' )";

    $sql_rsVENDOR .= " group by C_CODE, incdays order by C_CODE";
    // echo $sql_rsVENDOR;
    $rep = trim($_GET["cmbrep"]);

    $result_rsVENDOR = $db->RunQuery($sql_rsVENDOR);
    while ($row_rsVENDOR = mysql_fetch_array($result_rsVENDOR)) {
        if ($_GET['monthwise'] == "nch") {
            $sql_inschk = "select * from ins_payment where cusCode='" . $row_rsVENDOR["C_CODE"] . "' and I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "' and Type = '" . $_GET['cmbtype'] . "'";
        } else {
            $sql_inschk = "select * from ins_payment where cusCode='" . $row_rsVENDOR["C_CODE"] . "' and ((I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "') or (I_year='" . date("Y", strtotime($_GET["DTPicker2"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker2"]))) . "')) and Type = '" . $_GET['cmbtype'] . "'";
        }

        $result_inschk = $db->RunQuery($sql_inschk);
        if ($row_inschk = mysql_fetch_array($result_inschk)) {

        } else {

            if ($_GET['cmbtype'] == "TYRE") {

                $sql_rschper = "Select * from intper where sdate <= '" . $_GET["DTPicker1"] . "' and traget < " . $row_rsVENDOR["totsal1"] . " order by sdate desc,traget desc ";
            } elseif (($_GET["cmbtype"] == "GOODYEAR") or ($_GET["cmbtype"] == "MAXXIS MC TYRE")) {

                $sql_rschper = "Select * from intper_goodyear  where sdate <= '" . $_GET["DTPicker1"] . "' and brand='" . $_GET["cmbtype"] . "' and traget < " . $row_rsVENDOR["totsal1"] . " order by sdate desc,traget desc ";
            } else {

                //prawe new

                $cal_per = 0;
                $tot_grn1 = 0;
                $tot_sale1 = 0;

                $refDate = $_GET["DTPicker1"];
                $Mon = date("m", strtotime($refDate));
                $Yer = date("Y", strtotime($refDate));


                $sql_rrRScbal .= "select sum(AMOUNT) as AMOUNT from c_bal where  month(sdate1)='" . date("m", strtotime($_GET["DTPicker1"])) . "' and  year(sdate1)='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and CANCELL='0'and CUSCODE='" . $row_rsVENDOR["C_CODE"] . "' AND trn_type != 'ARN' and trn_type != 'REC' and trn_type != 'DGRN' and flag1 != '1' ";
                //                echo $sql_rrRScbal;
                if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                    $sql_rrRScbal .= " and brand = '" . $brand1 . "' ";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                    $sql_rrRScbal .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' )";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                    $sql_rrRScbal .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' )";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                    $sql_rrRScbal .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' or brand = '" . $brand4 . "' )";
                }

                $sql_rrRScbal .= " order by sdate1";
                $result_re_RScbal = $db->RunQuery($sql_rrRScbal);
                $re_RScbal = mysql_fetch_array($result_re_RScbal);

                $tot_grn1 = $re_RScbal["AMOUNT"];

                $sql_rrrssslma .= "select sum(GRAND_TOT) as grand_tot from s_salma where  month(sdate1)='" . date("m", strtotime($_GET["DTPicker1"])) . "' and  year(sdate1)='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and c_CODE = '" . $row_rsVENDOR["C_CODE"] . "' and cancell='0'  ";

                if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                    $sql_rrrssslma .= " and Brand = '" . $brand1 . "' ";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                    $sql_rrrssslma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' )";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                    $sql_rrrssslma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' )";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                    $sql_rrrssslma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' or Brand = '" . $brand4 . "' )";
                }

                $result_re_rrsalma = $db->RunQuery($sql_rrrssslma);
                $re_rrsalma = mysql_fetch_array($result_re_rrsalma);

                $tot_sale1 = $re_rrsalma["grand_tot"];

                //  pppppppppppppppppppp 19.08.23
                if ($_GET["cmbtype"] == 'ZEETEX') {
                    $mpaytot = 0;
                    $sql_rssalma1 = "select * from view_salma_sttr where C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . " and CANCELL = '0' and Brand = '" . $_GET['cmbtype'] . "'and deliin_amo <= '0' and deliin_lock = '0' order by st_days desc";

                    $result_rssalma1 = $db->RunQuery($sql_rssalma1);
                    while ($row_rssalma1 = mysql_fetch_array($result_rssalma1)) {
                        $days1 = 0;
                        $diff1 = 0;
                        if ((!is_null($row_rssalma1['Deli_date'])) and $row_rssalma1['Deli_date'] != "0000-00-00") {
                            $mdate = $row_rssalma1["Deli_date"];
                        } else {
                            $mdate = $row_rssalma1["sdate1"];
                        }
                        if ((is_null($row_rssalma1["st_chdate"]) == false) and (($row_rssalma1["st_chdate"]) != "0000-00-00")) {

                            $diff1 = abs(strtotime($row_rssalma1["st_chdate"]) - strtotime($mdate));
                            $days1 = dateDifference($row_rssalma1["st_chdate"], $mdate, $differenceFormat = '%a');
                            $days1 = floor($diff1 / (60 * 60 * 24));
                        } else {

                            $diff1 = abs(strtotime($row_rssalma1["ST_DATE"]) - strtotime($mdate));
                            $days1 = dateDifference($row_rssalma1["ST_DATE"], $mdate, $differenceFormat = '%a');
                            $days1 = floor($diff / (60 * 60 * 24));
                        }

                        //                                            


                        if ($row_rssalma1["cre_pe"] != "") {
                            $mdcou1 = $row_rssalma1["cre_pe"];
                        }

                        if ($days1 <= $mdcou1) {

                            $mpaytot = $mpaytot + ($row_rssalma1['ST_PAID'] / (1 + ($row_rssalma1['GST'] / 100)));
                        }
                    }
                    $mpaytot = $mpaytot * 108 / 100;
                }
                //                pppppppppppppppppppp  19.08.23

                $cal_per = ($mpaytot - $tot_grn1) / ($tot_sale1 - $tot_grn1);

                $refDate = $_GET["DTPicker1"];
                $Mon = date("m", strtotime($refDate));
                $Yer = date("Y", strtotime($refDate));

                $sql_inv .= "Select sum(Qty) as totQty from viewinv where  Cus_CODE = '" . trim($row_rsVENDOR["C_CODE"]) . "'  and month(sdate1) = '" . $Mon . "' and year(sdate1) = '" . $Yer . "' and cancel_m = '0' and stk_no <> 'A0350' and stk_no <> 'A0351' and stk_no <> 'A0352' and stk_no <> 'A0353' and stk_no <> 'A0354' and stk_no <> 'L0531' AND stk_no <> 'T3520' AND stk_no <> 'T3522' and stk_no <> 'A0356' and price <> 0 ";

                if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                    $sql_inv .= " and brand = '" . $brand1 . "' ";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                    $sql_inv .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' )";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                    $sql_inv .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' )";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                    $sql_inv .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' or brand = '" . $brand4 . "' )";
                }


                $sql_grn .= " Select sum(Qty) as totQty from viewcrntrn where  C_CODE = '" . trim($row_rsVENDOR["C_CODE"]) . "'  and month(sdate1) = '" . $Mon . "' and year(sdate1) = '" . $Yer . "' and cancell = '0' and stk_no <> 'A0350' and stk_no <> 'A0351' and stk_no <> 'A0352' and stk_no <> 'A0353' and stk_no <> 'A0354' and stk_no <> 'L0531' AND stk_no <> 'T3520' AND stk_no <> 'T3522' and stk_no <> 'A0356' ";

                if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                    $sql_grn .= " and brand = '" . $brand1 . "' ";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                    $sql_grn .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' )";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                    $sql_grn .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' )";
                } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                    $sql_grn .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' or brand = '" . $brand4 . "' )";
                }



                $invqty = 0;
                $rtnqty = 0;
                $netqty = 0;
                $res_inv = $db->RunQuery($sql_inv);
                if ($row_inv = mysql_fetch_array($res_inv)) {
                    if (!is_null($row_inv['totQty'])) {
                        $invqty = $row_inv['totQty'];
                    }
                }
                $res_grn = $db->RunQuery($sql_grn);
                if ($row_grn = mysql_fetch_array($res_grn)) {
                    if (!is_null($row_grn['totQty'])) {
                        $rtnqty = $row_grn['totQty'];
                    }
                }

                $netqty = $invqty - $rtnqty;
                if ($_GET["cmbtype"] == 'ZEETEX') {
                    $netqty = number_format($netqty * $cal_per, 0, ".", ",");
                }
                $month = date("Y-m-t", strtotime($refDate));




                $sql_rschper = "Select * from intper_1 where (sdate)<='" . trim($month) . "' and m_type = '" . trim($_GET["cmbtype"]) . "' and qty_tgt <= '" . $netqty . "' order by sdate desc,qty_tgt desc ";
                //                echo $netqty.'/';
            }



            $result_rschper = $db->RunQuery($sql_rschper);
            if ($row_rschper = mysql_fetch_array($result_rschper)) {
                if ($_GET["cmbrep"] == "All") {
                    if ($_GET['cmbtype'] == "TYRE") {
                        if ($_GET['monthwise'] == "nch") {
                            $sql_rssalma = "select * from view_salma_sttr where C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . " and CANCELL = '0' and (Brand='CHENG SHING' OR Brand='ETERNOPRESA') and deliin_amo <= '0' and deliin_lock = '0' order by st_days desc";
                        } else {
                            $sql_rssalma = "select * from view_salma_sttr where C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "') ) and CANCELL = '0' and (Brand='CHENG SHING' OR Brand='ETERNOPRESA') and deliin_amo <= '0' and deliin_lock = '0' order by st_days desc";
                        }

                    } else {
                        if ($_GET['monthwise'] == "nch") {
                            $sql_rssalma .= "select * from view_salma_sttr where C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . " and CANCELL = '0' and deliin_amo <= '0' and deliin_lock = '0' ";
                        } else {
                            $sql_rssalma .= "select * from view_salma_sttr where C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "') ) and CANCELL = '0' and deliin_amo <= '0' and deliin_lock = '0' ";
                        }

                        // ================prawee 25.06.08


                        if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                            $sql_rssalma .= " and Brand = '" . $brand1 . "' ";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                            $sql_rssalma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' )";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                            $sql_rssalma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' )";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                            $sql_rssalma .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' or Brand = '" . $brand4 . "' )";
                        }

                        $sql_rssalma .= "  order by st_days desc";
                        // ====
                    }


                    if ($_GET['cmbtype'] == "TYRE") {
                        if ($_GET['monthwise'] == "nch") {
                            $sql_rs = "select    sum(GRAND_TOT) as totsal, sum(GRAND_TOT-TOTPAY) as  out1 ,Brand from s_salma where Accname != 'NON STOCK' and month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0' and Brand = 'CHENG SHING'";
                        } else {
                            $sql_rs = "select    sum(GRAND_TOT) as totsal, sum(GRAND_TOT-TOTPAY) as  out1 ,Brand from s_salma where Accname != 'NON STOCK' and ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "') )  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0' and Brand = 'CHENG SHING'";
                        }

                    } else {
                        if ($_GET['monthwise'] == "nch") {
                            $sql_rs .= "select    sum(GRAND_TOT) as totsal, sum(GRAND_TOT-TOTPAY) as  out1 ,Brand from s_salma where Accname != 'NON STOCK' and month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0'  ";
                        } else {
                            $sql_rs .= "select    sum(GRAND_TOT) as totsal, sum(GRAND_TOT-TOTPAY) as  out1 ,Brand from s_salma where Accname != 'NON STOCK' and ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "') )  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0'  ";
                        }

                        if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                            $sql_rs .= " and Brand = '" . $brand1 . "' ";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                            $sql_rs .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' )";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                            $sql_rs .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' )";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                            $sql_rs .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' or Brand = '" . $brand4 . "' )";
                        }


                    }
                    if ($_GET['cmbtype'] == "TYRE") {
                        if ($_GET['monthwise'] == "nch") {
                            $sql_rs_sal = "Select sum(GRAND_TOT/(1+(gst/100))) as totsal1,sum(GRAND_TOT) as totsal,sum(GRAND_TOT-TOTPAY) as out1 ,Brand from view_s_salma_brand_mas where month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0' and delinrate = '2.5' and (Brand='CHENG SHING' OR Brand='ETERNOPRESA') ";
                        } else {
                            $sql_rs_sal = "Select sum(GRAND_TOT/(1+(gst/100))) as totsal1,sum(GRAND_TOT) as totsal,sum(GRAND_TOT-TOTPAY) as out1 ,Brand from view_s_salma_brand_mas where ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "') )  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0' and delinrate = '2.5' and (Brand='CHENG SHING' OR Brand='ETERNOPRESA') ";
                        }

                    } else {
                        if ($_GET['monthwise'] == "nch") {
                            $sql_rs_sal .= "Select sum(GRAND_TOT/(1+(gst/100))) as totsal1,sum(GRAND_TOT) as totsal,sum(GRAND_TOT-TOTPAY) as out1,Brand from view_s_salma_brand_mas where month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0' ";
                        } else {
                            $sql_rs_sal .= "Select sum(GRAND_TOT/(1+(gst/100))) as totsal1,sum(GRAND_TOT) as totsal,sum(GRAND_TOT-TOTPAY) as out1,Brand from view_s_salma_brand_mas where ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "') )  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0'  ";
                        }

                        if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                            $sql_rs_sal .= " and Brand = '" . $brand1 . "' ";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                            $sql_rs_sal .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' )";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                            $sql_rs_sal .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' )";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                            $sql_rs_sal .= " and (Brand = '" . $brand1 . "'  or Brand = '" . $brand2 . "' or Brand = '" . $brand3 . "' or Brand = '" . $brand4 . "' )";
                        }
                    }
                    $salret = 0;
                    if ($_GET['cmbtype'] == "TYRE") {

                        if ($_GET['monthwise'] == "nch") {
                            $sql_rs1 = "select * from c_bal where month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  and CUSCODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0' and (brand='CHENG SHING' OR brand='ETERNOPRESA') AND trn_type != 'ARN' and trn_type != 'REC' and trn_type != 'DGRN' and flag1 != '1' ";
                        } else {
                            $sql_rs1 = "select * from c_bal where ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "') ) and CUSCODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0' and (brand='CHENG SHING' OR brand='ETERNOPRESA') AND trn_type != 'ARN' and trn_type != 'REC' and trn_type != 'DGRN' and flag1 != '1' ";
                        }

                    } else {
                        if ($_GET['monthwise'] == "nch") {
                            $sql_rs1 .= "select * from c_bal where month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  and CUSCODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0'  AND trn_type != 'ARN' and trn_type != 'REC' and trn_type != 'DGRN' and flag1 != '1' ";
                        } else {
                            $sql_rs1 .= "select * from c_bal where ((year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker1"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker1"])) . "') or (year(sdate1) = '" . date("Y", strtotime($_GET["DTPicker2"])) . "' and month(sdate1) = '" . date("m", strtotime($_GET["DTPicker2"])) . "') )  and CUSCODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0'  AND trn_type != 'ARN' and trn_type != 'REC' and trn_type != 'DGRN' and flag1 != '1' ";
                        }

                        if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                            $sql_rs1 .= " and brand = '" . $brand1 . "' ";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                            $sql_rs1 .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' )";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                            $sql_rs1 .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' )";
                        } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                            $sql_rs1 .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' or brand = '" . $brand4 . "' )";
                        }

                    }


                    $result_rs1 = $db->RunQuery($sql_rs1);
                    while ($row_rs1 = mysql_fetch_array($result_rs1)) {

                        $sql_rsbrn_mas = "select * from brand_mas where barnd_name = '" . trim($row_rs1["brand"]) . "'";
                        $result_rsbrn_mas = $db->RunQuery($sql_rsbrn_mas);
                        if ($row_rsbrn_mas = mysql_fetch_array($result_rsbrn_mas)) {
                            if ($_GET['cmbtype'] == "TYRE") {
                                if ($row_rsbrn_mas["delinrate"] == 2.5) {
                                    $salret = $salret + ($row_rs1["AMOUNT"] / (1 + ($row_rs1['vatrate'] / 100)));
                                }
                            } else {
                                // if ($row_rsbrn_mas["barnd_name"] == $_GET['cmbtype']) {
                                $salret = $salret + ($row_rs1["AMOUNT"] / (1 + ($row_rs1['vatrate'] / 100)));
                                // }
                            }
                        }
                    }

                    $result_rs = $db->RunQuery($sql_rs_sal);
                    $row_rs = mysql_fetch_array($result_rs);

                    if (is_null($row_rs["totsal1"]) == false) {


                        $msal = $row_rs["totsal1"]; //(1+($txtvat_new/100));
                        $mret = $salret; // (1+($txtvat_new/100));


                        $mpay = 0;
                        if ($row_rs["out1"] < 50) {
                            $color = "";
                            // echo $sql_rssalma;
                            $result_rssalma = $db->RunQuery($sql_rssalma);
                            while ($row_rssalma = mysql_fetch_array($result_rssalma)) {

                                if (($color != "orange") and ($color != "red")) {

                                    if ($row_rssalma['ST_FLAG'] == "CHK") {
                                        $color = "orange";
                                        $sql_sch = "Select * from s_cheq where CR_CHNO = '" . $row_rssalma['ST_CHNO'] . "'   and CR_C_CODE = '" . $row_rssalma['C_CODE'] . "'   and CR_CHEVAL = '" . str_replace(',', '', $row_rssalma['ST_PAID']) . "' and CR_BANK = '" . $row_rssalma['st_chbank'] . "' and CR_FLAG='0'      ";
                                        $result_sch = $db->RunQuery($sql_sch);
                                        if ($row_sch = mysql_fetch_array($result_sch)) {
                                            $sql_salma = "select * from s_salma where REF_NO = '" . $row_rssalma["ST_INVONO"] . "'";
                                            $result_salma = $db->RunQuery($sql_salma);
                                            $row_salma = mysql_fetch_array($result_salma);


                                            $sql_chsstr = "Select sum(ST_PAID) as ST_PAID,COUNT(*) as COUNT from ch_sttr where ST_INVONO = '" . $row_sch['CR_REFNO'] . "'    ";
                                            $result_chsttr = $db->RunQuery($sql_chsstr);
                                            if ($row_chsttr = mysql_fetch_array($result_chsttr)) {

                                                if ($row_chsttr['COUNT'] > 1) {
                                                    $color = 'yellow';
                                                } else {
                                                    if ($row_chsttr['ST_PAID'] >= $row_chsttr['CR_CHEVAL']) {
                                                        $color = 'green';
                                                    } else {
                                                        $color = 'red';
                                                    }
                                                }


                                            } else {
                                                $color = 'red';
                                            }
                                        } else {
                                            $sql_invch = "Select * from s_invcheq where cheque_no = '" . $row_rssalma['ST_CHNO'] . "' and refno='" . $row_rssalma['ST_REFNO'] . "' and   cus_code = '" . $row_rsVENDOR['C_CODE'] . "'   and che_date='" . $row_rssalma['st_chdate'] . "' and bank = '" . $row_rssalma['st_chbank'] . "' order by che_date desc    ";

                                            $result_invch = $db->RunQuery($sql_invch);
                                            if ($row_invch = mysql_fetch_array($result_invch)) {
                                                if (($row_invch['realizedate'] != "0000-00-00") and ($row_invch['realizedate'] != "")) {
                                                    $color = 'green';
                                                }

                                            }
                                        }
                                    } else {
                                        $color = "green";
                                    }



                                    $sql_rs_type = "Select * from view_inv_item where REF_NO = '" . $row_rssalma["ST_INVONO"] . "' order by id";
                                    $result_rs_type = $db->RunQuery($sql_rs_type);
                                    $row_rs_type = mysql_fetch_array($result_rs_type);

                                    $sql_rsbrn_mas = "select * from brand_mas where barnd_name = '" . trim($row_rssalma["Brand"]) . "'";
                                    $result_rsbrn_mas = $db->RunQuery($sql_rsbrn_mas);

                                    if ($row_rsbrn_mas = mysql_fetch_array($result_rsbrn_mas)) {
                                        if ($_GET['cmbtype'] == "TYRE") {
                                            if ($row_rsbrn_mas["delinrate"] == 2.5) {
                                                $yok = "ok";
                                            } else {
                                                $yok = "";
                                            }
                                        } else {
                                            // if ($row_rsbrn_mas["barnd_name"] == $_GET['cmbtype']) {
                                            $yok = "ok";
                                            // } else {
                                            //     $yok = "";
                                            // }
                                        }

                                        if ($yok == "ok") {

                                            //                                         if ((trim($row_rs_type["type"]) == "TBR") or ( trim($row_rs_type["type"]) == "BIAS TYRES")) {
//                                             $mdcou = 90;
//                                         } else {
//                                             $mdcou = $row_rsVENDOR["incdays"];
// //                                            if ($_GET['cmbtype'] != "TYRE") {
//                                             $sql = "select * from br_trn where brand ='" . $row_rsbrn_mas["class"] . "' and cus_code = '" . $row_rsVENDOR["C_CODE"] . "' and Rep='" . $row_rsVENDOR["SAL_EX"] . "' order by days desc";
// //                                          echo $sql;
//                                             $result_days = $db->RunQuery($sql);
//                                             if ($row_days = mysql_fetch_array($result_days)) {
//                                                 $mdcou = $row_days['days'];
//                                             }
// //                                            }
//                                         }

                                            if ($row_rssalma["cre_pe"] != "") {
                                                $mdcou = $row_rssalma["cre_pe"];
                                            }


                                            if ((!is_null($row_rssalma['Deli_date'])) and $row_rssalma['Deli_date'] != "0000-00-00") {
                                                $mdate = $row_rssalma["Deli_date"];
                                            } else {
                                                $mdate = $row_rssalma["sdate1"];
                                            }
                                            $days = 0;
                                            $diff = 0;
                                            if ((is_null($row_rssalma["st_chdate"]) == false) and (($row_rssalma["st_chdate"]) != "0000-00-00")) {

                                                $diff = abs(strtotime($row_rssalma["st_chdate"]) - strtotime($mdate));
                                                $days = dateDifference($row_rssalma["st_chdate"], $mdate, $differenceFormat = '%a');
                                                $days = floor($diff / (60 * 60 * 24));
                                            } else {

                                                $diff = abs(strtotime($row_rssalma["ST_DATE"]) - strtotime($mdate));
                                                $days = dateDifference($row_rssalma["ST_DATE"], $mdate, $differenceFormat = '%a');
                                                $days = floor($diff / (60 * 60 * 24));
                                            }



                                            if ($days <= $mdcou) {

                                                $mpay = $mpay + ($row_rssalma['ST_PAID'] / (1 + ($row_rssalma['GST'] / 100)));
                                            }
                                        }
                                    }
                                } else {
                                    $mpay = 0;
                                }
                            }




                            //echo $mpay.'/';
                            if (is_null($salret) == false) {
                                $msal = $msal - $mret;
                                $mpay = $mpay - $mret;
                            }

                            if (trim($_GET["cmbtype"]) == "TYRE") {
                                if ($_GET['tardealer'] == "ch") {
                                    if ($_GET['tardealer1'] == "ch") {
                                        $sql_ch = "Select * from vendor where CODE= '" . $row_rsVENDOR["C_CODE"] . "'  and insentarget >0 ";
                                        $result_ch = $db->RunQuery($sql_ch);
                                        if ($row_ch = mysql_fetch_array($result_ch)) {
                                            if ($row_ch["insentarget"] < $mpay) {
                                                $mpay = 0;
                                            }
                                        }
                                        $sql_rsper = "Select * from intper where sdate <= '" . $_GET["DTPicker1"] . "' and traget < " . $mpay . " order by sdate desc, traget desc ";
                                    } else {
                                        $sql_rsper = "Select * from vendor where CODE= '" . $row_rsVENDOR["C_CODE"] . "'  and insentarget >0 ";
                                    }
                                } else {
                                    $sql_rsper = "Select * from intper where sdate <= '" . $_GET["DTPicker1"] . "' and traget < " . $mpay . " order by sdate desc, traget desc ";
                                }
                            } elseif (($_GET["cmbtype"] == "GOODYEAR") or ($_GET["cmbtype"] == "MAXXIS MC TYRE")) {
                                $sql_rsper = "Select * from intper_goodyear where sdate <= '" . $_GET["DTPicker1"] . "' and brand='" . $_GET["cmbtype"] . "' and traget < " . $mpay . " order by sdate desc, traget desc ";
                            } else {
                                $month = date("Y-m-t", strtotime($refDate));
                                $sql_rsper = "Select * from intper_1 where (sdate)<='" . trim($month) . "' and m_type = '" . trim($_GET["cmbtype"]) . "' and qty_tgt <= '" . $netqty . "' order by sdate desc,qty_tgt desc ";
                            }

                            $result_rsper = $db->RunQuery($sql_rsper);
                            if ($row_rsper = mysql_fetch_array($result_rsper)) {

                                $target_a = 0;
                                $newinsentarget = 0;
                                if (trim($_GET["cmbtype"]) == "TYRE") {
                                    if ($_GET['tardealer'] == "ch") {
                                        $newinsentarget = $row_rsper["insentarget"];

                                        if ($_GET['tardealer1'] == "ch") {
                                            $month3 = $mpay * (($row_rsper["per"] / 100));
                                        } else {
                                            if ($mpay >= 1575000.00) {
                                                $txt_percentage = 6.00;
                                                $month3 = $mpay * (($txt_percentage / 100));
                                            } elseif ($mpay >= $newinsentarget) {
                                                $txt_percentage = 5.00;
                                                $month3 = $mpay * (($txt_percentage / 100));
                                            } else {
                                                $txt_percentage = 0.00;
                                                $month3 = $mpay * (($txt_percentage / 100));
                                            }
                                        }
                                    } else {
                                        $month3 = $mpay * (($row_rsper["per"] / 100));
                                    }
                                } else {
                                    $month3 = $mpay * (($row_rsper["per"] / 100));
                                }

                                if ($_GET['monthwise'] == "nch") {
                                    $sql_rsincen = "select * from ins_payment where  I_month ='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "'  and I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and cusCode = '" . trim($row_rsVENDOR["C_CODE"]) . "' and Type = '" . $_GET['cmbtype'] . "' order by id ";
                                } else {
                                    $sql_rsincen = "select * from ins_payment where  ((I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "') or (I_year='" . date("Y", strtotime($_GET["DTPicker2"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker2"]))) . "')) and cusCode = '" . trim($row_rsVENDOR["C_CODE"]) . "' and Type = '" . $_GET['cmbtype'] . "' order by id ";
                                }

                                $result_rsincen = $db->RunQuery($sql_rsincen);
                                if ($row_rsincen = mysql_fetch_array($result_rsincen)) {

                                    $target_a = $row_rsincen["amount"];
                                }


                                if ($i != 0) {
                                    $insert = $insert . ", ";
                                }

                                $sql = "select * from vendor where code = '" . trim($row_rsVENDOR["C_CODE"]) . "'";
                                $result_rsincen = $db->RunQuery($sql);
                                if ($row_rsincen = mysql_fetch_array($result_rsincen)) {

                                    $cus_name = $row_rsincen["NAME"];
                                }

                                $insert = $insert . "('" . trim($row_rsVENDOR["C_CODE"]) . "', '" . $cus_name . "', '" . $msal . "', '" . $mret . "', '" . $month3 . "',  '" . $mpay . "', '" . $target_a . "', '" . $_SESSION["CURRENT_USER"] . "')";

                                $i = 1;
                            }
                        }
                    }
                } else {
                    $sql_rssalma = "select * from view_salma_sttr where C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . " and SAL_EX = '" . $rep . "' and CANCELL = '0' and deliin_amo <= '0' and deliin_lock = '0' order by st_days desc";

                    $sql_rs = "select    sum(GRAND_TOT) as totsal, sum(GRAND_TOT-TOTPAY) as  out1 from s_salma where Accname != 'NON STOCK' and month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  and C_CODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and SAL_EX = '" . $rep . "' and CANCELL = '0' ";

                    $salret = 0;

                    $sql_rs1 = "select * from c_bal where month(sdate1)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sdate1)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  and CUSCODE='" . trim($row_rsVENDOR["C_CODE"]) . "' and CANCELL = '0' AND SAL_EX = '" . $rep . "' and trn_type <> 'ARN' and trn_type != 'REC' and trn_type != 'DGRN' and flag1 != '1' ";
                    $result_rs1 = $db->RunQuery($sql_rs1);
                    while ($row_rs1 = mysql_fetch_array($result_rs1)) {
                        //pppppp probl
                        $sql_rsbrn_mas = "select * from brand_mas where barnd_name = '" . trim($row_rs1["brand"]) . "'";
                        $result_rsbrn_mas = $db->RunQuery($sql_rsbrn_mas);
                        if ($row_rsbrn_mas = mysql_fetch_array($result_rsbrn_mas)) {
                            if ($row_rsbrn_mas["delinrate"] == 2.5) {
                                $salret = $salret + $row_rs1["AMOUNT"];
                            }
                        }
                    }

                    if (is_null($row_rs["totsal"]) == false) {

                        $msal = $row_rs["totsal"] / (1 + ($txtvat_new / 100));
                        $mret = $salret / (1 + ($txtvat_new / 100));


                        // ===
                        $mpay = 0;

                        $color = "";
                        if ($row_rs["out1"] < 50) {

                            $result_rssalma = $db->RunQuery($sql_rssalma);
                            while ($row_rssalma = mysql_fetch_array($result_rssalma)) {

                                // prawe 23.01.24

                                if (($color != "orange") and ($color != "red")) {
                                    if ($row_rssalma['ST_FLAG'] == "CHK") {
                                        $color = "orange";
                                        $sql_sch = "Select * from s_cheq where CR_CHNO = '" . $row_rssalma['ST_CHNO'] . "'   and CR_C_CODE = '" . $row_rssalma['C_CODE'] . "'   and CR_CHEVAL = '" . str_replace(',', '', $row_rssalma['ST_PAID']) . "' and CR_BANK = '" . $row_rssalma['st_chbank'] . "' and CR_FLAG='0'      ";
                                        $result_sch = $db->RunQuery($sql_sch);
                                        if ($row_sch = mysql_fetch_array($result_sch)) {
                                            $color = 'red';
                                        } else {
                                            $sql_invch = "Select * from s_invcheq where cheque_no = '" . $row_rssalma['ST_CHNO'] . "' and refno='" . $row_rssalma['ST_REFNO'] . "' and   cus_code = '" . $row_rsVENDOR['C_CODE'] . "'   and che_date='" . $row_rssalma['st_chdate'] . "' and bank = '" . $row_rssalma['st_chbank'] . "' order by che_date desc    ";

                                            $result_invch = $db->RunQuery($sql_invch);
                                            if ($row_invch = mysql_fetch_array($result_invch)) {
                                                if (($row_invch['realizedate'] != "0000-00-00") and ($row_invch['realizedate'] != "")) {
                                                    $color = 'green';
                                                }

                                            }
                                        }
                                    } else {
                                        $color = "green";
                                    }



                                    $sql_rs_type = "Select * from view_inv_item where REF_NO = '" . $row_rssalma["REF_NO"] . "' order by id";
                                    $sql_rsbrn_mas = "select * from brand_mas where barnd_name = '" . trim($row_rssalma["Brand"]) . "'";

                                    $result_rsbrn_mas = $db->RunQuery($sql_rsbrn_mas);
                                    if ($row_rsbrn_mas = mysql_fetch_array($result_rsbrn_mas)) {

                                        if ($row_rsbrn_mas["delinrate"] == 2.5) {
                                            if ((is_null($row_rssalma["Deli_date"]) == false) and ($row_rssalma["Deli_date"] != "0000-00-00")) {
                                                if ((is_null($row_rssalma["st_chdate"]) == false) and ($row_rssalma["st_chdate"] != "0000-00-00")) {
                                                    $date1 = $row_rssalma["st_chdate"];
                                                    $date2 = $row_rssalma["Deli_date"];
                                                    $diff = abs(strtotime($date2) - strtotime($date1));
                                                    $mdate = round($diff / (60 * 60 * 24));
                                                } else {
                                                    $date1 = $row_rssalma["ST_DATE"];
                                                    $date2 = $row_rssalma["Deli_date"];
                                                    $diff = abs(strtotime($date2) - strtotime($date1));
                                                    $mdate = round($diff / (60 * 60 * 24));
                                                }

                                                if ((trim($row_rs_type["type"]) == "TBR") or (trim($row_rs_type["type"]) == "BIAS TYRES")) {
                                                    if (90 >= $mdate) {
                                                        $mpay = $mpay + $row_rssalma["ST_PAID"];
                                                    }
                                                } else {
                                                    if ($row_rsVENDOR["incdays"] >= $mdate) {
                                                        $mpay = $mpay + $row_rssalma["ST_PAID"];
                                                    }
                                                }
                                            }
                                        } else {
                                            if ((is_null($row_rssalma["st_chdate"]) == false) and ($row_rssalma["st_chdate"] != "0000-00-00")) {
                                                $date1 = $row_rssalma["st_chdate"];
                                                $date2 = $row_rssalma["sdate1"];
                                                $diff = abs(strtotime($date2) - strtotime($date1));
                                                $mdate = floor($diff / (60 * 60 * 24));
                                            } else {
                                                $date1 = $row_rssalma["ST_DATE"];
                                                $date2 = $row_rssalma["sdate1"];
                                                $diff = abs(strtotime($date2) - strtotime($date1));
                                                $mdate = floor($diff / (60 * 60 * 24));
                                            }

                                            if ((trim($row_rs_type["type"]) == "TBR") or (trim($row_rs_type["type"]) == "BIAS TYRES")) {
                                                if (90 >= $mdate) {
                                                    $mpay = $mpay + $row_rssalma["ST_PAID"];
                                                }
                                            } else {
                                                if ($row_rsVENDOR["incdays"] >= $mdate) {
                                                    $mpay = $mpay + $row_rssalma["ST_PAID"];
                                                }
                                            }
                                        }

                                    }



                                } else {
                                    $mpay = 0;
                                }
                            }

                            $mpay = $mpay / (1 + ($txtvat_new / 100));


                            if (is_null($salret) == false) {
                                $msal = $msal - $mret;
                                $mpay = $mpay - $mret;
                            }

                            if (date("Y", strtotime($_GET["DTPicker1"])) > 2012) {

                                $sql_rsper = "Select * from intper where sdate <= '" . $_GET["DTPicker1"] . "' and traget < " . $mpay . " order by sdate desc,traget desc ";
                            } else {
                                if (date("Y", strtotime($_GET["DTPicker1"])) < 2010) {
                                    $sql_rsper = "Select * from intper where incen_year = 2009 and traget < " . $mpay . " order by traget desc ";
                                } else {
                                    if ((date("m", strtotime($_GET["DTPicker1"])) > 3) or (date("Y", strtotime($_GET["DTPicker1"])) >= 2010)) {
                                        $sql_rsper = "Select * from intper where incen_year = 20101 and traget < " . $mpay . " order by traget desc ";
                                    } else {
                                        $sql_rsper = "Select * from intper where incen_year = 2010 and traget < " . $mpay . " order by traget desc ";
                                    }
                                }
                            }

                            $result_rsper = $db->RunQuery($sql_rsper);
                            if ($row_rsper = mysql_fetch_array($result_rsper)) {

                                $target_a = 0;
                                $month3 = $mpay * (($row_rsper["per"] / 100));

                                $sql_rsincen = "select * from ins_payment where  I_month ='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "'  and I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and cusCode = '" . trim($row_rsVENDOR["C_CODE"]) . "' and Type = '" . $_GET['cmbtype'] . "' order by id ";
                                //echo $sql_rsincen;
                                $result_rsincen = $db->RunQuery($sql_rsincen);
                                if ($row_rsincen = mysql_fetch_array($result_rsincen)) {

                                    $target_a = $row_rsincen["amount"];
                                }

                                if ($i != 0) {
                                    $insert = $insert . ", ";
                                }

                                $insert = $insert . "('" . trim($row_rsVENDOR["C_CODE"]) . "', '" . $row_rsVENDOR["CUS_NAME"] . "', '" . $msal . "', '" . $mret . "', '" . $month3 . "',  '" . $mpay . "', '" . $target_a . "', '" . $_SESSION["CURRENT_USER"] . "')";

                                $i = 1;


                                //echo $sql_dealer;
                            }
                        }


                    }
                }
            }
        }
    }

    $sql_dealer = "insert into monsales(Cus_Code, cus_name, month1, month2, month3, limit1, target, user_id) values " . $insert;
    //    echo $sql_dealer;
    $result_dealer = $db->RunQuery($sql_dealer);

    if ($_GET["cmbrep"] == "All") {

        $mRow = 1;

        $sql_tmp1 = "select * from monsales where target = 0 and user_id='" . $_SESSION["CURRENT_USER"] . "'";
        //echo $sql_tmp1;
        $result_tmp1 = $db->RunQuery($sql_tmp1);
        while ($row_tmp1 = mysql_fetch_array($result_tmp1)) {

            if (is_null($row_tmp1["Cus_Code"]) == false) {
                $Flexcus[$mRow][1] = trim($row_tmp1["Cus_Code"]);
            }
            if (is_null($row_tmp1["cus_name"]) == false) {
                $Flexcus[$mRow][2] = trim($row_tmp1["cus_name"]);
            }
            if (is_null($row_tmp1["limit1"]) == false) {
                $Flexcus[$mRow][3] = number_format($row_tmp1["limit1"], 2, ".", ",");
            }
            if (is_null($row_tmp1["month3"]) == false) {
                $Flexcus[$mRow][4] = number_format($row_tmp1["month3"], 2, ".", ",");
            }
            if (is_null($row_tmp1["month1"]) == false) {
                $Flexcus[$mRow][7] = number_format($row_tmp1["month1"], 2, ".", ",");
            }

            $mRow = $mRow + 1;
        }
    } else {

        $mrow1 = 1;
        $sql_tmp1 = "select * from monsales where user_id='" . $_SESSION["CURRENT_USER"] . "'";
        $result_tmp1 = $db->RunQuery($sql_tmp1);
        while ($row_tmp1 = mysql_fetch_array($result_tmp1)) {

            if (is_null($row_tmp1["Cus_Code"]) == false) {
                $Flexcus[$mrow1][1] = trim($row_tmp1["Cus_Code"]);
            }
            if (is_null($row_tmp1["cus_name"]) == false) {
                $Flexcus[$mrow1][2] = trim($row_tmp1["cus_name"]);
            }
            if (is_null($row_tmp1["limit1"]) == false) {
                $Flexcus[$mrow1][3] = number_format($row_tmp1["limit1"], 2, ".", ",");
            }
            if (is_null($row_tmp1["month3"]) == false) {
                $Flexcus[$mrow1][4] = number_format($row_tmp1["month3"], 2, ".", ",");
            }
            if ($row_tmp1["target"] > 0) {
                $Flexcus[$mrow1][5] = "Yes";
            }

            if ($_GET['monthwise'] == "nch") {
                $sql_rsincen = "select * from ins_payment where  I_month ='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "'  and I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and cusCode = '" . trim($row_tmp1["Cus_Code"]) . "'order by id ";
            } else {
                $sql_rsincen = "select * from ins_payment where  ((I_year='" . date("Y", strtotime($_GET["DTPicker1"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker1"]))) . "') or (I_year='" . date("Y", strtotime($_GET["DTPicker2"])) . "' and I_month='" . intval(date("m", strtotime($_GET["DTPicker2"]))) . "')) and cusCode = '" . trim($row_tmp1["Cus_Code"]) . "'order by id ";
            }

            $result_rsincen = $db->RunQuery($sql_rsincen);
            if ($row_rsincen = mysql_fetch_array($result_rsincen)) {

                $Flexcus[$mrow1][6] = $row_rsincen["remarks"];
            }
            if (is_null($row_tmp1["month1"]) == false) {
                $Flexcus[$mrow1][7] = number_format($row_tmp1["month1"], 2, ".", ",");
            }

            $mrow1 = $mrow1 + 1;
        }
    }

    $ResponseXML = "";

    if ($_GET["cmbrep"] == "All") {

        $ResponseXML .= "<table><tr>
    <td width=\"10\"  background=\"\" ><font color=\"#FFFFFF\"></font></td>
    <td width=\"50\"  background=\"\" ><font color=\"#FFFFFF\">Code</font></td>
    <td width=\"300\"  background=\"\"><font color=\"#FFFFFF\">Name</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Incen Target</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Net Sale</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Effect Sale</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Incentive</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Yes/No</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Sales Return After Sal.Month</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Pending Return Check</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Dealer Remark</font></td>
    <td   background=\"\"><font color=\"#FFFFFF\">...</font></td>
    </tr>";
    } else {

        $ResponseXML .= "<table><tr>
    <td width=\"10\"  background=\"\" ><font color=\"#FFFFFF\"></font></td>
    <td width=\"50\"  background=\"\" ><font color=\"#FFFFFF\">Code</font></td>
    <td width=\"300\"  background=\"\"><font color=\"#FFFFFF\">Name</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Net Sale</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Effect Sale</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Incentive</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Paid</font></td>
    <td width=\"100\"  background=\"\"><font color=\"#FFFFFF\">Chq Detail</font></td>


    </tr>";
    }


    if ($_GET["cmbrep"] == "All") {

        $i = 1;
        $xx = 1;
        while ($mRow > $i) {
            $sql478 = "Select insentarget from vendor where CODE= '" . $Flexcus[$i][1] . "' ";
            //             echo $sql478;
            $result478 = $db->RunQuery($sql478);
            $row478 = mysql_fetch_array($result478);

            if (trim($_GET["cmbtype"]) == "TYRE") {
                $insstar = $row478["insentarget"];
            } else {
                $insstar = 0;
            }
            $chk = "chk" . $i;

            if ($Flexcus[$i][4] > "0.00") {

                $ResponseXML .= "<tr>                              
            <td>" . $xx . "</td>
            <td>" . $Flexcus[$i][1] . "</td>
            <td>" . $Flexcus[$i][2] . "</td>
            <td>" . $insstar . "</td>
            <td>" . $Flexcus[$i][7] . "</td>
            <td>" . $Flexcus[$i][3] . "</td>
            <td>" . $Flexcus[$i][4] . "</td>
            <td><input type=\"checkbox\" name=\"" . $chk . "\" id=\"" . $chk . "\" onClick=\"chk_ad('" . $chk . "', '" . $Flexcus[$i][1] . "');\"></td>";

                $mret = 0;
                if ($_GET['monthwise'] == "nch") {
                    $sql = "SELECT sum(amount) as amount FROM view_cbal_bmas_crnma_salma WHERE cuscode = '" . $Flexcus[$i][1] . "' and month(sdate1) <>" . date("m", strtotime($_GET["DTPicker1"])) . " and month(sal_sdate)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sal_sdate)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  AND trn_type = 'GRN' AND CANCELL = '0'";
                } else {
                    $sql = "SELECT sum(amount) as amount FROM view_cbal_bmas_crnma_salma WHERE cuscode = '" . $Flexcus[$i][1] . "' and month(sdate1) <>" . date("m", strtotime($_GET["DTPicker1"])) . " and month(sal_sdate)=" . date("m", strtotime($_GET["DTPicker1"])) . " and year(sal_sdate)=" . date("Y", strtotime($_GET["DTPicker1"])) . "  AND trn_type = 'GRN' AND CANCELL = '0'";
                }

                if (trim($_GET["cmbtype"]) == "TYRE") {
                    //                $sql .= " and delinrate = '2.5' and brand ='CHENG SHING'";
                    $sql .= " and delinrate = '2.5'";
                } else {
                    // $sql .= " and brand = '" . trim($_GET["cmbtype"]) . "'";

                    if (($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                        $sql .= " and brand = '" . $brand1 . "' ";
                    } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL) && ($brand1 == NULL)) {
                        $sql .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' )";
                    } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 == NULL)) {
                        $sql .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' )";
                    } else if (($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL) && ($brand1 != NULL)) {
                        $sql .= " and (brand = '" . $brand1 . "'  or brand = '" . $brand2 . "' or brand = '" . $brand3 . "' or brand = '" . $brand4 . "' )";
                    }
                }
                //echo $sql;
                $result_rsincen = $db->RunQuery($sql);
                if ($row_rsincen = mysql_fetch_array($result_rsincen)) {
                    if (!is_null($row_rsincen['amount'])) {
                        $mret = $row_rsincen['amount'];
                    }
                }



                $ResponseXML .= "<td>" . $mret . "</td>";
                $sql = "select * from dealer_incen_rmk where d_code = '" . $Flexcus[$i][1] . "'";
                $result = $db->RunQuery($sql);
                $row = mysql_fetch_array($result);
                $mreturn = 0;
                $sqlrtn = "Select sum(CR_CHEVAL - PAID) as Rtn from s_cheq where CR_C_CODE = '" . $Flexcus[$i][1] . "' and CR_FLAG = '0' and CR_CHEVAL - PAID > 1 ";

                $resultrtn = $db->RunQuery($sqlrtn);
                $rowrtn = mysql_fetch_array($resultrtn);

                $mreturn = $rowrtn['Rtn'];
                $ResponseXML .= "<td>" . $mreturn . "  </td>";
                $ResponseXML .= "<td><input type='text' id = '" . $Flexcus[$i][1] . "' value='" . $row["rmk"] . "'></td>";
                $ResponseXML .= "<td><a class='btn_purchase' onclick=\"updt('" . $Flexcus[$i][1] . "');\">..</a></td>";


                $ResponseXML .= "</tr>";


                $xx += 1;
            }
            // 
            $i = $i + 1;

        }
    } else {

        $i = 1;

        while ($mrow1 > $i) {

            // if($Flexcus[$i][4]>0){
            $ResponseXML .= "<tr>                              
            <td>" . $i . "</td>
            <td>" . $Flexcus[$i][1] . "</td>
            <td>" . $Flexcus[$i][2] . "</td>
            <td>" . $Flexcus[$i][7] . "</td>
            <td>" . $Flexcus[$i][3] . "</td>
            <td>" . $Flexcus[$i][4] . "</td>
            <td>" . $Flexcus[$i][5] . "</td>
            <td>" . $Flexcus[$i][6] . "</td>
            </tr>";
            $i = $i + 1;

            // }

        }
    }





    $ResponseXML .= "   </table>";

    echo $ResponseXML;

    
}

if ($_GET['Command'] == "updt") {
    $sql = "delete from dealer_incen_rmk where d_code = '" . $_GET["cuscode"] . "'";
    require_once("config.inc.php");
    require_once("DBConnector.php");
    $db = new DBConnector();
    $result = $db->RunQuery($sql);

    $sql = "insert into dealer_incen_rmk (d_code,rmk) values ('" . $_GET['cuscode'] . "','" . $_GET['remark'] . "')";
    $result = $db->RunQuery($sql);
    echo "Saved";
}

if ($_GET["Command"] == "chk_ad") {


    if ($_GET["chk_val"] == "true") {
        $rs = "update monsales set print = '1' where Cus_Code = '" . $_GET["cuscode"] . "' and user_id='" . $_SESSION["CURRENT_USER"] . "'";
        $result = $db->RunQuery($rs);
        //$row = mysql_fetch_array($result)){
    } else {
        $rs = "update monsales set print = '0' where Cus_Code = '" . $_GET["cuscode"] . "' and user_id='" . $_SESSION["CURRENT_USER"] . "'";
        $result = $db->RunQuery($rs);
    }
    //echo $rs;
}

function dateDifference($date_1, $date_2, $differenceFormat = '%a')
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);
}

?>