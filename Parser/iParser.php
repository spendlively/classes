<?php
namespace Parser;


interface iParser {

    /**
     * Возвращает данные по запросу $query
     *
     * @param $query
     * @return mixed
     */
    public function getData($query);
} 