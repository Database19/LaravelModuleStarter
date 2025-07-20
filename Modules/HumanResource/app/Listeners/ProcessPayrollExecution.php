<?php

namespace Modules\HumanResource\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Events\PayrollProcessed;

class ProcessPayrollExecution implements ShouldQueue
{
    public function handle(PayrollProcessed $event): void
    {
        $payroll = $event->payroll;

        if ($payroll->status !== 'Paid') {
            return;
        }

        DB::transaction(function () use ($payroll) {
            $this->createJournalEntry($payroll);
        });
    }

    private function createJournalEntry($payroll): void
    {
        $employee = $payroll->employee;

        $journalId = DB::table('journal_entries')->insertGetId([
            'date' => $payroll->payment_date,
            'description' => 'Beban Gaji untuk ' . $employee->user->name . ' periode ' . $payroll->pay_period_start_date,
            'referenceable_type' => 'App\\Models\\Payroll',
            'referenceable_id' => $payroll->id,
            'user_id' => $payroll->created_by,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $totalExpense = $payroll->basic_salary + $payroll->allowances;

        $items = [
            ['journal_entry_id' => $journalId, 'account_id' => 41, 'debit' => $totalExpense, 'credit' => 0],
            ['journal_entry_id' => $journalId, 'account_id' => 4, 'debit' => 0, 'credit' => $payroll->net_salary],
        ];

        if ($payroll->deductions > 0) {
            $items[] = ['journal_entry_id' => $journalId, 'account_id' => 25, 'debit' => 0, 'credit' => $payroll->deductions];
        }

        if ($payroll->tax_amount > 0) {
            $items[] = ['journal_entry_id' => $journalId, 'account_id' => 23, 'debit' => 0, 'credit' => $payroll->tax_amount];
        }
        DB::table('journal_entry_items')->insert($items);
    }
}
