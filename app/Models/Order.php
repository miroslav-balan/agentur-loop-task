<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $customer_id
 * @property bool $payed
 * @property string|null $payment_method
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 *
 * @method static OrderFactory factory($count = null, $state = [])
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order onlyTrashed()
 * @method static Builder|Order query()
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereCustomerId($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order wherePayed($value)
 * @method static Builder|Order wherePaymentMethod($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order withTrashed()
 * @method static Builder|Order withoutTrashed()
 *
 * @mixin Eloquent
 */
class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'payed',
        'payment_provider',
    ];

    protected $casts = [
        'payed' => 'boolean',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
