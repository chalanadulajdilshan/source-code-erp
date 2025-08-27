<?php

include_once(dirname(__FILE__) . '/Database.php');
include_once(dirname(__FILE__) . '/User.php');
include_once(dirname(__FILE__) . '/UserType.php');
include_once(dirname(__FILE__) . '/DefaultData.php');
include_once(dirname(__FILE__) . '/Message.php');
include_once(dirname(__FILE__) . '/Upload.php');
include_once(dirname(__FILE__) . '/Helper.php');
include_once(dirname(__FILE__) . '/ItemMaster.php');
include_once(dirname(__FILE__) . '/Bank.php');
include_once(dirname(__FILE__) . '/Branch.php');
include_once(dirname(__FILE__) . '/BrandCategory.php');
include_once(dirname(__FILE__) . '/Brand.php');
include_once(dirname(__FILE__) . '/CategoryMaster.php');
include_once(dirname(__FILE__) . '/GroupMaster.php');
include_once(dirname(__FILE__) . '/CompanyProfile.php');
include_once(dirname(__FILE__) . '/Country.php');
include_once(dirname(__FILE__) . '/StockType.php');
include_once(dirname(__FILE__) . '/DepartmentMaster.php');
include_once(dirname(__FILE__) . '/CustomerCategory.php');
include_once(dirname(__FILE__) . '/District.php');
include_once(dirname(__FILE__) . '/Province.php');
include_once(dirname(__FILE__) . '/CustomerMaster.php');
include_once(dirname(__FILE__) . '/PaymentType.php');
include_once(dirname(__FILE__) . '/SalesInvoice.php');
include_once(dirname(__FILE__) . '/SalesInvoiceItem.php');
include_once(dirname(__FILE__) . '/PageCategory.php');
include_once(dirname(__FILE__) . '/Pages.php');
include_once(dirname(__FILE__) . '/Permission.php');
include_once(dirname(__FILE__) . '/UserPermission.php');
include_once(dirname(__FILE__) . '/MarketingExecutive.php');
include_once(dirname(__FILE__) . '/Quotation.php');
include_once(dirname(__FILE__) . '/QuotationItem.php');
include_once(dirname(__FILE__) . '/VatType.php');
include_once(dirname(__FILE__) . '/LabourMaster.php');
include_once(dirname(__FILE__) . '/LabourType.php');
include_once(dirname(__FILE__) . '/ExpenseTypeMaster.php');
include_once(dirname(__FILE__) . '/SubCategoryMaster.php');
include_once(dirname(__FILE__) . '/SizeMaster.php');
include_once(dirname(__FILE__) . '/BeltMaster.php');
include_once(dirname(__FILE__) . '/DesignMaster.php');
include_once(dirname(__FILE__) . '/StockTransaction.php');
include_once(dirname(__FILE__) . '/StockAdjustmentType.php');
include_once(dirname(__FILE__) . '/VehicleBrand.php');
include_once(dirname(__FILE__) . '/VehicleModel.php');
include_once(dirname(__FILE__) . '/InvoiceRemark.php');
include_once(dirname(__FILE__) . '/DiscountType.php');
include_once(dirname(__FILE__) . '/SupplierDiscount.php');
include_once(dirname(__FILE__) . '/Bank.php');
include_once(dirname(__FILE__) . '/EmployeeMaster.php');
include_once(dirname(__FILE__) . '/ArnMaster.php');
include_once(dirname(__FILE__) . '/ArnItem.php');
include_once(dirname(__FILE__) . '/DocumentTracking.php');
include_once(dirname(__FILE__) . '/PurchaseType.php');
include_once(dirname(__FILE__) . '/CreditPeriod.php');
include_once(dirname(__FILE__) . '/SalesType.php');
include_once(dirname(__FILE__) . '/PurchaseOrder.php');
include_once(dirname(__FILE__) . '/PurchaseOrderItem.php');
include_once(dirname(__FILE__) . '/StockMaster.php');
include_once(dirname(__FILE__) . '/AuditLog.php');
include_once(dirname(__FILE__) . '/PurchaseReturn.php');
include_once(dirname(__FILE__) . '/Dag.php');
include_once(dirname(__FILE__) . '/DagItem.php');
include_once(dirname(__FILE__) . '/StockItemTmp.php');
include_once(dirname(__FILE__) . '/DagCompany.php');
include_once(dirname(__FILE__) . '/ArnQrGenaretor.php');
include_once(dirname(__FILE__) . '/Expenses.php');
include_once(dirname(__FILE__) . '/NonPermissionPage.php');
include_once(dirname(__FILE__) . '/InvoicePayments.php');

session_start();
function dd($data)
{

    var_dump($data);

    exit();
}

function redirect($url)
{

    $string = '<script type="text/javascript">';

    $string .= 'window.location = "' . $url . '"';

    $string .= '</script>';



    echo $string;

    exit();
}
