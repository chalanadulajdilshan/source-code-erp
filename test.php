<!DOCTYPE html>
<html>
<head>
    <title>Print 1 to 5</title>
    <style>
        .page {
            page-break-after: always;
            font-size: 100px;
            text-align: center;
            margin-top: 200px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">Print Pages</button>
</div>

<?php
for ($i = 1; $i <= 5; $i++) {
    echo "<div class='page'>$i</div>";
}
?>

</body>
</html>
