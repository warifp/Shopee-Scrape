<?php
error_reporting(0);
/**
 * Author  : Wahyu Arif Purnomo
 * Name    : Shopee Scrape
 * Version : 1.0
 * Update  : 04 Desember 2019
 * 
 * If you are a reliable programmer or the best developer, please don't change anything.
 * If you want to be appreciated by others, then don't change anything in this script.
 * Please respect me for making this tool from the beginning.
 */
unlink('hasil/json/results.json');
require __DIR__ . '/vendor/autoload.php';
include "modules/function.php";

use \Curl\Curl;

$curl = new Curl();
$banner = "
@@@@@@@@@@@@((((((@@@@@@@@@@@ 
@@@@@@@@@@((#@@@@((%@@@@@@@@@
@@@@@@@@@((@@@@@@@@(&@@@@@@@@
@@@@@@@@#(@@@@@@@@@&(%@@@@@@@
@(((((((((((((//(((((((((((((
@((((((((((  ((((/ (((((((((( | AUTHOR : WAHYU ARIF PURNOMO
@(((((((((( ((((((((((((((((( | NAME   : SHOPEE SCRAPE
@((((((((((/  /(((((((((((((( | VERSION: 1.0
@((((((((((((((/  .(((((((((( | UPDATE : 4 DESEMBER 2019
@(((((((((((((((((. ((((((((# | If you are a reliable programmer or the best developer, please don't change anything.
@#(((((((( (((((((. ((((((((@
@@(((((((((,       (((((((((@
@@((((((((((((((((((((((((((@
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
";
print $banner;
echo "\nMau cari apa? ";
$search = trim(fgets(STDIN));

echo "Berapa? ";
$totalSearch = trim(fgets(STDIN));

$getSearch = getSearch($curl, $search, $totalSearch);
if($getSearch->error == null) {
    $no = 0;
    for ($x = 0; $x < $totalSearch; $x++) {
        $no++;
        $itemID = $getSearch->items[$x]->itemid;
        $shopID = $getSearch->items[$x]->shopid;

        $getItem = getItem($curl, $itemID, $shopID);
            $nameItem = $getItem->item->name;
            $priceItem = $getItem->item->price;
            //$diskonItem = $getItem->item->discount;
            $statusItem = $getItem->item->item_status;
            $lokasiToko = $getItem->item->shop_location;
            $imageItem = 'https://cf.shopee.co.id/file/' . $getItem->item->image;

            if($statusItem == "normal") {
                $status = "Tersedia";
            } else {
                $status = "Tidak Tersedia";
            }
            echo $no . '. [' . $status . '] [' . $priceItem . '] [' . $nameItem . '] [' . $lokasiToko . '] [' . $imageItem . "] \n";

            $export['data'][] = array(
                    'no' => $no,
                    'status' => $status,
                    'nama' => $nameItem,
                    'harga' => $priceItem,
                    'lokasi' => $lokasiToko,
                    'foto' => $imageItem,
                    'status' => $status
                );
            //echo json_encode($export) . "\n";
            if (($id = fopen('hasil/json/results.json', 'wb'))) {
                fwrite($id, json_encode($export));
                fclose($id);
            }
        }
    } 
    ob_start();
    htmlConverter();
    $htmlResults = ob_get_contents();
    ob_end_clean(); 
    file_put_contents("hasil/html/results.html", $htmlResults);
    
    echo "\n\e[0;32mSuccessfully scrape data from Shopee.\e[0m\n\n";
    echo "\e[0;31mFile saved :\n";
    echo "JSON : hasil/json/results.json\n";
    echo "HTML : hasil/html/results.html\e[0m";
    
/**
 * Author  : Wahyu Arif Purnomo
 * Name    : Shopee Scrape
 * Version : 1.0
 * Update  : 04 Desember 2019
 * 
 * If you are a reliable programmer or the best developer, please don't change anything.
 * If you want to be appreciated by others, then don't change anything in this script.
 * Please respect me for making this tool from the beginning.
 */
?>