@if(isset($errors))
	@if(count($errors)>0)

        <script>
            $(document).ready(function () {
                @foreach ($errors->all() as $error)
                    var message = "{{$error}}";
                    $.notify({
                        icon: "ti-na",
                        message: message

                    },{
                        type: 'danger',
                        timer: 1000,
                        placement: {
                            from: 'top',
                            align: 'right'
                        }
                    });
                @endforeach

            });
        </script>
	 @endif
@endif

{{-- @if(Session::has('error'))
    @include('styles')
    @include('scripts')

    <script>
        var message = "{{Session::get('error')}}";
        $.notify({
            icon: "ti-na",
            message: message

        },{
            type: 'danger',
            timer: 1000,
            placement: {
                from: 'top',
                align: 'right'
            }
        });

    </script>
@endif --}}

@if(Session::has('success'))
   
    <script>
        $(document).ready(function () {
            var message = "{{Session::get('success')}}";
            $.notify({
                icon: "ti-check",
                message: message

            },{
                type: 'success',
                timer: 1000,
                placement: {
                    from: 'top',
                    align: 'right'
                }
            });
        });

    </script>
@endif

@if(Session::has('warning'))



    <script>
        var message = "{{Session::get('warning')}}";
        $.notify({
            icon: "ti-help",
            message: message

        },{
            type: 'warning',
            timer: 1000,
            placement: {
                from: 'top',
                align: 'right'
            }
        });

    </script>
@endif
