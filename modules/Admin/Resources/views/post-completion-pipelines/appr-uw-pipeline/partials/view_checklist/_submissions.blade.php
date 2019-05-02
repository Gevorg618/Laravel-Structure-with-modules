<div>
    <ul class="nav nav-tabs">
        @if($UCDPUnits)
            <li class="active">
                <a data-toggle="tab" href="#UCDP">UCDP Submission </a>
            </li>
        @endif
        @if($EADUnits)
            <li>
                <a data-toggle="tab" href="#EAD">EAD Submission </a>
            </li>
        @endif
        @if($realView)
            <li>
                <a data-toggle="tab" href="#realView">RealView Submission </a>
            </li>
        @endif
    </ul>
    <div class="tab-content" style="margin-top: 30px;">
        @if($UCDPUnits) 
            <div id="UCDP" class="tab-pane fade in active">
                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._ucdp_submission', ['UCDPUnits' => $UCDPUnits, 'order' => $order ])
            </div>
        @endif
        @if($EADUnits) 
            <div id="EAD" class="tab-pane fade">
                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._ead_submission', ['EADUnits' => $EADUnits, 'order' => $order ])
            </div>
        @endif
        @if($realView)
            <div id="realView" class="tab-pane fade"> 
                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._real_view', ['realView' => $realView, 'order' => $order ])
            </div>
        @endif
    </div>
</div>