<?php
session_start();
date_default_timezone_set('Asia/Colombo');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Unsold Rep Wise Report</title>
		<style>
			table {
				border-collapse: collapse;
			}
			table, td, th {

				font-family: Arial, Helvetica, sans-serif;
				padding: 5px;
			}
			th {
				font-weight: bold;
				font-size: 14px;
			}
			td {
				font-size: 14px;
				border-bottom: none;
				border-top: none;
			}
		</style>
	</head>

	<body>
		<!-- Progress bar holder -->
		<!-- Progress information -->
		<div id="information" style="width"></div>

		<?php
		require_once ("connectioni.php");
                
                if(isset($_SESSION["UserName"])){

		$daysover = $_GET["txtdays"];
		$daysbel = $_GET["txtbel"];

		$sql = "delete from tmparmove where user_id = '" . $_SESSION["CURRENT_USER"] . "'";
		$result = mysqli_query($GLOBALS['dbinv'], $sql);

		if ($_GET["cmbbrand"] == "All") {
			$sql_smas = "select * from viewsubmas1 where STO_CODE='" . $_GET["com_dep"] . "' AND  QTY1> 0  limit 500";
		}
		if ($_GET["cmbbrand"] != "All") {

			$sql_smas = "select * from viewsubmas1 where STO_CODE='" . $_GET["com_dep"] . "'  AND QTY1> 0";
		}

		if ($_GET["cmbbrand"] != "All") {
			$sql_smas = $sql_smas . " and BRAND_NAME ='" . $_GET["cmbbrand"] . "'";
		}
		$i = 0;
		$result_smas = mysqli_query($GLOBALS['dbinv'], $sql_smas);
		while ($row_smas = mysqli_fetch_array($result_smas)) {

			$balqty = $row_smas["QTY1"];
			$totarqty = 0;
			$totbalqty = 0;

			$totbalqty = $row_smas["QTYINHAND"];
			while ($balqty != 0) {
				$sqlm = "select * from viewpur where STK_NO='" . $row_smas["STK_NO"] . "' and CANCEL='0' order by sdate desc limit 30";
				$result_artrn = mysqli_query($GLOBALS['dbinv'], $sqlm);
				
				$rowco = mysqli_num_rows($result_artrn);
				if ($rowco==0) {
					

					$userData[] = "('', '', '" . trim($row_smas["STK_NO"]) . "', '" . $row_smas["DESCRIPT"] . "', '" . $row_smas["PART_NO"] . "', '" . $row_smas["QTYINHAND"] . "', 0, '" . trim($row_smas["BRAND_NAME"]) . "', '', '', 0, 0, '" . $balqty . "', '" . $row_smas["acc_cost"] . "', 0, '" . $_SESSION["CURRENT_USER"] . "')";
					$balqty=0;
				}
					
				while ($row_artrn = mysqli_fetch_array($result_artrn)) {
					if ($balqty == 0) {
						break;
					}
					

					$sql = "select * from view_repwise where STK_NO='" . $row_smas["STK_NO"] . "' and refno='" . trim($row_artrn["REFNO"]) . "' and CANCEL='0' and DEP_TO = '" . $_GET["com_dep"] . "' limit 15";
					$result_artrn1 = mysqli_query($GLOBALS['dbinv'], $sql);
					$rowc = mysqli_num_rows($result_artrn1);
					$totarqty = $totarqty + $row_artrn["REC_QTY"];

					
					
					if ($rowc <> 0) {

						while ($row_artrn1 = mysqli_fetch_array($result_artrn1)) {
							if ($balqty != 0) {
								
								 
								$totar = $row_artrn1["QTY"];

								$ardate = $row_artrn["SDATE"];
								$AR_NO = trim($row_artrn["REFNO"]);
								$STK_NO = trim($row_smas["STK_NO"]);
								$des = trim($row_smas["DESCRIPT"]);
								$PART_NO = trim($row_smas["PART_NO"]);
								$qty_hnd = $row_smas["QTY1"];
								$AR_QTY = $row_artrn1["REC_QTY"];
								$brand = $row_smas["BRAND_NAME"];

								$SUPPLIER = trim($row_artrn["SUP_NAME"]);
								$LC_NO = trim($row_artrn["LCNO"]);

								if ($_SESSION['dev'] == '1') {
									$ARVALUE = $row_artrn1["COST"] * $row_artrn1["REC_QTY"];
								} else if ($_SESSION['dev'] == '0') {
									$ARVALUE = $row_artrn1["acc_cost"] * $row_artrn1["REC_QTY"];
								}
													
								if ($balqty > $row_artrn1['QTY']) {
									$balqty = $balqty - $row_artrn1['QTY'];
									$totar = $totar - $row_artrn1['QTY'];
									$UN_QTY = $row_artrn1["QTY"];

									if ($row_smas["QTYINHAND"] < $totarqty) {
										$UN_QTY = $UN_QTY + $balqty;
										$balqty = 0;
									}
								} else {
									$totar = 0;
									$UN_QTY = $balqty;
									$balqty = 0;
								}

								if ($totbalqty <= 0 and $balqty > 0) {
									$date = date("Y-m-d");
									$date = strtotime(date("Y-m-d", strtotime($date)) . " -91 days");
									$caldate = date("Y-m-d", $date);
									$sqlm = "select sum(rec_qty) as recqty from s_purtrn where STK_NO='" . $row_smas["STK_NO"] . "' and CANCEL='0' and SDATE > '" . $caldate . "' order by sdate desc ";

									$result_rs = mysqli_query($GLOBALS['dbinv'], $sqlm);
									$row_rs = mysqli_fetch_array($result_rs);

									$txtunsold = 0;

									if (is_null($row_rs["recqty"]) == false) {
										$mnewstk = $row_rs["recqty"];
									}

									if ($row_smas["QTYINHAND"] > $mnewstk) {
										$txtunsold = $row_smas["QTYINHAND"] - $mnewstk;
									}

									If ($txtunsold > 0) {
										$UN_QTY = $balqty;
									} else {
										$UN_QTY = "0";
										$monsales = "0";
										$sold = "0";
									}

								}

								if ($_SESSION['dev'] == '1') {
									$sold = $row_smas["COST"];
								} else if ($_SESSION['dev'] == '0') {
									$sold = $row_smas["acc_cost"];
								}
								$monsales = $sold;
								$period = (strtotime(date("Y-m-d")) - strtotime($row_artrn["SDATE"])) / (60 * 60 * 24);
								 

								$userData[] = " ('" . $ardate . "','" . $AR_NO . "','" . $STK_NO . "','" . $des . "','" . $PART_NO . "','" . $qty_hnd . "','" . $AR_QTY . "','" . $brand . "','" . $SUPPLIER . "','" . $LC_NO . "','" . $ARVALUE . "','" . $period . "','" . $UN_QTY . "','" . $sold . "','" . $monsales . "','" . $_SESSION["CURRENT_USER"] . "')";
								$totbalqty = $totbalqty - $row_artrn1['QTY'];				
							}
						}
					} else {

						if ($balqty != 0) {
		
							 

							$ardate = $row_artrn["SDATE"];
							$AR_NO = trim($row_artrn["REFNO"]);
							$STK_NO = trim($row_smas["STK_NO"]);
							$des = trim($row_smas["DESCRIPT"]);
							$PART_NO = trim($row_smas["PART_NO"]);
							$qty_hnd = $row_smas["QTY1"];
							$AR_QTY = $row_artrn["REC_QTY"];
							$brand = $row_smas["BRAND_NAME"];

							$SUPPLIER = trim($row_artrn["SUP_NAME"]);
							$LC_NO = trim($row_artrn["LCNO"]);

							if ($_SESSION['dev'] == '1') {
								$ARVALUE = $row_artrn["COST"] * $row_artrn["REC_QTY"];
							} else if ($_SESSION['dev'] == '0') {
								$ARVALUE = $row_artrn["acc_cost"] * $row_artrn["REC_QTY"];
							}
							
							$UN_QTY = 0;
							
							if ($_SESSION['dev'] == '1') {
									$sold = $row_smas["COST"];
								} else if ($_SESSION['dev'] == '0') {
									$sold = $row_smas["acc_cost"];
								}
									
							If (($totbalqty <= $row_artrn["REC_QTY"])) {
                               	$UN_QTY = $balqty;
                                $balqty = 0;
                            } elseif ($row_smas["QTYINHAND"] < $totarqty) {
								$UN_QTY = $balqty;
								$balqty = 0;
							}
							
							
							
							
							$monsales = $sold;
							$period = (strtotime(date("Y-m-d")) - strtotime($row_artrn["SDATE"])) / (60 * 60 * 24);
							$userData[] = " ('" . $ardate . "','" . $AR_NO . "','" . $STK_NO . "','" . $des . "','" . $PART_NO . "','" . $qty_hnd . "','" . $AR_QTY . "','" . $brand . "','" . $SUPPLIER . "','" . $LC_NO . "','" . $ARVALUE . "','" . $period . "','" . $UN_QTY . "','" . $sold . "','" . $monsales . "','" . $_SESSION["CURRENT_USER"] . "')";
							$totbalqty = $totbalqty - $row_artrn["REC_QTY"];					
							

						}
					}
				}
			}

		}

		$sqls = 'insert into tmparmove(ardate, AR_NO, STK_NO, des, PART_NO, qty_hnd, AR_QTY, brand, SUPPLIER,LC_NO,ARVALUE, period,UN_QTY,sold,monsales,user_id) values ' . implode(',', $userData);
		$res = mysqli_query($GLOBALS['dbinv'], $sqls);

		if ($_GET["cmbtype"] == "All") {
			$sql_rst = "select * from tmparmove  where user_id = '" . $_SESSION["CURRENT_USER"] . "' ";
		}
		if ($_GET["cmbtype"] == "Over") {
			$sql_rst = "select * from tmparmove where period>" . $daysover . " and user_id = '" . $_SESSION["CURRENT_USER"] . "'";
		}
		if ($_GET["cmbtype"] == "Between") {
			$sql_rst = "select * from tmparmove where period>" . $daysover . " and period<" . $daysbel . " and user_id = '" . $_SESSION["CURRENT_USER"] . "'";
		}
		//echo $sql_rst;

		$b30 = 0;
		$o36b45 = 0;
		$o46b60 = 0;
		$o61b75 = 0;
		$o76b91 = 0;
		$o91 = 0;
		$total = 0;

		$result_rst = mysqli_query($GLOBALS['dbinv'], $sql_rst);
		while ($row_rst = mysqli_fetch_array($result_rst)) {

			if ($row_rst["period"] < 31) {
				$b30 = $b30 + $row_rst["UN_QTY"] * $row_rst["sold"];
			}
			if (($row_rst["period"] > 30) and ($row_rst["period"] < 46)) {
				$o36b45 = $o36b45 + $row_rst["UN_QTY"] * $row_rst["sold"];
			}
			if (($row_rst["period"] > 45) and ($row_rst["period"] < 61)) {
				$o46b60 = $o46b60 + $row_rst["UN_QTY"] * $row_rst["sold"];
			}
			if (($row_rst["period"] > 60) and ($row_rst["period"] < 76)) {
				$o61b75 = $o61b75 + $row_rst["UN_QTY"] * $row_rst["sold"];
			}
			if (($row_rst["period"] > 75) and ($row_rst["period"] < 91)) {
				$o76b91 = $o76b91 + $row_rst["UN_QTY"] * $row_rst["sold"];
			}
			if ($row_rst["period"] > 90) {
				$o91 = $o91 + $row_rst["UN_QTY"] * $row_rst["sold"];
			}
			if ((is_null($row_rst["sold"]) == false) and (is_null($row_rst["UN_QTY"]) == false)) {
				$total = $total + $row_rst["UN_QTY"] * $row_rst["sold"];
			}
		}

		if ($_GET["unsold"] == "summery") {

			printSummery();
			exit();
		}

		if ($_GET["unsold"] == "soldsummery") {
			sold_sum();
			PRINT_WEEKS();
			exit();
		}

		$sql_head = "select * from invpara";
		$result_head = mysqli_query($GLOBALS['dbinv'], $sql_head);
		$row_head = mysqli_fetch_array($result_head);

		$txtrepono = $_SESSION["CURRENT_USER"] . " " . date("Y-m-d") . "  " . date("H:i:s");

		if ($_GET["cmbtype"] == "All") {
			$sql = "SELECT * from tmparmove where UN_QTY > 0 and user_id = '" . $_SESSION["CURRENT_USER"] . "' order by brand,stk_no,period";
			$txtdays = " All Stock";
		}

		if ($_GET["cmbtype"] == "Over") {
			$sql = "SELECT * from tmparmove where period>" . $daysover . " and UN_QTY > 0 and user_id = '" . $_SESSION["CURRENT_USER"] . "' order by brand,stk_no,period";
			$txtdays = " Over  " . $daysover . "  days Stock";
		}

		if ($_GET["cmbtype"] == "Between") {
			$sql = "SELECT * from tmparmove where period>" . $daysover . " and period<" . $daysbel . " and UN_QTY > 0 and user_id = '" . $_SESSION["CURRENT_USER"] . "' order by brand,stk_no,period";
			$txtdays = " Over  " . $daysover . "  Bellow   " . $daysbel . "  days Stock";
		}
		?>

		<center>
			<table width="1200">
				<tr>
					<th colspan="13" scope="col"><?php echo $row_head["COMPANY"]; ?></th>
				</tr>
				<tr>
					<th colspan="13" scope="col"><?php echo $row_head["ADD1"] . " , " . $row_head["ADD2"]; ?></th>
				</tr>
				<tr>
					<?php
					$sql_dep = "select * from s_stomas where CODE='" . $_GET["com_dep"] . "'";
					$result_dep = mysqli_query($GLOBALS['dbinv'], $sql_dep);
					$rows_dep = mysqli_fetch_array($result_dep);
					?>
					<td colspan="3">AR Moving Report - Department : <?php echo $_GET["com_dep"] . " - " . $rows_dep["DESCRIPTION"]; ?></td> 
					<td colspan="7" align="center"><?php echo $txtdays; ?></td>
					<td colspan="3" align="right"><?php echo date("Y-m-d"); ?></td>
				</tr>
			</table>
			<table width="1200" border="1">
				<tr>
					<th>Stock No</th><th width="250">Description</th><th>Part No</th><th>Qty In Ha</th><th>No of Days</th><th>Un Sold Stock</th><th>L/C No</td> <th>AR Date</th><th>AR No</th><th>AR Qty</th>
                                        <?php if ($_SESSION["CURRENT_REP"]=="") echo "<th>Total Value</th><th>Cost Value</th><th>Unsold Value</th>";?>
				</tr>
				<?php
				$brand = "";
				$STK_NO = "";
				$tot = 0;
$arqty=0;
				$result = mysqli_query($GLOBALS['dbinv'], $sql);
				while ($rows = mysqli_fetch_array($result)) {

					if ($brand != $rows["brand"]) {
						echo "<tr>
<td align=\"left\" colspan=13><b>" . $rows["brand"] . "</b></td></tr>";
						$brand = $rows["brand"];
					}
					if ($STK_NO != $rows["STK_NO"]) {

						echo "<tr>";
						echo "<td>" . $rows["STK_NO"] . "</td>";
						echo "<td>" . $rows["des"] . "</td>";
						echo "<td>" . $rows["PART_NO"] . "</td>";
						echo "<td align=\"right\">" . $rows["qty_hnd"] . "</td>";
						echo "<td>&nbsp;</td>";
						echo "<td align=\"right\">&nbsp;</td>";
						echo "<td>&nbsp;</td>";
						echo "<td>&nbsp;</td>";
						echo "<td>&nbsp;</td>";
						echo "<td align=\"right\">&nbsp;</td>";
                                                if ($_SESSION["CURRENT_REP"]==""){
                                                    echo "<td align=\"right\">&nbsp;</td>";
                                                    echo "<td align=\"right\">&nbsp;</td>";
                                                    echo "<td align=\"right\">&nbsp;</td>";
                                                }
						echo "</tr>";
						$STK_NO = $rows["STK_NO"];
					}
					echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td>&nbsp;</td>";
					echo "<td>&nbsp;</td>";
					echo "<td align=\"right\">&nbsp;</td>";
					echo "<td>" . $rows["period"] . "</td>";
					echo "<td align=\"right\"><b>" . $rows["UN_QTY"] . "</b></td>";
					echo "<td>" . $rows["LC_NO"] . "</td>";
					echo "<td>" . $rows["ardate"] . "</td>";
					echo "<td>" . $rows["AR_NO"] . "</td>";
					echo "<td align=\"right\">" . $rows["AR_QTY"] . "</td>";
                                        if ($_SESSION["CURRENT_REP"]==""){
                                            echo "<td align=\"right\">" . number_format($rows["ARVALUE"], 2, ".", ",") . "</td>";
                                            echo "<td align=\"right\">" . number_format($rows["sold"], 2, ".", ",") . "</td>";
                                            echo "<td align=\"right\">" . number_format($rows["sold"] * $rows["UN_QTY"], 2, ".", ",") . "</td>";
                                        }
					echo "</tr>";
					$tot = $tot + ($rows["sold"] * $rows["UN_QTY"]);
					$arqty +=$rows["UN_QTY"];
				}

				echo "<tr>";
                                if ($_SESSION["CURRENT_REP"]==""){
                                    echo "<td colspan=5>&nbsp;</td>";
                                     echo "<td align=\"right\"><b>" . number_format($arqty, 2, ".", ",") . "</b></td>";
                                     echo "<td colspan=4>&nbsp;</td>";
                                    echo "<td align=\"right\"><b>" . number_format($tot, 2, ".", ",") . "</b></td>";
                                }
				echo "</tr>";
				?>
			</table>
                        <?php if ($_SESSION["CURRENT_REP"]==""){?>
			<table width='1200'>
				<tr>
					<td width='400'><b>Total Value Rs.</b></td>
					<td align="right" width='200'><b><?php echo number_format($total, 2, ".", ","); ?></b></td>
					<td width='600'></td>

				</tr>
				<tr>
					<td width='400'><b>Below 30 Days Stock Rs.</b></td>
					<td align="right"><b><?php echo number_format($b30, 2, ".", ","); ?></b></td>
					<td width='600'></td>

				</tr>
				<tr>
					<td width='400'><b>Over 31 and Below 45 Days Stock Rs.</b></td>
					<td align="right"><b><?php echo number_format($o36b45, 2, ".", ","); ?></b></td>
					<td width='600'></td>

				</tr>
				<tr>
					<td width='400'><b>Over 46 and Below 60 Days Stock Rs.</b></td>
					<td align="right"><b><?php echo number_format($o46b60, 2, ".", ","); ?></b></td>
					<td width='600'></td>

				</tr>
				<tr>
					<td width='400'><b>Over 61 and Below 75 Days Stock Rs.</b></td>
					<td align="right"><b><?php echo number_format($o61b75, 2, ".", ","); ?></b></td>
					<td width='600'></td>

				</tr>
				<tr>
					<td width='400'><b>Over 76  and Below 90 Days Stock Rs.</b></td>
					<td align="right"><b><?php echo number_format($o76b91, 2, ".", ","); ?></b></td>
					<td width='600'></td>

				</tr>
				<tr>
					<td width='400'><b>Over 90 Days Stock Rs.</b></td>
					<td align="right"><b><?php echo number_format($o91, 2, ".", ","); ?></b></td>
					<td width='600'></td>
				</tr>
			</table>
                        

			<?php
                        }
                        }else{
                            echo "invalid user";
                        }
			function printSummery() {

				require_once ("connectioni.php");

				$sql_head = "select * from invpara";
				$result_head = mysqli_query($GLOBALS['dbinv'], $sql_head);
				$row_head = mysqli_fetch_array($result_head);

				//$txtrepono= $_SESSION["CURRENT_USER"] . " " . date("Y-m-d") . "  " . date("H:i:s");

				$sql_dep = "select * from s_stomas where CODE='" . $_GET["com_dep"] . "'";
				$result_dep = mysqli_query($GLOBALS['dbinv'], $sql_dep);
				$rows_dep = mysqli_fetch_array($result_dep);

				$TXTREP = "Sales Rep : " . $_GET["com_dep"] . " - " . $rows_dep["DESCRIPTION"];

				$daysover = $_GET["txtdays"];
				$daysbel = $_GET["txtbel"];

				if ($_GET["cmbtype"] == "All") {
					$txtdays = " All Stock";
				}

				if ($_GET["cmbtype"] == "Over") {
					$txtdays = " Over  " . $daysover . "  days Stock";
				}
				if ($_GET["cmbtype"] == "Between") {
					$txtdays = " Over  " . $daysover . "  Bellow   " . $daysbel . "  days Stock";
				}

				echo "<center><table width=\"1000\" border=\"0\">
<tr>
<th colspan=\"13\" scope=\"col\">" . $row_head["COMPANY"] . "</th>
</tr>
<tr>
<th colspan=\"13\" scope=\"col\">" . $row_head["ADD1"] . " , " . $row_head["ADD2"] . "</th>
</tr>
<tr>
<td colspan=\"3\">AR Moving Report</td>
<td colspan=\"7\" align=\"center\">" . $txtdays . "</td>
<td colspan=\"3\" align=\"right\">" . date("Y-m-d") . "</td>
</tr>
<tr>
<td colspan=\"3\">" . $TXTREP . "</td>
<td colspan=\"7\" align=\"center\"></td>
<td colspan=\"3\" align=\"right\"></td>
</tr>
</table><br>";

				echo "<center><table border=1  width=\"1000\" cellpadding=\"5\" cellspacing=\"0\"><tr>
<th>Brand</th><th>Bellow 60</th><th>60 to 90</th><th>90 to 120</th><th>Over 120</th><th>Total Stock</th>
<th>Total Over 90</th><th>%</th></tr>";

				$sql1 = "SELECT distinct brand from tmparmove where user_id = '" . $_SESSION["CURRENT_USER"] . "' order by brand";
				$result1 = mysqli_query($GLOBALS['dbinv'], $sql1);

				$all_totbel60 = 0;
				$all_toto60_90 = 0;
				$all_toto90to120 = 0;
				$all_toto120 = 0;
				$all_totunsold = 0;
				$all_tottotover90 = 0;
				while ($row1 = mysqli_fetch_array($result1)) {

					if ($_GET["cmbtype"] == "All") {
						$sql = "SELECT * from tmparmove where brand='" . $row1["brand"] . "' and user_id = '" . $_SESSION["CURRENT_USER"] . "' ";
					}

					if ($_GET["cmbtype"] == "Over") {
						$sql = "SELECT * from tmparmove where period>" . $daysover . " and brand='" . $row1["brand"] . "' and user_id = '" . $_SESSION["CURRENT_USER"] . "'";
					}
					if ($_GET["cmbtype"] == "Between") {
						$sql = "SELECT * from tmparmove where period>" . $daysover . " and period<" . $daysbel . " and brand='" . $row1["brand"] . "' and user_id = '" . $_SESSION["CURRENT_USER"] . "'";
					}

					$totbel60 = 0;
					$toto60_90 = 0;
					$toto90to120 = 0;
					$toto120 = 0;
					$totunsold = 0;
					$tottotover90 = 0;
					$totsubpr = 0;

					$result = mysqli_query($GLOBALS['dbinv'], $sql);
					while ($row = mysqli_fetch_array($result)) {

						$bel60 = 0;
						$o60_9 = 0;
						$o90to120 = 0;
						$o120 = 0;
						$unsold = 0;
						$totover90 = 0;
						$subpr = 0;


                        $qty60 = 0;

						$unsold = $row["sold"] * $row["UN_QTY"];

						if ($row["period"] <= 30) {
							$b30 = $row["sold"] * $row["UN_QTY"];
                            $qty30 = $row["UN_QTY"];
						} else {
							$b30 = 0;
                            $qty30 = 0;

						}
						if (($row["period"] > 30) and ($row["period"] <= 45)) {
							$o31to45 = $row["sold"] * $row["UN_QTY"];
                            $o31to45qty =  $row["UN_QTY"];
						} else {
							$o31to45 = 0;
                            $o31to45qty = 0;
						}

						if (($row["period"] > 45) and ($row["period"] <= 60)) {
							$o45to60 = $row["sold"] * $row["UN_QTY"];
                            $o45to60qty = $row["UN_QTY"];
						} else {
							$o45to60 = 0;
                            $o45to60qty=0;
						}

						if (($row["period"] > 60) and ($row["period"] <= 75)) {
							$o60to75 = $row["sold"] * $row["UN_QTY"];
						} else {
							$o60to75 = 0;
						}

						if (($row["period"] > 75) and ($row["period"] <= 90)) {
							$o75to90 = $row["sold"] * $row["UN_QTY"];
						} else {
							$o75to90 = 0;
						}

						if (($row["period"] > 90) and ($row["period"] <= 120)) {
							$o90to120 = $row["sold"] * $row["UN_QTY"];
						} else {
							$o90to120 = 0;
						}

						if ($row["period"] > 120) {
							$o120 = $row["sold"] * $row["UN_QTY"];
						} else {
							$o120 = 0;
						}

						$totover90 = $o120 + $o90to120;

						//if Sum ({@unsold}, {ado.brand})>0 then
						//Sum ({@totOver90}, {ado.brand})/Sum ({@unsold}, {ado.brand})*100

						if ($unsold > 0) {
							$subpr = $totover90 / $unsold * 100;
						}

						//if Sum ({@unsold})>0 then
						//Sum ({@totOver90})/Sum ({@unsold})*100
						if ($unsold > 0) {
							$totpr = ($totover90) / $unsold * 100;
						}

						$bel60 = $b30 + $o31to45 + $o45to60;

						$o60_90 = $o60to75 + $o75to90;

						//$result =mysqli_query($GLOBALS['dbinv'],$sql) ;
						//while($row = mysqli_fetch_array($result)){

						$totbel60 = $totbel60 + $bel60;
						$toto60_90 = $toto60_90 + $o60_90;
						$toto90to120 = $toto90to120 + $o90to120;
						$toto120 = $toto120 + $o120;
						$totunsold = $totunsold + $unsold;
						$tottotover90 = $tottotover90 + $totover90;


                        $qty60 =$qty30+ $o31to45qty +$o45to60qty;



						//$totsubpr=$totsubpr+$subpr;
						//echo "<tr>
						//<td>".$row1["brand"]."</td><td>".number_format($bel60, 2, ".", ",")."</td><td>".number_format($o60_90, 2, ".", ",")."</td><td>".number_format($o90to120, 2, ".", ",")."</td><td>".number_format($o120, 2, ".", ",")."</td><td>".number_format($unsold, 2, ".", ",")."</td><td>".number_format($totover90, 2, ".", ",")."</td><td>".number_format($subpr, 2, ".", ",")."</td></tr>";
					}

					if ($totunsold > 0) {
						$totsubpr = $tottotover90 / $totunsold * 100;
					}

					echo "<tr>
<td>" . $row1["brand"] . "</td><td>" . number_format($totbel60, 2, ".", ",") . "</td><td>" . number_format($toto60_90, 2, ".", ",") . "</td><td>" . number_format($toto90to120, 2, ".", ",") . "</td><td>" . number_format($toto120, 2, ".", ",") . "</td><td>" . number_format($totunsold, 2, ".", ",") . "</td><td>" . number_format($tottotover90, 2, ".", ",") . "</td><td>" . number_format($totsubpr, 2, ".", ",") . "</td></tr>";

					$all_totbel60 = $all_totbel60 + $totbel60;
					$all_toto60_90 = $all_toto60_90 + $toto60_90;
					$all_toto90to120 = $all_toto90to120 + $toto90to120;
					$all_toto120 = $all_toto120 + $toto120;
					$all_totunsold = $all_totunsold + $totunsold;
					$all_tottotover90 = $all_tottotover90 + $tottotover90;
				}

				if ($all_totunsold > 0) {
					$all_totsubpr = $all_tottotover90 / $all_totunsold * 100;
				}

				echo "<tr>
<td>&nbsp;</td>
<td>" . $qty60 ." -" . number_format($all_totbel60, 2, ".", ",") . "</td>
<td><b>" . number_format($all_toto60_90, 2, ".", ",") . "</b></td>
<td><b>" . number_format($all_toto90to120, 2, ".", ",") . "</b></td>
<td><b>" . number_format($all_toto120, 2, ".", ",") . "</b></td>
<td><b>" . number_format($all_totunsold, 2, ".", ",") . "</b></td>
<td><b>" . number_format($all_tottotover90, 2, ".", ",") . "</b></td>
<td><b>" . number_format($all_totsubpr, 2, ".", ",") . "</b></td></tr>";
			}
			?>
	</body>
</html>
