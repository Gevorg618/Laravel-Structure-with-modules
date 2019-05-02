<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Reply & Moderate</h3>
            </div>
            <div class="panel-body">
                <form id="ticket_reply_options" method="post"
                      action="{{ route('admin.ticket.manager.view', ['id' => $ticket->id, 'params' => $request->hashedQuery]) }}">
                    {{ csrf_field() }}

                    <input type="hidden" id="params" name="params" value="{{ $request->hashedQuery }}">
                    <input type="hidden" id="start" name="start" value="{{ time() }}">

                    @include('admin::ticket.manager.templates._ticket_reply_options')

                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::textarea('reply_text', $request->reply_text, [
                                'id' => 'reply_text', 'class' => 'editor order-search']) !!}
                        </div>
                    </div>
                    <hr/>
                    <button type="reset" name="reset" value="Reset" class="btn btn-default">Reset</button>
                    <button type="submit" name="submit" value="Submit" class="btn btn-primary">Submit</button>

                    <button type="submit" name="submit_and_next" value="Submit & Next" class="btn btn-primary">
                        Submit & Next
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>