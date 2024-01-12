@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Manual Ticket') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('organizer.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Manual Ticket') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Manual Ticket') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('organizer.manual.ticket.update',$id = $ticket->id) }}" enctype="multipart/form-data" method="POST">
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Edit Manual Ticket') }}</div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 offset-lg-2">
                <div class="alert alert-danger pb-1 dis-none" id="equipmentErrors">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <ul></ul>
                </div>

                @csrf
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Events') . '*' }}</label>
                      <select name="event_id" class="form-control" id="">
                        <option value="">Select Event</option>
                        @foreach ($events as $event)
                        <option @if($ticket->event_id == $event->event_id) {{ "selected" }} @endif value="{{ $event->event_id }}">{{ $event->title }}</option>
                        @endforeach  
                      </select>
                    </div>
                    @error('email')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Name') . '*' }}</label>
                      <input type="text" class="form-control"
                        name="customer_name" placeholder="{{ __('Enter Name') }}" value="{{ $ticket->customer_name }}">
                        <input type="hidden"
                        name="organizer_id" value="{{ $ticket->organizer_id }}">
                    </div>
                    @error('customer_name')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Email') . '*' }}</label>
                      <input type="email" class="form-control" 
                        name="customer_email" placeholder="{{ __('Enter Email') }}"  value="{{ $ticket->customer_email }}">
                    </div>
                    @error('email')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Phone') . '*' }}</label>
                      <input type="text" class="form-control"
                        name="customer_phone" placeholder="{{ __('Enter Phone') }}"  value="{{ $ticket->customer_phone }}">
                    </div>
                    @error('customer_phone')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Save') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
