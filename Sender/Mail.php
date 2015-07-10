<?php
namespace Sender;

/**
 * Class Mail
 * @package Sender
 *
 * Пример 1 (Отправка одному получателю):
 * $mailer = new \Sender\Mail();
 * $mailer->setParams(array(
 *     "subject" => "Ваша заявка принята!",
 *     "message" => "Добрый день, уважаемый клиент! Ваша заявка была успешно принята!",
 *     "to" => "spendlively@yandex.ru",
 *     "from" => "spendlively@mim54.ru",
 * ));
 * $mailer->send();
 *
 * Пример 2 (Пакетная отправка нескольким получателям):
 * $mailer = new \Sender\Mail();
 * $mailer->setParams(array(
 *     "subject" => "Ваша заявка принята!",
 *     "message" => "Добрый день, уважаемый клиент! Ваша заявка была успешно принята!",
 *     "to" => array(
 *          "spendlively@mail.ru",
 *          "spendlively@gmail.com",
 *          "spendlively@yandex.ru",
 *          "spendlively@ngs.ru",
 *          "spendlively2@rambler.ru",
 *          "spendlively@yahoo.com"
 *      ),
 *     "from" => "spendlively@mim54.ru",
 * ));
 * $mailer->send();
 *
 */
class Mail{

    /**
     * Текст сообщения письма
     *
     * @var string
     */
    public $message = "";

    /**
     * Текст темы сообщения письма
     *
     * @var string
     */
    public $subject = "";

    /**
     * Адреса получателей
     *
     * @var array
     */
    public $to = array();

    /**
     * Адрес отправителя
     *
     * @var string
     */
    public $from = "";

    /**
     * Кодировка сообщения
     *
     * @var string
     */
    public $charset = "utf-8";

    public function __construct($params = array()){

        if(!empty($params)){
            $this->setParams($params);
        }
    }

    /**
     * Устанавливает параметры конфигурации
     *
     * @param array $params
     */
    public function setParams($params = array()){

        if(isset($params['message'])){
            $this->setMessage($params['message']);
        }

        if(isset($params['subject'])){
            $this->setSubject($params['subject']);
        }

        if(isset($params['to'])){
            $this->setTo($params['to']);
        }

        if(isset($params['from'])){
            $this->setFrom($params['from']);
        }

        if(isset($params['charset'])){
            $this->setCharset($params['charset']);
        }
    }

    /**
     * Сеттер для текста сообщения $this->message
     *
     * @param null $message
     */
    public function setMessage($message = null){

        if($message){
            $this->message = (string)$message;
        }
    }

    /**
     * Сеттер для темы сообщения
     *
     * @param null $subject
     */
    public function setSubject($subject = null){

        if($subject){
            $this->subject = (string)$subject;
        }
    }

    /**
     * Сеттер для адреса доставки сообщения $this->to
     *
     * @param null $to
     */
    public function setTo($to = null){

        if($to){
            if(is_string($to)){
                $this->to = array($to);
            }
            elseif(is_array($to) && !empty($to)){
                foreach($to as $t){
                    if(!in_array($t, $this->to)){
                        $this->to[] = (string)$t;
                    }
                }
            }
        }
    }

    /**
     * Добавляет адреса доставки сообщения к уже существующим в $this->to
     *
     * @param null $to
     */
    public function addTo($to = null){

        if($to){
            if(is_string($to) && !in_array($to, $this->to)){
                $this->to[] = $to;
            }
            elseif(is_array($to) && !empty($to)){
                foreach($to as $t){
                    if(!in_array($t, $this->to)){
                        $this->to[] = (string)$t;
                    }
                }
            }
        }
    }

    /**
     * Сеттер для адреса отправителя сообщения $this->from
     *
     * @param null $from
     */
    public function setFrom($from = null){

        if($from){
            $this->from = (string)$from;
        }
    }

    /**
     * Сеттер для параметра кодировка
     *
     * @param $charset
     */
    public function setCharset($charset){

        if($charset){
            $this->charset = (string)$charset;
        }
    }

    /**
     * Проверка, правильно ли заполнены параметры сообщения
     *
     * @return bool
     */
    public function validate(){

        return $this->message && $this->subject && !empty($this->to) && $this->from;
    }

    /**
     * Геттер для текста сообщения $this->message
     *
     * @return string
     */
    public function getMessage(){

        return wordwrap((string)$this->message, 70, "\r\n");
    }

    /**
     * Геттер для темы сообщения $this->subject
     *
     * @return string
     */
    public function getSubject(){

        return $this->subject;
    }

    /**
     * Геттер для адреса получателя сообщения $this->to
     *
     * @return array
     */
    public function getTo(){

        return $this->to;
    }

    /**
     * Геттер для адреса отправителя сообщения $this->from
     *
     * @return string
     */
    public function getFrom(){

        return $this->from;
    }

    /**
     * Геттер для кодировки сообщения $this->charset
     *
     * @return string
     */
    public function getCharset(){

        return $this->charset;
    }

    /**
     * Возвращает заголовки сообщения
     *
     * @return string
     */
    public function getHeaders(){

        $headers = "From: " . $this->getFrom() . "\r\n";
        $headers .= "Reply-To: " . $this->getFrom() . "\r\n";
        $headers .= "Content-type: text/html; charset=" . $this->getCharset() . "\r\n";

        return $headers;
    }

    /**
     * Отправка сообщения
     *
     * @throws \Exception
     */
    public function send(){

        if($this->validate()){

            foreach($this->getTo() as $to){
                mail($to, $this->getSubject(), $this->getMessage(), $this->getHeaders());
            }
        }
        else{
            throw new \Exception('Не все параметры сообщения оформлены правильно!');
        }
    }
}
