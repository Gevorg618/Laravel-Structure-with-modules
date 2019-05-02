@php
    $fieldName = $name ? $name : 'attach';
    $colSpan = 2;
    $count = 0;
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <p class="hidden attachment-file-size-span">Total Size: <i>0</i> MB
                (Max {{ config('app.attach_max_size') }} MB)
            </p>
            <table class="table table-hover table-condensed">
                <tr>
                    @if ($orderFiles && count($orderFiles))
                        @foreach ($orderFiles as $filePath => $file)
                            @if ($count % $colSpan == 0)
                </tr>
                <tr>
                    @endif

                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="attachment-calc-size" id="{{ $file['name'] }}_id_{{ $count }}"
                                       name="{{ $fieldName }}[{{ $filePath }}]"
                                       value="{{ $file['name'] }}"> {{ $file['name'] }}
                                <small>({{ $file['date'] }})</small>

                                <input type="hidden" name="{{ $fieldName }}_id_{{ $count }}_size"
                                       id="{{ $fieldName }}_id_{{ $count }}_size"
                                       value="{{ floatval($file['size'] / 1024) }}">
                            </label>
                        </div>
                    </td>

                    @php $count++; @endphp
                    @endforeach

                    @if ($count % $colSpan != 0)
                        <td colspan="{{ $colSpan }}">&nbsp;</td>
                    @endif

                    @else
                        <td><i>None Found</i></td>
                    @endif
                </tr>
            </table>
        </div>
    </div>
</div>
