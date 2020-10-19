<?php
//$_SERVER["DOCUMENT_ROOT"]='/home/bitrix/www';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
try {
    $APPLICATION->IncludeComponent("litebox.z1:staff", ".default", array(), false);
}
catch (\Throwable $exception)
{
    print_r($exception->getMessage().$exception->getTraceAsString());
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
