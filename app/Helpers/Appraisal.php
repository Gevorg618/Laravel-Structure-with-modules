<?php

function appraisalStatuses() {
	$statuses = [];
	foreach (App\Models\Customizations\Status::all() as $value) {
		$statuses[$value->id] = $value->descrip;
	}
	return $statuses;
}

function getScoreStripedClass($score)
{
	if($score >= 900) {
		return 'success';
	} elseif($score >= 600 && $score < 900) {
		return '';
	} elseif($score >= 400 && $score < 600) {
		return 'info';
	} elseif($score >= 200 && $score < 400) {
		return 'warning';
	} else {
		return 'danger';
	}
}