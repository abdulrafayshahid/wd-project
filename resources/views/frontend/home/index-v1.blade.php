@extends('frontend.layout')
@section('pageHeading')
  {{ __('Home') }}
@endsection

@php
  $metaKeywords = !empty($seo->meta_keyword_home) ? $seo->meta_keyword_home : '';
  $metaDescription = !empty($seo->meta_description_home) ? $seo->meta_description_home : '';
@endphp
@section('meta-keywords', "{{ $metaKeywords }}")
@section('meta-description', "$metaDescription")
<script>
  document.addEventListener('DOMContentLoaded', function() {
    function updateActiveState(event) {
        // Get all div elements in the bottom-nav
        var divs = document.querySelectorAll('.bottom-nav div');

        // Remove the active classes from all divs
        divs.forEach(function(div) {
            div.classList.remove('active', 'res-active');
        });

        // Add the active classes to the clicked div
        event.currentTarget.classList.add('active', 'res-active');
    }

    // Add click event listener to each div in the bottom-nav
    document.querySelectorAll('.bottom-nav div').forEach(function(div) {
        div.addEventListener('click', updateActiveState);
    });
  });
</script>
@section('hero-section')
  <!-- Hero Section Start -->
  @if ($heroSection)
    <section class="rf-hero-section lazy"
      data-bg="{{ asset('assets/admin/img/hero-section/banner-img.jpg') }}" style="background-size: 100% 100%;">
    @else
      <section class="hero-section overlay pt-105 pb-120 lazy"
        data-bg="{{ asset('assets/front/images/hero-bg.jpg') }}">
  @endif
  <div class="container pt-100 pr-40">
  <!-- <form id="event-search" class="event-search mt-35" name="event-search" action="{{ route('events') }}" method="get">
        <div class="search-item">
          <label for="borwseby"><i class="fas fa-list"></i></label>
          <select name="category" id="borwseby">
            <option value="">{{ __('All Category') }}</option>
            @foreach ($categories as $category)
              <option value="{{ $category->slug }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="search-item">
          <label for="search"><i class="fas fa-search"></i></label>
          <input type="search" id="search" name="search-input" placeholder="{{ __('Search Anything') }}">
        </div>
        <button type="submit" class="theme-btn">{{ $heroSection ? $heroSection->first_button : __('Search') }}</button>
      </form> -->
      <form id="event-search" class="mt-35" name="event-search" action="{{ route('events') }}" method="get">
      <div class="search-box">
    <input type="search" id="search" class="search-txt search-input" placeholder=" Where Are You Going">
    <button type="submit" class="search-btn">{{ __('Search') }}</button>
    </div><br>
    </form>
    <div class="hero-content">
      <!-- <h1>
        {{ $heroSection ? $heroSection->first_title : __('Event Ticketing and Booking System') }}
      </h1> -->
      <h2>Our Vision<br>Our Innovation<br>Event Solutions</h2><br>
      <h3 style="color: grey;">Exploring the World of the McLaren<br>Technology Centre</h3>
      <!-- <p>
        {{ $heroSection ? $heroSection->second_title : __('This is an affordable and powerful event ticketing platform for event organisers, promoters, and managers. Easily create, promote and sell tickets to your events of every type and size.') }}
      </p> -->
      
    </div>
  </div>
  </section>
  <div class="rf-bottom-nav bottom-nav">
    <div class="active res-active">Wedding</div>
    <div>Business</div>
    <div>Career</div>
    <div>Conference</div>
    <div>Sports</div>
  </div>

  <script>
function updateLayout() {
  var mediaQuery = window.matchMedia('(max-width: 540px)');
  var bottomNav = document.querySelector('.rf-bottom-nav');
  var largemediaQuery = window.matchMedia('(min-width: 540px)');

  if (mediaQuery.matches) {
    // For smaller screens
    bottomNav.classList.remove('bottom-nav');
    bottomNav.classList.add('row');
    bottomNav.classList.add('res-bottom-nav');

    bottomNav.querySelectorAll('div').forEach(function (div) {
      div.classList.add('col-5');
      div.classList.remove('active');
      div.classList.add('res-active');
    });
  } else if (largemediaQuery.matches) {
    // For larger screens, revert to original classes if needed
    bottomNav.classList.add('bottom-nav');
    bottomNav.classList.remove('row');
    bottomNav.classList.remove('res-bottom-nav');

    var divElements = bottomNav.querySelectorAll('div');
    for (var i = 0; i < divElements.length; i++) {
        divElements[i].classList.remove('col-5');
        divElements[i].classList.add('active');
        divElements[i].classList.remove('res-active');
    break
}
  
  }
}

// Attach the function to the window resize event
window.addEventListener('resize', updateLayout);
window.addEventListener('DOMContentLoaded', updateLayout);
// Call the function initially
updateLayout();
</script>
<style>
  .rf-social-link{
    padding-top: 12px;
  }
</style>
  <!-- Hero Section End -->
@endsection
@section('content')

  <!-- Events Section Start -->
  @if ($secInfo->featured_section_status == 1)
    <section class="events-section pt-110 rpt-90 pb-90 rpb-70 bg-lighter">
      <div class="container">

        <div class="section-title text-center mb-45">
          <h2>{{ $secTitleInfo ? $secTitleInfo->event_section_title : __('Featured Events') }}</h2>
        </div>
        @if (count($eventCategories) < 1)
          <p class="text-center">{{ __('No Events Found') }}</p>
        @else
          <ul class="events-filter mb-40">
            <li data-filter="*" class="current">{{ __('All') }}</li>
            @foreach ($eventCategories as $item)
              <li data-filter=".{{ $item->id }}">{{ $item->name }}</li>
            @endforeach
          </ul>
          <div class="row events-active">
            @foreach ($eventCategories as $item)
              @php
                $now_time = \Carbon\Carbon::now();
                $dateToFind = \Carbon\Carbon::now()->toDateString();
                $events = DB::table('event_contents')
                    ->join('events', 'events.id', '=', 'event_contents.event_id')
                    ->where([['event_contents.event_category_id', '=', $item->id], ['event_contents.language_id', '=', $currentLanguageInfo->id], ['events.status', 1], ['events.end_date_time', '>=', $now_time], ['events.is_featured', '=', 'yes']])
                    ->whereJsonContains('events.is_featured_date', $dateToFind)
                    ->where('events.is_featured', "yes")
                    ->orderBy('events.created_at', 'desc')
                    ->get();
              @endphp
              @foreach ($events as $event)
                <div class="col-lg-4 col-md-6 item {{ $item->id }} motivational">
                  <div class="event-item">
                    <div class="event-image">
                      <a href="{{ route('event.details', [$event->slug, $event->id]) }}">
                        <img src="{{ asset('assets/admin/img/event/thumbnail/' . $event->thumbnail) }}" alt="Event">
                      </a>
                    </div>
                    <div class="event-content">
                      <ul class="time-info">
                          @php
                          if ($event->date_type == 'multiple') {
                              $event_date = eventLatestDates($event->id);
                              $date = strtotime(@$event_date->start_date);
                          } else {
                              $date = strtotime($event->start_date);
                          }
                        @endphp
                        <li>
                          <i class="far fa-calendar-alt"></i>
                          <span>
                            {{ date('d M', $date) }}
                          </span>
                        </li>
                        
                        <li>
                          <i class="far fa-hourglass"></i>
                          <span
                            title="{{ __('Event Duration') }}">{{ $event->date_type == 'multiple' ? @$event_date->duration : $event->duration }}</span>
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

                      @if (strlen($desc) > 100)
                        <p class="event-description">{{ mb_substr($desc, 0, 100) . '....' }}</p>
                      @else
                        <p class="event-description">{{ $desc }}</p>
                      @endif
                      @php
                        if ($event->event_type == 'online') {
                            $ticket = App\Models\Event\Ticket::where('event_id', $event->id)
                                ->orderBy('price', 'asc')
                                ->first();
                        } else {
                            $ticket = App\Models\Event\Ticket::where([['event_id', $event->id], ['price', '!=', null]])
                                ->orderBy('price', 'asc')
                                ->first();
                            if (empty($ticket)) {
                                $ticket = App\Models\Event\Ticket::where([['event_id', $event->id], ['f_price', '!=', null]])
                                    ->orderBy('price', 'asc')
                                    ->first();
                            }
                        }
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
                                    $v_min_price = array_reduce(
                                        $variation,
                                        function ($a, $b) {
                                            return $a['price'] < $b['price'] ? $a : $b;
                                        },
                                        array_shift($variation),
                                    );
                                    $price = $v_min_price['price'];
                                  @endphp
                                  <span class="price">
                                    @if ($currentLanguageInfo->direction == 1)
                                      <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                    @endif
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
                                    @if ($currentLanguageInfo->direction != 1)
                                      <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                    @endif
                                  </span>
                                </span>
                              @elseif($ticket->pricing_type == 'normal')
                                <span class="price" dir="ltr">
                                  @if ($currentLanguageInfo->direction == 1)
                                    <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                  @endif
                                  @if ($ticket->early_bird_discount == 'enable')
                                    {{-- check discount date over or not --}}
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

                                  @if ($currentLanguageInfo->direction != 1)
                                    <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                  @endif
                                </span>
                              @else
                                <span class="price">{{ __('Free') }}
                                  <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
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
                </div>
              @endforeach
            @endforeach

          </div>
        @endif
      </div>
      @if (!empty(showAd(3)))
        <div class="text-center mt-4">
          {!! showAd(3) !!}
        </div>
      @endif
    </section>
  @endif
  <!-- Events Section End -->

  <!-- Category Section Start -->
  @if ($secInfo->categories_section_status == 1)
    <section class="category-section pt-110 rpt-90 pb-80 rpb-60">
      <div class="container">
        <div class="section-title mb-60">
          <h2>{{ $secTitleInfo ? $secTitleInfo->category_section_title : __('Categories') }}</h2>
        </div>
        <div class="category-wrap text-white">
          @if (count($eventCategories) > 0)
            @foreach ($eventCategories as $item)
              <a href="{{ route('events', ['category' => $item->slug]) }}" class="category-item">
                <img class="lazy" data-src="{{ asset('assets/admin/img/event-category/' . $item->image) }}" alt="Category">
                <div class="category-content">
                  <h5>{{ $item->name }}</h5>
                </div>
              </a>
            @endforeach
          @else
            <h3 class="text-dark">{{ __('No Category Found') }}</h3>
          @endif


        </div>
      </div>
    </section>
  @endif
  <!-- Category Section End -->

  <!-- About Section Start -->
  @if ($secInfo->about_section_status == 1)
    <section class="about-section pb-120 rpb-95">
      <div class="container">
        @if (is_null($aboutUsSection))
          <h2 class="text-center">{{ __('No data found for about section') }}</h2>
        @endif
        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="about-image-part pt-10 rmb-55">
              @if (!is_null($aboutUsSection))
                <img class="lazy" data-src="{{ asset('assets/admin/img/about-us-section/' . $aboutUsSection->image) }}" alt="About">
              @endif
            </div>
          </div>
          <div class="col-lg-6">
            <div class="about-content">
              <div class="section-title mb-30">
                <h2>{{ $aboutUsSection ? $aboutUsSection->title : '' }}</h2>
              </div>
              <p>{{ $aboutUsSection ? $aboutUsSection->subtitle : '' }}</p>
              <div>
                {!! $aboutUsSection ? $aboutUsSection->text : '' !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- About Section End -->


  <!-- Feature Section Start -->
  <section class="feature-section pt-110 rpt-90 bg-lighter">
    @if ($secInfo->features_section_status == 1)
      <div class="container pb-90 rpb-70">
        <div class="section-title text-center mb-55">
          <h2>{{ $featureEventSection ? $featureEventSection->title : '' }}</h2>
          <p>{{ $featureEventSection ? $featureEventSection->text : '' }}</p>
          @if (count($featureEventItems) < 1)
            <h2>{{ __('No data found for features section') }}</h2>
          @endif
        </div>
        <div class="row justify-content-center">
          @foreach ($featureEventItems as $item)
            <div class="col-xl-4 col-md-6">
              <div class="feature-item">
                <i class="{{ $item->icon }}"></i>
                <div class="feature-content">
                  <h5>{{ $item->title }}</h5>
                  <p>{{ $item->text }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    @endif
    @if ($secInfo->how_work_section_status == 1)
      @if ($howWork)
        <div class="work-process text-center">
          <div class="container">
            <div class="work-process-inner pt-110 rpt-90 pb-80 rpb-60">

              <div class="section-title mb-60">
                <h2>{{ $howWork->title }}</h2>
                <p>{{ $howWork->text }}</p>
              </div>
              <div class="row justify-content-center">
                @foreach ($howWorkItems as $item)
                  <div class="col-xl-3 col-md-6">
                    <div class="work-process-item">
                      <div class="icon">
                        <span class="number">{{ $item->serial_number }}</span>
                        <i class="{{ $item->icon }}"></i>
                      </div>
                      <div class="content">
                        <h4>{{ $item->title }}</h4>
                        <p>{{ $item->text }}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      @else
        <div class="work-process text-center">
          <div class="container">
            <h2>{{ __('No Data Found for how work section') }}</h2>
          </div>
        </div>
      @endif
    @endif
  </section>
  <!-- Feature Section End -->


  <!-- Testimonial Section Start -->
  @if ($secInfo->testimonials_section_status == 1)
    <section class="testimonial-section pt-120 rpt-80">
      <div class="container">
        <div class="row pb-75 rpb-55">
          <div class="col-lg-4">
            <div class="testimonial-content pt-10 rmb-55">
              <div class="section-title mb-30">
                <h2>{{ $testimonialData ? $testimonialData->title : __('What say our client about us') }}</h2>
              </div>
              <p>{{ $testimonialData ? $testimonialData->text : '' }}</p>
              <div class="total-client-reviews mt-40 bg-lighter">
                <div class="review-images mb-30">
                  @if (!is_null($testimonialData))
                    <img class="lazy" data-src="{{ asset('assets/admin/img/testimonial/' . $testimonialData->image) }}" alt="Reviewer">
                  @else
                    <img class="lazy" data-src="{{ asset('assets/admin/img/testimonial/clients.png') }}" alt="Reviewer">
                  @endif
                  <span class="pluse"><i class="fas fa-plus rf-social-link"></i></span>
                </div>
                <h6>{{ $testimonialData ? $testimonialData->review_text : __('0 Clients Reviews') }}</h6>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="testimonial-wrap">
              @if (count($testimonials) > 0)
                <div class="row">
                  @foreach ($testimonials as $item)
                    <div class="col-md-6">
                      <div class="testimonial-item">
                        <div class="author">
                          <img class="lazy" data-src="{{ asset('assets/admin/img/clients/' . $item->image) }}" alt="Author">
                          <div class="content">
                            <h5>{{ $item->name }}</h5>
                            <span>{{ $item->occupation }}</span>
                            <div class="ratting">
                              @for ($i = 1; $i <= $item->rating; $i++)
                                <i class="fas fa-star"></i>
                              @endfor
                            </div>
                          </div>
                        </div>
                        <p>{{ $item->comment }}</p>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <h4 class="text-center">{{ __('No Review Found') }}</h4>
              @endif
            </div>
          </div>
        </div>
        <hr>
      </div>

    </section>
  @endif

  
  <!-- Testimonial Section End -->
  <div class="container rf-container-cities">
  <section class="city-section">
    <h2>Awe-inspiring sports around the world</h2>
    <div class="cities-grid">
    <article class="city">
  <img src="{{ asset('assets/admin/img/cities-section/Rectangle 18.png') }}" alt="Boston" />
  <div class="city-label">Boston</div>
</article>
<article class="city">
  <img src="{{ asset('assets/admin/img/cities-section/Rectangle 19.png') }}" alt="London" />
  <div class="city-label">London</div>
</article>
<article class="city">
  <img src="{{ asset('assets/admin/img/cities-section/Rectangle 20.png') }}" alt="Amsterdam" />
  <div class="city-label">Amsterdam</div>
</article>
<article class="city">
  <img src="{{ asset('assets/admin/img/cities-section/Rectangle 21.png') }}" alt="New York City" />
  <div class="city-label">New York City</div>
</article>
<article class="city">
  <img src="{{ asset('assets/admin/img/cities-section/Rectangle 23.png') }}" alt="Stuttgart" />
  <div class="city-label">Stuttgart</div>
</article>
<article class="city">
  <img src="{{ asset('assets/admin/img/cities-section/Rectangle 22.png') }}" alt="Berlin" />
  <div class="city-label">Berlin</div>
</article>
    </div>
  </section>
</div>
  <!-- Client Logo Start -->
  @if ($secInfo->partner_section_status == 1)
    <section class="client-logo-area text-center pt-95 rpt-80 pb-90 rpb-70">
      <div class="container">
        <div class="section-title mb-55">
          <h2>{{ $partnerInfo ? $partnerInfo->title : __('Our Partner') }}</h2>
          <p>{{ $partnerInfo ? $partnerInfo->text : '' }}</p>
        </div>
        <div class="client-logo-wrap">
          @if (count($partners) > 0)
            @foreach ($partners as $item)
              <div class="client-logo-item">
                <a href="{{ $item->url }}" target="_blank"><img class="lazy"
                    data-src="{{ asset('assets/admin/img/partner/' . $item->image) }}" alt="Client Logo"></a>
              </div>
            @endforeach
          @else
            <h5>{{ __('No Partner Found') }}</h5>
          @endif
        </div>
      </div>
    </section>
  @endif
  <!-- Client Logo End -->
@endsection
