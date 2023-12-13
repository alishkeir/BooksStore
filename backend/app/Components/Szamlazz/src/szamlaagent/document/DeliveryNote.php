<?php

namespace SzamlaAgent\Document;

use SzamlaAgent\Document\Invoice\Invoice;
use SzamlaAgent\Header\DeliveryNoteHeader;

/**
 * Szállítólevél segédosztály
 */
class DeliveryNote extends Invoice
{
    /**
     * Szállítólevél kiállítása
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(null);
        // Alapértelmezett fejléc adatok hozzáadása
        $this->setHeader(new DeliveryNoteHeader());
    }
}
