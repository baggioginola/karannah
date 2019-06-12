<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 19/feb/2017
 * Time: 14:35
 */
function getPrice($array = array())
{
    if (!$array) {
        return false;
    }

    $total = $array['precio'] * $array['tipo_cambio'];
    $total = $total - ($total * ($array['descuento'] / 100));

    return number_format($total, 2, '.', '');
}