<?php

namespace Alomgyar\Customers;

use Alomgyar\Affiliates\Affiliate;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $model = Customer::latest()->paginate(25);

        return view('customers::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('customers::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $newCustomer = request()->all();
        $newCustomer['email_verified_at'] = date('Y-m-d H:i:s');
        $customer = Customer::create($newCustomer);

        session()->flash('success', 'Ügyfél sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $customer, 'return_url' => route('customers.index')]);
        //        return redirect()->route('customers.index')->with('success', 'Customer sikeresen létrehozva!');
    }

    public function edit(Customer $customer)
    {
        $countBillingAddresses = $customer->billingAddresses()->count();
        $countShippingAddresses = $customer->shippingAddresses()->count();
        $countComments = $customer->countComments();

        return view('customers::edit', [
            'model' => $customer,
            'countBillingAddresses' => $countBillingAddresses,
            'countShippingAddresses' => $countShippingAddresses,
            'countComments' => $countComments,
        ]);
    }

    public function update(Customer $customer)
    {
        $data = request()->all();
        $customer->refresh();
        $this->validateRequest(isset($customer->affiliate));
        $checks = ['status', 'affiliate_status'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }

        // if(isset($customer->affiliate) && $customer->affiliate->code == null)
        // {
        //     unset($data['affiliate_code']);
        // }

        if ($data['affiliate_status'] !== 0) {
            if (Auth::user()->hasRole('skvadmin')) {
                $affiliateData = [
                    'name' => $data['affiliate_name'],
                    'country' => $data['affiliate_country'],
                    'zip' => $data['affiliate_zip'],
                    'city' => $data['affiliate_city'],
                    'address' => $data['affiliate_address'],
                    'vat' => $data['affiliate_vat'],
                    'status' => $data['affiliate_status'],
                ];

                if (isset($customer->affiliate)) {
                    if ($customer->affiliate->code == null) {
                        $affiliateData['code'] = $data['affiliate_code'];
                    }
                    $customer->affiliate()->update($affiliateData);
                } else {
                    $affiliateData['code'] = $data['affiliate_code'];
                    $customer->affiliate()->save(new Affiliate($affiliateData));
                }
            }
        }
        $customer->update($data);
        session()->flash('success', 'Ügyfél sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $customer, 'return_url' => route('customers.edit', ['customer' => $customer->id])]);
        //return response()->json(['success' => true, 'model' => $customer, 'return_url' => route('customers.index')]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer '.__('messages.deleted'));
    }

    protected function validateRequest($isAffiliate = false)
    {
        $rules = [
            'email' => 'required',
            'affiliate_status' => 'required',
            'affiliate_name' => 'required_if:affiliate_status,1|nullable',
            'affiliate_country' => 'required_if:affiliate_status,1|nullable',
            'affiliate_zip' => 'required_if:affiliate_status,1|numeric|nullable',
            'affiliate_city' => 'required_if:affiliate_status,1|nullable',
            'affiliate_address' => 'required_if:affiliate_status,1|nullable',
            'affiliate_vat' => 'required_if:affiliate_status,1|nullable',
            'affiliate_code' => 'required_if:affiliate_status,1|regex:([A-Z]{2}[0-9]{3})|nullable|unique:affiliates,code,'.(isset(request()->customer) && isset(request()->customer->affiliate) ? request()->customer?->affiliate?->id : ''),
        ];
        if ($isAffiliate) {
            $rules['affiliate_code'] = '';
        }

        return request()->validate($rules);
    }

    public function search(Request $request)
    {
        $term = trim($request->q);

        $customers = Customer::select('id', 'firstname', 'lastname', 'email')
            ->search($term)
            ->latest();

        $customers = $customers->paginate(25);

        return response([
            'results' => CustomerSelectResource::collection($customers),
            'pagination' => [
                'more' => $customers->currentPage() !== $customers->lastPage(),
            ],
        ]);
    }
}
