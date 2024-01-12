@extends('frontend.layout')
@section('pageHeading')
  {{ $content->title }}
@endsection

@php
  $og_title = $content->title;
  $og_description = strip_tags($content->description);
  $og_image = asset('assets/admin/img/event/thumbnail/' . $content->thumbnail);
@endphp

@section('meta-keywords', "{{ $content->meta_keywords }}")
@section('meta-description', "$content->meta_description")
@section('og-title', "$og_title")
@section('og-description', "$og_description")
@section('og-image', "$og_image")

@section('custom-style')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection

@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">
          {{ strlen($content->title) > 30 ? mb_substr($content->title, 0, 30, 'UTF-8') . '...' : $content->title }}
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->event_details_page_title ?? __('Event Details') }}
              @else
                {{ __('Event Details') }}
              @endif
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection
@section('content')
<link rel="stylesheet" href="{{ url('public/assets/slick-carousel/slick-theme.css') }}">
<link rel="stylesheet" href="{{ url('public/assets/slick-carousel/slick.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link rel="stylesheet" href="style.css">
<style>
  .image_main{
    height: 400px;
    width: 100%;
}
.image_main_2{
    height: 200px;
    width: 100%;
}
.image_main_3{
    height: 200px;
    width: 50%;
    padding-top: 0.6rem;
    padding-right: 0.3rem;

}
.image_main_4{
    height: 200px;
    width: 50%;
    padding-left: 0.3rem;
    padding-top: 0.6rem;
}
.slick-next{
    right: 20px !important;
}
.slick-prev{
    left: 20px !important;
    z-index: 10;
}
.main_banner_slider{
    height: 300px;
    width: 100%;
}
@media only screen and (max-width: 768px) {
    .main_banner{
        display: none;
    }
    .main_banner_slider{
        display: block;
    }
}
@media only screen and (min-width: 768px) {
    .main_banner_slider{
        display: none;
    }
}

</style>
  <!-- Event Page Start -->
  @php
    $map_address = preg_replace('/\s+/u', ' ', trim($content->address));
    $map_address = str_replace('/', ' ', $map_address);
    $map_address = str_replace('?', ' ', $map_address);
    $map_address = str_replace(',', ' ', $map_address);
  @endphp
  <section class="event-details-section pt-110 rpt-90 pb-90 rpb-70">
    <div class="container" id="main_container">
      <div class="event-details-content">
        <div class="d-flex flex-wrap-wrap has-gap">
          @php
            if ($content->date_type == 'multiple') {
                $event_date = eventLatestDates($content->id);
                $date = strtotime(@$event_date->start_date);
            } else {
                $date = strtotime($content->start_date);
            }
          @endphp
          @if ($content->date_type != 'multiple')
            <div class="event-top-date">
              <div class="event-month">{{ date('M', $date) }}</div>
              <div class="event-date">{{ date('d', $date) }}</div>
            </div>
          @endif
          <div class="event-bottom-content">
            @php
              if ($content->date_type == 'multiple') {
                  $event_date = eventLatestDates($content->id);
                  $startDateTime = @$event_date->start_date_time;
                  $endDateTime = @$event_date->end_date_time;
                  //for multiple get last end date
                  $last_end_date = eventLastEndDates($content->id);
                  $last_end_date = $last_end_date->end_date_time;
              
                  $now_time = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
              } else {
                  $now_time = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                  $startDateTime = $content->start_date . ' ' . $content->start_time;
                  $endDateTime = $content->end_date . ' ' . $content->end_time;
              }
              $over = false;
              
            @endphp
            @if ($content->date_type == 'single' && $content->countdown_status == 1)
              <div class="event-details-top">
                @if ($startDateTime >= $now_time)
                  <h2 class="title">{{ $content->title }} <span class="badge badge-info">{{ __('Upcoming') }}</span>
                  </h2>
                @elseif ($startDateTime <= $endDateTime && $endDateTime >= $now_time)
                  <h2 class="title">
                    {{ $content->title }}
                    <span class="badge badge-success">{{ __('Running') }}</span>
                  </h2>
                @else
                  @php
                    $over = true;
                  @endphp
                  <h2 class="title">
                    {{ $content->title }}
                    <span class="badge badge-danger">{{ __('Over') }}</span>
                  </h2>
                @endif
              </div>
            @elseif ($content->date_type == 'multiple')
              <div class="event-details-top">
                <h2 class="title">{{ $content->title }}
                  @if ($startDateTime >= $now_time)
                    <span class="badge badge-info">{{ __('Upcoming') }}</span>
                  @elseif ($startDateTime <= $last_end_date && $last_end_date >= $now_time)
                    <span class="badge badge-success">{{ __('Running') }}</span>
                  @else
                    @php
                      $over = true;
                    @endphp
                    <span class="badge badge-danger">{{ __('Over') }}</span>
                  @endif
                </h2>
              </div>
            @else
              <div class="event-details-top">
                <h2 class="title">{{ $content->title }}</h2>
              </div>

            @endif

            <div class="event-details-header mb-25">
              <ul>
                <li><i class="far fa-calendar-alt"></i>
                  {{ date('D, dS M Y', $date) }}</li>

                <li><i class="far fa-clock"></i>
                  {{ $content->date_type == 'multiple' ? @$event_date->duration : $content->duration }}
                </li>
                @if ($content->event_type == 'venue')
                  <li><i class="fas fa-map-marker-alt"></i>
                    @if ($content->city != null)
                      {{ $content->city }}
                    @endif
                    @if ($content->state)
                      , {{ $content->state }}
                    @endif
                    @if ($content->country)
                      , {{ $content->country }}
                    @endif
                  </li>
                @else
                  <li><i class="fas fa-map-marker-alt"></i> {{ __('Online') }}</li>
                @endif
              </ul>
            </div>
          </div>
        </div>
        <div class="event-details-image mb-50">
          <div class="main_banner">
                <div class="row">
                    <div class="col-md-8 m-0 p-0" style="padding-right: 0.6rem !important;">
                         <img  class="image_main"  src="{{ url('public/assets/images/98.jpg')}}" alt="">  
                    </div>
                    <div class="col-md-4 m-0 p-0">
                        <img  class="image_main_2"  src="{{ url('public/assets/images/97.jpg')}}" alt="">  
                        <div class="d-flex">
                            <img  class="image_main_3"  src="{{ url('public/assets/images/53.jpg')}}" alt=""> 
                            <img  class="image_main_4"  src="{{ url('public/assets/images/53 (1).jpg')}}" alt=""> 
                        </div>
                    </div>
                </div>
          </div>
        
            <div class="main_banner_slider">
                <div class="your-class">
                    <img src="{{ url('public/assets/images/98.jpg')}}" alt="" style="height: 300px;width: 100%;">
                    <img src="{{ url('public/assets/images/97.jpg')}}" alt="" style="height: 300px;width: 100%;">            
                    <img src="{{ url('public/assets/images/53.jpg')}}" alt="" style="height: 300px;width: 100%;">            
                    <img src="{{ url('public/assets/images/53 (1).jpg')}}" alt="" style="height: 300px;width: 100%;">            
                </div>
            </div>
     

        </div>
        <div class="row">
          <div class="col-lg-7">
            <div class="event-details-content-inner">
              <div class="event-info d-flex align-items-center mb-1">
                <span>
                  <a href="{{ route('events', ['category' => $content->slug]) }}">{{ $content->name }}</a>
                </span>
              </div>
              @if (Session::has('paypal_error'))
                <div class="alert alert-danger">{{ Session::get('paypal_error') }}</div>
              @endif
              @php
                Session::put('paypal_error', null);
              @endphp
              <h3 class="inner-title mb-25">{{ __('Description') }}</h3>

              <div class="summernote-content">
                {!! $content->description !!}
              </div>
              @if ($content->event_type != 'online')
                <h3 class="inner-title mb-30">{{ __('Map') }}</h3>
                <div class="our-location mb-50">
                
                      <input type="hidden" value="{{ $content->longitude }}" name="longitude"
                      class="lng">
                      <input type="hidden" value="{{ $content->latitude }}" name="latitude"
                      class="lat">
                     
                      <div id="map" style="height: 400px; width: 100%;margin-top: 1rem;">
                    
                      </div>
                 
                </div>
              @endif

              @if (!empty($content->refund_policy))
                <h3>{{ __('Return Policy') }}</h3>
                <p>{{ @$content->refund_policy }}</p>
              @endif

              @if (!empty($content->Guide_activity_info)) <!-- Check if Food Drink property exists -->
              <h3>{{ __('Transportation') }}</h3>
              <p>
                @php
                $foodDrinkData = json_decode($content->transportation, false);
            @endphp

<p>Transport: {{ $content->black_cab ?? 'N/A' }}</p>
<p>Private/shared: {{ $content->black_cab == "private" ? 'Private' : 'Shared' }}</p>


              </p>
          @endif
              @if (!empty($content->Guide_activity_info)) <!-- Check if Food Drink property exists -->
              <h3>{{ __('Guide & activity info') }}</h3>
              <p>
                @php
                $foodDrinkData = json_decode($content->Guide_activity_info, false);
            @endphp

<p>Who will your customers mainly interact with during your activity:   {{ $foodDrinkData->activity ?? 'N/A' }}</p>
<p>Will customers sleep overnight somewhere as part of the activity: {{ $foodDrinkData->sleep ?? 'N/A' }}</p>
<p>Is accommodation included in the price: {{ $foodDrinkData->accomodation ?? 'N/A' }}</p>

              </p>
          @endif







              @if (!empty($content->food_drink)) <!-- Check if Food Drink property exists -->
              <h3>{{ __('Food Drink') }}</h3>
              <p>
                @php
                $foodDrinkData = json_decode($content->food_drink, false);
            @endphp

<p>foodDrink: {{ $foodDrinkData->foodDrink ?? 'N/A' }} </p>
<p>food: {{ $foodDrinkData->Food->food ?? 'N/A' }}</p>
<h6>Food</h6>

<p>Type_of_meal: {{ $foodDrinkData->Food->Type_of_meal ?? 'N/A' }}</p>
<p>Time_of_day: {{ $foodDrinkData->Food->Time_of_day ?? 'N/A' }}</p>
<p>Tags: {{ $foodDrinkData->Food->Tags ?? 'N/A' }}</p>
<p>Dietary - Diabetic: {{ $foodDrinkData->Food->Dietary->Diabetic ?? 'N/A' }}</p>
<p>Dietary - Egg_free: {{ $foodDrinkData->Food->Dietary->Egg_free ?? 'N/A' }}</p>
<!-- Add more keys here -->

<p>Dietary - Gluten_free: {{ $foodDrinkData->Food->Dietary->Gluten_free ?? 'N/A' }}</p>
<p>Dietary - Halal: {{ $foodDrinkData->Food->Dietary->Halal ?? 'N/A' }}</p>
<p>Dietary - Keto: {{ $foodDrinkData->Food->Dietary->Keto ?? 'N/A' }}</p>
<p>Dietary - Kosher: {{ $foodDrinkData->Food->Dietary->Kosher ?? 'N/A' }}</p>
<p>Dietary - Lactose_free: {{ $foodDrinkData->Food->Dietary->Lactose_free ?? 'N/A' }}</p>
<p>Dietary - Low_carb: {{ $foodDrinkData->Food->Dietary->Low_carb ?? 'N/A' }}</p>
<p>Dietary - Seafood_fish_free: {{ $foodDrinkData->Food->Dietary->Seafood_fish_free ?? 'N/A' }}</p>
<p>Dietary - Pescatarian: {{ $foodDrinkData->Food->Dietary->Pescatarian ?? 'N/A' }}</p>
<p>Dietary - Vegan: {{ $foodDrinkData->Food->Dietary->Vegan ?? 'N/A' }}</p>
<p>Dietary - Vegetarian: {{ $foodDrinkData->Food->Dietary->Vegetarian ?? 'N/A' }}</p>
              </p>
              <h6>Drink</h6>
              <p>Drink: {{ $foodDrinkData->Drink->drink ?? 'N/A' }}</p>
              <p>Drink_Tags: {{ $foodDrinkData->Drink->drink_Tags ?? 'N/A' }}</p>
          @endif



          @if (!empty($content->Guide_activity_info)) <!-- Check if Food Drink property exists -->
          <h3>{{ __('Inclusions & descriptions') }}</h3>
          <p>
            @php
            $main_inclusions = json_decode($content->main_inclusions, false);
            
            
                        
            $inclusion_optional_description = json_decode($content->inclusion_optional_description, false);
            
            
            $inclusion_short_description = json_decode($content->inclusion_short_description, false);
            
            
            $inclusion_full_description = json_decode($content->inclusion_full_description, false);
            
            $inclusions_activity_one = json_decode($content->inclusions_activity_one, false);
            $inclusions_activity_two = json_decode($content->inclusions_activity_two, false);
            
              $inclusions_activity_three = json_decode($content->inclusions_activity_three, false);

            
           
                    
                             

            
        @endphp

<p>{{ $content->main_inclusions ?? 'N/A' }}</p>
<p>{{ $content->inclusion_optional_description ?? 'N/A' }}</p>
<p>{{ $content->inclusion_short_description ?? 'N/A' }}</p>
<p>{{ $content->inclusion_full_description ?? 'N/A' }}</p>
<p>{{ $content->inclusions_activity_one ?? 'N/A' }}</p>
<p>{{ $content->inclusions_activity_two ?? 'N/A' }}</p>
<p>{{ $content->inclusions_activity_three ?? 'N/A' }}</p>


          </p>
      @endif


            </div>
          </div>
          <div class="col-lg-5">
            <div class="sidebar-sticky">
              <form action="{{ route('check-out2') }}" method="post"
                @if ($over == true) onsubmit="return false" @endif>
                @csrf
                <input type="hidden" name="event_id" value="{{ $content->id }}" id="">
                <input type="hidden" name="pricing_type" value="{{ $content->pricing_type }}" id="">
                <div class="event-details-information">
                  <input type="hidden" name="date_type" value="{{ $content->date_type }}">
                  @if ($content->date_type == 'multiple')
                    @php
                      $dates = eventDates($content->id);
                      $exp_dates = eventExpDates($content->id);
                    @endphp
                    <div class="form-group">
                      <label for="">{{ __('Select Date') }}</label>
                      <select name="event_date" id="" class="form-control">
                        @if (count($dates) > 0)
                          @foreach ($dates as $date)
                            <option value="{{ FullDateTime($date->start_date_time) }}">
                              {{ FullDateTime($date->start_date_time) }}
                              ({{ timeZoneOffset($websiteInfo->timezone) }} {{ __('GMT') }})
                            </option>
                          @endforeach
                        @endif
                        @if (count($exp_dates) > 0)
                          @foreach ($exp_dates as $exp_date)
                            <option disabled value="">
                              {{ FullDateTime($exp_date->start_date_time) }}
                              ({{ timeZoneOffset($websiteInfo->timezone) }} {{ __('GMT') }})
                            </option>
                          @endforeach
                        @endif
                      </select>
                      @error('event_date')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  @else
                    <input type="hidden" name="event_date"
                      value="{{ FullDateTime($content->start_date . $content->start_time) }}">
                  @endif

                  {{-- Count down start --}}
                  @if ($content->date_type == 'single' && $content->countdown_status == 1)
                    <div class="event-details-top">
                      @if ($startDateTime >= $now_time)
                        <b>{{ __('Event Starts In') }}</b>
                        <hr>
                        @php
                          $dt = Carbon\Carbon::parse($startDateTime);
                          $year = $dt->year;
                          $month = $dt->month;
                          $day = $dt->day;
                          $end_time = Carbon\Carbon::parse($startDateTime);
                          $hour = $end_time->hour;
                          $minute = $end_time->minute;
                          $now = str_replace('+00:00', '.000' . timeZoneOffset($websiteInfo->timezone) . '00:00', gmdate('c'));
                        @endphp
                        <div class="count-down mb-3" dir="ltr">
                          <div class="event-countdown" data-now="{{ $now }}" data-year="{{ $year }}"
                            data-month="{{ $month }}" data-day="{{ $day }}"
                            data-hour="{{ $hour }}" data-minute="{{ $minute }}"
                            data-timezone="{{ timeZoneOffset($websiteInfo->timezone) }}">
                          </div>
                        </div>
                      @elseif ($startDateTime <= $endDateTime && $endDateTime >= $now_time)
                        <p>{{ __('The Event is Running') }}</p>
                      @else
                        <p>{{ __('The Event is Over') }}</p>
                      @endif
                    </div>
                  @endif

                  {{-- Countdown end --}}
                  <b>{{ __('Organised By') }}</b>
                  <hr>
                  @if ($organizer == '')
                    @php
                      $admin = App\Models\Admin::first();
                    @endphp
                    <div class="author">
                      <a
                        href="{{ route('frontend.organizer.details', [$admin->id, str_replace(' ', '-', $admin->username), 'admin' => 'true']) }}"><img
                          class="lazy" data-src="{{ asset('assets/admin/img/admins/' . $admin->image) }}"
                          alt="Author"></a>
                      <div class="content">
                        <h6><a
                            href="{{ route('frontend.organizer.details', [$admin->id, str_replace(' ', '-', $admin->username), 'admin' => 'true']) }}">{{ $admin->username }}</a>
                        </h6>
                      </div>
                    </div>
                  @else
                    <div class="author">
                      <a
                        href="{{ route('frontend.organizer.details', [$organizer->id, str_replace(' ', '-', $organizer->username)]) }}">
                        @if ($organizer->photo != null)
                          <img class="lazy"
                            data-src="{{ asset('assets/admin/img/organizer-photo/' . $organizer->photo) }}"
                            alt="Author">
                        @else
                          <img class="lazy" data-src="{{ asset('assets/front/images/user.png') }}" alt="Author">
                        @endif

                      </a>

                      <div class="content">
                        <h6><a
                            href="{{ route('frontend.organizer.details', [$organizer->id, str_replace(' ', '-', $organizer->username)]) }}">{{ $organizer->username }}</a>
                        </h6>
                        <a
                          href="{{ route('frontend.organizer.details', [$organizer->id, str_replace(' ', '-', $organizer->username)]) }}">{{ __('View  Profile') }}</a>
                      </div>
                    </div>
                  @endif
                  @if ($content->address != null)
                    <b><i class="fas fa-map-marker-alt"></i> {{ $content->address }}</b>
                    <hr>
                  @endif

                  {{-- Add to calendar --}}
                  @php
                    $start_date = str_replace('-', '', $content->start_date);
                    $start_time = str_replace(':', '', $content->start_time);
                    $end_date = str_replace('-', '', $content->end_date);
                    $end_time = str_replace(':', '', $content->end_time);
                  @endphp
                  <div class="dropdown show pt-4 pb-4">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-calendar-alt"></i> {{ __('Add to Calendar') }}
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                      <a target="_blank" class="dropdown-item"
                        href="//calendar.google.com/calendar/u/0/r/eventedit?text={{ $content->title }}&dates={{ $start_date }}T{{ $start_time }}/{{ $end_date }}T{{ $end_time }}&ctz={{ $websiteInfo->timezone }}&details=For+details,+click+here:+{{ route('event.details', [$content->eventSlug, $content->id]) }}&location={{ $content->event_type == 'online' ? 'Online' : $content->address }}&sf=true">{{ __('Google Calendar') }}</a>
                      <a target="_blank" class="dropdown-item"
                        href="//calendar.yahoo.com/?v=60&view=d&type=20&TITLE={{ $content->title }}&ST={{ $start_date }}T{{ $start_time }}&ET={{ $end_date }}T{{ $end_time }}&DUR=9959&DESC=For%20details%2C%20click%20here%3A%20{{ route('event.details', [$content->eventSlug, $content->id]) }}&in_loc={{ $content->event_type == 'online' ? 'Online' : $content->address }}">{{ __('Yahoo') }}</a>
                    </div>
                  </div>


                  @if ($content->event_type == 'online' && $content->pricing_type == 'normal')

                    @php
                      $ticket = App\Models\Event\Ticket::where('event_id', $content->id)->first();
                      $event_count = App\Models\Event\Ticket::where('event_id', $content->id)
                          ->get()
                          ->count();
                      if ($ticket->ticket_available_type == 'limited') {
                          $stock = $ticket->ticket_available;
                      } else {
                          $stock = 'unlimited';
                      }
                      //ticket purchase or not check
                      if (Auth::guard('customer')->user() && $ticket->max_ticket_buy_type == 'limited') {
                          $purchase = isTicketPurchaseOnline($ticket->event_id, $ticket->max_buy_ticket);
                      } else {
                          $purchase = ['status' => 'false', 'p_qty' => 0];
                      }
                    @endphp
                    @if ($ticket)

                      <b>{{ __('Select Tickets') }}</b>
                      <hr>
                      <div class="price-count">
                        <h6 dir="ltr">

                          @if ($ticket->early_bird_discount == 'enable')
                            @php
                              $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                            @endphp

                            @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                              @php
                                $calculate_price = $ticket->price - $ticket->early_bird_discount_amount;
                              @endphp
                              {{ symbolPrice($calculate_price) }}
                              <del>
                                {{ symbolPrice($ticket->price) }}
                              </del>
                            @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                              @php
                                $c_price = ($ticket->price * $ticket->early_bird_discount_amount) / 100;
                                $calculate_price = $ticket->price - $c_price;
                              @endphp
                              {{ symbolPrice($calculate_price) }}
                              <del>
                                {{ symbolPrice($ticket->price) }}
                              </del>
                            @else
                              @php
                                $calculate_price = $ticket->price;
                              @endphp
                              {{ symbolPrice($calculate_price) }}
                            @endif
                          @else
                            @php
                              $calculate_price = $ticket->price;
                            @endphp
                            {{ symbolPrice($calculate_price) }}
                          @endif


                        </h6>
                        <div class="quantity-input">
                          <button class="quantity-down" type="button" id="quantityDown">
                            -
                          </button>
                          <input class="quantity" type="number" readonly value="0"
                            data-price="{{ $calculate_price }}" data-max_buy_ticket="{{ $ticket->max_buy_ticket }}"
                            name="quantity" data-ticket_id="{{ $ticket->id }}" data-stock="{{ $stock }}"
                            data-purchase="{{ $purchase['status'] }}" data-p_qty="{{ $purchase['p_qty'] }}">
                          <button class="quantity-up" type="button" id="quantityUP">
                            +
                          </button>
                        </div>



                        @if ($ticket->early_bird_discount == 'enable')
                          @php
                            $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                          @endphp
                          @if (!$discount_date->isPast())
                            <p>{{ __('Discount available') . ' ' }} : ({{ __('till') . ' ' }} :
                              <span dir="ltr">{{ date_format($discount_date, 'Y/m/d h:i a') }}</span>)
                            </p>
                          @endif
                        @endif


                      </div>
                      <p
                        class="text-warning max_error_{{ $ticket->id }}{{ $ticket->max_ticket_buy_type == 'limited' ? $ticket->max_buy_ticket : '' }} ">
                      </p>

                    @endif
                  @elseif($content->event_type == 'online' && $content->pricing_type == 'free')
                    <b>{{ __('Select Tickets') }}</b>
                    <hr>
                    @php
                      $ticket = App\Models\Event\Ticket::where('event_id', $content->id)->first();
                      $event_count = App\Models\Event\Ticket::where('event_id', $content->id)
                          ->get()
                          ->count();
                      
                      if ($ticket->ticket_available_type == 'limited') {
                          $stock = $ticket->ticket_available;
                      } else {
                          $stock = 'unlimited';
                      }
                      
                      //ticket purchase or not check
                      if (Auth::guard('customer')->user() && $ticket->max_ticket_buy_type == 'limited') {
                          $purchase = isTicketPurchaseOnline($ticket->event_id, $ticket->max_buy_ticket);
                          $max_buy_ticket = $ticket->max_buy_ticket;
                      } else {
                          $purchase = ['status' => 'false', 'p_qty' => 0];
                          $max_buy_ticket = 999999;
                      }
                    @endphp
                    <div class="price-count">
                      <h6>
                        {{ __('Free') }}
                      </h6>
                      <div class="quantity-input">
                        <button class="quantity-down" type="button" id="quantityDown">
                          -
                        </button>
                        <input class="quantity" readonly type="number" value="0"
                          data-price="{{ $content->price }}" data-max_buy_ticket="{{ $max_buy_ticket }}"
                          name="quantity" data-ticket_id="{{ $ticket->id }}" data-stock="{{ $stock }}"
                          data-purchase="{{ $purchase['status'] }}" data-p_qty="{{ $purchase['p_qty'] }}">
                        <button class="quantity-up" type="button" id="quantityUP">
                          +
                        </button>
                      </div>

                    </div>
                    <p
                      class="text-warning max_error_{{ $ticket->id }}{{ $ticket->max_ticket_buy_type == 'limited' ? $ticket->max_buy_ticket : '' }} ">
                    </p>
                  @elseif($content->event_type == 'venue')
                    @php
                      $tickets = DB::table('tickets')
                          ->where('event_id', $content->id)
                          ->get();
                    @endphp
                    @if (count($tickets) > 0)
                      <b>{{ __('Select Tickets') }}</b>
                      <hr>
                      @foreach ($tickets as $ticket)
                        @if ($ticket->pricing_type == 'normal')
                          @php
                            if ($ticket->ticket_available_type == 'limited') {
                                $stock = $ticket->ticket_available;
                            } else {
                                $stock = 'unlimited';
                            }
                            
                            //ticket purchase or not check
                            $ticket_content = App\Models\Event\TicketContent::where([['language_id', $currentLanguageInfo->id], ['ticket_id', $ticket->id]])->first();
                            
                            if (Auth::guard('customer')->user() && $ticket->max_ticket_buy_type == 'limited') {
                                $purchase = isTicketPurchaseVenue($ticket->event_id, $ticket->max_buy_ticket, $ticket->id, @$ticket_content->title);
                            } else {
                                $purchase = ['status' => 'false', 'p_qty' => 0];
                            }
                            
                          @endphp
                          <p class="mb-0"><strong>{{ @$ticket_content->title }}</strong></p>
                          <div class="click-show">
                            <div class="show-content">
                              {!! @$ticket_content->description !!}
                            </div>
                            @if (strlen(@$ticket_content->description) > 50)
                              <div class="read-more-btn">
                                <span>{{ __('Read more') }}</span>
                                <span>{{ __('Read less') }}</span>
                              </div>
                            @endif
                          </div>
                          <div class="price-count">
                            <h6 dir="ltr">
                              @if ($ticket->early_bird_discount == 'enable')
                                @php
                                  $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                @endphp

                                @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                                  @php
                                    $calculate_price = $ticket->price - $ticket->early_bird_discount_amount;
                                  @endphp
                                  {{ symbolPrice($calculate_price) }}
                                  <del>

                                    {{ symbolPrice($ticket->price) }}
                                  </del>
                                @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                                  @php
                                    $c_price = ($ticket->price * $ticket->early_bird_discount_amount) / 100;
                                    $calculate_price = $ticket->price - $c_price;
                                  @endphp
                                  {{ symbolPrice($calculate_price) }}

                                  <del>
                                    {{ symbolPrice($ticket->price) }}
                                  </del>
                                @else
                                  @php
                                    $calculate_price = $ticket->price;
                                  @endphp
                                  {{ symbolPrice($calculate_price) }}
                                @endif
                              @else
                                @php
                                  $calculate_price = $ticket->price;
                                @endphp
                                {{ symbolPrice($calculate_price) }}
                              @endif


                            </h6>
                            <div class="quantity-input">
                              <button class="quantity-down" type="button" id="quantityDown">
                                -
                              </button>
                              <input class="quantity" readonly type="number" value="0"
                                data-price="{{ $calculate_price }}"
                                data-max_buy_ticket="{{ $ticket->max_buy_ticket }}" name="quantity[]"
                                data-ticket_id="{{ $ticket->id }}" data-stock="{{ $stock }}"
                                data-purchase="{{ $purchase['status'] }}" data-p_qty="{{ $purchase['p_qty'] }}">
                              <button class="quantity-up" type="button" id="quantityUP">
                                +
                              </button>
                            </div>


                            @if ($ticket->early_bird_discount == 'enable')
                              @php
                                $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                              @endphp
                              @if (!$discount_date->isPast())
                                <p>{{ __('Discount available') . ' ' }} : ({{ __('till') . ' ' }} :
                                  <span dir="ltr">{{ date_format($discount_date, 'Y/m/d h:i a') }}</span>)
                                </p>
                              @endif
                            @endif

                          </div>
                          <p
                            class="text-warning max_error_{{ $ticket->id }}{{ $ticket->max_ticket_buy_type == 'limited' ? $ticket->max_buy_ticket : '' }} ">
                          </p>
                        @elseif($ticket->pricing_type == 'variation')
                          @php
                            $variations = json_decode($ticket->variations);
                            
                            $varition_names = App\Models\Event\VariationContent::where([['ticket_id', $ticket->id], ['language_id', $currentLanguageInfo->id]])->get();
                            
                            $de_lang = App\Models\Language::where('is_default', 1)->first();
                            $de_varition_names = App\Models\Event\VariationContent::where([['ticket_id', $ticket->id], ['language_id', $de_lang->id]])->get();
                          @endphp

                          @foreach ($variations as $key => $item)
                            @php
                              //ticket purchase or not check
                              if (Auth::guard('customer')->user()) {
                                  $purchase = isTicketPurchaseVenue($ticket->event_id, $item->v_max_ticket_buy, $ticket->id, $de_varition_names[$key]['name']);
                              } else {
                                  $purchase = ['status' => 'false', 'p_qty' => 0];
                              }
                              $ticket_content = App\Models\Event\TicketContent::where([['language_id', $currentLanguageInfo->id], ['ticket_id', $ticket->id]])->first();
                            @endphp
                            <p class="mb-0"><strong>{{ @$ticket_content->title }} -
                                {{ @$varition_names[$key]['name'] }}</strong>
                            </p>
                            <div class="click-show">
                              <div class="show-content">
                                {!! @$ticket_content->description !!}
                              </div>
                              @if (strlen(@$ticket_content->description) > 50)
                                <div class="read-more-btn">
                                  <span>{{ __('Read more') }}</span>
                                  <span>{{ __('Read less') }}</span>
                                </div>
                              @endif
                            </div>
                            <div class="price-count">
                              <h6 dir="ltr">
                                @if ($ticket->early_bird_discount == 'enable')
                                  @php
                                    $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                  @endphp
                                  @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                                    @php
                                      $calculate_price = $item->price - $ticket->early_bird_discount_amount;
                                    @endphp
                                    {{ symbolPrice($calculate_price) }}

                                    <del>
                                      {{ symbolPrice($item->price) }}
                                    </del>
                                  @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                                    @php
                                      $c_price = ($item->price * $ticket->early_bird_discount_amount) / 100;
                                      $calculate_price = $item->price - $c_price;
                                    @endphp
                                    {{ symbolPrice($calculate_price) }}

                                    <del>
                                      {{ symbolPrice($item->price) }}
                                    </del>
                                  @else
                                    @php
                                      $calculate_price = $item->price;
                                    @endphp
                                    {{ symbolPrice($calculate_price) }}
                                  @endif
                                @else
                                  @php
                                    $calculate_price = $item->price;
                                  @endphp
                                  {{ symbolPrice($calculate_price) }}
                                @endif

                              </h6>

                              <div class="quantity-input">
                                <button class="quantity-down_variation" type="button" id="quantityDown">
                                  -
                                </button>
                                <input type="hidden" name="v_name[]" value="{{ $item->name }}">
                                @php
                                  if ($item->ticket_available_type == 'limited') {
                                      $stock = $item->ticket_available;
                                  } else {
                                      $stock = 'unlimited';
                                  }
                                  if ($item->max_ticket_buy_type == 'limited') {
                                      $max_buy = $item->v_max_ticket_buy;
                                  } else {
                                      $max_buy = 'unlimited';
                                  }
                                @endphp
                                <input type="number" value="0" class="quantity"
                                  data-price="{{ $calculate_price }}" data-max_buy_ticket="{{ $max_buy }}"
                                  data-name="{{ $item->name }}" name="quantity[]"
                                  data-ticket_id="{{ $ticket->id }}" readonly data-stock="{{ $stock }}"
                                  data-purchase="{{ $purchase['status'] }}" data-p_qty="{{ $purchase['p_qty'] }}">
                                <button class="quantity-up" type="button" id="quantityUP">
                                  +
                                </button>
                              </div>
                              @if ($ticket->early_bird_discount == 'enable')
                                @php
                                  $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                @endphp
                                @if (!$discount_date->isPast())
                                  <p>{{ __('Discount available') . ' ' }} : ({{ __('till') . ' ' }} :
                                    <span dir="ltr">{{ date_format($discount_date, 'Y/m/d h:i a') }}</span>)
                                  </p>
                                @endif
                              @endif
                            </div>
                            <p class="text-warning max_error_{{ $ticket->id }}{{ $item->v_max_ticket_buy }} ">
                            </p>
                          @endforeach
                        @elseif($ticket->pricing_type == 'free')
                          @php
                            if ($ticket->ticket_available_type == 'limited') {
                                $stock = $ticket->ticket_available;
                            } else {
                                $stock = 'unlimited';
                            }
                            
                            //ticket purchase or not check
                            $de_lang = App\Models\Language::where('is_default', 1)->first();
                            $ticket_content_default = App\Models\Event\TicketContent::where([['language_id', $de_lang->id], ['ticket_id', $ticket->id]])->first();
                            if (Auth::guard('customer')->user() && $ticket->max_ticket_buy_type == 'limited') {
                                $purchase = isTicketPurchaseVenue($ticket->event_id, $ticket->max_buy_ticket, $ticket->id, @$ticket_content_default->title);
                            } else {
                                $purchase = ['status' => 'false', 'p_qty' => 1];
                            }
                            $ticket_content = App\Models\Event\TicketContent::where([['language_id', $currentLanguageInfo->id], ['ticket_id', $ticket->id]])->first();
                          @endphp
                          <p class="mb-0"><strong>{{ @$ticket_content->title }}</strong></p>
                          <div class="click-show">
                            <div class="show-content">
                              {!! @$ticket_content->description !!}
                            </div>
                            @if (strlen(@$ticket_content->description) > 50)
                              <div class="read-more-btn">
                                <span>{{ __('Read more') }}</span>
                                <span>{{ __('Read less') }}</span>
                              </div>
                            @endif
                          </div>
                          <div class="price-count">
                            <h6>
                              <span class="">{{ __('free') }}</span>
                            </h6>
                            <div class="quantity-input">
                              <button class="quantity-down" type="button" id="quantityDown">
                                -
                              </button>
                              <input class="quantity" data-max_buy_ticket="{{ $ticket->max_buy_ticket }}"
                                type="number" value="0" data-price="{{ $ticket->price }}" name="quantity[]"
                                data-ticket_id="{{ $ticket->id }}" readonly data-stock="{{ $stock }}"
                                data-purchase="{{ $purchase['status'] }}" data-p_qty="{{ $purchase['p_qty'] }}">
                              <button class="quantity-up" type="button" id="quantityUP">
                                +
                              </button>
                            </div>
                          </div>
                          <p
                            class="text-warning max_error_{{ $ticket->id }}{{ $ticket->max_ticket_buy_type == 'limited' ? $ticket->max_buy_ticket : '' }} ">
                          </p>
                        @endif
                      @endforeach
                    @endif
                  @endif
                  @if ($tickets_count > 0)
                    <div class="total">
                      <b>{{ __('Total Price') . ' :' }} </b>
                      <span class="h4" dir="ltr">
                        <span>{{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}</span>
                        <span id="total_price">0</span>
                        <span>{{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}</span>

                      </span>
                      <input type="hidden" name="total" id="total">
                    </div>
                    <button class="theme-btn w-100 mt-20" type="submit">{{ __('Book Now') }}</button>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>
        @if (!empty(showAd(3)))
          <div class="text-center mt-4">
            {!! showAd(3) !!}
          </div>
        @endif
      </div>
      @if (count($related_events) > 0)
        <hr>
        <div class="releted-event-header mt-50">
          <h3>{{ __('Related Events') }}</h3>
          <div class="slick-next-prev mb-10">
            <button class="prev"><i class="fas fa-chevron-left"></i></button>
            <button class="next"><i class="fas fa-chevron-right"></i></button>
          </div>
        </div>
        <div class="related-event-wrap">
          @foreach ($related_events as $event)
            <div class="event-item">
              <div class="event-image">
                <a href="{{ route('event.details', [$event->slug, $event->id]) }}">
                  <img class="lazy" data-src="{{ asset('assets/admin/img/event/thumbnail/' . $event->thumbnail) }}"
                    alt="Event">
                </a>
              </div>
              <div class="event-content">
                <ul class="time-info">
                  <li>
                    <i class="far fa-calendar-alt"></i>
                    <span>
                      @php
                        $date = strtotime($event->start_date);
                      @endphp
                      {{ date('d M', $date) }}
                    </span>
                  </li>
                  @php
                    if ($event->date_type == 'multiple') {
                        $event_date = eventLatestDates($event->id);
                        $date = strtotime(@$event_date->start_date);
                    } else {
                        $date = strtotime($event->start_date);
                    }
                  @endphp
                  <li>
                    <i class="far fa-hourglass"></i>
                    <span
                      title="Event Duration">{{ $event->date_type == 'multiple' ? @$event_date->duration : $event->duration }}</span>
                  </li>
                  <li>
                    <i class="far fa-clock"></i>
                    <span>
                      @php
                        $start_time = strtotime($event->start_time);
                      @endphp
                      {{ date('h:s A', $start_time) }}
                    </span>
                  </li>
                </ul>
                @if ($event->organizer_id != null)
                  @php
                    $organizer = App\Models\Organizer::where('id', $event->organizer_id)->first();
                  @endphp
                  @if ($organizer)
                    <a href="{{ route('frontend.organizer.details', [$organizer->id, str_replace(' ', '-', $organizer->username)]) }}"
                      class="organizer">{{ __('By') }}&nbsp;&nbsp;{{ $organizer->username }}</a>
                  @endif
                @else
                  @php
                    $admin = App\Models\Admin::first();
                  @endphp
                  <a href="{{ route('frontend.organizer.details', [$admin->id, str_replace(' ', '-', $admin->username), 'admin' => 'true']) }}"
                    class="organizer">{{ $admin->username }}</a>
                @endif
                <h5>
                  <a href="{{ route('event.details', [$event->slug, $event->id]) }}">
                    @if (strlen($event->title) > 30)
                      {{ mb_substr($event->title, 0, 30) . '...' }}
                    @else
                      {{ $event->title }}
                    @endif
                  </a>
                </h5>
                @php
                  $desc = strip_tags($event->description);
                @endphp

                @if (strlen($desc) > 45)
                  <p>{{ mb_substr($desc, 0, 50) . '....' }}</p>
                @else
                  <p>{{ $desc }}</p>
                @endif
                @php
                  $ticket = DB::table('tickets')
                      ->where('event_id', $event->id)
                      ->first();
                  $event_count = DB::table('tickets')
                      ->where('event_id', $event->id)
                      ->get()
                      ->count();
                @endphp

                <div class="price-remain">
                  <div class="location">
                    @if ($event->event_type == 'venue')
                      <i class="fas fa-map-marker-alt"></i>
                      <span>
                        @if ($event->city != null)
                          {{ $event->city }}
                        @endif
                        @if ($event->country)
                          , {{ $event->country }}
                        @endif
                      </span>
                    @else
                      <i class="fas fa-map-marker-alt"></i>
                      <span>{{ __('Online') }}</span>
                    @endif
                  </div>
                  <span>
                    @if ($ticket)
                      @if ($ticket->event_type == 'online')
                        @if ($ticket->price != null)
                          <span class="price" dir="ltr">
                            @if ($ticket->early_bird_discount == 'enable')
                              @php
                                $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                              @endphp
                              @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                                @php
                                  $calculate_price = $ticket->price - $ticket->early_bird_discount_amount;
                                @endphp
                                {{ symbolPrice($calculate_price) }}
                                <span>
                                  <del>
                                    {{ symbolPrice($ticket->price) }}
                                  </del>
                                </span>
                              @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                                @php
                                  $p_price = ($ticket->price * $ticket->early_bird_discount_amount) / 100;
                                  $calculate_price = $ticket->price - $p_price;
                                @endphp
                                {{ symbolPrice($calculate_price) }}

                                <span>
                                  <del>
                                    {{ symbolPrice($ticket->price) }}
                                  </del>
                                </span>
                              @else
                                @php
                                  $calculate_price = $ticket->price;
                                @endphp
                                {{ symbolPrice($calculate_price) }}
                              @endif
                            @else
                              @php
                                $calculate_price = $ticket->price;
                              @endphp
                              {{ symbolPrice($calculate_price) }}
                            @endif
                          </span>
                        @else
                          <span class="price">{{ __('Free') }}</span>
                        @endif
                      @endif
                      @if ($ticket->event_type == 'venue')
                        @if ($ticket->pricing_type == 'variation')
                          <span class="price" dir="ltr">
                            @php
                              $variation = json_decode($ticket->variations, true);
                              $price = $variation[0]['price'];
                            @endphp
                            <span class="price">

                              @if ($ticket->early_bird_discount == 'enable')
                                @php
                                  $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                @endphp
                                @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                                  @php
                                    $calculate_price = $price - $ticket->early_bird_discount_amount;
                                  @endphp
                                  {{ symbolPrice($calculate_price) }}
                                  <span><del>
                                      {{ symbolPrice($price) }}
                                    </del></span>
                                @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                                  @php
                                    $p_price = ($price * $ticket->early_bird_discount_amount) / 100;
                                    $calculate_price = $p_price - $price;
                                  @endphp

                                  {{ symbolPrice($calculate_price) }}

                                  <span>
                                    <del>
                                      {{ symbolPrice($price) }}
                                    </del>
                                  </span>
                                @else
                                  @php
                                    $calculate_price = $price;
                                  @endphp
                                  {{ symbolPrice($calculate_price) }}
                                @endif
                              @else
                                @php
                                  $calculate_price = $price;
                                @endphp
                                {{ symbolPrice($calculate_price) }}
                              @endif
                              <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                            </span>
                          </span>
                        @elseif($ticket->pricing_type == 'normal')
                          <span class="price" dir="ltr">

                            @if ($ticket->early_bird_discount == 'enable')
                              @php
                                $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                              @endphp
                              @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                                @php
                                  $calculate_price = $ticket->price - $ticket->early_bird_discount_amount;
                                @endphp

                                {{ symbolPrice($calculate_price) }}
                                <span>
                                  <del>
                                    {{ symbolPrice($ticket->price) }}
                                  </del>
                                </span>
                              @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                                @php
                                  $p_price = ($ticket->price * $ticket->early_bird_discount_amount) / 100;
                                  $calculate_price = $ticket->price - $p_price;
                                @endphp
                                {{ symbolPrice($calculate_price) }}

                                <span>
                                  <del>
                                    {{ symbolPrice($ticket->price) }}
                                  </del>
                                </span>
                              @else
                                @php
                                  $calculate_price = $ticket->price;
                                @endphp
                                {{ symbolPrice($calculate_price) }}
                              @endif
                            @else
                              @php
                                $calculate_price = $ticket->price;
                              @endphp
                              {{ symbolPrice($calculate_price) }}
                            @endif
                            <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                          </span>
                        @else
                          <span class="price">{{ __('Free') }}</span>
                        @endif
                      @endif
                    @endif
                  </span>
                </div>
              </div>
              @if (Auth::guard('customer')->check())
                @php
                  $customer_id = Auth::guard('customer')->user()->id;
                  $event_id = $event->id;
                  $checkWishList = checkWishList($event_id, $customer_id);
                @endphp
              @else
                @php
                  $checkWishList = false;
                @endphp
              @endif
              <a href="{{ $checkWishList == false ? route('addto.wishlist', $event->id) : route('remove.wishlist', $event->id) }}"
                class="wishlist-btn {{ $checkWishList == true ? 'bg-success' : '' }}">
                <i class="far fa-bookmark"></i>
              </a>
            </div>
          @endforeach
        </div>

      @endif
    </div>
  </section>
  <!-- Event Page End -->
<script>
  function initMap() {
  const lat = $('.lat').val(); 
  const lng = $('.lng').val();  

  const mapOptions = {
    center: { lat: parseFloat(lat), lng: parseFloat(lng) },
    zoom: 10, // Adjust the zoom level as needed
  };

  const map = new google.maps.Map(document.getElementById("map"), mapOptions);


 
}
window.onload = initMap;

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDB8ahpPhHkuWIh0JyxSg3uALTj9ynOS74&libraries=places&callback=initMap" async defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script>
  $('.your-class').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            arrows: true,
            focusOnSelect: true
        });
       
        if($(window).width() < 767){
          $('#main_container').removeClass('container');
          $('#main_container').addClass('container-fluid');
        }else{
          $('#main_container').removeClass('container-fluid');
          $('#main_container').addClass('container');
        }
       
</script>
@endsection
   
@section('modals')
  @includeIf('frontend.partials.modals')
@endsection
