<?php

namespace SzamlaAgent\Item;

/**
 * Díjbekérő tétel
 */
class ProformaItem extends InvoiceItem
{
    /**
     * Díjbekérő tétel létrehozása
     *
     * @param  string  $name          tétel név
     * @param  float  $netUnitPrice  nettó egységár
     * @param  float  $quantity      mennyiség
     * @param  string  $quantityUnit  mennyiségi egység
     * @param  string  $vat           áfatartalom
     */
    public function __construct($name, $netUnitPrice, $quantity = self::DEFAULT_QUANTITY, $quantityUnit = self::DEFAULT_QUANTITY_UNIT, $vat = self::DEFAULT_VAT)
    {
        parent::__construct($name, $netUnitPrice, $quantity, $quantityUnit, $vat);
    }
}
