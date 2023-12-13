<?php

namespace Alomgyar\Affiliates;

use Alomgyar\Customers\Customer;
use App\Helpers\AffiliateHelper;
use App\Services\GeneratePdfService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AffiliateRedeem extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'customer_id',
        'pdf'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function generateRedeemFileName()
    {
        $dateInFilename = Carbon::create($this->created_at)->format('Ymd');
        $pdfName = $dateInFilename . '-' . $this->customer?->affiliate?->code;
        return $pdfName;
    }

    public function getRedeemFileNameAttribute()
    {
        if (!empty($this->pdf)){
            return $this->generateRedeemFileName();
        }
        return;
    }
    public function getRedeemFileUrlAttribute()
    {
        if (! empty($this->pdf)) {
            if(file_exists(Storage::path('public'.DIRECTORY_SEPARATOR.'redeem-pdfs'. DIRECTORY_SEPARATOR.$this->pdf)))
            {
                return config('app.url').Storage::url('redeem-pdfs'. DIRECTORY_SEPARATOR.$this->pdf);
            }
        }
    }
    protected static function booted()
    {
        static::created(function ($model) {
            AffiliateHelper::flushAffiliateCacheForCustomer($model->customer?->id);
            (new GeneratePdfService)->generateRedeemPdf($model);
        });

        static::updated(function ($model) {
            AffiliateHelper::flushAffiliateCacheForCustomer($model->customer?->id);
        });
    }
}
