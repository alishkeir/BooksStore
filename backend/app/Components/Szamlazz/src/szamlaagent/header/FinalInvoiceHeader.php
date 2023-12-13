<?php

namespace SzamlaAgent\Header;

use SzamlaAgent\Document\Invoice\Invoice;

/**
 * Végszámla fejléc
 */
class FinalInvoiceHeader extends InvoiceHeader
{
    /**
     * @param  int  $type
     *
     * @throws \SzamlaAgent\SzamlaAgentException
     */
    public function __construct($type = Invoice::INVOICE_TYPE_P_INVOICE)
    {
        parent::__construct($type);
        $this->setFinal(true);
    }
}
