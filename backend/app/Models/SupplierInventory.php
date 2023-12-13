<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SupplierInventory
 * 
 * @property int $id
 * @property int $supplier_id
 * @property int $product_id
 * @property int $stock
 * 
 * @property Product $product
 * @property Supplier $supplier
 *
 * @package App\Models
 */
class SupplierInventory extends Model
{
	protected $table = 'supplier_inventories';
	public $timestamps = false;

	protected $casts = [
		'supplier_id' => 'int',
		'product_id' => 'int',
		'stock' => 'int'
	];

	protected $fillable = [
		'supplier_id',
		'product_id',
		'stock'
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
