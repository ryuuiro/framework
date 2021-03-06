<?php

namespace AvoRed\Framework\Models\Database;

class Order extends BaseModel
{
    protected $fillable = [
        'shipping_address_id',
        'billing_address_id',
        'user_id',
        'shipping_option',
        'payment_option',
        'order_status_id',
        'currency_code',
        'track_code'
    ];

    /**
     * Order Return Request can have many comments.
     *
     */
    public function comments()
    {
        return $this->morphToMany(OrderReturnComment::class, 'commentable');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('price', 'qty', 'tax_amount', 'product_info');
    }

    public function history()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function orderProductVariation()
    {
        return $this->hasMany(OrderProductVariation::class, '');
    }

    public function user()
    {
        $userModel = config('avored-framework.model.user');
        return $this->belongsTo($userModel);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function getShippingAddressAttribute()
    {
        $addressClass = config('avored-framework.model.address');
        $addressModel = new $addressClass;
        $shippingAddress = $addressModel->findorfail($this->attributes['shipping_address_id']);

        return $shippingAddress;
    }

    public function getOrderStatusNameAttribute()
    {
        return $this->orderStatus->name;
    }

    public function getBillingAddressAttribute()
    {
        $addressClass = config('avored-framework.model.address');
        $addressModel = new $addressClass;
        $address = $addressModel->findorfail($this->attributes['billing_address_id']);

        return $address;
    }
}
