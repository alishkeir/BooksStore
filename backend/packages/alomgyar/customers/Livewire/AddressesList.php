<?php

namespace Alomgyar\Customers\Livewire;

use Alomgyar\Countries\Country;
use Alomgyar\Customers\Address;
use Alomgyar\Customers\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class AddressesList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = false;

    public $status;

    public $type;

    public $customer_id;

    public Collection $addresses;

    public Collection $countries;

    public Customer $customer;

    public array $address = [];

    public $updateMode = false;

    public function mount()
    {
        $this->countries = Country::all();
    }

    public function sortBy($column)
    {
        if ($this->sortField === $column) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $column;
    }

    public function updatingS()
    {
        $this->reset();
    }

    public function destroy($addressId)
    {
        Address::find($addressId)->delete();
    }

    public function edit($addressId)
    {
        $this->address = Address::find($addressId)->toArray();
        $this->updateMode = true;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    public function update()
    {
        $validator = Validator::make($this->address, Address::$validationRules, Address::$validationMessages);
        $validatedData = $validator->validate();

        if ($this->address['id']) {
            $address = Address::find($this->address['id']);
            $address->update($this->normalizeData($validatedData));
        }
        $this->resetInputFields();
    }

    private function normalizeData($data)
    {
        if ($this->address['entity_type'] == 1) {
            // magán
            $data['business_name'] = $data['vat_number'] = null;
        }

        return $data;
    }

    private function resetInputFields()
    {
        $this->name = null;
        $this->email = null;
        $this->updateMode = false;
        $this->render();
    }

    public function render()
    {
        if ($this->type === 'billing') {
            $this->customer = Customer::with(['billingAddresses' => function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            }])->find($this->customer_id);
            $this->addresses = $this->customer->billingAddresses;
        } else {
            $this->customer = Customer::with(['shippingAddresses' => function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            }])->find($this->customer_id);
            $this->addresses = $this->customer->shippingAddresses;
        }

        return view('customers::partials.addresses-list');
    }

    public function updatedAddressEntityType()
    {
        $address = Address::find($this->address['id']);
        $address->update(['entity_type' => $this->address['entity_type']]);
    }
}
