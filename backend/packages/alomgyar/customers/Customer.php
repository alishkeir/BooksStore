<?php

namespace Alomgyar\Customers;

use Alomgyar\Affiliates\Affiliate;
use Alomgyar\Affiliates\AffiliateRedeem;
use Alomgyar\Authors\Author;
use Alomgyar\Carts\Cart;
use Alomgyar\Comments\Comment;
use Alomgyar\Customers\Notifications\CustomerVerifyEmail;
use Alomgyar\Customers\Notifications\ResetPasswordNotification;
use Alomgyar\Products\Product;
use App\Order;
use App\OrderItem;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use LogsActivity, HasApiTokens, HasFactory, Notifiable;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    private static int $tokenValidity = 2;

    protected $fillable = [
        'email',
        'email_verified_at',
        'password',
        'firstname',
        'lastname',
        'phone',
        'marketing_accepted',
        'author_follow_up',
        'comment_follow_up',
        'status',
        'store',
        'remember_token',
        'last_login_at',
        'last_login_ip',
        'last_login_device',
        'provider_id',
        'personal_discount_alomgyar',
        'personal_discount_all',
        'affiliate_id',
    ];

    protected $appends = ['full_name'];

    protected static $logAttributes = ['*'];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        'status' => self::STATUS_INACTIVE,
    ];

    protected $casts = [
        'author_follow_up' => 'boolean',
        'comment_follow_up' => 'boolean',
        'last_login_device' => 'array',
        'marketing_accepted' => 'boolean',
    ];

    public static $messages = [
        'required' => 'A :attribute kitöltése kötelező!',
        'email.required' => 'Az :attribute kitöltése kötelező!',
        'max' => 'Nem lehet hosszabb, mint :max karakter!',
        'alpha_num' => 'Csak az ABC betűit és számokat használhatod!',
        'alpha' => 'Csak az ABC betűit használhatod!',
        'string' => 'Csak az ABC betűit használhatod!',
        'min' => 'Nem lehet kevesebb, mint :min karakter!',
        'date' => 'Nem megfelelő dátum formátum!',
        'numeric' => 'Csak szám lehet!',
        'integer' => 'Csak szám lehet!',
        'email' => 'Nem megfelelő formátum!',
        'unique' => 'Ezzel az email címmel már regisztráltak!',
        'phone.regex' => 'Nem megfelelő formátum!',
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query->where('id', 'like', '%'.$term.'%')
                    ->orWhere('email', 'like', '%'.$term.'%')
                    ->orWhere('firstname', 'like', '%'.$term.'%')
                    ->orWhere('lastname', 'like', '%'.$term.'%');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status === self::STATUS_ACTIVE ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomerVerifyEmail($this->store));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public static function whichStore(Customer $customer): string
    {
        $stores = config('pam.store_urls');

        return $stores[$customer->store];
    }

    public function getCustomerLastNameAttribute(): string
    {
        return $this->lastname ?? 'Vásárló';
    }

    public function getCustomerFirstNameAttribute(): string
    {
        return $this->firstname ?? 'Vásárló';
    }

    public function getCustomerFullNameAttribute(): string
    {
        return $this->firstname && $this->lastname ? "{$this->lastname} {$this->firstname}" : 'Vásárló';
    }

    public function getFullNameAttribute()
    {
        return "{$this->lastname} {$this->firstname}";
    }

    public function getUsernameAttribute()
    {
        return empty($this->firstname) ? Str::before($this->email, '@') : $this->firstname;
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class);
    }

    public function getEbooksAttribute()
    {
        return $this->orderItems()->whereHas('order', function (Builder $query) {
            $query->whereNotIn('status', [Order::STATUS_DRAFT, Order::STATUS_DELETED])
                ->where('payment_status', Order::STATUS_PAYMENT_PAID);
        })
            ->get()
            ->map(function ($item) {
                if ($item->product?->type === Product::EBOOK) {
                    return $item->product;
                }
            })->filter()->unique()->values();
    }

    public function preorders()
    {
        return $this->belongsToMany(Product::class, 'customer_preorders');
    }

    public function reviews()
    {
        return $this->belongsToMany(Product::class,
            'product_review')->whereStore(request('store'))->latest('product_review.updated_at')->withPivot('review',
                'product_review.updated_at as review_date');
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'customer_wishlist');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'customer_authors');
    }

    public static function subscribe($customer, $authorId)
    {
        if ($customer->author_follow_up ?? false) {
            $exist = DB::select(DB::raw(' SELECT id FROM customer_authors WHERE customer_id='.$customer->id.' AND author_id = '.$authorId.''));

            if (! $exist) {
                $sql = ' INSERT INTO `customer_authors` (customer_id, author_id, created_at) VALUES ('.$customer->id.', '.$authorId.', NOW()) ';
                DB::statement($sql);
            }
        }
    }

    public function billingAddresses()
    {
        return $this->hasMany(Address::class, 'role_id')
            ->where([
                ['type', 'billing'],
                ['role', 'customer'],
            ]);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(Address::class, 'role_id')
            ->where([
                ['type', 'shipping'],
                ['role', 'customer'],
            ]);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public static function tokenValidUntil()
    {
        return isset(request()->body['remember_me']) && request()->body['remember_me']
            ? now()->addYear()
            : now()->addWeeks(self::$tokenValidity);
    }

    public function isProductSelected($productId)
    {
        return $this->preorders()->where('product_id', $productId)->exists()
            || $this->cart?->items()->where('product_id', $productId)->exists();
    }

    public function countComments()
    {
        return Comment::where('customer_id', $this->id)->count();
    }
    public function getIsAffiliateAttribute()
    {
        return ($this->affiliate && $this->affiliate?->status == self::STATUS_ACTIVE);
    }
    public function affiliate()
    {
        return $this->hasOne(Affiliate::class);
    }
    public function affiliateRedeems()
    {
        return $this->hasMany(AffiliateRedeem::class);
    }
}
