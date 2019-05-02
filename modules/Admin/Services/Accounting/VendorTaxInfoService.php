<?php

namespace Modules\Admin\Services\Accounting;


use Modules\Admin\Helpers\StringHelper;
use Modules\Admin\Repositories\Accounting\OrderAddFeeRepository;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Repositories\Accounting\VendorRepository;

/**
 * Class VendorTaxInfoService
 * @package Modules\Admin\Services
 */
class VendorTaxInfoService
{
    protected $userRepo;
    protected $vendorRepo;
    protected $orderFeeRepo;
    protected $orderRepo;

    /**
     * VendorTaxInfoService constructor.
     * @param UserRepository $userRepo
     * @param VendorRepository $vendorRepo
     * @param OrderRepository $orderRepo
     */
    public function __construct(
        UserRepository $userRepo,
        VendorRepository $vendorRepo,
        OrderAddFeeRepository $orderFeeRepo,
        OrderRepository $orderRepo
    )
    {
        $this->userRepo = $userRepo;
        $this->vendorRepo = $vendorRepo;
        $this->orderFeeRepo = $orderFeeRepo;
        $this->orderRepo = $orderRepo;
    }

    /**
     * @param $year
     * @return array
     */
    public function getData($year)
    {
        // Grab all vendors who got paid this year
        $endDate = strtotime($year . '-12-31 23:59:59');
        $taxList = [];
        $duplicatedCompanies = [];

        $users = $this->userRepo->getVendorTaxInfoUsers($year);
        $userIds = $users->pluck('id')->toArray();
        $userEins = $users->pluck('ein')->toArray();

        $userTaxRows = [];
        $usersInfo = [];
        $taxRows = $this->vendorRepo->getTaxRows($userIds, $userEins)
            ->groupBy('user_id');

        // We check to see if this user changed his tax info
        foreach ($users as $user) {

            $usersInfo[$user->id] = $user;
            if ($taxRows->get($user->id)) {
                // For each tax row we lookup how much the appraiser got paid
                foreach ($taxRows->get($user->id) as $taxRow) {
                    $company = $taxRow->company;
                    $ein = preg_replace('/[^0-9]/', '', $taxRow->ein);

                    $taxClass = $taxRow->tax_class;

                    if (isset($userTaxRows[$user->id][$ein])) {
                        if (!isset($userTaxRows[$user->id][$ein]['company']) && $company) {
                            $userTaxRows[$user->id][$ein]['company'] = $company;
                        }

                        if (!isset($userTaxRows[$user->id][$ein]['class']) && $taxClass) {
                            $userTaxRows[$user->id][$ein]['class'] = $taxClass;
                        }
                    } else {
                        $userTaxRows[$user->id][$ein] = ['ein' => $ein, 'created_date' => $taxRow->created_date, 'created_date_human' => date('m/d/Y', $taxRow->created_date)];
                        if ($company) {
                            $userTaxRows[$user->id][$ein]['company'] = $company;
                        }

                        if ($taxClass) {
                            $userTaxRows[$user->id][$ein]['class'] = $taxClass;
                        }
                    }
                }
            }

            // Add current tax if its not there yet
            $company = $user->payable_company;
            $ein = preg_replace('/[^0-9]/', '', $user->ein);
            $taxClass = $user->tax_class;

            if ($ein) {
                if (isset($userTaxRows[$user->id][$ein])) {
                    if (!isset($userTaxRows[$user->id][$ein]['company']) && $company) {
                        $userTaxRows[$user->id][$ein]['company'] = $company;
                    }

                    if (!isset($userTaxRows[$user->id][$ein]['class']) && $taxClass) {
                        $userTaxRows[$user->id][$ein]['class'] = $taxClass;
                    }
                } else {
                    $userTaxRows[$user->id][$ein] = ['ein' => $ein];
                    if ($company) {
                        $userTaxRows[$user->id][$ein]['company'] = $company;
                    }

                    if ($taxClass) {
                        $userTaxRows[$user->id][$ein]['class'] = $taxClass;
                    }
                }
            }
        }

        $fromDate = strtotime($year . '-01-01 00:00:00');
        $dateRange = [
            date('Y-m-d H:i:s', $fromDate),
            date('Y-m-d H:i:s', $endDate),
        ];

        $orderAmounts = $this->orderRepo->getOrderAmounts($userIds, $dateRange)
            ->keyBy('acceptedby');
        $feeAmounts = $this->orderFeeRepo->getFeeAmounts($userIds, $dateRange)
            ->keyBy('apprid');

        // Loop over user tax rows
        foreach ($userTaxRows as $userId => $items) {
            $totalPaid = optional($orderAmounts->get($userId))->sum + optional($feeAmounts->get($userId))->sum;

            $userTaxRows[$userId][$usersInfo[$userId]->ein]['amount'] = $totalPaid;
        }

        // Build one array
        foreach ($userTaxRows as $userId => $row) {
            foreach ($row as $item) {
                if (!isset($item['ein']) || !isset($item['amount'])) {
                    continue;
                }

                if (isset($item['company'])) {
                    if (isset($companies[$item['company']])) {
                        $duplicatedCompanies[] = [$item['company']];
                    }

                    $companies[$item['company']] = $item['company'];
                }

                $phone = $usersInfo[$userId]->phone ?: $usersInfo[$userId]->mobile;

                $taxList[] = [
                    'id' => $userId,
                    'payee_name' => ucwords(strtolower($usersInfo[$userId]->payable_company)),
                    'firstname' => ucwords(strtolower($usersInfo[$userId]->firstname)),
                    'lastname' => ucwords(strtolower($usersInfo[$userId]->lastname)),
                    'email' => strtolower($usersInfo[$userId]->email),
                    'phone' => StringHelper::formatPhone($phone),
                    'ein' => $item['ein'],
                    'tax_class' => $item['class'] ?? '',
                    'company' => $item['company'] ?? '',
                    'address' => ucwords(strtolower($usersInfo[$userId]->address)),
                    'city' => ucwords(strtolower($usersInfo[$userId]->payable_city)),
                    'state' => strtoupper(($usersInfo[$userId]->payable_state)),
                    'zip' => $usersInfo[$userId]->payable_zip,
                    'amount' => $item['amount'],
                ];
            }
        }

        return [
            $taxList,
            $duplicatedCompanies,
        ];
    }
}