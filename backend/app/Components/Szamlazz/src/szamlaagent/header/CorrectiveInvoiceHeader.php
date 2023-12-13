<?php

namespace SzamlaAgent\Header;

use SzamlaAgent\Document\Invoice\Invoice;

/**
 * Helyesbítő számla fejléc
 */
class CorrectiveInvoiceHeader extends InvoiceHeader
{
    /**
     * @param  int  $type
     *
     * @throws \SzamlaAgent\SzamlaAgentException
     */
    public function __construct($type = Invoice::INVOICE_TYPE_P_INVOICE)
    {
        parent::__construct($type);
        $this->setCorrective(true);
    }
}
