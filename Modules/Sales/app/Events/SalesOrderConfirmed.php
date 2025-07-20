<?php

namespace Modules\Sales\Events;

use App\Models\SalesOrder;
use Illuminate\Queue\SerializesModels;

class SalesOrderConfirmed
{
    use SerializesModels;

    public $salesOrder;

    public function __construct(SalesOrder $salesOrder)
    {
        $this->salesOrder = $salesOrder;
    }
}
