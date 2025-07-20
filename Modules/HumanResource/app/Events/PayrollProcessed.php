<?php

namespace Modules\HumanResource\Events;

use App\Models\Payroll;
use Illuminate\Queue\SerializesModels;

class PayrollProcessed
{
    use SerializesModels;

    public $payroll;

    public function __construct(Payroll $payroll)
    {
        $this->payroll = $payroll;
    }
}
