@extends('backend.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Edit Event') }}</h4>
  <ul class="breadcrumbs">
    <li class="nav-home">
      <a href="{{ route('admin.dashboard') }}">
        <i class="flaticon-home"></i>
      </a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Events Management') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="{{ route('admin.event_management.event', ['language' => $defaultLang->code]) }}">{{ __('All Events') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>

    @php
    $event_title = DB::table('event_contents')
    ->where('language_id', $defaultLang->id)
    ->where('event_id', $event->id)
    ->select('title')
    ->first();

    @endphp
    <li class="nav-item">
      <a href="#">
        {{ strlen($event_title->title) > 35 ? mb_substr($event_title->title, 0, 35, 'UTF-8') . '...' : $event_title->title }}
      </a>

    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>

    <li class="nav-item">
      <a href="#">{{ __('Edit Event') }}</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title d-inline-block">{{ __('Edit Event') }}</div>


        <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ url()->previous() }}">
          <span class="btn-label">
            <i class="fas fa-backward"></i>
          </span>
          {{ __('Back') }}
        </a>
        <a class="mr-2 btn btn-success btn-sm float-right d-inline-block" href="{{ route('event.details', ['slug' => eventSlug($defaultLang->id, $event->id), 'id' => $event->id]) }}" target="_blank">
          <span class="btn-label">
            <i class="fas fa-eye"></i>
          </span>
          {{ __('Preview') }}
        </a>
        @if ($event->event_type == 'venue')
        <a class="mr-2 btn btn-secondary btn-sm float-right d-inline-block" href="{{ route('admin.event.ticket', ['language' => $defaultLang->code, 'event_id' => $event->id, 'event_type' => $event->event_type]) }}" target="_blank">
          <span class="btn-label">
            <i class="far fa-ticket"></i>
          </span>
          {{ __('Tickets') }}
        </a>
        @endif
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-8 offset-lg-2">
            <div class="alert alert-danger pb-1 dis-none" id="eventErrors">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <ul></ul>
            </div>
            <div class="col-lg-12">
              <label for="" class="mb-2"><strong>{{ __('Gallery Images') }} **</strong></label>
              <div id="reload-slider-div">
                <div class="row mt-2">
                  <div class="col">
                    <table class="table" id="img-table">

                    </table>
                  </div>
                </div>
              </div>
              <form action="{{ route('admin.event.imagesstore') }}" id="my-dropzone" enctype="multipart/formdata" class="dropzone create">
                @csrf
                <div class="fallback">
                  <input name="file" type="file" multiple />
                </div>
                <input type="hidden" value="{{ $event->id }}" name="event_id">
              </form>
              <div class=" mb-0" id="errpreimg">

              </div>
              <p class="text-warning">{{ __('Image Size : 1170 x 570') }}</p>
            </div>

            <form id="eventForm" action="{{ route('admin.event.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="event_id" value="{{ $event->id }}">
              <input type="hidden" name="event_type" value="{{ $event->event_type }}">
              <input type="hidden" name="gallery_images" value="0">
              <div class="form-group">
                <label for="">{{ __('Thumbnail Image') . '*' }}</label>
                <br>
                <div class="thumb-preview">
                  <img src="{{ $event->thumbnail ? asset('assets/admin/img/event/thumbnail/' . $event->thumbnail) : asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                </div>
                <div class="mt-3">
                  <div role="button" class="btn btn-primary btn-sm upload-btn">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="thumbnail">
                  </div>
                </div>
                <p class="text-warning">{{ __('Image Size : 320x230') }}</p>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group mt-1">
                    <label for="">{{ __('Date Type') . '*' }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="date_type" {{ $event->date_type == 'single' ? 'checked' : '' }} value="single" class="selectgroup-input eventDateType" checked>
                        <span class="selectgroup-button">{{ __('Single') }}</span>
                      </label>

                      <label class="selectgroup-item">
                        <input type="radio" name="date_type" {{ $event->date_type == 'multiple' ? 'checked' : '' }} value="multiple" class="selectgroup-input eventDateType">
                        <span class="selectgroup-button">{{ __('Multiple') }}</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row countDownStatus {{ $event->date_type == 'multiple' ? 'd-none' : '' }} ">
                <div class="col-lg-12">
                  <div class="form-group mt-1">
                    <label for="">{{ __('Countdown Status') . '*' }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="countdown_status" value="1" class="selectgroup-input" {{ $event->countdown_status == 1 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>

                      <label class="selectgroup-item">
                        <input type="radio" name="countdown_status" value="0" class="selectgroup-input" {{ $event->countdown_status == 0 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              {{-- single dates --}}
              <div class="row {{ $event->date_type == 'multiple' ? 'd-none' : '' }}" id="single_dates">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label>{{ __('Start Date"') . '*' }}</label>
                    <input type="date" name="start_date" value="{{ $event->start_date }}" placeholder="Enter Start Date" class="form-control">
                  </div>
                </div>

                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="">{{ __('Start Time') . '*' }}</label>
                    <input type="time" name="start_time" value="{{ $event->start_time }}" class="form-control">
                  </div>
                </div>

                <div class="col-lg-3">
                  <div class="form-group">
                    <label>{{ __('End Date"') . '*' }}</label>
                    <input type="date" name="end_date" value="{{ $event->end_date }}" placeholder="Enter End Date" class="form-control">
                  </div>
                </div>

                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="">{{ __('End Time') . '*' }}</label>
                    <input type="time" name="end_time" value="{{ $event->end_time }}" class="form-control">
                  </div>
                </div>
              </div>

              {{-- multiple dates --}}
              <div class="row">
                <div class="col-lg-12 {{ $event->date_type == 'single' ? 'd-none' : '' }}" id="multiple_dates">
                  @if ($event->date_type == 'multiple')
                  @php
                  $event_dates = $event->dates()->get();
                  @endphp
                  @else
                  @php
                  $event_dates = [];
                  @endphp
                  @endif
                  <div class="form-group">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th scope="col">{{ __('Start Date') }}</th>
                            <th scope="col">{{ __('Start Time') }}</th>
                            <th scope="col">{{ __('End Date') }}</th>
                            <th scope="col">{{ __('End Time') }}</th>
                            <th scope="col"><a href="javascrit:void(0)" class="btn btn-success addDateRow"><i class="fas fa-plus-circle"></i></a></th>
                          </tr>
                        <tbody>
                          @if (count($event_dates) > 0)
                          @foreach ($event_dates as $date)
                          <tr>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Start Date') . '*' }}</label>
                                <input type="date" name="m_start_date[]" class="form-control" value="{{ $date->start_date }}">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Start Time') . '*' }}</label>
                                <input type="time" name="m_start_time[]" class="form-control" value="{{ $date->start_time }}">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('End Date') . '*' }} </label>
                                <input type="date" name="m_end_date[]" class="form-control" value="{{ $date->end_date }}">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('End Time') . '*' }} </label>
                                <input type="time" name="m_end_time[]" class="form-control" value="{{ $date->end_time }}">
                              </div>
                            </td>
                            <input type="hidden" name="date_ids[]" value="{{ $date->id }}">
                            <td>
                              <a href="javascript:void(0)" data-url="{{ route('admin.event.delete.date', $date->id) }}" class="btn btn-danger deleteDateDbRow">
                                <i class="fas fa-minus"></i></a>
                            </td>
                          </tr>
                          @endforeach
                          @else
                          <tr>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Start Date') . '*' }}</label>
                                <input type="date" name="m_start_date[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Start Time') . '*' }}</label>
                                <input type="time" name="m_start_time[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('End Date') . '*' }} </label>
                                <input type="date" name="m_end_date[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('End Time') . '*' }} </label>
                                <input type="time" name="m_end_time[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <a href="javascript:void(0)" class="btn btn-danger deleteDateRow">
                                <i class="fas fa-minus"></i></a>
                            </td>
                          </tr>
                          @endif

                        </tbody>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row ">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="">{{ __('Status') . '*' }}</label>
                    <select name="status" class="form-control">
                      <option selected disabled>{{ __('Select a Status') }}</option>
                      <option {{ $event->status == '1' ? 'selected' : '' }} value="1">{{ __('Active') }}
                      </option>
                      <option {{ $event->status == '0' ? 'selected' : '' }} value="0">{{ __('Deactive') }}
                      </option>
                    </select>

                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="">{{ __('Is Feature') . '*' }}</label>
                    <select name="is_featured" class="form-control is_featured">
                      <option selected disabled>{{ __('Select') }}</option>
                      <option value="yes" {{ $event->is_featured == 'yes' ? 'selected' : '' }}>{{ __('Yes') }}
                      </option>
                      <option value="no" {{ $event->is_featured == 'no' ? 'selected' : '' }}>{{ __('No') }}
                      </option>
                    </select>
                    <div class="featured_day mt-2">
                      <label for="">{{ __('How Many Days Is Feature') . '*' }}</label>
                      <?php 
                      if(isset($event->is_featured_date)){
                        $multi_date = json_decode($event->is_featured_date);
                        $multi_date_count = count($multi_date);
                        ?>
                        <input type="number" name="" class="form-control featured_day_input" placeholder="0" value="{{ $multi_date_count }}">
                        <?php
                        for ($i=0; $i < $multi_date_count; $i++) {   ?>
                          
                          <input type="date" name="is_featured_date[]" class="form-control date-inp" value="{{ $multi_date[$i] }}">
                          <?php  
                        }
                      }
                      ?>

                  </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="">{{ __('Organizer') }}</label>
                    <select name="organizer_id" class="form-control js-example-basic-single">
                      <option value="" selected>{{ __('Select Organizer') }}</option>
                      @foreach ($organizers as $organizer)
                      <option {{ $organizer->id == $event->organizer_id ? 'selected' : '' }} value="{{ $organizer->id }}">{{ $organizer->username }}</option>
                      @endforeach
                    </select>
                    <p class="text-warning">{{ __("Please leave it blank for Admin's event") }}</p>
                  </div>
                </div>

                @if ($event->event_type == 'venue')
               
               
                <!-- <div class="col-lg-4">
                      <div class="form-group">
                        <label for="">{{ __('Latitude') }}</label>
                        <input type="text" placeholder="Latitude" name="latitude" value="{{ $event->latitude }}"
                          class="form-control">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="">{{ __('Longitude') }}</label>
                        <input type="text" placeholder="Longitude" name="longitude"
                          value="{{ $event->longitude }}" class="form-control">
                      </div>
                    </div> -->
                @endif
              </div>
              @if ($event->event_type == 'online')
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group mt-1">
                    <label for="">{{ __('Total Number of Available Tickets') . '*' }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="ticket_available_type" value="unlimited" class="selectgroup-input" {{ @$event->ticket->ticket_available_type == 'unlimited' ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Unlimited') }}</span>
                      </label>

                      <label class="selectgroup-item">
                        <input type="radio" name="ticket_available_type" value="limited" class="selectgroup-input" {{ @$event->ticket->ticket_available_type == 'limited' ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Limited') }}</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 {{ @$event->ticket->ticket_available_type == 'limited' ? '' : 'd-none' }}" id="ticket_available">
                  <div class="form-group">
                    <label>{{ __('Enter total number of available tickets') . '*' }}</label>
                    <input type="number" name="ticket_available" placeholder="Enter total number of available tickets" class="form-control" value="{{ @$event->ticket->ticket_available }}">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mt-1">
                    <label for="">{{ __('Maximum number of tickets for each customer') . '*' }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="max_ticket_buy_type" value="unlimited" class="selectgroup-input" {{ @$event->ticket->max_ticket_buy_type == 'unlimited' ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Unlimited') }}</span>
                      </label>

                      <label class="selectgroup-item">
                        <input type="radio" name="max_ticket_buy_type" value="limited" class="selectgroup-input" {{ @$event->ticket->max_ticket_buy_type == 'limited' ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Limited') }}</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 {{ @$event->ticket->max_ticket_buy_type == 'limited' ? '' : 'd-none' }}" id="max_buy_ticket">
                  <div class="form-group">
                    <label>{{ __('Enter Maximum number of tickets for each customer') . '*' }}</label>
                    <input type="number" name="max_buy_ticket" placeholder="Enter Maximum number of tickets for each customer" class="form-control" value="{{ @$event->ticket->max_buy_ticket }}">
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="">
                    <div class="form-group">
                      <label for="">{{ __('Price') }} ({{ $getCurrencyInfo->base_currency_text }})
                        *</label>
                      <input type="number" name="price" id="ticket-pricing" value="{{ $event->ticket->price }}" placeholder="Enter Price" class="form-control {{ optional($event->ticket)->pricing_type == 'free' ? 'd-none' : '' }}">
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="checkbox" name="pricing_type" {{ optional($event->ticket)->pricing_type == 'free' ? 'checked' : '' }} value="free" class="" id="free_ticket"> <label for="free_ticket">Tickets are Free</label>
                  </div>
                </div>
              </div>



              <div class="row {{ optional($event->ticket)->pricing_type == 'free' ? 'd-none' : '' }}" id="early_bird_discount_free">
                <div class="col-lg-12">
                  <div class="form-group mt-1">
                    <label for="">{{ __('Early Bird Discount') . '*' }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="early_bird_discount_type" {{ optional($event->ticket)->early_bird_discount == 'disable' ? 'checked' : '' }} value="disable" class="selectgroup-input" checked>
                        <span class="selectgroup-button">{{ __('Disable') }}</span>
                      </label>

                      <label class="selectgroup-item">
                        <input type="radio" name="early_bird_discount_type" {{ optional($event->ticket)->early_bird_discount == 'enable' ? 'checked' : '' }} value="enable" class="selectgroup-input">
                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12 {{ optional($event->ticket)->early_bird_discount == 'disable' ? 'd-none' : '' }}" id="early_bird_dicount">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label for="">{{ __('Discount') }} *</label>
                        <select name="discount_type" class="form-control discount_type">
                          <option disabled>Select Discount Type</option>
                          <option {{ optional($event->ticket)->early_bird_discount_type == 'fixed' ? 'selected' : '' }} value="fixed">Fixed</option>
                          <option {{ optional($event->ticket)->early_bird_discount_type == 'percentage' ? 'selected' : '' }} value="percentage">Percentage</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label for="">{{ __('Amount') }} *</label>
                        <input type="number" name="early_bird_discount_amount" value="{{ optional($event->ticket)->early_bird_discount_amount }}" class="form-control early_bird_discount_amount">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label for="">{{ __('Discount End Date') }} *</label>
                        <input type="date" name="early_bird_discount_date" value="{{ optional($event->ticket)->early_bird_discount_date }}" class="form-control">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label for="">{{ __('Discount End Time') }} *</label>
                        <input type="time" name="early_bird_discount_time" value="{{ optional($event->ticket)->early_bird_discount_time }}" class="form-control">
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              @endif


              <div id="accordion" class="mt-3">
                @foreach ($languages as $language)
                <div class="version">
                  <div class="version-header" id="heading{{ $language->id }}">
                    <h5 class="mb-0">
                      <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $language->id }}" aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}" aria-controls="collapse{{ $language->id }}">
                        {{ $language->name . __(' Language') }}
                        {{ $language->is_default == 1 ? '(Default)' : '' }}
                      </button>
                    </h5>
                  </div>
                  @php
                  $event_content = DB::table('event_contents')
                  ->where('language_id', $language->id)
                  ->where('event_id', $event->id)
                  ->first();
                  @endphp
                  <div id="collapse{{ $language->id }}" class="collapse {{ $language->is_default == 1 ? 'show' : '' }}" aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                    <div class="version-body">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label>{{ __('Event Title') . '*' }}</label>
                            <input type="text" class="form-control" name="{{ $language->code }}_title" value="{{ @$event_content->title }}" placeholder="Enter Event Name">
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            @php
                            $categories = DB::table('event_categories')
                            ->where('language_id', $language->id)
                            ->where('status', 1)
                            ->orderBy('serial_number', 'asc')
                            ->get();
                            @endphp

                            <label for="">{{ __('Category') . '*' }}</label>
                            <select name="{{ $language->code }}_category_id" class="form-control">
                              <option selected disabled>{{ __('Select Category') }}</option>

                              @foreach ($categories as $category)
                              <option value="{{ $category->id }}" {{ @$event_content->event_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                              </option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      @if ($event->event_type == 'venue')
                      <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label for="">{{ __('Address') . '*' }}</label>
                            <input type="text" id="location" name="{{ $language->code }}_address" class="address form-control" placeholder="Enter Address" value="{{ @$event_content->address }}">
                            <input type="hidden" id="lng" value="{{ $event->longitude }}" name="longitude"
                            class="lng">
                            <input type="hidden" value="{{ $event->latitude }}" name="latitude"
                            class="lat" id="lat">
                       
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <div id="map" style="height: 400px; width: 100%;margin-top: 1rem;">
                          
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label for="">{{ __('County') . '*' }}</label>
                            <select name="{{ $language->code }}_country" id="country" class="form-control">
                              <option>select country</option>
                              <option @if(@$event_content->country == "Afghanistan") {{ "selected" }} @endif value="Afghanistan">Afghanistan</option>
                              <option @if(@$event_content->country == "Aland Islands") {{ "selected" }} @endif value="Aland Islands">Aland Islands</option>
                              <option @if(@$event_content->country == "Albania") {{ "selected" }} @endif value="Albania">Albania</option>
                              <option @if(@$event_content->country == "Algeria") {{ "selected" }} @endif value="Algeria">Algeria</option>
                              <option @if(@$event_content->country == "American Samoa") {{ "selected" }} @endif value="American Samoa">American Samoa</option>
                              <option @if(@$event_content->country == "Andorra") {{ "selected" }} @endif value="Andorra">Andorra</option>
                              <option @if(@$event_content->country == "Angola") {{ "selected" }} @endif value="Angola">Angola</option>
                              <option @if(@$event_content->country == "Anguilla") {{ "selected" }} @endif value="Anguilla">Anguilla</option>
                              <option @if(@$event_content->country == "Antarctica") {{ "selected" }} @endif value="Antarctica">Antarctica</option>
                              <option @if(@$event_content->country == "Antigua and Barbuda") {{ "selected" }} @endif value="Antigua and Barbuda">Antigua and Barbuda</option>
                              <option @if(@$event_content->country == "Argentina") {{ "selected" }} @endif value="Argentina">Argentina</option>
                              <option @if(@$event_content->country == "Armenia") {{ "selected" }} @endif value="Armenia">Armenia</option>
                              <option @if(@$event_content->country == "Aruba") {{ "selected" }} @endif value="Aruba">Aruba</option>
                              <option @if(@$event_content->country == "Australia") {{ "selected" }} @endif value="Australia">Australia</option>
                              <option @if(@$event_content->country == "Austria") {{ "selected" }} @endif value="Austria">Austria</option>
                              <option @if(@$event_content->country == "Azerbaijan") {{ "selected" }} @endif value="Azerbaijan">Azerbaijan</option>
                              <option @if(@$event_content->country == "Bahamas") {{ "selected" }} @endif value="Bahamas">Bahamas</option>
                              <option @if(@$event_content->country == "Bahrain") {{ "selected" }} @endif value="Bahrain">Bahrain</option>
                              <option @if(@$event_content->country == "Bangladesh") {{ "selected" }} @endif value="Bangladesh">Bangladesh</option>
                              <option @if(@$event_content->country == "Barbados") {{ "selected" }} @endif value="Barbados">Barbados</option>
                              <option @if(@$event_content->country == "Belarus") {{ "selected" }} @endif value="Belarus">Belarus</option>
                              <option @if(@$event_content->country == "Belgium") {{ "selected" }} @endif value="Belgium">Belgium</option>
                              <option @if(@$event_content->country == "Belize") {{ "selected" }} @endif value="Belize">Belize</option>
                              <option @if(@$event_content->country == "Benin") {{ "selected" }} @endif value="Benin">Benin</option>
                              <option @if(@$event_content->country == "Bermuda") {{ "selected" }} @endif value="Bermuda">Bermuda</option>
                              <option @if(@$event_content->country == "Bhutan") {{ "selected" }} @endif value="Bhutan">Bhutan</option>
                              <option @if(@$event_content->country == "Bolivia") {{ "selected" }} @endif value="Bolivia">Bolivia</option>
                              <option @if(@$event_content->country == "Bonaire, Sint Eustatius and Saba") {{ "selected" }} @endif value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba
                              </option>
                              <option @if(@$event_content->country == "Bosnia and Herzegovina") {{ "selected" }} @endif value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                              <option @if(@$event_content->country == "Botswana") {{ "selected" }} @endif value="Botswana">Botswana</option>
                              <option @if(@$event_content->country == "Bouvet Island") {{ "selected" }} @endif value="Bouvet Island">Bouvet Island</option>
                              <option @if(@$event_content->country == "Brazil") {{ "selected" }} @endif value="Brazil">Brazil</option>
                              <option @if(@$event_content->country == "British Indian Ocean Territory") {{ "selected" }} @endif value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                              <option @if(@$event_content->country == "Brunei Darussalam") {{ "selected" }} @endif value="Brunei Darussalam">Brunei Darussalam</option>
                              <option @if(@$event_content->country == "Bulgaria") {{ "selected" }} @endif value="Bulgaria Bulgaria">Bulgaria</option>
                              <option @if(@$event_content->country == "Burkina Faso") {{ "selected" }} @endif value="Burkina Faso">Burkina Faso</option>
                              <option @if(@$event_content->country == "Burundi") {{ "selected" }} @endif value="Burundi">Burundi</option>
                              <option @if(@$event_content->country == "Cambodia") {{ "selected" }} @endif value="Cambodia">Cambodia</option>
                              <option @if(@$event_content->country == "Cameroon") {{ "selected" }} @endif value="Cameroon">Cameroon</option>
                              <option @if(@$event_content->country == "Canada") {{ "selected" }} @endif value="Canada">Canada</option>
                              <option @if(@$event_content->country == "Cape Verde") {{ "selected" }} @endif value="Cape">Cape Verde</option>
                              <option @if(@$event_content->country == "Cayman Islands") {{ "selected" }} @endif value="Cayman Islands">Cayman Islands</option>
                              <option @if(@$event_content->country == "Central African Republic") {{ "selected" }} @endif value="Central African Republic">Central African Republic</option>
                              <option @if(@$event_content->country == "Chad") {{ "selected" }} @endif value="Chad">Chad</option>
                              <option @if(@$event_content->country == "Chile") {{ "selected" }} @endif value="Chile">Chile</option>
                              <option @if(@$event_content->country == "China") {{ "selected" }} @endif value="China">China</option>
                              <option @if(@$event_content->country == "Christmas Island") {{ "selected" }} @endif value="Christmas Island">Christmas Island</option>
                              <option @if(@$event_content->country == "Cocos (Keeling) Islands") {{ "selected" }} @endif value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                              <option @if(@$event_content->country == "Colombia") {{ "selected" }} @endif value="Colombia">Colombia</option>
                              <option @if(@$event_content->country == "Comoros") {{ "selected" }} @endif value="Comoros">Comoros</option>
                              <option @if(@$event_content->country == "Congo") {{ "selected" }} @endif value="Congo">Congo</option>
                              <option @if(@$event_content->country == "Congo, Democratic Republic of the Congo") {{ "selected" }} @endif value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of
                                the Congo</option>
                              <option @if(@$event_content->country == "Cook Islands") {{ "selected" }} @endif value="Cook Islands">Cook Islands</option>
                              <option @if(@$event_content->country == "Costa Rica") {{ "selected" }} @endif value="Costa Rica">Costa Rica</option>
                              <option @if(@$event_content->country == "Cote D'Ivoire") {{ "selected" }} @endif value="Cote D'Ivoire">Cote D'Ivoire</option>
                              <option @if(@$event_content->country == "Croatia") {{ "selected" }} @endif value="Croatia">Croatia</option>
                              <option @if(@$event_content->country == "Cuba") {{ "selected" }} @endif value="Cuba">Cuba</option>
                              <option @if(@$event_content->country == "Curacao") {{ "selected" }} @endif value="Curacao">Curacao</option>
                              <option @if(@$event_content->country == "Cyprus") {{ "selected" }} @endif value="Cyprus">Cyprus</option>
                              <option @if(@$event_content->country == "Czech Republic") {{ "selected" }} @endif value="Czech Republic">Czech Republic</option>
                              <option @if(@$event_content->country == "Denmark") {{ "selected" }} @endif value="Denmark">Denmark</option>
                              <option @if(@$event_content->country == "Djibouti") {{ "selected" }} @endif value="Djibouti">Djibouti</option>
                              <option @if(@$event_content->country == "Dominica") {{ "selected" }} @endif value="Dominica">Dominica</option>
                              <option @if(@$event_content->country == "Dominican Republic") {{ "selected" }} @endif value="Dominican Republic">Dominican Republic</option>
                              <option @if(@$event_content->country == "Ecuador") {{ "selected" }} @endif value="Ecuador">Ecuador</option>
                              <option @if(@$event_content->country == "Egypt") {{ "selected" }} @endif value="Egypt">Egypt</option>
                              <option @if(@$event_content->country == "El Salvador") {{ "selected" }} @endif value="El Salvador">El Salvador</option>
                              <option @if(@$event_content->country == "Equatorial Guinea") {{ "selected" }} @endif value="Equatorial Guinea">Equatorial Guinea</option>
                              <option @if(@$event_content->country == "Eritrea") {{ "selected" }} @endif value="Eritrea">Eritrea</option>
                              <option @if(@$event_content->country == "Estonia") {{ "selected" }} @endif value="Estonia">Estonia</option>
                              <option @if(@$event_content->country == "Ethiopia") {{ "selected" }} @endif value="Ethiopia">Ethiopia</option>
                              <option @if(@$event_content->country == "Falkland Islands (Malvinas)") {{ "selected" }} @endif value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                              <option @if(@$event_content->country == "Faroe Islands") {{ "selected" }} @endif value="Faroe Islands">Faroe Islands</option>
                              <option @if(@$event_content->country == "Fiji") {{ "selected" }} @endif value="Fiji">Fiji</option>
                              <option @if(@$event_content->country == "Finland") {{ "selected" }} @endif value="Finland">Finland</option>
                              <option @if(@$event_content->country == "France") {{ "selected" }} @endif value="France">France</option>
                              <option @if(@$event_content->country == "French Guiana") {{ "selected" }} @endif value="French Guiana">French Guiana</option>
                              <option @if(@$event_content->country == "French Polynesia") {{ "selected" }} @endif value="French Polynesia">French Polynesia</option>
                              <option @if(@$event_content->country == "French Southern Territories") {{ "selected" }} @endif value="French Southern Territories">French Southern Territories</option>
                              <option @if(@$event_content->country == "Gabon") {{ "selected" }} @endif value="Gabon">Gabon</option>
                              <option @if(@$event_content->country == "Gambia") {{ "selected" }} @endif value="Gambia">Gambia</option>
                              <option @if(@$event_content->country == "Georgia") {{ "selected" }} @endif value="Georgia">Georgia</option>
                              <option @if(@$event_content->country == "Germany") {{ "selected" }} @endif value="Germany">Germany</option>
                              <option @if(@$event_content->country == "Ghana") {{ "selected" }} @endif value="Ghana">Ghana</option>
                              <option @if(@$event_content->country == "Gibraltar") {{ "selected" }} @endif value="Gibraltar">Gibraltar</option>
                              <option @if(@$event_content->country == "Greece") {{ "selected" }} @endif value="Greece">Greece</option>
                              <option @if(@$event_content->country == "Greenland") {{ "selected" }} @endif value="Greenland">Greenland</option>
                              <option @if(@$event_content->country == "Grenada") {{ "selected" }} @endif value="Grenada">Grenada</option>
                              <option @if(@$event_content->country == "Guadeloupe") {{ "selected" }} @endif value="Guadeloupe">Guadeloupe</option>
                              <option @if(@$event_content->country == "Guam") {{ "selected" }} @endif value="Guam">Guam</option>
                              <option @if(@$event_content->country == "Guatemala") {{ "selected" }} @endif value="Guatemala">Guatemala</option>
                              <option @if(@$event_content->country == "Guernsey") {{ "selected" }} @endif value="Guernsey">Guernsey</option>
                              <option @if(@$event_content->country == "Guinea") {{ "selected" }} @endif value="Guinea">Guinea</option>
                              <option @if(@$event_content->country == "Guinea-Bissau") {{ "selected" }} @endif value="Guinea-Bissau">Guinea-Bissau</option>
                              <option @if(@$event_content->country == "Guyana") {{ "selected" }} @endif value="Guyana">Guyana</option>
                              <option @if(@$event_content->country == "Haiti") {{ "selected" }} @endif value="Haiti">Haiti</option>
                              <option @if(@$event_content->country == "Heard Island and Mcdonald Islands") {{ "selected" }} @endif value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands
                              </option>
                              <option @if(@$event_content->country == "Holy See (Vatican City State)") {{ "selected" }} @endif value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                              <option @if(@$event_content->country == "Honduras") {{ "selected" }} @endif value="Honduras">Honduras</option>
                              <option @if(@$event_content->country == "Hong Kong") {{ "selected" }} @endif value="Hong Kong">Hong Kong</option>
                              <option @if(@$event_content->country == "Hungary") {{ "selected" }} @endif value="Hungary">Hungary</option>
                              <option @if(@$event_content->country == "Iceland") {{ "selected" }} @endif value="Iceland">Iceland</option>
                              <option @if(@$event_content->country == "India") {{ "selected" }} @endif value="India">India</option>
                              <option @if(@$event_content->country == "Indonesia") {{ "selected" }} @endif value="Indonesia">Indonesia</option>
                              <option @if(@$event_content->country == "Iran, Islamic Republic of") {{ "selected" }} @endif value="Iran Islamic Republic of">Iran, Islamic Republic of</option>
                              <option @if(@$event_content->country == "Iraq") {{ "selected" }} @endif value="Iraq">Iraq</option>
                              <option @if(@$event_content->country == "Ireland") {{ "selected" }} @endif value="Ireland">Ireland</option>
                              <option @if(@$event_content->country == "Isle of Man") {{ "selected" }} @endif value="Isle">Isle of Man</option>
                              <option @if(@$event_content->country == "Israel") {{ "selected" }} @endif value="Israel">Israel</option>
                              <option @if(@$event_content->country == "Italy") {{ "selected" }} @endif value="Italy">Italy</option>
                              <option @if(@$event_content->country == "Jamaica") {{ "selected" }} @endif value="Jamaica">Jamaica</option>
                              <option @if(@$event_content->country == "Japan") {{ "selected" }} @endif value="Japan">Japan</option>
                              <option @if(@$event_content->country == "Jersey") {{ "selected" }} @endif value="Jersey">Jersey</option>
                              <option @if(@$event_content->country == "Jordan") {{ "selected" }} @endif value="Jordan">Jordan</option>
                              <option @if(@$event_content->country == "Kazakhstan") {{ "selected" }} @endif value="Kazakhstan">Kazakhstan</option>
                              <option @if(@$event_content->country == "Kenya") {{ "selected" }} @endif value="Kenya">Kenya</option>
                              <option @if(@$event_content->country == "Kiribati") {{ "selected" }} @endif value="Kiribati">Kiribati</option>
                              <option @if(@$event_content->country == "Korea, Democratic People's Republic of") {{ "selected" }} @endif value="Korea Democratic People's Republic of">Korea, Democratic People's
                                Republic of</option>
                              <option @if(@$event_content->country == "Korea, Republic of") {{ "selected" }} @endif value="Korea Korea, Republic of">Korea, Republic of</option>
                              <option @if(@$event_content->country == "Kosovo") {{ "selected" }} @endif value="Kosovo">Kosovo</option>
                              <option @if(@$event_content->country == "Kuwait") {{ "selected" }} @endif value="Kuwait">Kuwait</option>
                              <option @if(@$event_content->country == "Kyrgyzstan") {{ "selected" }} @endif value="Kyrgyzstan">Kyrgyzstan</option>
                              <option @if(@$event_content->country == "Lao People's Democratic Republic") {{ "selected" }} @endif value="Lao People's Democratic Republic">Lao People's Democratic Republic
                              </option>
                              <option @if(@$event_content->country == "Latvia") {{ "selected" }} @endif value="Latvia">Latvia</option>
                              <option @if(@$event_content->country == "Lebanon") {{ "selected" }} @endif value="Lebanon">Lebanon</option>
                              <option @if(@$event_content->country == "Lesotho") {{ "selected" }} @endif value="Lesotho">Lesotho</option>
                              <option @if(@$event_content->country == "Liberia") {{ "selected" }} @endif value="Liberia">Liberia</option>
                              <option @if(@$event_content->country == "Libyan Arab Jamahiriya") {{ "selected" }} @endif value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                              <option @if(@$event_content->country == "Liechtenstein") {{ "selected" }} @endif value="Liechtenstein">Liechtenstein</option>
                              <option @if(@$event_content->country == "Lithuania") {{ "selected" }} @endif value="Lithuania">Lithuania</option>
                              <option @if(@$event_content->country == "Luxembourg") {{ "selected" }} @endif value="Luxembourg">Luxembourg</option>
                              <option @if(@$event_content->country == "Macao") {{ "selected" }} @endif value="Macao">Macao</option>
                              <option @if(@$event_content->country == "Macedonia, the Former Yugoslav Republic of") {{ "selected" }} @endif value="Macedonia the Former Yugoslav Republic of">Macedonia, the Former
                                Yugoslav Republic of</option>
                              <option @if(@$event_content->country == "Madagascar") {{ "selected" }} @endif value="Madagascar">Madagascar</option>
                              <option @if(@$event_content->country == "Malawi") {{ "selected" }} @endif value="Malawi">Malawi</option>
                              <option @if(@$event_content->country == "Malaysia") {{ "selected" }} @endif value="Malaysia">Malaysia</option>
                              <option @if(@$event_content->country == "Maldives") {{ "selected" }} @endif value="Maldives">Maldives</option>
                              <option @if(@$event_content->country == "Mali") {{ "selected" }} @endif value="Mali">Mali</option>
                              <option @if(@$event_content->country == "Malta") {{ "selected" }} @endif value="Malta">Malta</option>
                              <option @if(@$event_content->country == "Marshall Islands") {{ "selected" }} @endif value="Marshall">Marshall Islands</option>
                              <option @if(@$event_content->country == "Martinique") {{ "selected" }} @endif value="Martinique">Martinique</option>
                              <option @if(@$event_content->country == "Mauritania") {{ "selected" }} @endif value="Mauritania">Mauritania</option>
                              <option @if(@$event_content->country == "Mauritius") {{ "selected" }} @endif value="Mauritius">Mauritius</option>
                              <option @if(@$event_content->country == "Mayotte") {{ "selected" }} @endif value="Mayotte">Mayotte</option>
                              <option @if(@$event_content->country == "Mexico") {{ "selected" }} @endif value="Mexico">Mexico</option>
                              <option @if(@$event_content->country == "Micronesia, Federated States of") {{ "selected" }} @endif value="Micronesia Federated States of">Micronesia, Federated States of
                              </option>
                              <option @if(@$event_content->country == "Moldova, Republic of") {{ "selected" }} @endif value="Moldova, Republic of">Moldova, Republic of</option>
                              <option @if(@$event_content->country == "Monaco") {{ "selected" }} @endif value="Monaco">Monaco</option>
                              <option @if(@$event_content->country == "Mongolia") {{ "selected" }} @endif value="Mongolia">Mongolia</option>
                              <option @if(@$event_content->country == "Montenegro") {{ "selected" }} @endif value="Montenegro">Montenegro</option>
                              <option @if(@$event_content->country == "Montserrat") {{ "selected" }} @endif value="Montserrat">Montserrat</option>
                              <option @if(@$event_content->country == "Morocco") {{ "selected" }} @endif value="Morocco">Morocco</option>
                              <option @if(@$event_content->country == "Mozambique") {{ "selected" }} @endif value="Mozambique">Mozambique</option>
                              <option @if(@$event_content->country == "Myanmar") {{ "selected" }} @endif value="Myanmar">Myanmar</option>
                              <option @if(@$event_content->country == "Namibia") {{ "selected" }} @endif value="Namibia">Namibia</option>
                              <option @if(@$event_content->country == "Nauru") {{ "selected" }} @endif value="Nauru">Nauru</option>
                              <option @if(@$event_content->country == "Nepal") {{ "selected" }} @endif value="Nepal">Nepal</option>
                              <option @if(@$event_content->country == "Netherlands") {{ "selected" }} @endif value="Netherlands">Netherlands</option>
                              <option @if(@$event_content->country == "Netherlands Antilles") {{ "selected" }} @endif value="Netherlands Antilles">Netherlands Antilles</option>
                              <option @if(@$event_content->country == "New Caledonia") {{ "selected" }} @endif value="New Caledonia">New Caledonia</option>
                              <option @if(@$event_content->country == "New Zealand") {{ "selected" }} @endif value="New Zealand">New Zealand</option>
                              <option @if(@$event_content->country == "Nicaragua") {{ "selected" }} @endif value="Nicaragua">Nicaragua</option>
                              <option @if(@$event_content->country == "Niger") {{ "selected" }} @endif value="Niger">Niger</option>
                              <option @if(@$event_content->country == "Nigeria") {{ "selected" }} @endif value="Nigeria">Nigeria</option>
                              <option @if(@$event_content->country == "Niue") {{ "selected" }} @endif value="Niue">Niue</option>
                              <option @if(@$event_content->country == "Norfolk Island") {{ "selected" }} @endif value="Norfolk Island">Norfolk Island</option>
                              <option @if(@$event_content->country == "Northern Mariana Islands") {{ "selected" }} @endif value="Northern Mariana Islands">Northern Mariana Islands</option>
                              <option @if(@$event_content->country == "Norway") {{ "selected" }} @endif value="Norway">Norway</option>
                              <option @if(@$event_content->country == "Oman") {{ "selected" }} @endif value="Oman">Oman</option>
                              <option @if(@$event_content->country == "Pakistan") {{ "selected" }} @endif value="Pakistan">Pakistan</option>
                              <option @if(@$event_content->country == "Palau") {{ "selected" }} @endif value="Palau">Palau</option>
                              <option @if(@$event_content->country == "Palestinian Territory, Occupied") {{ "selected" }} @endif value="Palestinian Territory, Occupied">Palestinian Territory, Occupied
                              </option>
                              <option @if(@$event_content->country == "Panama") {{ "selected" }} @endif value="Panama">Panama</option>
                              <option @if(@$event_content->country == "Papua New Guinea") {{ "selected" }} @endif value="Papua New Guinea">Papua New Guinea</option>
                              <option @if(@$event_content->country == "Paraguay") {{ "selected" }} @endif value="Paraguay">Paraguay</option>
                              <option @if(@$event_content->country == "Peru") {{ "selected" }} @endif value="Peru">Peru</option>
                              <option @if(@$event_content->country == "Philippines") {{ "selected" }} @endif value="Philippines">Philippines</option>
                              <option @if(@$event_content->country == "Pitcairn") {{ "selected" }} @endif value="Pitcairn">Pitcairn</option>
                              <option @if(@$event_content->country == "Poland") {{ "selected" }} @endif value="Poland">Poland</option>
                              <option @if(@$event_content->country == "Portugal") {{ "selected" }} @endif value="Portugal">Portugal</option>
                              <option @if(@$event_content->country == "Puerto Rico") {{ "selected" }} @endif value="Puerto">Puerto Rico</option>
                              <option @if(@$event_content->country == "Qatar") {{ "selected" }} @endif value="Qatar">Qatar</option>
                              <option @if(@$event_content->country == "Reunion") {{ "selected" }} @endif value="Reunion">Reunion</option>
                              <option @if(@$event_content->country == "Romania") {{ "selected" }} @endif value="Romania">Romania</option>
                              <option @if(@$event_content->country == "Russian Federation") {{ "selected" }} @endif value="Russian Federation">Russian Federation</option>
                              <option @if(@$event_content->country == "Rwanda") {{ "selected" }} @endif value="Rwanda">Rwanda</option>
                              <option @if(@$event_content->country == "Saint Barthelemy") {{ "selected" }} @endif value="Saint Barthelemy">Saint Barthelemy</option>
                              <option @if(@$event_content->country == "Saint Helena") {{ "selected" }} @endif value="Saint Helena">Saint Helena</option>
                              <option @if(@$event_content->country == "Saint Kitts and Nevis") {{ "selected" }} @endif value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                              <option @if(@$event_content->country == "Saint Lucia") {{ "selected" }} @endif value="Saint Lucia">Saint Lucia</option>
                              <option @if(@$event_content->country == "Saint Martin") {{ "selected" }} @endif value="Saint Martin">Saint Martin</option>
                              <option @if(@$event_content->country == "Saint Pierre and Miquelon") {{ "selected" }} @endif value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                              <option @if(@$event_content->country == "Saint Vincent and the Grenadines") {{ "selected" }} @endif value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
                              </option>
                              <option @if(@$event_content->country == "Samoa") {{ "selected" }} @endif value="Samoa">Samoa</option>
                              <option @if(@$event_content->country == "San Marino") {{ "selected" }} @endif value="San Marino">San Marino</option>
                              <option @if(@$event_content->country == "Sao Tome and Principe") {{ "selected" }} @endif value="Sao Tome and Principe">Sao Tome and Principe</option>
                              <option @if(@$event_content->country == "Saudi Arabia") {{ "selected" }} @endif value="Saudi Arabia">Saudi Arabia</option>
                              <option @if(@$event_content->country == "Senegal") {{ "selected" }} @endif value="Senegal">Senegal</option>
                              <option @if(@$event_content->country == "Serbia") {{ "selected" }} @endif value="Serbia">Serbia</option>
                              <option @if(@$event_content->country == "Serbia and Montenegro") {{ "selected" }} @endif value="Serbia and Montenegro">Serbia and Montenegro</option>
                              <option @if(@$event_content->country == "Seychelles") {{ "selected" }} @endif value="Seychelles">Seychelles</option>
                              <option @if(@$event_content->country == "Sierra Leone") {{ "selected" }} @endif value="Sierra Leone">Sierra Leone</option>
                              <option @if(@$event_content->country == "Singapore") {{ "selected" }} @endif value="Singapore">Singapore</option>
                              <option @if(@$event_content->country == "Sint Maarten") {{ "selected" }} @endif value="Sint Maarten">Sint Maarten</option>
                              <option @if(@$event_content->country == "Slovakia") {{ "selected" }} @endif value="Slovakia">Slovakia</option>
                              <option @if(@$event_content->country == "Slovenia") {{ "selected" }} @endif value="Slovenia">Slovenia</option>
                              <option @if(@$event_content->country == "Solomon Islands") {{ "selected" }} @endif value="Solomon Islands">Solomon Islands</option>
                              <option @if(@$event_content->country == "Somalia") {{ "selected" }} @endif value="Somalia">Somalia</option>
                              <option @if(@$event_content->country == "South Africa") {{ "selected" }} @endif value="South Africa">South Africa</option>
                              <option @if(@$event_content->country == "South Georgia and the South Sandwich Islands") {{ "selected" }} @endif value="South Georgia and the South Sandwich Islands">South Georgia and the
                                South Sandwich Islands</option>
                              <option @if(@$event_content->country == "South Sudan") {{ "selected" }} @endif value="South Sudan">South Sudan</option>
                              <option @if(@$event_content->country == "Spain") {{ "selected" }} @endif value="Spain">Spain</option>
                              <option @if(@$event_content->country == "Sri Lanka") {{ "selected" }} @endif value="Sri Lanka">Sri Lanka</option>
                              <option @if(@$event_content->country == "Sudan") {{ "selected" }} @endif value="Sudan">Sudan</option>
                              <option @if(@$event_content->country == "Suriname") {{ "selected" }} @endif value="Suriname">Suriname</option>
                              <option @if(@$event_content->country == "Svalbard and Jan Mayen") {{ "selected" }} @endif value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                              <option @if(@$event_content->country == "Swaziland") {{ "selected" }} @endif value="Swaziland">Swaziland</option>
                              <option @if(@$event_content->country == "Sweden") {{ "selected" }} @endif value="Sweden">Sweden</option>
                              <option @if(@$event_content->country == "Switzerland") {{ "selected" }} @endif value="Switzerland">Switzerland</option>
                              <option @if(@$event_content->country == "Syrian Arab Republic") {{ "selected" }} @endif value="Syrian Arab Republic">Syrian Arab Republic</option>
                              <option @if(@$event_content->country == "Taiwan, Province of China") {{ "selected" }} @endif value="Taiwan Province of China">Taiwan, Province of China</option>
                              <option @if(@$event_content->country == "Tajikistan") {{ "selected" }} @endif value="Tajikistan">Tajikistan</option>
                              <option @if(@$event_content->country == "Tanzania, United Republic of") {{ "selected" }} @endif value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                              <option @if(@$event_content->country == "Thailand") {{ "selected" }} @endif value="Thailand">Thailand</option>
                              <option @if(@$event_content->country == "Timor-Leste") {{ "selected" }} @endif value="Timor -Leste">Timor-Leste</option>
                              <option @if(@$event_content->country == "Togo") {{ "selected" }} @endif value="Togo">Togo</option>
                              <option @if(@$event_content->country == "Tokelau") {{ "selected" }} @endif value="Tokelau">Tokelau</option>
                              <option @if(@$event_content->country == "Tonga") {{ "selected" }} @endif value="Tonga">Tonga</option>
                              <option @if(@$event_content->country == "Trinidad and Tobago") {{ "selected" }} @endif value="Trinidad and Tobago">Trinidad and Tobago</option>
                              <option @if(@$event_content->country == "Tunisia") {{ "selected" }} @endif value="Tunisia">Tunisia</option>
                              <option @if(@$event_content->country == "Turkey") {{ "selected" }} @endif value="Turkey">Turkey</option>
                              <option @if(@$event_content->country == "Turkmenistan") {{ "selected" }} @endif value="Turkmenistan">Turkmenistan</option>
                              <option @if(@$event_content->country == "Turks and Caicos Islands") {{ "selected" }} @endif value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                              <option @if(@$event_content->country == "Tuvalu") {{ "selected" }} @endif value="Tuvalu">Tuvalu</option>
                              <option @if(@$event_content->country == "Uganda") {{ "selected" }} @endif value="Uganda">Uganda</option>
                              <option @if(@$event_content->country == "Ukraine") {{ "selected" }} @endif value="Ukraine">Ukraine</option>
                              <option @if(@$event_content->country == "United Arab Emirates") {{ "selected" }} @endif value="United Arab Emirates">United Arab Emirates</option>
                              <option @if(@$event_content->country == "United Kingdom") {{ "selected" }} @endif value="United Kingdom">United Kingdom</option>
                              <option @if(@$event_content->country == "United States") {{ "selected" }} @endif value="United States">United States</option>
                              <option @if(@$event_content->country == "United States Minor Outlying Islands") {{ "selected" }} @endif value="United States Minor Outlying Islands">United States Minor Outlying
                                Islands</option>
                              <option @if(@$event_content->country == "Uruguay") {{ "selected" }} @endif value="Uruguay">Uruguay</option>
                              <option @if(@$event_content->country == "Uzbekistan") {{ "selected" }} @endif value="Uzbekistan">Uzbekistan</option>
                              <option @if(@$event_content->country == "Vanuatu") {{ "selected" }} @endif value="Vanuatu">Vanuatu</option>
                              <option @if(@$event_content->country == "Venezuela") {{ "selected" }} @endif value="Venezuela">Venezuela</option>
                              <option @if(@$event_content->country == "Viet Nam") {{ "selected" }} @endif value="Viet Nam">Viet Nam</option>
                              <option @if(@$event_content->country == "Virgin Islands, British") {{ "selected" }} @endif value="Virgin Islands, British">Virgin Islands, British</option>
                              <option @if(@$event_content->country == "Virgin Islands, U.s.") {{ "selected" }} @endif value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                              <option @if(@$event_content->country == "Wallis and Futuna") {{ "selected" }} @endif value="Wallis and Futuna">Wallis and Futuna</option>
                              <option @if(@$event_content->country == "Western Sahara") {{ "selected" }} @endif value="Western Sahara">Western Sahara</option>
                              <option @if(@$event_content->country == "Yemen") {{ "selected" }} @endif value="Yemen">Yemen</option>
                              <option @if(@$event_content->country == "Zambia") {{ "selected" }} @endif value="Zambia">Zambia</option>
                              <option @if(@$event_content->country == "Zimbabwe") {{ "selected" }} @endif value="Zimbabwe">Zimbabwe</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label for="">{{ __('Sate') }}</label>
                            <input type="text" name="{{ $language->code }}_state" id="state" class="form-control" placeholder="Enter State" value="{{ @$event_content->state }}">
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label for="">{{ __('City') . '*' }}</label>
                            <input type="text" name="{{ $language->code }}_city" id="city" class="form-control" placeholder="Enter City" value="{{ @$event_content->city }}">
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label for="">{{ __('Zip/Post Code ') }}</label>
                            <input type="text" placeholder="Enter Zip/Post Code" id="zip" name="{{ $language->code }}_zip_code" class="form-control" value="{{ @$event_content->zip_code }}">
                          </div>
                        </div>
                      </div>
                      @endif

                      <div class="row">
                        <div class="col">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label>{{ __('Description') . '*' }}</label>
                            <textarea id="descriptionTmce{{ $language->id }}" class="form-control summernote" name="{{ $language->code }}_description" placeholder="Enter Event Description" data-height="300">{!! @$event_content->description !!}</textarea>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label>{{ __('Refund Policy') }} *</label>
                            <textarea class="form-control" name="{{ $language->code }}_refund_policy" rows="5" placeholder="Enter Refund Policy">{{ @$event_content->refund_policy }}</textarea>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label>{{ __('Event Meta Keywords') }}</label>
                            <input class="form-control" name="{{ $language->code }}_meta_keywords" value="{{ @$event_content->meta_keywords }}" placeholder="Enter Meta Keywords" data-role="tagsinput">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <label>{{ __('Event Meta Description') }}</label>
                            <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5" placeholder="Enter Meta Description">{{ @$event_content->meta_description }}</textarea>
                          </div>
                        </div>
                      </div>



                      <div class="row">
                        <div class="col">
                          @php $currLang = $language; @endphp

                          @foreach ($languages as $language)
                          @continue($language->id == $currLang->id)

                          <div class="form-check py-0">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                              <span class="form-check-sign">{{ __('Clone for') }} <strong class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                {{ __('language') }}</span>
                            </label>
                          </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>

              <div id="sliders"></div>
            </form>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <div class="row">
          <div class="col-12 text-center">
            <button type="submit" id="EventSubmitadminupdate" class="btn btn-primary">
              {{ __('Update') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
@php
$languages = App\Models\Language::get();
@endphp
<script>
  let languages = "{{ $languages }}";
</script>
<script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin_dropzone.js') }}"></script>
<script>
  $(document).ready(function() {
    $('.is_featured').trigger('change');

    $('.js-example-basic-single').select2();
  });
</script>
@endsection

@section('variables')
<script>
  "use strict";
  var storeUrl = "{{ route('admin.event.imagesstore') }}";
  var removeUrl = "{{ route('admin.event.imagermv') }}";

  var rmvdbUrl = "{{ route('admin.event.imgdbrmv') }}";
  var loadImgs = "{{ route('admin.event.images', $event->id) }}";

// google map api code start 
let map, marker, geocoder;

function initMap() {
  // Initialize map
  map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 37.7749, lng: -122.4194 },
    zoom: 12
  });

  // Initialize marker (initial position)
  marker = new google.maps.Marker({
    position: { lat: 37.7749, lng: -122.4194 },
    map: map,
    draggable: true
  });

  // Initialize geocoder
  geocoder = new google.maps.Geocoder();

  // Add event listener for marker drag
  marker.addListener('dragend', onMarkerDragEnd);

  // Retrieve initial location from database (replace with your data)
  const initialLatitude = $('#lat').val();
  const initialLongitude =  $('#lng').val();
  const initialLatLng = new google.maps.LatLng(initialLatitude, initialLongitude);
  marker.setPosition(initialLatLng);
  map.setCenter(initialLatLng);
  updateLocationInfo(initialLatLng);
}

function onMarkerDragEnd(event) {
  const position = event.latLng;

  // Update marker position
  marker.setPosition(position);

  // Update map center
  map.setCenter(position);

  // Update location information
  updateLocationInfo(position);
}

function updateLocationInfo(position) {
  // Get address details based on marker position
  geocoder.geocode({ location: position }, (results, status) => {
    if (status === 'OK') {
      if (results[0]) {
        const addressComponents = results[0].address_components;
        const formattedAddress = results[0].formatted_address;
        let country, city, state, zip;

        for (const component of addressComponents) {
          if (component.types.includes('locality')) {
            city = component.long_name;
          }
          if (component.types.includes('administrative_area_level_1')) {
            state = component.short_name;
          }
          if (component.types.includes('postal_code')) {
            zip = component.long_name;
          }
          if (component.types.includes('country')) {
          country = component.long_name;
        }
        }
        document.getElementById('location').value = formattedAddress;
        document.getElementById('city').value = city || '';
        document.getElementById('country').value = country || '';
        document.getElementById('state').value = state || '';
        document.getElementById('zip').value = zip || '';
        document.getElementById('lat').value = position.lat();
        document.getElementById('lng').value = position.lng();
      }
    } else {
      console.error('Geocoder failed due to: ' + status);
    }
  });
}
    
  // google map api code end 

  $(document).ready(function(){
        $('.rtl').eq(0).children().closest('input').attr("placeholder", "أدخل اسم الحدث");
        $('.rtl').eq(0).children().closest('label').text("عنوان الحدث*");
        $('.rtl').eq(1).children().closest('select').find('option').eq(0).text("اختر الفئة");
        $('.rtl').eq(1).children().closest('label').text("فئة*");
        $('.rtl').eq(2).children().closest('input').attr("placeholder", "عنوان");
        $('.rtl').eq(2).children().closest('label').text("عنوان*");
        $('.rtl').eq(3).children().closest('select').find('option').remove();
        $('.rtl').eq(3).children().closest('label').text("مقاطعة*");
        $('.rtl').eq(4).children().closest('label').text("أشبع*");
        $('.rtl').eq(4).children().closest('input').attr("placeholder", "إدخال الدولة");
        $('.rtl').eq(5).children().closest('label').text("مدينة*");
        $('.rtl').eq(5).children().closest('input').attr("placeholder", "أدخل المدينة");
        $('.rtl').eq(6).children().closest('label').text("الرمز البريدي / الرمز البريدي*");
        $('.rtl').eq(6).children().closest('input').attr("placeholder", "أدخل الرمز البريدي");
        $('.rtl').eq(7).children().closest('label').text("وصف*");
        $('.rtl').eq(7).children().closest('textarea').attr("placeholder", "أدخل وصف الحدث");
        $('.rtl').eq(8).children().closest('label').text("سياسة الاسترجاع*");
        $('.rtl').eq(8).children().closest('textarea').attr("placeholder", "أدخل سياسة الاسترداد");
        $('.rtl').eq(9).children().closest('label').text("الكلمات المفتاحية للحدث*");
        $('.rtl').eq(9).children().children().closest('input').attr("placeholder", "أدخل الكلمات الأساسية ميتا");
        $('.rtl').eq(10).children().closest('label').text("وصف تعريف الحدث*");
        $('.rtl').eq(10).children().closest('textarea').attr("placeholder", "أدخل وصف التعريف");
        $('.rtl').eq(3).children().closest('select').append('<option>حدد الدولة</option>\
        <option>اختر الدولة</option>\
    <option value="أفغانستان">أفغانستان</option>\
    <option value="جزر آلاند">جزر آلاند</option>\
    <option value="ألبانيا">ألبانيا</option>\
    <option value="الجزائر">الجزائر</option>\
    <option value="ساموا الأمريكية">ساموا الأمريكية</option>\
    <option value="أندورا">أندورا</option>\
    <option value="أنغولا">أنغولا</option>\
    <option value="أنغويلا">أنغويلا</option>\
    <option value="القطب الجنوبي">القطب الجنوبي</option>\
    <option value="أنتيغوا وبربودا">أنتيغوا وبربودا</option>\
    <option value="الأرجنتين">الأرجنتين</option>\
    <option value="أرمينيا">أرمينيا</option>\
    <option value="أروبا">أروبا</option>\
    <option value="أستراليا">أستراليا</option>\
    <option value="النمسا">النمسا</option>\
    <option value="أذربيجان">أذربيجان</option>\
    <option value="البهاما">البهاما</option>\
    <option value="البحرين">البحرين</option>\
    <option value="بنغلاديش">بنغلاديش</option>\
    <option value="بربادوس">بربادوس</option>\
    <option value="بيلاروسيا">بيلاروسيا</option>\
    <option value="بلجيكا">بلجيكا</option>\
    <option value="بليز">بليز</option>\
    <option value="بنين">بنين</option>\
    <option value="برمودا">برمودا</option>\
    <option value="بوتان">بوتان</option>\
    <option value="بوليفيا">بوليفيا</option>\
    <option value="بونير وسانت أوستايوس وسابا">بونير وسانت أوستايوس وسابا</option>\
    <option value="البوسنة والهرسك">البوسنة والهرسك</option>\
    <option value="بوتسوانا">بوتسوانا</option>\
    <option value="جزيرة بوفيه">جزيرة بوفيه</option>\
    <option value="البرازيل">البرازيل</option>\
    <option value="إقليم المحيط البريطاني الهندي">إقليم المحيط البريطاني الهندي</option>\
    <option value="بروناي">بروناي</option>\
    <option value="بلغاريا">بلغاريا</option>\
    <option value="بوركينا فاسو">بوركينا فاسو</option>\
    <option value="بوروندي">بوروندي</option>\
    <option value="كمبوديا">كمبوديا</option>\
    <option value="الكاميرون">الكاميرون</option>\
    <option value="كندا">كندا</option>\
    <option value="الرأس الأخضر">الرأس الأخضر</option>\
    <option value="جزر كايمان">جزر كايمان</option>\
    <option value="جمهورية أفريقيا الوسطى">جمهورية أفريقيا الوسطى</option>\
    <option value="تشاد">تشاد</option>\
    <option value="شيلي">شيلي</option>\
    <option value="الصين">الصين</option>\
    <option value="جزيرة الكريسماس">جزيرة الكريسماس</option>\
    <option value="جزر كوكوس (كيلينغ)">جزر كوكوس (كيلينغ)</option>\
    <option value="كولومبيا">كولومبيا</option>\
    <option value="جزر القمر">جزر القمر</option>\
    <option value="الكونغو">الكونغو</option>\
    <option value="جمهورية الكونغو الديمقراطية">جمهورية الكونغو الديمقراطية</option>\
    <option value="جزر كوك">جزر كوك</option>\
    <option value="كوستاريكا">كوستاريكا</option>\
    <option value="كوت ديفوار">كوت ديفوار</option>\
    <option value="كرواتيا">كرواتيا</option>\
    <option value="كوبا">كوبا</option>\
    <option value="كوراكاو">كوراكاو</option>\
    <option value="قبرص">قبرص</option>\
    <option value="جمهورية التشيك">جمهورية التشيك</option>\
    <option value="الدنمارك">الدنمارك</option>\
    <option value="جيبوتي">جيبوتي</option>\
    <option value="دومينيكا">دومينيكا</option>\
    <option value="جمهورية الدومينيكان">جمهورية الدومينيكان</option>\
    <option value="الإكوادور">الإكوادور</option>\
    <option value="مصر">مصر</option>\
    <option value="السلفادور">السلفادور</option>\
    <option value="غينيا الاستوائية">غينيا الاستوائية</option>\
    <option value="إريتريا">إريتريا</option>\
    <option value="استونيا">استونيا</option>\
    <option value="إثيوبيا">إثيوبيا</option>\
    <option value="جزر فوكلاند (مالفيناس)">جزر فوكلاند (مالفيناس)</option>\
    <option value="جزر فارو">جزر فارو</option>\
    <option value="فيجي">فيجي</option>\
    <option value="فنلندا">فنلندا</option>\
    <option value="فرنسا">فرنسا</option>\
    <option value="غويانا الفرنسية">غويانا الفرنسية</option>\
    <option value="بولينيزيا الفرنسية">بولينيزيا الفرنسية</option>\
    <option value="المناطق الجنوبية لفرنسا">المناطق الجنوبية لفرنسا</option>\
    <option value="الجابون">الجابون</option>\
    <option value="غامبيا">غامبيا</option>\
    <option value="جورجيا">جورجيا</option>\
    <option value="ألمانيا">ألمانيا</option>\
    <option value="غانا">غانا</option>\
    <option value="جبل طارق">جبل طارق</option>\
    <option value="اليونان">اليونان</option>\
    <option value="غرينلاند">غرينلاند</option>\
    <option value="غرينادا">غرينادا</option>\
    <option value="جوادلوب">جوادلوب</option>\
    <option value="غوام">غوام</option>\
    <option value="غواتيمالا">غواتيمالا</option>\
    <option value="غيرنزي">غيرنزي</option>\
    <option value="غينيا">غينيا</option>\
    <option value="غينيا بيساو">غينيا بيساو</option>\
    <option value="غيانا">غيانا</option>\
    <option value="هايتي">هايتي</option>\
    <option value="جزيرة هيرد وجزر ماكدونالد">جزيرة هيرد وجزر ماكدونالد</option>\
    <option value="الكرسي الرسولي (الفاتيكان)">الكرسي الرسولي (الفاتيكان)</option>\
    <option value="هندوراس">هندوراس</option>\
    <option value="هونغ كونغ">هونغ كونغ</option>\
    <option value="هنغاريا">هنغاريا</option>\
    <option value="أيسلندا">أيسلندا</option>\
    <option value="الهند">الهند</option>\
    <option value="إندونيسيا">إندونيسيا</option>\
    <option value="إيران (جمهورية إيران الإسلامية)">إيران (جمهورية إيران الإسلامية)</option>\
    <option value="العراق">العراق</option>\
    <option value="أيرلندا">أيرلندا</option>\
    <option value="جزيرة آيل أوف مان">جزيرة آيل أوف مان</option>\
    <option value="إسرائيل">إسرائيل</option>\
    <option value="إيطاليا">إيطاليا</option>\
    <option value="جامايكا">جامايكا</option>\
    <option value="اليابان">اليابان</option>\
    <option value="جيرسي">جيرسي</option>\
    <option value="الأردن">الأردن</option>\
    <option value="كازاخستان">كازاخستان</option>\
    <option value="كينيا">كينيا</option>\
    <option value="كيريباس">كيريباس</option>\
    <option value="كوريا (جمهورية كوريا الشعبية الديمقراطية)">كوريا (جمهورية كوريا الشعبية الديمقراطية)</option>\
    <option value="كوريا (جمهورية كوريا)">كوريا (جمهورية كوريا)</option>\
    <option value="كوسوفو">كوسوفو</option>\
    <option value="الكويت">الكويت</option>\
    <option value="قيرغيزستان">قيرغيزستان</option>\
    <option value="جمهورية لاو الديمقراطية الشعبية">جمهورية لاو الديمقراطية الشعبية</option>\
    <option value="لاتفيا">لاتفيا</option>\
    <option value="لبنان">لبنان</option>\
    <option value="ليسوتو">ليسوتو</option>\
    <option value="ليبيريا">ليبيريا</option>\
    <option value="الجماهيرية الليبية">الجماهيرية الليبية</option>\
    <option value="ليختنشتاين">ليختنشتاين</option>\
    <option value="ليتوانيا">ليتوانيا</option>\
    <option value="لوكسمبورغ">لوكسمبورغ</option>\
    <option value="ماكاو">ماكاو</option>\
    <option value="مقدونيا (جمهورية مقدونيا السابقة)">مقدونيا (جمهورية مقدونيا السابقة)</option>\
    <option value="مدغشقر">مدغشقر</option>\
    <option value="ملاوي">ملاوي</option>\
    <option value="ماليزيا">ماليزيا</option>\
    <option value="جزر المالديف">جزر المالديف</option>\
    <option value="مالي">مالي</option>\
    <option value="مالطا">مالطا</option>\
    <option value="جزر مارشال">جزر مارشال</option>\
    <option value="مارتينيك">مارتينيك</option>\
    <option value="موريتانيا">موريتانيا</option>\
    <option value="موريشيوس">موريشيوس</option>\
    <option value="مايوت">مايوت</option>\
    <option value="المكسيك">المكسيك</option>\
    <option value="ميكرونيزيا (ولايات ميكرونيزيا الموحدة)">ميكرونيزيا (ولايات ميكرونيزيا الموحدة)</option>\
    <option value="مولدوفا (جمهورية مولدوفا)">مولدوفا (جمهورية مولدوفا)</option>\
    <option value="موناكو">موناكو</option>\
    <option value="منغوليا">منغوليا</option>\
    <option value="الجبل الأسود">الجبل الأسود</option>\
    <option value="مونتسيرات">مونتسيرات</option>\
    <option value="المغرب">المغرب</option>\
    <option value="موزمبيق">موزمبيق</option>\
    <option value="ميانمار">ميانمار</option>\
    <option value="ناميبيا">ناميبيا</option>\
    <option value="ناورو">ناورو</option>\
    <option value="نيبال">نيبال</option>\
    <option value="هولندا">هولندا</option>\
    <option value="جزر الأنتيل الهولندية">جزر الأنتيل الهولندية</option>\
    <option value="كاليدونيا الجديدة">كاليدونيا الجديدة</option>\
    <option value="نيوزيلندا">نيوزيلندا</option>\
    <option value="نيكاراغوا">نيكاراغوا</option>\
    <option value="النيجر">النيجر</option>\
    <option value="نيجيريا">نيجيريا</option>\
    <option value="نيوي">نيوي</option>\
    <option value="جزيرة نورفولك">جزيرة نورفولك</option>\
    <option value="جزر مريانا الشمالية">جزر مريانا الشمالية</option>\
    <option value="النرويج">النرويج</option>\
    <option value="عمان">عمان</option>\
    <option value="باكستان">باكستان</option>\
    <option value="بالاو">بالاو</option>\
    <option value="فلسطين">فلسطين</option>\
    <option value="بنما">بنما</option>\
    <option value="بابوا غينيا الجديدة">بابوا غينيا الجديدة</option>\
    <option value="باراغواي">باراغواي</option>\
    <option value="بيرو">بيرو</option>\
    <option value="الفلبين">الفلبين</option>\
    <option value="جزر بيتكيرن">جزر بيتكيرن</option>\
    <option value="بولندا">بولندا</option>\
    <option value="البرتغال">البرتغال</option>\
    <option value="بورتوريكو">بورتوريكو</option>\
    <option value="قطر">قطر</option>\
    <option value="ريونيون">ريونيون</option>\
    <option value="رومانيا">رومانيا</option>\
    <option value="روسيا (الاتحاد الروسي)">روسيا (الاتحاد الروسي)</option>\
    <option value="رواندا">رواندا</option>\
    <option value="سانت بارتيليمي">سانت بارتيليمي</option>\
    <option value="سانت هيلانة وأسينشن وتريستان دا كونها">سانت هيلانة وأسينشن وتريستان دا كونها</option>\
    <option value="سانت كيتس ونيفيس">سانت كيتس ونيفيس</option>\
    <option value="سانت لوسيا">سانت لوسيا</option>\
    <option value="سانت مارتين (الجزء الهولندي)">سانت مارتين (الجزء الهولندي)</option>\
    <option value="سانت بيير وميكلون">سانت بيير وميكلون</option>\
    <option value="سانت فينسنت وجزر غرينادين">سانت فينسنت وجزر غرينادين</option>\
    <option value="ساموا">ساموا</option>\
    <option value="سان مارينو">سان مارينو</option>\
    <option value="ساو تومي وبرينسيبي">ساو تومي وبرينسيبي</option>\
    <option value="المملكة العربية السعودية">المملكة العربية السعودية</option>\
    <option value="السنغال">السنغال</option>\
    <option value="صربيا">صربيا</option>\
    <option value="سيشيل">سيشيل</option>\
    <option value="سيراليون">سيراليون</option>\
    <option value="سنغافورة">سنغافورة</option>\
    <option value="سينت مارتن">سينت مارتن</option>\
    <option value="سلوفاكيا">سلوفاكيا</option>\
    <option value="سلوفينيا">سلوفينيا</option>\
    <option value="جزر سليمان">جزر سليمان</option>\
    <option value="الصومال">الصومال</option>\
    <option value="جنوب أفريقيا">جنوب أفريقيا</option>\
    <option value="جورجيا الجنوبية وجزر ساندويتش الجنوبية">جورجيا الجنوبية وجزر ساندويتش الجنوبية</option>\
    <option value="جنوب السودان">جنوب السودان</option>\
    <option value="إسبانيا">إسبانيا</option>\
    <option value="سريلانكا">سريلانكا</option>\
    <option value="السودان">السودان</option>\
    <option value="سورينام">سورينام</option>\
    <option value="سفالبارد وجان ماين">سفالبارد وجان ماين</option>\
    <option value="سوازيلاند">سوازيلاند</option>\
    <option value="السويد">السويد</option>\
    <option value="سويسرا">سويسرا</option>\
    <option value="الجمهورية العربية السورية">الجمهورية العربية السورية</option>\
    <option value="تايوان (مقاطعة الصين)">تايوان (مقاطعة الصين)</option>\
    <option value="طاجيكستان">طاجيكستان</option>\
    <option value="تنزانيا">تنزانيا</option>\
    <option value="تايلاند">تايلاند</option>\
    <option value="تيمور الشرقية">تيمور الشرقية</option>\
    <option value="توغو">توغو</option>\
    <option value="توكيلو">توكيلو</option>\
    <option value="تونغا">تونغا</option>\
    <option value="ترينيداد وتوباغو">ترينيداد وتوباغو</option>\
    <option value="تونس">تونس</option>\
    <option value="تركيا">تركيا</option>\
    <option value="تركمانستان">تركمانستان</option>\
    <option value="جزر توركس وكايكوس">جزر توركس وكايكوس</option>\
    <option value="توفالو">توفالو</option>\
    <option value="أوغندا">أوغندا</option>\
    <option value="أوكرانيا">أوكرانيا</option>\
    <option value="الإمارات العربية المتحدة">الإمارات العربية المتحدة</option>\
    <option value="المملكة المتحدة">المملكة المتحدة</option>\
    <option value="الولايات المتحدة الأمريكية">الولايات المتحدة الأمريكية</option>\
    <option value="الولايات المتحدة الصغرى البعيدة الصغيرة">الولايات المتحدة الصغرى البعيدة الصغيرة</option>\
    <option value="أوروغواي">أوروغواي</option>\
    <option value="أوزبكستان">أوزبكستان</option>\
    <option value="فانواتو">فانواتو</option>\
    <option value="فنزويلا (جمهورية فنزويلا البوليفارية)">فنزويلا (جمهورية فنزويلا البوليفارية)</option>\
    <option value="فيتنام">فيتنام</option>\
    <option value="جزر الفرجين البريطانية">جزر الفرجين البريطانية</option>\
    <option value="جزر الفرجين الأمريكية">جزر الفرجين الأمريكية</option>\
    <option value="واليس وفوتونا">واليس وفوتونا</option>\
    <option value="الصحراء الغربية">الصحراء الغربية</option>\
    <option value="اليمن">اليمن</option>\
    <option value="زامبيا">زامبيا</option>\
    <option value="زيمبابوي">زيمبابوي</option>');
      });

  
</script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDB8ahpPhHkuWIh0JyxSg3uALTj9ynOS74&libraries=places&callback=initMap" async defer></script>
@endsection