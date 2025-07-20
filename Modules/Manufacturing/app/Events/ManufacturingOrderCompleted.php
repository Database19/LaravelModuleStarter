<?php

namespace Modules\Manufacturing\Events;

// Pastikan model Anda di-import dari lokasi yang benar.
// Bisa dari app/Models atau dari dalam modul itu sendiri.
use App\Models\ManufacturingOrder;
use Illuminate\Queue\SerializesModels;

class ManufacturingOrderCompleted
{
    use SerializesModels;

    /**
     * @var \App\Models\ManufacturingOrder
     */
    public $manufacturingOrder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ManufacturingOrder $manufacturingOrder)
    {
        $this->manufacturingOrder = $manufacturingOrder;
    }
}
