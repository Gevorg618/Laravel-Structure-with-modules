<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\FNC\FNCLoanReason;

class FNCLoanReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $loanReason = [
            'ACQ' => 'Acquisition',
            'AddCo' => 'Additional Collateral',
            'AMAN' => 'Asset Management',
            'BFR' => 'Bankruptcy/Foreclosure/Rehab',
            'BsLOC' => 'Business LOC',
            'BusLo' => 'Business Loan',
            'Cash' => 'Refinance - Cash Out',
            'ChgCo' => 'Change in Collateral',
            'CPERM' => 'Construction - Permanent',
            'HELOC' => 'HELOC',
            'NCash' => 'Refinance - No Cash Out',
            'NCon' => 'New Construction',
            'Other' => 'Other',
            'PreFo' => 'Pre-Foreclosure',
            'Purc' => 'Purchase',
            'RCVY' => 'Recovery',
            'Refi' => 'Refinance',
            'RenCa' => 'Renewal Cash-Out',
            'Renew' => 'Renewal',
            'RenLo' => 'Renewal Loss-Share',
            'RENOV' => 'Renovation-Perm',
            'REOLS' => 'REO Loss-Share',
            'RMon' => 'Routine Monitoring',
            'Work' => 'Workout',
            'Item' => 'not provided/unkown',
        ];

        foreach ($loanReason as $key => $value) {
            FNCLoanReason::updateOrCreate(
                [ 'key' => $key ],
                [ 'value' => $value ]
            );
        }
    }
}
