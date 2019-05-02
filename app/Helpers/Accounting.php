<?php
function getAdminReasonTitleByKey($key) {
    $rows = getAdministrativeReasonCats();
    return isset($rows[$key]) ? $rows[$key]['name'] : 'N/A';
}

function getAdministrativeReasonCats() {
    return [
        'stoppayment' => [
            'name' => 'Stop Payment',
            'id' => 'stoppayment',
            'invoice_visible' => true,
        ],
        'nsf' => [
            'name' => 'NSF',
            'id' => 'nsf',
            'invoice_visible' => true,
        ],
        'paidback_nsf' => [
            'name' => 'Paid Back NSF',
            'id' => 'paidback_nsf',
            'invoice_visible' => true,
        ],
        'chargeback' => [
            'name' => 'Chargeback',
            'id' => 'chargeback',
            'invoice_visible' => true,
        ],
        'makeitright' => [
            'name' => 'Make It Right',
            'id' => 'makeitright',
            'invoice_visible' => true,
        ],
        'writeoff' => [
            'name' => 'Write Off',
            'id' => 'writeoff',
            'invoice_visible' => false,
        ],
        'settlement' => [
            'name' => 'Settlement',
            'id' => 'settlement',
            'invoice_visible' => false,
        ],
        'transfer' => [
            'name' => 'Transfer',
            'id' => 'transfer',
            'invoice_visible' => false,
        ],
        'manualentry' => [
            'name' => 'Manual Entry',
            'id' => 'manualentry',
            'invoice_visible' => false,
        ],
        'reversal' => [
            'name' => 'Reversal',
            'id' => 'reversal',
            'invoice_visible' => false,
        ],
        'puerto_rico_taxes' => [
            'name' => 'Puerto Rico Taxes',
            'id' => 'puerto_rico_taxes',
            'invoice_visible' => false,
        ],
        'compliance' => [
            'name' => 'Compliance',
            'id' => 'compliance',
            'invoice_visible' => false,
        ],
        'refund' => [
            'name' => 'Refund',
            'id' => 'refund',
            'invoice_visible' => false,
        ],
        'creditmemo' => [
            'name' => 'Credit Memo',
            'id' => 'creditmemo',
            'invoice_visible' => false,
        ],
    ];
}

function getAdminAmountTypeSymbol($key) {
    switch($key) {
        case 'subtract':
            return '-';
            break;

        case 'add':
            return '+';
            break;
    }

    return '';
}