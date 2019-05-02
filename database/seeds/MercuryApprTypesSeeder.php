<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\MercuryNetwork\MercuryApprType;

class MercuryApprTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * The key in array is unique and it isn't changeable
     * If You want to add row add it in the end, because they display in alphabetical order  
     * @return void
     */
    public function run()
    {
        $appraisalTypes = [
	        1 => 'Appraisal Update/Inspection of Repairs (FNMA 1004D)',
	        2 => 'Appraisal Update/Recertification (FNMA 1004D)',
	        3 => 'Broker Price Opinion Drive by',
	        4 => 'Broker Price Opinion Exterior Inspection',
	        5 => 'Broker Price Opinion Interior & Exterior Inspection',
	        6 => 'Broker Price Opinion Interior Inspection',
	        7 => 'Co-op Appraisal (FNMA 2090)',
	        8 => 'Co-op Investment (2090, 1007, and 216)',
	        9 => 'Co-op Investment w/Comparable Rent Schedule (2090 and 1007)',
	        10 => 'Co-op Investment w/Operating Income Statement (2090 and 216)',
	        11 => 'Commercial Appraisal Report',
	        12 => 'Commercial Appraisal Review',
	        13 => 'Commercial Land Appraisal Report',
	        14 => 'Commercial Restricted Appraisal Report',
	        15 => 'Comparable Rent Schedule (1007)',
	        16 => 'Comparable Rent Schedule w/Operating Income Statement (1007 and 216)',
	        17 => 'Condition and Marketability Report (FHLMC 2070)',
	        18 => 'Condo Appraisal (FNMA 1073)',
	        19 => 'Condo Appraisal Conversion to FHA (FNM 1073)',
	        20 => 'Condo Appraisal w/REO (FNMA 1073)',
	        21 => 'Condo FHA (1073)',
	        22 => 'Condo FHA Investment (1073, 1007, and 216)',
	        23 => 'Condo FHA Investment w/Comparable Rent Schedule (1073 and 1007)',
	        24 => 'Condo FHA Investment w/Operating Income Statement (1073 and 216)',
	        25 => 'Condo Investment (1073, 1007, and 216)',
	        26 => 'Condo Investment w/Comparable Rent Sch (1073 and 1007)',
	        27 => 'Condo Investment w/Operating Income Statement (1073 and 216)',
	        28 => 'Construction Inspection Report',
	        29 => 'Conventional Conversion Appraisal Update',
	        30 => 'Desktop Quantitative Appraisal',
	        31 => 'Desktop Restricted Use Appraisal (DRA2)',
	        32 => 'Desktop Review',
	        33 => 'Desktop Summary Appraisal',
	        34 => 'Disaster Area Property Inspection Report',
	        35 => 'Drive by Appraisal (Legacy 2055)',
	        36 => 'Employee Relocation Council Report (ERC)',
	        37 => 'Enhanced Desktop Review',
	        38 => 'Evaluation',
	        39 => 'Exterior Only Co-op Appraisal (FNMA 2095)',
	        40 => 'Exterior Only Condo Appraisal (FNMA 1075)',
	        41 => 'Exterior Only Condo Investment (1075, 1007, and 216)',
	        42 => 'Exterior Only Condo Investment w/Comparable Rent Schedule (1075 and 1007)',
	        43 => 'Exterior Only Investment (2055, 1007, and 216)',
	        44 => 'Exterior Only Investment w/Comparable Rent Schedule (2055 and 1007)',
	        45 => 'Exterior Only Multi-Family Appraisal (FNMA 1025)',
	        46 => 'Exterior Only Residential Report (FNMA 2055)',
	        47 => 'Exterior Only Residential Report w/ Comparable Photos (FNMA 2055)',
	        48 => 'FHA Appraisal (FNMA 1004)',
	        49 => 'FHA Comparable Rent Schedule w/Operating Income Statement (1007 and 216)',
	        50 => 'FHA Conversion Appraisal Update',
	        51 => 'FHA Field Review (HUD 1038)',
	        52 => 'FHA Inspection (CIR)',
	        53 => 'FHA Manufactured Home (FNMA 1004C)',
	        54 => 'FHA Manufactured Home (FNMA 1004C and 1007)',
	        55 => 'FHA Manufactured Home (FNMA 1004C, 1007, and 216)',
	        56 => 'FHA Manufactured Home (FNMA 1004C and 216)',
	        57 => 'Field Review (FNMA 2000)',
	        58 => 'General Property Inspection (no form specified)',
	        59 => 'General Purpose Appraisal Report',
	        60 => 'Land Appraisal',
	        61 => 'LASS-O Multi-Family Drive by',
	        62 => 'LASS-O Multi-Family Full w/ Income Approach',
	        63 => 'LASS-O SFR/Condo Drive by',
	        64 => 'LASS-O SFR/Condo Full',
	        65 => 'Manufactured Home (FNMA 1004C)',
	        66 => 'Manufactured Home Investment (FNMA 1004C, 1007, and 216)',
	        67 => 'Manufactured Home Investment with Comparable Rent Schedule (FNMA 1004C and 1007)',
	        68 => 'Mobile Home Appraisal',
	        69 => 'Multi-Family Appraisal (FNMA 1025)',
	        70 => 'Multi-Family Appraisal Conversion to FHA (FNM 1025)',
	        71 => 'Multi-Family FHA (FNMA 1025)',
	        72 => 'Multi-Family FHA Investment (FNMA 1025 and 216)',
	        73 => 'Multi-Family Field Review (FNMA 2000A)',
	        74 => 'Multi-Family Investment (FNMA 1025 and 216)',
	        75 => 'Operating Income Statement (216)',
	        76 => 'Occupancy Inspection',
	        77 => 'Property Inspection (FNMA 2075)',
	        78 => 'Real Estate Value Estimate (RVE)',
	        79 => 'Residential Income Property (71A)',
	        80 => 'Residential Income Property (71B)',
	        81 => 'Retro Field Review',
	        82 => 'Reverse Mortgage Appraisal (1004/FHA)',
	        83 => 'Reverse Mortgage Condo (1073/FHA)',
	        84 => 'Reverse Mortgage Multi-Family (1025/FHA)',
	        85 => 'Reverse Mortgage Manufactured Home (1004C/FHA)',
	        86 => 'RPI\'s Price, Income, and Market Analysis Report',
	        87 => 'Second Mortgage Property Value Analysis Report (FRE 704)',
	        88 => 'Single Family FHA Investment (1004, 1007, and 216)',
	        89 => 'Single Family FHA Investment w/Comparable Rent Schedule (1004 and 1007)',
	        90 => 'Single Family FHA Investment w/Operating Income Statement (1004 and 216)',
	        91 => 'Single Family Investment (1004, 1007, and 216)',
	        92 => 'Single Family Investment w/Comparable Rent Sch (1004 and 1007)',
	        93 => 'Single Family Investment w/Operating Income Statement (1004 and 216)',
	        94 => 'Supplemental REO Addendum',
	        95 => 'Uniform Residential Appraisal (FNMA 1004)',
	        96 => 'Uniform Residential Appraisal w/ REO (FNMA 1004)',
	        97 => 'USDA Appraisal (FNMA 1004)',
	        98 => 'USDA Condo (FNMA 1073)',
	        99 => 'USDA-RD GRH Appraisal Addendum',
	        100 => 'Valuation Risk Analysis (VRA)',
	        101 => 'FHA Comparable Rent Schedule (1007)',
	    ];

	    foreach ($appraisalTypes as $key => $value) {
	    	MercuryApprType::updateOrCreate(
	    		[ 'external_id' => $key ],
	    		[ 'title' => $value ]
	    	);
	    }
    }
}
