@extends('layouts.app')

@section('title', 'Edit Booking')
@section('content')

<div class="row">
    <div class="col-xl-4 col-md-12 mb-12">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">
                    Booking 
                </h6> 
            </div>
            <div class="card-body">
                <form action="{!! action('VatbookController@update') !!}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input id="date" class="datepicker form-control" type="text" name="date" required>
                    </div>

                    <div class="form-group">
                        <label for="start_at">Start (Zulu)</label>
                        <input id="start_at" class="starttimepicker form-control" type="text" name="start_at" required>
                    </div>

                    <div class="form-group">
                        <label for="end_at">End (Zulu)</label>
                        <input id="end_at" class="endtimepicker form-control" type="text" name="end_at" required>
                    </div>

                    <div class="form-group">
                        <label for="position">Position</label>
                    <input id="position" class="form-control" type="text" name="position" list="positions" value="{{ $booking->position->callsign }}" required/>
                        <datalist id="positions">
                            @foreach($positions as $position)
                                <option value="{{ $position->callsign }}">{{ $position->name }}</option>
                            @endforeach
                        </datalist>
                    </div>

                    @if ($user->isMentor())
                        <div class="form-group">
                            @if ($booking->training == 1)
                                <input id="training" type="checkbox" name="training" value=1 checked>
                            @else
                                <input id="training" type="checkbox" name="training" value=1>
                            @endif
                            <label for="training">Training</label>
                        </div>
                    @endif

                    @if ($user->isModerator())
                        <div class="form-group">
                            @if ($booking->event == 1)
                                <input id="event" type="checkbox" name="event" value=1 checked>
                            @else
                                <input id="event" type="checkbox" name="event" value=1>
                            @endif
                            <label for="event">Event</label>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="user">User</label>
                        <input id="user" class="form-control" type="text" name="user" readonly="readonly" value="{{ $booking->user->name }} ({{ $booking->user->id }})">
                    </div>

                    <input type="hidden" name="id" value="{{{ $booking->id }}}"> 

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('vatbook.delete', $booking->id) }}" onclick="return confirm('Are you sure you want to delete this booking?')" class="btn btn-danger">Delete</a>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    //Activate bootstrap tooltips
    $(document).ready(function() {
        $('div').tooltip();

        var defaultDate = "{{ empty(old('date')) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->time_start)->format('d/m/Y') : old('date') }}"
        var startTime = "{{ empty(old('start_at')) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->time_start)->format('H:i') : old('start_at') }}"
        var endTime = "{{ empty(old('end_at')) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->time_end)->format('H:i') : old('end_at') }}"

        $(".datepicker").flatpickr({ minDate: "{!! date('Y-m-d') !!}", dateFormat: "d/m/Y", defaultDate: defaultDate, locale: {firstDayOfWeek: 1 } });
        $(".starttimepicker").flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: startTime});
        $(".endtimepicker").flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: endTime });
        
        $('.flatpickr-input:visible').on('focus', function () {
            $(this).blur();
        });
        $('.flatpickr-input:visible').prop('readonly', false);
    })
</script>
@endsection