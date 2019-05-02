@php
// Figure priority
$classes = [];

if ($row->priority) {
    if (isset($priorityClasses[$row->priority])) {
        $classes[] = 'label ' . $priorityClasses[$row->priority];
    }
}

$subject = $row->subject ? $row->subject : '<i>No Subject</i>';
if (!empty($linkSubject)) {
    $route = route('admin.ticket.manager.view', ['id' => $row->id, 'params' => $hashedQuery]);
    $subject = '<a href="' . $route . '" target="_blank">' . $subject . '</a>';
}

if ($row->locked_date > 0 && ($row->locked_by && $row->locked_by != admin()->id)) {
	$subject = $row['subject'] ? $row['subject'] : '<i>No Subject</i>';
}

@endphp

@if ($row->closed_date)
<span class="low-opacity {{ implode(' ', $classes) }}"><s>{!! $subject !!}</s></span>
@elseif ($row->locked_date)
<span class="low-opacity {{ implode(' ', $classes) }}">{!! $subject !!}</span>
@else
<span class="{{ implode(' ', $classes) }}">{!! $subject !!}</span>
@endif