<?php

namespace Modules\Accounting\Events;

use App\Models\CustomerPayment;
use Illuminate\Queue\SerializesModels;

class CustomerPaymentRecorded
{
    use SerializesModels;

    public $payment;

    public function __construct(CustomerPayment $payment)
    {
        $this->payment = $payment;
    }
}
