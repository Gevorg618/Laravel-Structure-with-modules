<div class="panel panel-default">
    <div class="ibox-title panel-heading">
        <h5 class="panel-title">
            <p>{{ $question->title }}</p>
        </h5>
        <div class="ibox-tools">
            <div class="btn-group">
                <button data-toggle="collapse" data-parent="#accordion"
                        href="#collapse-{{ $question['id'] }}"
                        class="btn btn-white" type="button">
                    Toggle
                </button>
                <button class="btn btn-primary dropdown-toggle" type="button"
                        data-toggle="dropdown">
                    Options
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.appraisal.under-writing.checklist.question.create',$question->id) }}">
                            Edit Question
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.appraisal.under-writing.checklist.question.active-inactive',$question->id) }}">
                            {{ ($category->is_active)?'Make Inactive':'Make Active' }}
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{ route('admin.appraisal.under-writing.checklist.question.delete',$question->id) }}">
                            Delete &nbsp;<i class="fa fa-trash"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="collapse-{{ $question['id'] }}" class="panel-collapse collapse">
        <div class="panel-body">
            <table class="table">
                <tr>
                    <td>Correction</td>
                    <td>{{ $question['correction'] }}</td>
                </tr>
                <tr>
                    <td>Active</td>
                    <td>{{ $question['is_active']?'Yes':'No' }}</td>
                </tr>
                <tr>
                    <td>Loan Type</td>
                    <td>
                        @if(count($question->loan_type))
                            @foreach($question->loan_type as $loan_type)
                                {{ $loan_type->descrip }}
                                {!! $loop->remaining ? '<br>':'' !!}
                            @endforeach
                        @else
                            <p>ALL</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Clients</td>
                    <td>
                        @if(count($question->clients))
                            @foreach($question->clients as $client)
                                {{ $client->descrip }}
                                {!! $loop->remaining ? '<br>':'' !!}
                            @endforeach
                        @else
                            <p>ALL</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Lenders</td>
                    <td>
                        @if(count($question->lenders))
                            @foreach($question->lenders as $lenders)
                                {{ $lenders->lender }}
                                {!! $loop->remaining ? '<br>':'' !!}
                            @endforeach
                        @else
                            <p>ALL</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Loan Purpose</td>
                    <td>
                        @if(count($question->loan_reason))
                            @foreach($question->loan_reason as $loan_reason)
                                {{ $loan_reason->descrip }}
                                {!! $loop->remaining ? '<br>':'' !!}
                            @endforeach
                        @else
                            <p>ALL</p>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Appraisal Types</td>
                    <td>
                        @if(count($question->appr_type))
                            @foreach($question->appr_type as $appr_type)
                                {{ $appr_type->short_descrip }}
                                {!! $loop->remaining ? '<br>':'' !!}
                            @endforeach
                        @else
                            <p>ALL</p>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>