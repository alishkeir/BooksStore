<?php

namespace App\Http\Livewire;

use Alomgyar\Customers\Customer;
use App\User;
use Livewire\Component;

class AffiliateForm extends Component
{
    public $customer;
    public $isAffiliate;
    public $isAuthor = false;
    public $customerModel;
    public $affiliateRedeems;
    public function mount()
    {
        if ($this->customer){
            $this->customerModel = Customer::find($this->customer);
            if ($this->customerModel){
                $this->isAuthor = User::where('email', $this->customerModel->email)->first()?->hasRole('szerző');

                // get affiliate redeems
                $this->affiliateRedeems = $this->customerModel->affiliateRedeems;
            }
        }
    }
    public function render()
    {
        return view('livewire.affiliate-form', ['model' => $this->model]);
    }
    public function getModelProperty()
    {
        if ($this->customer && $this->customerModel) {
            return $this->customerModel->affiliate;
        }
        return;
    }
}
