<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Alomgyar\Products\Product;
use Alomgyar\Suppliers\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Consumption
 *
 * @property int $id
 * @property int $supplier_id
 * @property int $product_id
 * @property int $quantity
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Product $product
 * @property Supplier $supplier
 *
 * @package App\Models
 */
class Consumption extends Model
{
	protected $table = 'consumptions';

	protected $casts = [
		'supplier_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
        'price' => 'int',
        'remaining_quantity' => 'int'
	];

	protected $fillable = [
		'supplier_id',
		'product_id',
		'quantity',
        'price',
        'remaining_quantity'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}
}
