<!DOCTYPE html>
<html>

<head>
    <title>Static Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .invoice {
            width: 21.8cm;
            height: 20.93cm;
            padding: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table td {
            padding: 5px;
            border: 1px solid #ccc;
            text-align: right;
        }



        .summary {
            width: 100%;
            margin-top: 30px;
        }

        .summary td {
            padding: 5px;
        }

        .total-row {
            font-weight: bold;
            transform: translateY(-30px);
            position: relative;
            z-index: 10;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="invoice">
        <!-- Item Table -->
        <table class="table">
            <tr>
                <td colspan="5" style="text-align: right;">
                    <div><?php echo !empty($txttaxinv) ? $txttaxinv : "-"; ?></div>
                    <div style="margin-top: 5px;"><?php echo !empty($txtcomvat) ? $txtcomvat : "-"; ?></div>
                    <div style="margin-top: 5px;"><?php echo !empty($txtoursvat) ? $txtoursvat : "-"; ?></div>
                </td>


                <td colspan="2"> </td>
            </tr>
            <tr style="height:25px">
                <td colspan="8"> </td>
            </tr>
            <?php if (!empty($delivery_date) && $delivery_date != '0000-00-00'): ?>
                <tr>
                    <td colspan="7">
                        D/M - <?php echo $delivery_date; ?>
        </div>
        </td>
        </tr>
    <?php endif; ?>
    <?php if (!empty($shdate)): ?>
        <tr>
            <td colspan="7">S/D : <?php echo $shdate; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td rowspan="2" colspan="3">
            <?php echo $rtxtSupCode; ?>
            <?php echo $rtxtSupName; ?><br>
            <?php echo $txtadd; ?><br>
            <?php echo $txtcusvat; ?><br>
            <?php echo $txtcussavat; ?>

        </td>

        <td colspan="2" class="right"><?php echo $rtxtordno; ?></td>
        <?php if (!empty($invno)): ?>
            <td colspan="2" class="right"> <?php echo $invno; ?></td>
        <?php endif; ?>
    </tr>
    <tr>

        <td colspan=" 2"> <?php echo $rtxtrep; ?></td>
        <td colspan="2"><?php echo $rtxtdate; ?></td>

    </tr>

    </table>
    <div style="height: 300px; overflow-y: auto;margin-top:30px">
        <table class="table">
            <tbody>
                <?php
                $i = 1;
                $totsuntot = 0;

                $sql1 = "Select * from s_invo where REF_NO='" . $invno . "' order by PRICE DESC";

                $result1 = mysqli_query($GLOBALS['dbinv'], $sql1);
                while ($row1 = mysqli_fetch_array($result1)) {
                    $sql_part = "Select * from s_mas where STK_NO='" . $row1["STK_NO"] . "'";
                    $result_part = mysqli_query($GLOBALS['dbinv'], $sql_part);
                    $row_part = mysqli_fetch_array($result_part);

                    if (($VAT_per == "1") or ($VAT_per == "2")) {
                        $vatr = 100 + $row["GST"];
                        $PRICE = $row1["PRICE"] / $vatr * 100;
                    } else {
                        $PRICE = $row1["PRICE"];
                    }
                    ?>
                    <tr>
                        <td><?php echo $row1["STK_NO"] ?></td>
                        <td><?php echo $row1["DESCRIPT"] ?></td>
                        <td><?php echo substr(trim($row_part["PART_NO"]), 0, 15) ?></td>
                        <td><?php echo number_format($PRICE, 2, ".", ",") ?></td>
                        <td><?php echo number_format($row1["QTY"], 0, ".", ",") ?></td>

                        <?php
                        if ($row['maindepartment'] == 'LUBRICANT') {
                            echo "<td  align=\"right\"><span class=\"\">" . number_format($row1["DIS_per"], 3, ".", ",") . "</span></td>";
                            $discount1 = $PRICE * $row1["QTY"] * $row1["DIS_per"] / 100;
                            $subtot = ($PRICE * $row1["QTY"]) - $discount1;
                        } else {
                            echo "<td >" . number_format($row1["Print_dis1"], 3, ".", ",") . "</td>";
                            $discount1 = $PRICE * $row1["QTY"] * $row1["Print_dis1"] / 100;
                            $subtot = ($PRICE * $row1["QTY"]) - $discount1;
                        }
                        ?>
                        <td><?php echo number_format($subtot, 2, ".", ",") ?></td>

                    </tr>
                    <?php
                    $totsuntot = $totsuntot + $subtot;
                    $i = $i + 1;

                }




                $txtdis2 = $totsuntot / 100 * $row["DIS1"];
                ?>

            </tbody>
        </table>
    </div>
    <!-- Summary -->
    <table class="table">
        <tbody>
            <?php

            if ($txtdis2 >= 0):
                ?>
                <tr>
                    <td colspan="6">
                        <?php
                        if (!empty($txtspdis)) {
                            echo $txtspdis;
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                    <td style="width: 80px;">
                        <?php
                        if ($txtdis2 > 0) {
                            echo number_format($txtdis2, 2, ".", ",");
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php
            if ($txtsubtot >= 0):
                ?>
                <tr>
                    <td colspan="6">
                        <?php
                        if (!empty($txtsubtotdes)) {
                            echo $txtsubtotdes;
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                    <td style="  width: 80px;">
                        <?php
                        if ($txtsubtot > 0 || $txtsubtot === 0) {
                            echo number_format($txtsubtot, 2, ".", ",");
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php
            if ($RTXVATAMU >= 0):
                ?>
                <tr>
                    <td colspan="6">
                        <?php
                        if (!empty($RTXTVAT)) {
                            echo $RTXTVAT;
                        } else {
                            echo "-";
                        }
                        ?>

                    </td>
                    <td style="  width: 80px;">
                        <?php
                        if ($RTXVATAMU > 0 || $RTXVATAMU === 0) {
                            echo number_format($RTXVATAMU, 2, ".", ",");
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                </tr>
            <?php endif; ?>

    </table>

    <table class="table">
        <tbody>
            <tr>
                <td colspan="6"> </td>
                <td style="  width: 80px;">
                    <?php
                    if ($rtxttot > 0) {
                        if ($VAT_per == 0) {

                            if ($row['CREDITNOTEVAL'] != 0) {
                                $totsuntot = $totsuntot - $row['CREDITNOTEVAL'];
                            }
                            if (round($totsuntot - $txtdis2) == round($rtxttot)) {
                                echo number_format($rtxttot, 2, ".", ",");
                            } else {
                                echo 'Error';
                                echo $rtxttot . '-' . round($totsuntot - $txtdis2);
                            }
                        } else {
                            echo number_format($rtxttot, 2, ".", ",");
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="6"></td>
                <td style="  width: 80px;"><?php echo $TXTDEP; ?></td>
            </tr>

    </table>

    <table class="table">
        <tbody>
            <tr>
                <td></td>
                <td style="text-align:center" colspan="2"><?php echo date('Y-m-d h:i:a') ?></td>

                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            <tr>
                <td></td>
                <td style="width: 20%;">
                    <?php

                    $sqlinv = "SELECT * FROM entry_log WHERE refno = '" . $invno . "' AND trnType = 'Save'";
                    $result3 = mysqli_query($GLOBALS['dbinv'], $sqlinv);
                    $row_res = mysqli_fetch_array($result3);
                    if ($row_res) {
                        echo $row_res['username'];
                    }
                    ?>
                </td>
                <td style="width: 20%;">
                    <?php

                    $sql_checkby = "SELECT invoice_checkedby FROM s_salma WHERE REF_NO='" . $invno . "'";
                    $result_checkby = mysqli_query($GLOBALS['dbinv'], $sql_checkby);
                    $row_res2 = mysqli_fetch_array($result_checkby);
                    if ($row_res2) {
                        echo $row_res2['invoice_checkedby'];
                    }
                    ?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>


    </table>

    </div>


</body>

</html>