<?php
/**
 * Created by PhpStorm.
 * User: countzero
 * Date: 11/04/2018
 * Time: 21:01
 */



function currency($number) {
    return number_format($number, 2, ".", "");
}