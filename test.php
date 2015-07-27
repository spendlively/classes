<?php

include_once "Parser/iParser.php";
include_once "Parser/Gis.php";
include_once "Sender/Mail.php";

$query = "шланги пвх";
$parser = new \Parser\Gis();
$data = $parser->getData($query);

$mails = array();
if(!empty($data)){
    $iterator = new RecursiveArrayIterator($data);
    foreach(new RecursiveIteratorIterator($iterator) as $key => $value) {
        $value = trim($value);
        if(preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|ui", $value, $matches)){
            if(!empty($matches)){
                $mail = trim($matches[0]);
                if(!in_array($mail, $mails)){
                    $mails[] = $mail;
                }
            }
        }
    }
}
else{
    die('1) $data is empty');
}

if(!empty($mails)){

//    $mails = array();
    $mails[] = 'spendlively@mail.ru';
    $mails[] = 'spendlively@ngs.ru';
    $mails[] = 'spendlively@yandex.ru';
    $mails[] = 'spendlively@gmail.com';

    $message = "Добрый день, я ищу пищевой шланг пвх. \n\r";
    $message .= "Можно не армированный, простой, главное, чтобы он налез на 1/2\", с а другой стороны на 3/4\". \n\r";
    $message .= "Константин, тел. 89538026240 \n\r";

    $mailer = new \Sender\Mail();
    $mailer->setParams(array(
        "subject" => "Нужен шланг пвх",
        "message" => $message,
        "to" => $mails,
        "from" => "spendlively@mim54.ru",
    ));
    $mailer->send();
}
else{
    die('2) $mails is empty');
}
echo "<pre>";
print_r($mails);
echo "</pre>";
