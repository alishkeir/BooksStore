<?php

namespace Alomgyar\Product_movements;

use App\Http\Controllers\Controller;
use TCPDF;

class ProductMovementController extends Controller
{
    public function index()
    {
        return view('product_movements::index');
    }

    public function create()
    {
        return view('product_movements::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $product_movement = ProductMovement::create(request()->all());

        $product_movement->addMediaFromDisk('/product_movements/covers/'.$data['cover'], 'public')
            ->toMediaCollection('cover');

        foreach ($data['gallery'] as $gallery) {
            $product_movement->addMediaFromDisk('/product_movements/gallery/'.$gallery, 'public')
                ->toMediaCollection('gallery');
        }

        session()->flash('success', 'Product_movement sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $product_movement, 'return_url' => route('product_movements.index')]);
//        return redirect()->route('product_movements.index')->with('success', 'Product_movement sikeresen létrehozva!');
    }

    public function edit(ProductMovement $product_movement)
    {
        return view('product_movements::edit', [
            'model' => $product_movement,
        ]);
    }

    public function update(ProductMovement $product_movement)
    {
        $data = $this->validateRequest();
        $product_movement->update($data);

        if (! empty($data['cover'])) {
            $product_movement->clearMediaCollection('cover');
            $product_movement->addMediaFromDisk('/product_movements/covers/'.$data['cover'], 'public')
                ->toMediaCollection('cover');
        }

        if (! empty($data['gallery'])) {
            $product_movement->clearMediaCollection('gallery');
            foreach ($data['gallery'] as $gallery) {
                $product_movement->addMediaFromDisk('/product_movements/gallery/'.$gallery, 'public')
                    ->toMediaCollection('gallery');
            }
        }

        session()->flash('success', 'Product_movement sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $product_movement, 'return_url' => route('product_movements.index')]);
//        return redirect()->route('product_movements.index', ['product_movement' => $product_movement->id]);
    }

    public function show(ProductMovement $product_movement)
    {
        return view('product_movements::show', [
            'model' => $product_movement,
        ]);
    }

    public function destroy(ProductMovement $product_movement)
    {
        $product_movement->delete();

        return redirect()->route('product_movements.index')->with('success', 'Product_movement '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    }

    public function export($productMovement)
    {
        $pm = ProductMovement::find($productMovement);
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($pm->reference_nr.' sz. szállító');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
//        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set font
        $pdf->SetFont('dejavusans', '', 10);

        // add a page
        $pdf->AddPage();

        // output the HTML content
        if ($pm->destination_type === ProductMovement::DESTINATION_TYPE_MERCHANT) {
            $pdf->writeHTML(view('exports.product-movements-merchant', [
                'productMovement' => $pm,
            ]), true, false, true, false, '');
        } else {
            $pdf->writeHTML(view('exports.product-movements', [
                'productMovement' => $pm,
            ]), true, false, true, false, '');
        }

        // reset pointer to the last page
        $pdf->lastPage();

        //Close and output PDF document
        $pdf->Output($pm->reference_nr.'-szallito.pdf', 'D');
    }
}
