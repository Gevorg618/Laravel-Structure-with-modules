<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\FNC\FNCStatuses;

class FNCStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            '?4' => 'WAITING FOR PAYMENT',
            '?D' => 'WAITING FOR DECISION',
            'A' => 'ASSIGNED TO PROVIDER',
            'AC' => 'PROVIDER ACCEPTS WITH CONDITION',
            'AER' => 'AUTO-ASSIGN ESCALATED REVIEW',
            'AR' => 'AUTO-ASSIGN REVIEWER',
            'BA' => 'BID AWARDED',
            'BC' => 'BIDDING COMPLETE',
            'BO' => 'OUT FOR BID',
            'BR' => 'RFP OPEN',
            'BW' => 'READY FOR AWARD',
            'C' => 'CANCELLED - GENERAL',
            'CD' => 'CANCELLED - DUPLICATE REQUEST',
            'CE' => 'CANCELLED - REFUND DUE',
            'CR' => 'CANCELLED - BY REQUEST',
            'D' => 'DRAFT RECEIVED FROM PROVIDER',
            'D9' => 'DORMANT 90 DAYS',
            'DP' => 'DECISION PENDING',
            'DR' => 'DOCUMENTS REQUESTED',
            'H' => 'ON HOLD',
            'I' => 'INCOMING (NEW)',
            'L' => 'CANCELLED - WITH FEE',
            'LA' => 'APPROVED - AS IS',
            'LC' => 'APPROVED - WITH CONDITIONS',
            'LD' => 'DECLINED',
            'M' => 'ACCEPTED',
            'N' => 'DRAFT NOT ACCEPTABLE',
            'NP' => 'PROCESSOR ASSIGNMENT NEEDED',
            'OH' => 'ON HOLD - WAITING FOR APPROVAL',
            'P' => 'IN PROCESS',
            'PI' => 'PDF EXTRACTION INCOMPLETE',
            'R1' => 'REJECTED ONLINE BY PROVIDER',
            'R2' => 'REJECTED - NO PROVIDER RESPONSE',
            'TC' => 'CANCEL PENDING',
            'U1' => 'GATEWAY IN PROGRESS',
            'W' => 'WITH REVIEWER',
            'WD' => 'WAITING FOR DISCLOSURES',
            'WER' => 'WITH ESCALATED REVIEWER',
            'WH' => 'WHOLESALE - WAITING FOR APPRAISAL',
            'X?' => 'EXCEPTION - INCOMPLETE',
            'XD' => 'EXCEPTION - DUPLICATE ORDER',
            'XE' => 'ESCALATED REVIEW REQUIRED',
            'XEP' => 'READY FOR ESCALATED REVIEW',
            'XER' => 'EXCEPTION - ESCALATED REVIEWER',
            'XG' => 'EXCEPTION-GSE RULES',
            'XM' => 'EXCEPTION - MANUAL ASSIGN',
            'XO' => 'CUSTOMER REQUESTED REVIEW',
            'XP' => 'READY FOR REVIEW',
            'XQ' => 'EXCEPTION - PROCESSOR',
            'XR' => 'EXCEPTION - REVIEWER',
            'XU' => 'EXCEPTION - GATEWAY',
            'XX' => 'RFP EXPIRED',
            'XY' => 'READY FOR RFP',
        ];

        foreach ($statuses as $key => $value) {
            FNCStatuses::updateOrCreate(
                [ 'key' => $key ],
                [ 'value' => $value ]
            );
        }
    }
}
