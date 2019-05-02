<?php

use App\Models\Documents\DocumentType;

function getDocumentTypeIdByCode($code) {
	if(!$code) {
		return 0;
	}
	$row = getDocumentTypeByCode($code);
	return $row ? $row->id : 0;
}

function getDocumentTypeByCode($code) {
	return DocumentType::where('code', $code)->first();
}

function getDocumentVaultDocumentTypeByRecord($row) {
    $arr_types = [];
    $arr_codes = [];
	$types = DocumentType::getDocumentTypeList();
    $codes = DocumentType::getDocumentTypeList();
    foreach ($types as $type) {
        $arr_types[$type->id] = $type->name;
    }
    foreach ($codes as $code) {
        $arr_codes[$code->code] = $code->name;
    }
	if($row->is_final_report) {
		return $arr_codes['pdf'];
	} elseif($row->is_xml) {
		return $arr_codes['xml'];
	} elseif($row->is_icc) {
		return $arr_codes['icc'];
	} elseif($row->is_invoice) {
		return $arr_codes['invoice'];
	} else {
		if(isset($arr_types[$row->document_type])) {
			return $arr_types[$row->document_type];
		} else {
			$arr_types['other'];
		}
	}
}
