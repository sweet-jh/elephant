<?php
/**
 * Created by PhpStorm.
 * User: hjing
 * Date: 3/23/17
 * Time: 3:08 PM
 */

$inputState = $argv[1];
$item = $argv[2];
$qut = $argv[3];

$handle = @fopen(dirname(__FILE__). '/input/'.item, "r");
$handleGoods = @fopen(dirname(__FILE__). '/input/'.goods, "r");
$handleTax = @fopen(dirname(__FILE__). '/input/'.tax, "r");
$resultItem = getResult($handle);
$goods = getResult($handleGoods);
$taxRate = getResult($handleTax);

echo "\r\n item price table\r\n";
foreach($resultItem as $key => $value) {
    echo '['. $key . '] - price : $ ' . $value;
}

echo "\r\n";

echo "\r\n tax table\r\n";
foreach($taxRate as $key => $value) {
    echo '['. $key . '] - tax : ' . number_format($value * 100, 2) . '%';
    echo "\r\n";
}

echo "\r\n";
$price = 0;
$price = $resultItem[$item] * $qut;
//foreach ($goods as $good => $count){
//    $price += $resultItem[$good] * $count;
//}

echo "\r\n";
echo "discount table \r\n";
echo "> $1000 and less than 5000, %5 \r\n";
echo "...\r\n";
echo "> more $50000 %15\r\n";
echo "\r\n";
function getResult($handle) {
    if ($handle) {
        while (($buffer = fgets($handle, 4096)) !== false) {
            $items = explode("  " , $buffer);
            $result[$items[0]] = $items[1];

        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    }
    return $result;

}


$price = buildTaxIn($price, $inputState, $taxRate);

$price = buildDiscountIn($price);

outPutBill($price, $inputState, $item, $qut);



function buildTaxIn($price, $state, $taxRate){
    return $price *  (1 - $taxRate[$state]);
}
function buildDiscountIn($price){
    if($price > 1000 && $price < 5000) {
        return $price * 0.97;
    }else if($price >= 5000 && $price < 7000){
        return $price * 0.95;
    }else if ($price >= 7000 && $price < 10000){
        return $price * 0.93;
    }else if($price >= 10000 && $price < 50000){
        echo "*** discount 9% ^_^ \r\n";
        return $price * 0.9;
    }else if($price >= 50000){
        echo "*** discount 15% ^_^ \r\n";
        return $price * 0.85;
    }
    return $price;
}

function outPutBill($price, $inputState, $item, $qut){
    echo "You are in $inputState \r\n";
    echo "You buy $qut $item \r\n";
    echo "Total price: ". $price;
    echo "\r\n";
}
