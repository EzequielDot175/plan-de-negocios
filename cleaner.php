<?php
require_once("../core_nufarm/libs.php");


$pn = new PlanDeNegocios();
$duplicados = $pn->query("SELECT * FROM facturacion ORDER BY id ASC")->fetchAll();

$collection1 = array();
$collection2 = array();
$collection3 = array();

foreach ($duplicados as $kdupl => $vdupl) {
    if(!in_array($vdupl->id_vendedor,$collection1)){
        $collection1[$vdupl->id_vendedor]  = array();
    }
}

foreach ($duplicados as $kdupl => $vdupl) {
    $collection1[$vdupl->id_vendedor][$vdupl->id_user] = $vdupl;
}
/*
foreach ($collection1 as $kdupl => $vdupl) {
       echo "<pre>";
       print_r($vdupl);
       echo "<pre>";
       die;
}
*/
echo "<pre>";
print_r($collection1);
echo "<pre>";

/*
echo "<pre>";
print_r($duplicados);
echo "<pre>";
die;
*/
/**
 * Created by PhpStorm.
 * User: dot175
 * Date: 05/11/2015
 * Time: 10:47 AM
 */