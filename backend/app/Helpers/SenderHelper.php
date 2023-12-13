<?php
/*
Author: Hódi
Date: 2021. 06. 25. 13:27
Project: alomgyar-webshop-be
*/

namespace App\Helpers;

class SenderHelper
{
    public static function getSenderGroupID()
    {
        if (request('store') == config('pam.stores.alomgyar')) {
            return config('pam.sender.alomgyar_group');
        }

        if (request('store') == config('pam.stores.olcsokonyvek')) {
            return config('pam.sender.olcsokonyvek_group');
        }
    }
}
