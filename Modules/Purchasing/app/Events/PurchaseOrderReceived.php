<?php

namespace Modules\Purchasing\Events;

use App\Models\PurchaseOrder;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderReceived
{
    use SerializesModels;

    public $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }
}
