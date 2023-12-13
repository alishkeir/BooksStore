<?php

namespace Alomgyar\Orders;

use Alomgyar\PickUpPoints\Model\PickUpPoint;
use Alomgyar\Product_movements\ProductMovement;
use App\Components\Szamlazz\Invoice;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders::index');
    }

    public function orderItemsOk()
    {
        return view('orders::orderitems-ok');
    }

    public function orderItemsNo()
    {
        return view('orders::orderitems-no');
    }

    public function orderItemsAlmost()
    {
        return view('orders::orderitems-almost');
    }

    public function orderItems()
    {
        return view('orders::orderitems-items');
    }

    public function create()
    {
        return view('orders::create');
    }

    public function edit(Order $order)
    {
        return view('orders::edit', [
            'model' => $order,
            'onlyEbook' => $order->onlyEbook(),
        ]);
    }

    public function update(Order $order)
    {
        $data = $this->validateRequest();
        $order->update($data);

        session()->flash('success', 'Megrendelés sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $order, 'return_url' => route('orders.index')]);
    }

    public function show(Order $order)
    {
        return view('orders::show', [
            'model' => $order,
            'onlyEbook' => $order->onlyEbook(),
        ]);
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    }

    public function getinvoicepdf(Request $request)
    {
        $file = app_path().'/Components/Szamlazz/pdf/'.$request->id.'.pdf';

        if (is_file($file)) {
            header('Content-Type: application/pdf');
            header('Content-Length:'.filesize($file));
            if ($request->type == 'download') {
                header("Content-Disposition: attachment; filename=$request->id.pdf");
            }
            readfile($file);
            exit();
        }
    }

    public function setstatus(Request $request)
    {
        if (! isset($request->id) || ! isset($request->status)) {
            abort(419);
        }

        $invoiceType = $request->invoice ?? false;

        $model = Order::where('id', $request->id)->first();

        $silentMode = $request->silent ?? false;

        if ($model->setStatus($request->status, $silentMode, $invoiceType)) {
            $response[$model->id] = true;
        } else {
            $response[$model->id] = false;
        }

        return $response ?? [];
    }

    public function deleteOrder(Request $request)
    {
        if (! isset($request->id) || ! isset($request->status)) {
            abort(419);
        }
        $order = Order::where('id', $request->id)->first();
        if ($request->status == Order::STATUS_DELETED) {

            if (!empty($order->invoice_url)) {
                $storno = new Invoice($order->payment_token);
                $storno->createStorno();
            }

            $productMovement = DB::table('product_movements')
                ->whereIn('destination_type', [
                    ProductMovement::DESTINATION_TYPE_WEBSHOP_ORDER,
                    ProductMovement::DESTINATION_TYPE_SHOP_ORDER
                ])
                ->where('source_type','shop')
                ->where('is_canceled',0)
                ->where('destination_id', $order->id)
                ->count();

            if($productMovement > 0){
                $order->updateStock(true);
            }

            $order->status = Order::STATUS_DELETED;
            $order->save();

            return true;
        }

        return $response ?? [];
    }

    public function setPaymentStatus(Request $request)
    {
        if (! isset($request->id)) {
            abort(419);
        }

        $model = Order::where('id', $request->id)->first();

        if ($model->payment_status == Order::STATUS_PAYMENT_PAID) {
            $model->payment_status = Order::STATUS_PAYMENT_WAITING;
            $model->payment_date = null;
        } else {
            $model->payment_status = Order::STATUS_PAYMENT_PAID;
            if (isset($request->payment_date)) {
                $model->payment_date = $request->payment_date;
            }
        }

        if ($model->save()) {
            $response[$model->id] = true;
        } else {
            $response[$model->id] = false;
        }

        return $response ?? [];
    }

    public function searchPickupPoint(Request $request)
    {
        $term      = trim($request->q);
        $pickupPoints = PickUpPoint::select([
                'provider',
                'provider_name',
                'provider_id',
                'name',
            ])
            ->where('status', 1)
            ->where('provider_name', 'like', '%' . $term . '%')
            ->orWhere('name', 'like', '%' . $term . '%')
            // ->orWhere('provider', 'like', '%' . $term . '%')
            // ->orWhere('provider', 'like', '%' . $term . '%')
            // ->orWhere('provider', 'like', '%' . $term . '%')
            ->paginate(25);

        return response([
            'results'    => PickupPointSelectResource::collection($pickupPoints),
            'pagination' => [
                'more' => $pickupPoints->currentPage() !== $pickupPoints->lastPage(),
            ],
        ]);
    }
}
