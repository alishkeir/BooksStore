<?php

namespace SzamlaAgent\Header;

use SzamlaAgent\SzamlaAgentException;
use SzamlaAgent\SzamlaAgentUtil;

/**
 * Sztornó nyugta fejléc
 */
class ReverseReceiptHeader extends ReceiptHeader
{
    /**
     * XML-ben kötelezően kitöltendő mezők
     *
     * @var array
     */
    protected $requiredFields = ['receiptNumber'];

    /**
     * Sztornó nyugta fejléc létrehozása
     * Beállítja a nyugta fejlécének alapértelmezett adatait
     *
     * @param  string  $receiptNumber nyugtaszám
     */
    public function __construct($receiptNumber = '')
    {
        parent::__construct($receiptNumber);
        $this->setReverseReceipt(true);
    }

    /**
     * Ellenőrizzük a mező típusát
     *
     *
     * @return string
     *
     * @throws SzamlaAgentException
     */
    public function checkField($field, $value)
    {
        if (property_exists(get_parent_class($this), $field) || property_exists($this, $field)) {
            $required = in_array($field, $this->getRequiredFields());
            switch ($field) {
                case 'receiptNumber':
                case 'pdfTemplate':
                case 'callId':
                    SzamlaAgentUtil::checkStrField($field, $value, $required, __CLASS__);
                    break;
            }
        }

        return $value;
    }
}
