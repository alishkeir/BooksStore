<?php

namespace Alomgyar\Affiliates;

use App\Helpers\HumanReadable;
use App\Http\Controllers\Controller;
use App\Services\AffiliateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Alomgyar\Affiliates\Affiliate;

class AffiliateController extends Controller
{
    protected $affiliateService;
    public function __construct() {
        $this->affiliateService = new AffiliateService;
    }
    public function index()
    {
        $model = Affiliate::latest()->paginate(25);
        $unpaidCredit = HumanReadable::formatHUF($this->affiliateService->getUnpaidCreditTotal());
        $paidCredit = HumanReadable::formatHUF($this->affiliateService->getPaidCreditTotal());
        return view('affiliates::index', [
            'model' => $model,
            'unpaidCredit' => $unpaidCredit,
            'paidCredit' => $paidCredit,
        ]);
    }
}
