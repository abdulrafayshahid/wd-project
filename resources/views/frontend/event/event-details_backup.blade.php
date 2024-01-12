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
    <link rel="stylesheet" href="https://cdn.getyourguide.com/tf/assets/compiled/client/assets/index.4b597cbc.css">
    <link rel="stylesheet" href="https://cdn.getyourguide.com/tf/assets/compiled/client/assets/index.4b597cbc.css">
@endsection

@section('hero-section')

    </section>
    <!-- Page Banner End -->
@endsection
@section('content')
<style>
  .overlay:before{
    background: none;
}
.activity-highlights__list .activity-highlights__list-item{
  list-style: disc;
}
</style>
    <!-- Event Page Start -->
    @php
        $map_address = preg_replace('/\s+/u', ' ', trim($content->address));
        $map_address = str_replace('/', ' ', $map_address);
        $map_address = str_replace('?', ' ', $map_address);
        $map_address = str_replace(',', ' ', $map_address);
    @endphp
    <section class="event-details-section pt-25 rpt-90 pb-90 rpb-70">
        <div class="container">
            <div class="event-details-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="event-details-content-inner">
                            <section class="container" data-v-33fc729c="">
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
                                                    <h1 class="activity__title js-title" data-track="activity-title"
                                                        data-test-id="activity-title" data-v-33fc729c="">
                                                        {{ $content->title }} <span
                                                            class="badge badge-info">{{ __('Upcoming') }}</span>
                                                    </h1>
                                                @elseif ($startDateTime <= $endDateTime && $endDateTime >= $now_time)
                                                    <h1 class="activity__title js-title" data-track="activity-title"
                                                        data-test-id="activity-title" data-v-33fc729c="">
                                                        {{ $content->title }}
                                                    </h1>
                                                    <span class="badge badge-success">{{ __('Running') }}</span>
                                                @else
                                                    @php
                                                        $over = true;
                                                    @endphp
                                                    <h1 class="activity__title js-title" data-track="activity-title"
                                                        data-test-id="activity-title" data-v-33fc729c="">
                                                        {{ $content->title }}
                                                    </h1>
                                                    <span class="badge badge-danger">{{ __('Over') }}</span>
                                                @endif
                                            </div>
                                        @elseif ($content->date_type == 'multiple')
                                            <div class="event-details-top">
                                                <h1 class="activity__title js-title" data-track="activity-title"
                                                    data-test-id="activity-title" data-v-33fc729c="">{{ $content->title }}
                                                </h1>
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
                                                <h1 class="activity__title js-title" data-track="activity-title"
                                                    data-test-id="activity-title" data-v-33fc729c="">{{ $content->title }}
                                                </h1>
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
                               
                                <p>hello</p>

                                <section data-test-id="activity-category"
                                    class="activity__category-label activity__category-label--mobile" data-v-33fc729c="">
                                    <span class="c-classifier-badge" data-v-33fc729c="">
                                        <!--[-->Guided tour
                                        <!--]--></span>
                                </section>
                                <h1 class="activity__title activity__title--mobile js-title" data-track="activity-title"
                                    data-v-33fc729c="">
                                    Chelsea Football Club Stadium and Museum Tour</h1>
                                <!---->
                                <!---->
                                <!---->
                                <section class="activity__mobile-header" data-v-33fc729c="">
                                    <section data-v-33fc729c="">
                                        <div class="supplier-name" data-test-id="activity-provider" data-v-33fc729c=""
                                            data-v-acecc794=""><span class="visibility-pixel" data-v-acecc794=""
                                                data-v-27926c18=""></span><small class="supplier-name__label"
                                                data-v-acecc794="">Activity provider:</small><a
                                                href="https://www.getyourguide.com/chelsea-fc-stadium-tour-museum-s11848/"
                                                class="supplier-name__link adp-simple-experiment__link"
                                                data-v-acecc794="">
                                                <!--[-->Chelsea FC Stadium Tour &amp; Museum
                                                <!--]--></a></div>
                                        <div data-track="activity-rating"
                                            class="activity__row activity__rating activity__rating--mobile"
                                            data-v-33fc729c="">
                                            <div class="rating-star" data-v-c7644bbe="">
                                                <!--[--><span class="c-icon rating-star__icon rating-star__icon--full"
                                                    data-v-c7644bbe="">
                                                    <!--[--><svg>
                                                        <use xlink:href="#c-star-fill"></use>
                                                    </svg>
                                                    <!--]-->
                                                    <!----></span><span
                                                    class="c-icon rating-star__icon rating-star__icon--full"
                                                    data-v-c7644bbe="">
                                                    <!--[--><svg>
                                                        <use xlink:href="#c-star-fill"></use>
                                                    </svg>
                                                    <!--]-->
                                                    <!----></span><span
                                                    class="c-icon rating-star__icon rating-star__icon--full"
                                                    data-v-c7644bbe="">
                                                    <!--[--><svg>
                                                        <use xlink:href="#c-star-fill"></use>
                                                    </svg>
                                                    <!--]-->
                                                    <!----></span><span
                                                    class="c-icon rating-star__icon rating-star__icon--full"
                                                    data-v-c7644bbe="">
                                                    <!--[--><svg>
                                                        <use xlink:href="#c-star-fill"></use>
                                                    </svg>
                                                    <!--]-->
                                                    <!----></span><span
                                                    class="c-icon c-icon--with-status rating-star__icon rating-star__icon--empty"
                                                    data-v-c7644bbe="">
                                                    <!--[--><svg>
                                                        <use xlink:href="#c-star-fill"></use>
                                                    </svg>
                                                    <!--]--><span class="c-icon__status">
                                                        <!--[--><span
                                                            class="c-icon rating-star__icon rating-star__icon--half"
                                                            data-v-c7644bbe="">
                                                            <!--[--><svg>
                                                                <use xlink:href="#c-star-fill"></use>
                                                            </svg>
                                                            <!--]-->
                                                            <!----></span>
                                                        <!--]--></span></span>
                                                <!--]-->
                                            </div>
                                            <p class="activity__rating--totals">4.6</p>
                                            <p class="activity__rating--count"><a href="#customer-reviews"
                                                    class="activity__rating--anchor gtm-trigger__adp-total-reviews-btn adp-simple-experiment__link">
                                                    <!--[--><span class="gtm-trigger__adp-total-reviews-btn">1343
                                                        reviews</span>
                                                    <!--]--></a></p>
                                        </div>
                                        <!---->
                                    </section>
                                    <section class="activity__price activity__price--mobile" data-v-33fc729c=""
                                        data-v-65a9bae7="">
                                        <div class="price-block--persuation-badge price-block price-block--has-price"
                                            data-test-id="activity-price-block" data-v-65a9bae7="">
                                            <!---->
                                            <section class="price-block__persuation-badge-container" data-v-65a9bae7="">
                                                <span
                                                    class="c-marketplace-badge c-marketplace-badge--primary persuasion-badge--LTSO persuasion-badge--size-R price-block__persuation-badge"
                                                    data-test-id="price-block-is-likely-to-sell-out" data-v-65a9bae7="">
                                                    <!--[-->Likely to sell out
                                                    <!--]--></span>
                                            </section>
                                            <div class="price-block-display-price-wrapper"
                                                data-test-id="activity-price-block" data-v-65a9bae7="">
                                                <p class="price-block-display-price" data-v-65a9bae7=""><span
                                                        class="price-block__from">From</span>

                                                    <strong class="price-block__price-actual "> <span>US$&nbsp;35.94</span>
                                                    </strong> <span class="price-block__explanation">per person</span>
                                                </p>
                                                <div class="price-block__button" data-v-65a9bae7=""><button
                                                        class="c-button c-button--medium c-button--filled-standard gtm-trigger__book-now-price-box-btn"
                                                        type="button" id="btn-booking-header"
                                                        data-test-id="btn-booking-header" data-v-65a9bae7="">
                                                        <!---->
                                                        <!--[-->Book now
                                                        <!--]--></button></div>
                                            </div>
                                            <!---->
                                            <!---->
                                        </div>
                                    </section>
                                </section>
                                <!---->
                                <section class="activity__persuasion-badge-container" data-v-33fc729c=""><span
                                        class="c-marketplace-badge c-marketplace-badge--primary persuasion-badge--LTSO persuasion-badge--size-R activity__persuasion-badge"
                                        data-v-33fc729c="">
                                        <!--[-->Likely to sell out
                                        <!--]--></span></section>
                                <section class="activity__content activity__row" data-v-33fc729c="">
                                    <div class="activity__container-columns" data-v-33fc729c="">
                                        <div class="activity__container-columns--main js-section-content" id="overview"
                                            data-v-33fc729c="">
                                            <div data-track="overview" data-v-33fc729c=""><!---->
                                                <section id="activity-overview" class="activity-overview"
                                                    data-component="overview" data-v-33fc729c="" data-v-5726f79d="">
                                                    <!----><!---->
                                                    <p class="activity-overview__content"
                                                        data-test-id="activity-overview-content" data-v-5726f79d="">When
                                                        you’re in London be sure to make time to visit Stamford Bridge on a
                                                        guided stadium and museum tour. Chelsea F.C. prides itself on having
                                                        knowledgeable and enthusiastic tour guides, who will take you behind
                                                        the scenes for a special experience.</p>
                                                </section>
                                            </div><!----><!---->
                                            <div data-track="key-details" data-v-33fc729c="">
                                                <section id="key-details" class="activity-key-details js-section-content"
                                                    data-component="key-details" data-test-id="activity-key-details"
                                                    data-v-33fc729c="" data-v-369d6368="">
                                                    <h2 class="activity-key-details__title" data-v-369d6368="">About this
                                                        activity</h2>
                                                    <section class="activity-key-details__container" data-v-369d6368="">
                                                        <dl class="activity-key-details__list" data-v-369d6368=""><!--[-->
                                                           
                                                          <div data-v-369d6368="">
                                                            <dt class="activity-key-details__term" data-v-369d6368="">
                                                                <span class="activity-key-details__term-icon"
                                                                    data-v-369d6368=""><span
                                                                        class="c-icon activity-key-details__icon"
                                                                        aria-label="Free cancellation"
                                                                        data-v-369d6368="">
                                                                        <!--[--><span data-v-369d6368=""><svg
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd"
                                                                                    clip-rule="evenodd"
                                                                                    d="M8 2v2h8V2h2v2h4v13a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V4h4V2h2Zm8 4v1h2V6h2v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6h2v1h2V6h8Z"
                                                                                    fill="currentColor"></path>
                                                                                <path fill-rule="evenodd"
                                                                                    clip-rule="evenodd"
                                                                                    d="m16.74 10.423-5.705 6.276-3.242-3.242 1.414-1.414 1.758 1.758 4.295-4.724 1.48 1.346Z"
                                                                                    fill="#007850"></path>
                                                                            </svg></span>
                                                                        <!--]-->
                                                                        <!----></span></span><span
                                                                    data-v-369d6368="">Free
                                                                    cancellation</span>
                                                            </dt>
                                                            <dd class="activity-key-details__description"
                                                                data-v-369d6368="">Cancel up to 24
                                                                hours in advance for a full refund
                                                                <!---->
                                                            </dd>
                                                        </div>
                                                        <div data-v-369d6368="">
                                                            <dt class="activity-key-details__term" data-v-369d6368="">
                                                                <span class="activity-key-details__term-icon"
                                                                    data-v-369d6368=""><span
                                                                        class="c-icon activity-key-details__icon"
                                                                        aria-label="Reserve now &amp; pay later"
                                                                        data-v-369d6368="">
                                                                        <!--[--><span data-v-369d6368=""><svg
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd"
                                                                                    clip-rule="evenodd"
                                                                                    d="M2 6a3 3 0 0 1 3-3h12.75a3 3 0 0 1 3 3v4.858a7.007 7.007 0 0 0-2-1.297v-.936H4V15a1 1 0 0 0 1 1h4c0 .695.101 1.366.29 2H5a3 3 0 0 1-3-3V6Zm3-1h12.75a1 1 0 0 1 1 1v.625H4V6a1 1 0 0 1 1-1Z"
                                                                                    fill="currentColor"></path>
                                                                                <path fill-rule="evenodd"
                                                                                    clip-rule="evenodd"
                                                                                    d="M12 16a4 4 0 1 1 8 0 4 4 0 0 1-8 0Zm4-6a6 6 0 1 0 0 12 6 6 0 0 0 0-12Zm1 5.586V13h-2v3.414l2.293 2.293 1.414-1.414L17 15.586Z"
                                                                                    fill="#0071EB"></path>
                                                                            </svg></span>
                                                                        <!--]-->
                                                                        <!----></span></span><span
                                                                    data-v-369d6368="">Reserve now &amp; pay
                                                                    later</span>
                                                            </dt>
                                                            <dd class="activity-key-details__description"
                                                                data-v-369d6368="">Keep your
                                                                travel plans flexible — book your spot and pay nothing
                                                                today.
                                                                <!---->
                                                            </dd>
                                                        </div>
                                                        <div data-v-369d6368="">
                                                          <dt class="activity-key-details__term" data-v-369d6368="">
                                                              <span class="activity-key-details__term-icon"
                                                                  data-v-369d6368=""><span
                                                                      class="c-icon activity-key-details__icon"
                                                                      aria-label="Reserve now &amp; pay later"
                                                                      data-v-369d6368="">
                                                                      <!--[--><span data-v-369d6368=""><i class="fa-solid fa-clock"></i></span>
                                                                      <!--]-->
                                                                      <!----></span></span><span
                                                                  data-v-369d6368="">Duration 1 hour</span>
                                                          </dt>
                                                          <dd class="activity-key-details__description"
                                                              data-v-369d6368="">Check availability to see starting times.</dd>
                                                      </div>
                                                      <div data-v-369d6368="">
                                                        <dt class="activity-key-details__term" data-v-369d6368="">
                                                            <span class="activity-key-details__term-icon"
                                                                data-v-369d6368=""><span
                                                                    class="c-icon activity-key-details__icon"
                                                                    aria-label="Reserve now &amp; pay later"
                                                                    data-v-369d6368="">
                                                                    <!--[--><span data-v-369d6368=""><i class="fa-solid fa-user"></i></span>
                                                                    <!--]-->
                                                                    <!----></span></span><span
                                                                data-v-369d6368="">Live tour guide</span>
                                                        </dt>
                                                        <dd class="activity-key-details__description"
                                                            data-v-369d6368="">English
                                                        </dd>
                                                    </div>
                                                    <div data-v-369d6368="">
                                                      <dt class="activity-key-details__term" data-v-369d6368="">
                                                          <span class="activity-key-details__term-icon"
                                                              data-v-369d6368=""><span
                                                                  class="c-icon activity-key-details__icon"
                                                                  aria-label="Reserve now &amp; pay later"
                                                                  data-v-369d6368="">
                                                                  <!--[--><span data-v-369d6368=""><i class="fa-solid fa-wheelchair"></i></span>
                                                                  <!--]-->
                                                                  <!----></span></span><span
                                                              data-v-369d6368="">Wheelchair accessible
                                                            </span>
                                                      </dt>
                                                     
                                                  </div>
                                                        </dl>
                                                    </section><!---->
                                                </section>
                                            </div><!---->
                                            <section class="activity-swap-columns" data-v-33fc729c="">
                                                <div class="overlay" data-v-33fc729c=""><!----><!--[-->
                                                    <div id="activity-experience"
                                                        class="activity-experience js-section-content" data-v-33fc729c=""
                                                        data-v-fe064f3a="">
                                                        <h2 class="activity-experience__header"
                                                            data-test-id="activity-experience-title" data-v-fe064f3a="">
                                                            Experience</h2><!----><!----><!---->
                                                        <section class="activity-highlights activity-experience__item"
                                                            id="highlights" data-v-fe064f3a="" data-v-b2dd5c7c="">
                                                            <div class="activity-accordion-item" data-v-b2dd5c7c="">
                                                                <div class="activity-accordion-item-header"><span
                                                                        class="activity-accordion-item__title"><!--[-->Highlights<!--]--><!----></span><span
                                                                        class="activity-accordion-item__icon"><span
                                                                            class="c-icon activity-accordion-item__icon-position adp-simple-experiment__link"><!--[--><svg>
                                                                                <use xlink:href="#c-chevron-down"></use>
                                                                            </svg><!--]--><!----></span></span></div>
                                                                <div
                                                                    class="activity-accordion-item__content--hidden activity-accordion-item__content">
                                                                    <!--[-->
                                                                    <ul class="activity-highlights__list"
                                                                        data-test-id="activity-highlights"
                                                                        data-v-b2dd5c7c=""><!--[-->
                                                                        <li class="activity-highlights__list-item"
                                                                            data-v-b2dd5c7c="">Enjoy a guided tour of
                                                                            Stamford Bridge, home to Chelsea Football Club
                                                                        </li>
                                                                        <li class="activity-highlights__list-item"
                                                                            data-v-b2dd5c7c="">Get a behind-the-scenes look
                                                                            at one of the world's greatest football clubs
                                                                        </li>
                                                                        <li class="activity-highlights__list-item"
                                                                            data-v-b2dd5c7c="">Feel the excitement as you
                                                                            walk down the tunnel to the pitch side</li>
                                                                        <!--]-->
                                                                    </ul><!--]-->
                                                                </div>
                                                            </div>
                                                        </section>
                                                        <section class="full-description activity-experience__item"
                                                            data-test-id="activity-full-description" data-v-fe064f3a=""
                                                            data-v-c7a0227e="">
                                                            <div class="activity-accordion-item" data-v-c7a0227e="">
                                                                <div class="activity-accordion-item-header"><span
                                                                        class="activity-accordion-item__title"><!--[-->Full
                                                                        description<!--]--><!----></span><span
                                                                        class="activity-accordion-item__icon"><span
                                                                            class="c-icon activity-accordion-item__icon-position adp-simple-experiment__link"><!--[--><svg>
                                                                                <use xlink:href="#c-chevron-down"></use>
                                                                            </svg><!--]--><!----></span></span></div>
                                                                <div
                                                                    class="activity-accordion-item__content--hidden activity-accordion-item__content">
                                                                    <!--[-->
                                                                    <section class="toggle-content" data-v-c7a0227e=""
                                                                        data-v-f700c6ce="">
                                                                        <div class="toggle-content__content toggle-content__content--packable"
                                                                            data-v-f700c6ce=""><!--[-->
                                                                            <div data-test-id="activity-full-description-text"
                                                                                data-v-c7a0227e="">If you’re a football fan
                                                                                you won’t want to miss out on a visit to
                                                                                Stamford Bridge, the home of Chelsea
                                                                                Football Club. This fun and informative
                                                                                1-hour tour includes a visit to the stadium
                                                                                and the museum.

                                                                                Your knowledgeable guide will take you
                                                                                behind the scenes at one of the greatest
                                                                                football clubs in the world, giving you
                                                                                access to areas normally reserved for
                                                                                players and officials.

                                                                                Imagine what it would be like to meet the
                                                                                press as you sit behind the desk in the
                                                                                press room. Soak up the atmosphere in the
                                                                                spectacular home dressing room, and then
                                                                                imagine the excitement as you walk down the
                                                                                tunnel to the pitch side, with the roar of a
                                                                                capacity crowd ringing in your ears!

                                                                                Stadium tours and museum opening times are
                                                                                subject to availability, cancellation, and
                                                                                change at short notice due to the nature of
                                                                                fixtures, and the operator apologizes for
                                                                                any inconvenience this may cause.&nbsp;
                                                                            </div><!--]-->
                                                                        </div>
                                                                        <div class="toggle-content__label-placeholder"
                                                                            data-v-f700c6ce=""><button
                                                                                class="toggle-content__label adp-simple-experiment__call-to-action gtm-trigger__adp-show-more-description-btn"
                                                                                type="button" data-v-f700c6ce=""><span
                                                                                    class="gtm-trigger__adp-show-more-description-btn"
                                                                                    data-test-id="see-more-button"
                                                                                    data-v-f700c6ce="">See
                                                                                    more</span></button></div>
                                                                    </section><!--]-->
                                                                </div>
                                                            </div>
                                                        </section>
                                                        <section class="activity-includes activity-experience__item"
                                                            data-v-fe064f3a="" data-v-80ad01d5="">
                                                            <div class="activity-accordion-item" data-v-80ad01d5="">
                                                                <div class="activity-accordion-item-header"><span
                                                                        class="activity-accordion-item__title"><!--[-->Includes<!--]--><!----></span><span
                                                                        class="activity-accordion-item__icon"><span
                                                                            class="c-icon activity-accordion-item__icon-position adp-simple-experiment__link"><!--[--><svg>
                                                                                <use xlink:href="#c-chevron-down"></use>
                                                                            </svg><!--]--><!----></span></span></div>
                                                                <div
                                                                    class="activity-accordion-item__content--hidden activity-accordion-item__content">
                                                                    <!--[-->
                                                                    <section class="activity-inclusions"
                                                                        data-test-id="activity-inclusions"
                                                                        data-v-80ad01d5="" data-v-6cf08a0d="">
                                                                        <ul class="activity-inclusions-inclusion"
                                                                            data-v-6cf08a0d=""><!--[-->
                                                                            <li class="activity-inclusions__item activity-inclusions__item--inclusion"
                                                                                data-v-6cf08a0d=""><span
                                                                                    class="c-icon activity-inclusions__icon activity-inclusions__icon--include"
                                                                                    data-v-6cf08a0d=""><i class="fa-solid fa-check"></i></span><span
                                                                                    class="activity-inclusions__test activity-inclusions__test--include"
                                                                                    data-v-6cf08a0d="">1-hour guided
                                                                                    stadium tour</span></li>
                                                                            <li class="activity-inclusions__item activity-inclusions__item--inclusion"
                                                                                data-v-6cf08a0d=""><span
                                                                                    class="c-icon activity-inclusions__icon activity-inclusions__icon--include"
                                                                                    data-v-6cf08a0d=""><i class="fa-solid fa-check"></i></span><span
                                                                                    class="activity-inclusions__test activity-inclusions__test--include"
                                                                                    data-v-6cf08a0d="">Museum
                                                                                    admission</span></li><!--]-->
                                                                        </ul>
                                                                        <ul class="activity-inclusions-exclusion"
                                                                            data-v-6cf08a0d=""><!--[--><!--]--></ul>
                                                                    </section><!--]-->
                                                                </div>
                                                            </div>
                                                        </section><!----><!----><!---->
                                                    </div><!--]-->
                                                </div>
                                                <div data-track="booking-assistant" data-v-33fc729c="">
                                                    <section id="booking-assistant" data-test-id="booking-assistant"
                                                        data-v-33fc729c="" data-v-d8ebe57c="">
                                                        <div class="booking-assistant-configurator booking-assistant"
                                                            is-date-selected="" data-v-d8ebe57c="" data-v-7851d6de="">
                                                            <h2 class="booking-assistant-configurator__header"
                                                                data-v-7851d6de="">Select participants and date</h2>
                                                            <section class="ba-dropdown people-picker"
                                                                data-test-id="activity-filters-primary-people-picker"
                                                                data-v-7851d6de="" data-v-f3167e6d="" data-v-82dd92fc="">
                                                                <section class="ba-input" data-v-82dd92fc=""
                                                                    data-v-d099617c=""><input type="checkbox"
                                                                        class="ba-input__toggle gtm-trigger__adp-people-picker-interaction"
                                                                        tabindex="0" aria-haspopup="true"
                                                                        aria-expanded="false" title="Adult x 1"
                                                                        data-v-d099617c=""><label class="ba-input__label"
                                                                        data-v-d099617c="">
                                                                        <div class="ba-input__label-wrapper"
                                                                            data-v-d099617c="">
                                                                            <div class="ba-input__label-icon"
                                                                                aria-hidden="true" data-v-d099617c="">
                                                                                <!--[--><!--[--><!--[--><span
                                                                                    class="c-icon people-picker__icon"
                                                                                    data-v-f3167e6d=""><!--[--><svg>
                                                                                        <use xlink:href="#c-users"></use>
                                                                                    </svg><!--]--><!----></span><!--]--><!--]--><!--]-->
                                                                            </div>
                                                                            <div class="ba-input__label-text-wrapper"
                                                                                data-v-d099617c=""><!---->
                                                                                <div class="ba-input__label-text-container"
                                                                                    data-v-d099617c=""><!--[--><input
                                                                                        readonly=""
                                                                                        class="ba-input__label-text"
                                                                                        value="Loading..."
                                                                                        title="Adult x 1"
                                                                                        data-v-d099617c=""><!--]--></div>
                                                                            </div><span class="ba-input__label-caret"
                                                                                data-v-d099617c=""></span>
                                                                        </div>
                                                                    </label></section>
                                                                <div class="ba-dropdown-overlay" data-v-82dd92fc=""></div>
                                                                <section class="ba-dropdown-content" data-v-82dd92fc="">
                                                                    <div class="ba-dropdown-header" data-v-82dd92fc="">
                                                                        <span class="ba-dropdown-header__cancel"
                                                                            role="button" data-v-82dd92fc=""><span
                                                                                class="c-icon ba-dropdown-header__cancel-icon"
                                                                                data-v-82dd92fc=""><!--[--><svg>
                                                                                    <use xlink:href="#c-cross"></use>
                                                                                </svg><!--]--><!----></span></span>
                                                                        <p class="ba-dropdown-header__title"
                                                                            data-v-82dd92fc="">Participants</p>
                                                                    </div>
                                                                    <section class="ba-dropdown-inner-content"
                                                                        data-v-82dd92fc=""><!--[-->
                                                                        <section class="participants-picker"
                                                                            data-test-id="ba-participants-picker-dialog"
                                                                            data-v-5404b80e="" data-v-f3167e6d="">
                                                                            <section class="participants-picker-options"
                                                                                data-test-id="ba-participants-picker-options-container"
                                                                                data-v-5404b80e="">
                                                                                <section class="participants-picker-option"
                                                                                    data-v-5404b80e="">
                                                                                    <section
                                                                                        class="participants-picker__label"
                                                                                        data-v-5404b80e=""><label
                                                                                            for="1"
                                                                                            class="participants-picker__label-name"
                                                                                            data-v-5404b80e=""><span
                                                                                                data-v-5404b80e="">Adult
                                                                                                &nbsp;</span><span
                                                                                                class="participants-picker__label-age"
                                                                                                data-v-5404b80e="">(Age
                                                                                                16-64)</span></label>
                                                                                    </section>
                                                                                    <section
                                                                                        class="participants-picker__input-wrapper"
                                                                                        data-v-5404b80e=""><button
                                                                                            type="button"
                                                                                            data-test-id="ba-participants-picker-minus-button"
                                                                                            class="participants-picker__minus"
                                                                                            tabindex="0"
                                                                                            aria-label="decrease quantity"
                                                                                            data-v-5404b80e=""><span
                                                                                                class="c-icon"
                                                                                                data-v-5404b80e=""><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-minus-in-circle">
                                                                                                    </use>
                                                                                                </svg><!----></span></button><input
                                                                                            id="1"
                                                                                            class="participants-picker__input"
                                                                                            data-test-id="ba-participants-picker-input"
                                                                                            type="number" pattern="[0-9]"
                                                                                            min="0" max="6"
                                                                                            step="1" maxlength="2"
                                                                                            autocomplete="off"
                                                                                            name="categories[1][amount]"
                                                                                            data-v-5404b80e=""><input
                                                                                            type="hidden"
                                                                                            name="categories[1][code]"
                                                                                            value="1"
                                                                                            data-v-5404b80e=""><button
                                                                                            type="button"
                                                                                            data-test-id="ba-participants-picker-plus-button"
                                                                                            class="participants-picker__plus"
                                                                                            tabindex="0"
                                                                                            aria-label="increase quantity"
                                                                                            data-v-5404b80e=""><span
                                                                                                class="c-icon"
                                                                                                data-v-5404b80e=""><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-plus-in-circle">
                                                                                                    </use>
                                                                                                </svg><!----></span></button>
                                                                                    </section>
                                                                                </section>
                                                                                <section class="participants-picker-option"
                                                                                    data-v-5404b80e="">
                                                                                    <section
                                                                                        class="participants-picker__label"
                                                                                        data-v-5404b80e=""><label
                                                                                            for="3"
                                                                                            class="participants-picker__label-name"
                                                                                            data-v-5404b80e=""><span
                                                                                                data-v-5404b80e="">Children
                                                                                                &nbsp;</span><span
                                                                                                class="participants-picker__label-age"
                                                                                                data-v-5404b80e="">(Age
                                                                                                5-15)</span></label>
                                                                                    </section>
                                                                                    <section
                                                                                        class="participants-picker__input-wrapper"
                                                                                        data-v-5404b80e=""><button
                                                                                            type="button"
                                                                                            data-test-id="ba-participants-picker-minus-button"
                                                                                            class="participants-picker__minus"
                                                                                            tabindex="0" disabled=""
                                                                                            aria-label="decrease quantity"
                                                                                            data-v-5404b80e=""><span
                                                                                                class="c-icon"
                                                                                                data-v-5404b80e=""><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-minus-in-circle">
                                                                                                    </use>
                                                                                                </svg><!----></span></button><input
                                                                                            id="3"
                                                                                            class="participants-picker__input"
                                                                                            data-test-id="ba-participants-picker-input"
                                                                                            type="number" pattern="[0-9]"
                                                                                            min="0" max="6"
                                                                                            step="1" maxlength="2"
                                                                                            autocomplete="off"
                                                                                            name="categories[3][amount]"
                                                                                            data-v-5404b80e=""><input
                                                                                            type="hidden"
                                                                                            name="categories[3][code]"
                                                                                            value="3"
                                                                                            data-v-5404b80e=""><button
                                                                                            type="button"
                                                                                            data-test-id="ba-participants-picker-plus-button"
                                                                                            class="participants-picker__plus"
                                                                                            tabindex="0"
                                                                                            aria-label="increase quantity"
                                                                                            data-v-5404b80e=""><span
                                                                                                class="c-icon"
                                                                                                data-v-5404b80e=""><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-plus-in-circle">
                                                                                                    </use>
                                                                                                </svg><!----></span></button>
                                                                                    </section>
                                                                                </section>
                                                                                <section class="participants-picker-option"
                                                                                    data-v-5404b80e="">
                                                                                    <section
                                                                                        class="participants-picker__label"
                                                                                        data-v-5404b80e=""><label
                                                                                            for="5"
                                                                                            class="participants-picker__label-name"
                                                                                            data-v-5404b80e=""><span
                                                                                                data-v-5404b80e="">Senior
                                                                                                &nbsp;</span><span
                                                                                                class="participants-picker__label-age"
                                                                                                data-v-5404b80e="">(Age
                                                                                                65-99)</span></label>
                                                                                    </section>
                                                                                    <section
                                                                                        class="participants-picker__input-wrapper"
                                                                                        data-v-5404b80e=""><button
                                                                                            type="button"
                                                                                            data-test-id="ba-participants-picker-minus-button"
                                                                                            class="participants-picker__minus"
                                                                                            tabindex="0" disabled=""
                                                                                            aria-label="decrease quantity"
                                                                                            data-v-5404b80e=""><span
                                                                                                class="c-icon"
                                                                                                data-v-5404b80e=""><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-minus-in-circle">
                                                                                                    </use>
                                                                                                </svg><!----></span></button><input
                                                                                            id="5"
                                                                                            class="participants-picker__input"
                                                                                            data-test-id="ba-participants-picker-input"
                                                                                            type="number" pattern="[0-9]"
                                                                                            min="0" max="6"
                                                                                            step="1" maxlength="2"
                                                                                            autocomplete="off"
                                                                                            name="categories[5][amount]"
                                                                                            data-v-5404b80e=""><input
                                                                                            type="hidden"
                                                                                            name="categories[5][code]"
                                                                                            value="5"
                                                                                            data-v-5404b80e=""><button
                                                                                            type="button"
                                                                                            data-test-id="ba-participants-picker-plus-button"
                                                                                            class="participants-picker__plus"
                                                                                            tabindex="0"
                                                                                            aria-label="increase quantity"
                                                                                            data-v-5404b80e=""><span
                                                                                                class="c-icon"
                                                                                                data-v-5404b80e=""><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-plus-in-circle">
                                                                                                    </use>
                                                                                                </svg><!----></span></button>
                                                                                    </section>
                                                                                </section>
                                                                                <section class="participants-picker-option"
                                                                                    data-v-5404b80e="">
                                                                                    <section
                                                                                        class="participants-picker__label"
                                                                                        data-v-5404b80e=""><label
                                                                                            for="6"
                                                                                            class="participants-picker__label-name"
                                                                                            data-v-5404b80e=""><span
                                                                                                data-v-5404b80e="">Student
                                                                                                (with ID) &nbsp;</span><span
                                                                                                class="participants-picker__label-age"
                                                                                                data-v-5404b80e="">(Age
                                                                                                16-99)</span></label>
                                                                                    </section>
                                                                                    <section
                                                                                        class="participants-picker__input-wrapper"
                                                                                        data-v-5404b80e=""><button
                                                                                            type="button"
                                                                                            data-test-id="ba-participants-picker-minus-button"
                                                                                            class="participants-picker__minus"
                                                                                            tabindex="0" disabled=""
                                                                                            aria-label="decrease quantity"
                                                                                            data-v-5404b80e=""><span
                                                                                                class="c-icon"
                                                                                                data-v-5404b80e=""><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-minus-in-circle">
                                                                                                    </use>
                                                                                                </svg><!----></span></button><input
                                                                                            id="6"
                                                                                            class="participants-picker__input"
                                                                                            data-test-id="ba-participants-picker-input"
                                                                                            type="number" pattern="[0-9]"
                                                                                            min="0" max="6"
                                                                                            step="1" maxlength="2"
                                                                                            autocomplete="off"
                                                                                            name="categories[6][amount]"
                                                                                            data-v-5404b80e=""><input
                                                                                            type="hidden"
                                                                                            name="categories[6][code]"
                                                                                            value="6"
                                                                                            data-v-5404b80e=""><button
                                                                                            type="button"
                                                                                            data-test-id="ba-participants-picker-plus-button"
                                                                                            class="participants-picker__plus"
                                                                                            tabindex="0"
                                                                                            aria-label="increase quantity"
                                                                                            data-v-5404b80e=""><span
                                                                                                class="c-icon"
                                                                                                data-v-5404b80e=""><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-plus-in-circle">
                                                                                                    </use>
                                                                                                </svg><!----></span></button>
                                                                                    </section>
                                                                                </section>
                                                                            </section><!----><!----><!---->
                                                                        </section><!--]-->
                                                                    </section>
                                                                    <footer class="ba-dropdown-footer" data-v-82dd92fc="">
                                                                        <!--[--><button
                                                                            class="c-button c-button--medium c-button--filled-standard people-picker__cta"
                                                                            type="button"
                                                                            data-v-f3167e6d=""><!----><!--[-->Done<!--]--></button><!--]-->
                                                                    </footer>
                                                                </section>
                                                            </section>
                                                            <section class="ba-dropdown ba-date-picker"
                                                                data-test-id="activity-filters-primary-date-picker"
                                                                data-v-7851d6de="" data-v-82dd92fc="">
                                                                <section class="ba-input" data-v-82dd92fc=""
                                                                    data-v-d099617c=""><input type="checkbox"
                                                                        class="ba-input__toggle gtm-trigger__adp-date-picker-interaction"
                                                                        tabindex="0" aria-haspopup="true"
                                                                        aria-expanded="false" title="Select date"
                                                                        data-v-d099617c=""><label class="ba-input__label"
                                                                        data-v-d099617c="">
                                                                        <div class="ba-input__label-wrapper"
                                                                            data-v-d099617c="">
                                                                            <div class="ba-input__label-icon"
                                                                                aria-hidden="true" data-v-d099617c="">
                                                                                <!--[--><!--[--><!--[--><span
                                                                                    class="c-icon ba-date-picker__icon"><!--[--><svg>
                                                                                        <use xlink:href="#c-calendar">
                                                                                        </use>
                                                                                    </svg><!--]--><!----></span><!--]--><!--]--><!--]-->
                                                                            </div>
                                                                            <div class="ba-input__label-text-wrapper"
                                                                                data-v-d099617c=""><!---->
                                                                                <div class="ba-input__label-text-container"
                                                                                    data-v-d099617c=""><!--[--><input
                                                                                        readonly=""
                                                                                        class="ba-input__label-text"
                                                                                        value="Select date"
                                                                                        title="Select date"
                                                                                        data-v-d099617c=""><!--]--></div>
                                                                            </div><span class="ba-input__label-caret"
                                                                                data-v-d099617c=""></span>
                                                                        </div>
                                                                    </label></section>
                                                                <div class="ba-dropdown-overlay" data-v-82dd92fc=""></div>
                                                                <section class="ba-dropdown-content" data-v-82dd92fc="">
                                                                    <div class="ba-dropdown-header" data-v-82dd92fc="">
                                                                        <span class="ba-dropdown-header__cancel"
                                                                            role="button" data-v-82dd92fc=""><span
                                                                                class="c-icon ba-dropdown-header__cancel-icon"
                                                                                data-v-82dd92fc=""><!--[--><svg>
                                                                                    <use xlink:href="#c-cross"></use>
                                                                                </svg><!--]--><!----></span></span>
                                                                        <p class="ba-dropdown-header__title"
                                                                            data-v-82dd92fc="">Date</p>
                                                                    </div>
                                                                    <section class="ba-dropdown-inner-content"
                                                                        data-v-82dd92fc=""><!--[-->
                                                                        <section class="ba-date-picker-calendar">
                                                                            <section class="calendar-date-picker"><input
                                                                                    id="calendar-date-picker-input"
                                                                                    class="calendar-date-picker__input flatpickr-input"
                                                                                    type="text" name="date"
                                                                                    data-test-id="ba-calendar-date-input"
                                                                                    readonly="readonly">
                                                                                <div class="flatpickr-calendar animate multiMonth inline"
                                                                                    tabindex="-1" style="width: 624px;">
                                                                                    <div class="flatpickr-months"><span
                                                                                            class="flatpickr-prev-month flatpickr-disabled"><span
                                                                                                class="c-icon calendar-navigation"><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-arrow-left">
                                                                                                    </use>
                                                                                                </svg></span></span>
                                                                                        <div class="flatpickr-month">
                                                                                            <div
                                                                                                class="flatpickr-current-month">
                                                                                                <span
                                                                                                    class="cur-month">August
                                                                                                </span>
                                                                                                <div
                                                                                                    class="numInputWrapper">
                                                                                                    <input
                                                                                                        class="numInput cur-year"
                                                                                                        type="number"
                                                                                                        tabindex="-1"
                                                                                                        aria-label="Year"
                                                                                                        min="2023"><span
                                                                                                        class="arrowUp"></span><span
                                                                                                        class="arrowDown"></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="flatpickr-month">
                                                                                            <div
                                                                                                class="flatpickr-current-month">
                                                                                                <span
                                                                                                    class="cur-month">September
                                                                                                </span>
                                                                                                <div
                                                                                                    class="numInputWrapper">
                                                                                                    <input
                                                                                                        class="numInput cur-year"
                                                                                                        type="number"
                                                                                                        tabindex="-1"
                                                                                                        aria-label="Year"
                                                                                                        min="2023"><span
                                                                                                        class="arrowUp"></span><span
                                                                                                        class="arrowDown"></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div><span
                                                                                            class="flatpickr-next-month"><span
                                                                                                class="c-icon calendar-navigation"><svg>
                                                                                                    <use
                                                                                                        xlink:href="#c-arrow-right">
                                                                                                    </use>
                                                                                                </svg></span></span>
                                                                                    </div>
                                                                                    <div class="flatpickr-innerContainer">
                                                                                        <div class="flatpickr-rContainer">
                                                                                            <div
                                                                                                class="flatpickr-weekdays">
                                                                                                <div
                                                                                                    class="flatpickr-weekdaycontainer">
                                                                                                    <span
                                                                                                        class="flatpickr-weekday">
                                                                                                        Sun</span><span
                                                                                                        class="flatpickr-weekday">Mon</span><span
                                                                                                        class="flatpickr-weekday">Tue</span><span
                                                                                                        class="flatpickr-weekday">Wed</span><span
                                                                                                        class="flatpickr-weekday">Thu</span><span
                                                                                                        class="flatpickr-weekday">Fri</span><span
                                                                                                        class="flatpickr-weekday">Sat
                                                                                                    </span>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="flatpickr-weekdaycontainer">
                                                                                                    <span
                                                                                                        class="flatpickr-weekday">
                                                                                                        Sun</span><span
                                                                                                        class="flatpickr-weekday">Mon</span><span
                                                                                                        class="flatpickr-weekday">Tue</span><span
                                                                                                        class="flatpickr-weekday">Wed</span><span
                                                                                                        class="flatpickr-weekday">Thu</span><span
                                                                                                        class="flatpickr-weekday">Fri</span><span
                                                                                                        class="flatpickr-weekday">Sat
                                                                                                    </span>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="flatpickr-days"
                                                                                                tabindex="-1"
                                                                                                style="width: 624px;">
                                                                                                <div class="dayContainer">
                                                                                                    <span
                                                                                                        class="flatpickr-day prevMonthDay hidden flatpickr-disabled"
                                                                                                        aria-label="July 30, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-07-30">
                                                                                                            30
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day prevMonthDay hidden flatpickr-disabled"
                                                                                                        aria-label="July 31, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-07-31">
                                                                                                            31
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day today current-month flatpickr-available"
                                                                                                        aria-label="August 1, 2023"
                                                                                                        aria-current="date"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-01">
                                                                                                            1
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 2, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-02">
                                                                                                            2
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 3, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-03">
                                                                                                            3
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 4, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-04">
                                                                                                            4
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day flatpickr-disabled current-month"
                                                                                                        aria-label="August 5, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-05">
                                                                                                            5
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 6, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-06">
                                                                                                            6
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 7, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-07">
                                                                                                            7
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 8, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-08">
                                                                                                            8
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 9, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-09">
                                                                                                            9
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 10, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-10">
                                                                                                            10
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 11, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-11">
                                                                                                            11
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 12, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-12">
                                                                                                            12
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day flatpickr-disabled current-month"
                                                                                                        aria-label="August 13, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-13">
                                                                                                            13
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 14, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-14">
                                                                                                            14
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 15, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-15">
                                                                                                            15
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 16, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-16">
                                                                                                            16
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 17, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-17">
                                                                                                            17
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 18, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-18">
                                                                                                            18
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 19, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-19">
                                                                                                            19
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 20, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-20">
                                                                                                            20
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 21, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-21">
                                                                                                            21
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 22, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-22">
                                                                                                            22
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 23, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-23">
                                                                                                            23
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 24, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-24">
                                                                                                            24
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 25, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-25">
                                                                                                            25
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 26, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-26">
                                                                                                            26
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 27, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-27">
                                                                                                            27
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 28, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-28">
                                                                                                            28
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 29, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-29">
                                                                                                            29
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 30, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-30">
                                                                                                            30
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="August 31, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-31">
                                                                                                            31
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day nextMonthDay hidden flatpickr-available"
                                                                                                        aria-label="September 1, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-01">
                                                                                                            1
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day nextMonthDay hidden flatpickr-disabled"
                                                                                                        aria-label="September 2, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-02">
                                                                                                            2
                                                                                                        </span></span></div>
                                                                                                <div class="dayContainer">
                                                                                                    <span
                                                                                                        class="flatpickr-day prevMonthDay hidden flatpickr-available"
                                                                                                        aria-label="August 27, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-27">
                                                                                                            27
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day prevMonthDay hidden flatpickr-available"
                                                                                                        aria-label="August 28, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-28">
                                                                                                            28
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day prevMonthDay hidden flatpickr-available"
                                                                                                        aria-label="August 29, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-29">
                                                                                                            29
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day prevMonthDay hidden flatpickr-available"
                                                                                                        aria-label="August 30, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-30">
                                                                                                            30
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day prevMonthDay hidden flatpickr-available"
                                                                                                        aria-label="August 31, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-08-31">
                                                                                                            31
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 1, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-01">
                                                                                                            1
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day flatpickr-disabled current-month"
                                                                                                        aria-label="September 2, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-02">
                                                                                                            2
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 3, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-03">
                                                                                                            3
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 4, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-04">
                                                                                                            4
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 5, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-05">
                                                                                                            5
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 6, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-06">
                                                                                                            6
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 7, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-07">
                                                                                                            7
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 8, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-08">
                                                                                                            8
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day flatpickr-disabled current-month"
                                                                                                        aria-label="September 9, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-09">
                                                                                                            9
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 10, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-10">
                                                                                                            10
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 11, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-11">
                                                                                                            11
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 12, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-12">
                                                                                                            12
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day flatpickr-disabled current-month"
                                                                                                        aria-label="September 13, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-13">
                                                                                                            13
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 14, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-14">
                                                                                                            14
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 15, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-15">
                                                                                                            15
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 16, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-16">
                                                                                                            16
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 17, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-17">
                                                                                                            17
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 18, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-18">
                                                                                                            18
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 19, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-19">
                                                                                                            19
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 20, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-20">
                                                                                                            20
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 21, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-21">
                                                                                                            21
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 22, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-22">
                                                                                                            22
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day flatpickr-disabled current-month"
                                                                                                        aria-label="September 23, 2023"
                                                                                                        data-test-id="ba-calendar-day">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-23">
                                                                                                            23
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 24, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-24">
                                                                                                            24
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 25, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-25">
                                                                                                            25
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 26, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-26">
                                                                                                            26
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 27, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-27">
                                                                                                            27
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 28, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-28">
                                                                                                            28
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 29, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-29">
                                                                                                            29
                                                                                                        </span></span><span
                                                                                                        class="flatpickr-day current-month flatpickr-available"
                                                                                                        aria-label="September 30, 2023"
                                                                                                        tabindex="-1"
                                                                                                        data-test-id="ba-calendar-day-available">
                                                                                                        <span
                                                                                                            class="date-wrapper"
                                                                                                            data-test-id="ba-calendar-day-value"
                                                                                                            data-date-value="2023-09-30">
                                                                                                            30
                                                                                                        </span></span></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </section><!---->
                                                                        </section><!--]-->
                                                                    </section>
                                                                    <footer class="ba-dropdown-footer"
                                                                        data-v-82dd92fc=""><!--[--><!--]--></footer>
                                                                </section>
                                                            </section><!----><button
                                                                class="c-button c-button--medium c-button--filled-standard js-check-availability gtm-trigger__adp-check-availability-btn avoid-close-dropdown-on-click"
                                                                type="button" style=""
                                                                data-v-7851d6de=""><!----><!--[-->Check
                                                                availability<!--]--></button>
                                                        </div><!----><!---->
                                                        <div class="overlay" data-v-d8ebe57c=""><!----><!--[--><!---->
                                                            <div data-v-d8ebe57c=""><!--[--><!--]--></div><!--]-->
                                                        </div>
                                                    </section>
                                                </div>
                                            </section>
                                            <section id="preparation-info" class="preparation-info js-section-content"
                                                data-v-33fc729c="" data-v-586ad2c4="">
                                                <section
                                                    class="activity-meeting-point preparation-info__item preparation-info__meeting-point"
                                                    data-test-id="activity-meeting-point" data-v-586ad2c4="">
                                                    <div class="activity-accordion-item">
                                                        <div class="activity-accordion-item-header"><span
                                                                class="activity-accordion-item__title"><!--[-->Meeting
                                                                point<!--]--><!----></span><span
                                                                class="activity-accordion-item__icon"><span
                                                                    class="c-icon activity-accordion-item__icon-position adp-simple-experiment__link"><!--[--><svg>
                                                                        <use xlink:href="#c-chevron-down"></use>
                                                                    </svg><!--]--><!----></span></span></div>
                                                        <div
                                                            class="activity-accordion-item__content--hidden activity-accordion-item__content">
                                                            <!--[--><!--[-->
                                                            <p class="activity-meeting-point__summary">Tickets to be
                                                                collected at the Stadium Tours &amp; Museum Store, located
                                                                at the back corner of the stadium. Signage and security
                                                                officers available to provide directions.</p><a
                                                                href="https://maps.google.com/?q=@51.48269271850586,-0.1915235072374344"
                                                                rel="noopener" target="_blank"
                                                                class="activity-meeting-point__map-link adp-simple-experiment__link"><!--[-->Open
                                                                in Google Maps ⟶ <!--]--></a><!--]--><!----><!--]-->
                                                        </div>
                                                    </div>
                                                </section><!--[-->
                                                <section
                                                    class="activity-important-information preparation-info__item preparation-info__important-information"
                                                    id="section--important-information"
                                                    data-test-id="activity-important-information" data-v-586ad2c4=""
                                                    data-v-7acd8271="">
                                                    <div class="activity-accordion-item" data-v-7acd8271="">
                                                        <div class="activity-accordion-item-header"><span
                                                                class="activity-accordion-item__title"><!--[-->Important
                                                                information<!--]--><!----></span><span
                                                                class="activity-accordion-item__icon"><span
                                                                    class="c-icon activity-accordion-item__icon-position adp-simple-experiment__link"><!--[--><svg>
                                                                        <use xlink:href="#c-chevron-down"></use>
                                                                    </svg><!--]--><!----></span></span></div>
                                                        <div
                                                            class="activity-accordion-item__content--hidden activity-accordion-item__content">
                                                            <!--[--><!----><!---->
                                                            <div class="activity-important-information__content"
                                                                data-v-7acd8271="">
                                                                <p class="activity-important-information__title"
                                                                    data-v-7acd8271="">Know before you go</p>
                                                                <section class="toggle-content" data-v-7acd8271=""
                                                                    data-v-f700c6ce="">
                                                                    <div class="toggle-content__content toggle-content__content--packable"
                                                                        data-v-f700c6ce=""><!--[-->
                                                                        <ul class="activity-important-information__list good-to-know"
                                                                            data-v-7acd8271=""><!--[-->
                                                                            <li class="activity-important-information__item"
                                                                                data-v-7acd8271="">Please note should you
                                                                                have any special requirements e.g.
                                                                                Accessible Tour Route you must notify the
                                                                                operator in advance.</li>
                                                                            <li class="activity-important-information__item"
                                                                                data-v-7acd8271="">The Club may, on short
                                                                                notice, reschedule, change the contents of
                                                                                or make out of bounds some areas of the
                                                                                Museum and/or Stadium Tour due to, for
                                                                                example:</li>
                                                                            <li class="activity-important-information__item"
                                                                                data-v-7acd8271="">The needs of the Club
                                                                                or any of its group companies</li>
                                                                            <li class="activity-important-information__item"
                                                                                data-v-7acd8271="">A fire or other
                                                                                emergency, or by order of any public
                                                                                authority</li>
                                                                            <li class="activity-important-information__item"
                                                                                data-v-7acd8271="">This tour is available
                                                                                in 10 languages via the TOUR+ web-app and is
                                                                                free to download for all guests, languages
                                                                                offered include English, French, Spanish,
                                                                                Italian, German, Portuguese, Hebrew,
                                                                                Japanese, Korean and Mandarin.</li><!--]-->
                                                                        </ul><!--]-->
                                                                    </div>
                                                                    <div class="toggle-content__label-placeholder"
                                                                        data-v-f700c6ce=""><button
                                                                            class="toggle-content__label adp-simple-experiment__call-to-action gtm-trigger__adp-show-more-important-info-btn"
                                                                            type="button" data-v-f700c6ce=""><span
                                                                                class="gtm-trigger__adp-show-more-important-info-btn"
                                                                                data-test-id="see-more-button"
                                                                                data-v-f700c6ce="">See
                                                                                more</span></button></div>
                                                                </section>
                                                            </div><!--]-->
                                                        </div>
                                                    </div>
                                                </section><!--]-->
                                            </section>
                                        </div>
                                        <aside class="activity__container-columns--side" data-v-33fc729c="">
                                            <div class="event-details-information">
                                                <input type="hidden" name="date_type"
                                                    value="{{ $content->date_type }}">
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
                                                                    <option
                                                                        value="{{ FullDateTime($date->start_date_time) }}">
                                                                        {{ FullDateTime($date->start_date_time) }}
                                                                        ({{ timeZoneOffset($websiteInfo->timezone) }}
                                                                        {{ __('GMT') }})
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                            @if (count($exp_dates) > 0)
                                                                @foreach ($exp_dates as $exp_date)
                                                                    <option disabled value="">
                                                                        {{ FullDateTime($exp_date->start_date_time) }}
                                                                        ({{ timeZoneOffset($websiteInfo->timezone) }}
                                                                        {{ __('GMT') }})
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
                                                                <div class="event-countdown"
                                                                    data-now="{{ $now }}"
                                                                    data-year="{{ $year }}"
                                                                    data-month="{{ $month }}"
                                                                    data-day="{{ $day }}"
                                                                    data-hour="{{ $hour }}"
                                                                    data-minute="{{ $minute }}"
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
                                                                class="lazy"
                                                                data-src="{{ asset('assets/admin/img/admins/' . $admin->image) }}"
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
                                                                <img class="lazy"
                                                                    data-src="{{ asset('assets/front/images/user.png') }}"
                                                                    alt="Author">
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
                                                    <a class="dropdown-toggle" href="#" role="button"
                                                        id="dropdownMenuLink" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
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
                                                                <button class="quantity-down" type="button"
                                                                    id="quantityDown">
                                                                    -
                                                                </button>
                                                                <input class="quantity" type="number" readonly
                                                                    value="0" data-price="{{ $calculate_price }}"
                                                                    data-max_buy_ticket="{{ $ticket->max_buy_ticket }}"
                                                                    name="quantity"
                                                                    data-ticket_id="{{ $ticket->id }}"
                                                                    data-stock="{{ $stock }}"
                                                                    data-purchase="{{ $purchase['status'] }}"
                                                                    data-p_qty="{{ $purchase['p_qty'] }}">
                                                                <button class="quantity-up" type="button"
                                                                    id="quantityUP">
                                                                    +
                                                                </button>
                                                            </div>



                                                            @if ($ticket->early_bird_discount == 'enable')
                                                                @php
                                                                    $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                                                @endphp
                                                                @if (!$discount_date->isPast())
                                                                    <p>{{ __('Discount available') . ' ' }} :
                                                                        ({{ __('till') . ' ' }} :
                                                                        <span
                                                                            dir="ltr">{{ date_format($discount_date, 'Y/m/d h:i a') }}</span>)
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
                                                            <button class="quantity-down" type="button"
                                                                id="quantityDown">
                                                                -
                                                            </button>
                                                            <input class="quantity" readonly type="number"
                                                                value="0" data-price="{{ $content->price }}"
                                                                data-max_buy_ticket="{{ $max_buy_ticket }}"
                                                                name="quantity" data-ticket_id="{{ $ticket->id }}"
                                                                data-stock="{{ $stock }}"
                                                                data-purchase="{{ $purchase['status'] }}"
                                                                data-p_qty="{{ $purchase['p_qty'] }}">
                                                            <button class="quantity-up" type="button"
                                                                id="quantityUP">
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
                                                                <p class="mb-0">
                                                                    <strong>{{ @$ticket_content->title }}</strong>
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
                                                                        <button class="quantity-down" type="button"
                                                                            id="quantityDown">
                                                                            -
                                                                        </button>
                                                                        <input class="quantity" readonly type="number"
                                                                            value="0"
                                                                            data-price="{{ $calculate_price }}"
                                                                            data-max_buy_ticket="{{ $ticket->max_buy_ticket }}"
                                                                            name="quantity[]"
                                                                            data-ticket_id="{{ $ticket->id }}"
                                                                            data-stock="{{ $stock }}"
                                                                            data-purchase="{{ $purchase['status'] }}"
                                                                            data-p_qty="{{ $purchase['p_qty'] }}">
                                                                        <button class="quantity-up" type="button"
                                                                            id="quantityUP">
                                                                            +
                                                                        </button>
                                                                    </div>


                                                                    @if ($ticket->early_bird_discount == 'enable')
                                                                        @php
                                                                            $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                                                        @endphp
                                                                        @if (!$discount_date->isPast())
                                                                            <p>{{ __('Discount available') . ' ' }} :
                                                                                ({{ __('till') . ' ' }} :
                                                                                <span
                                                                                    dir="ltr">{{ date_format($discount_date, 'Y/m/d h:i a') }}</span>)
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
                                                                    <p class="mb-0">
                                                                        <strong>{{ @$ticket_content->title }} -
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
                                                                            <button class="quantity-down_variation"
                                                                                type="button" id="quantityDown">
                                                                                -
                                                                            </button>
                                                                            <input type="hidden" name="v_name[]"
                                                                                value="{{ $item->name }}">
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
                                                                            <input type="number" value="0"
                                                                                class="quantity"
                                                                                data-price="{{ $calculate_price }}"
                                                                                data-max_buy_ticket="{{ $max_buy }}"
                                                                                data-name="{{ $item->name }}"
                                                                                name="quantity[]"
                                                                                data-ticket_id="{{ $ticket->id }}"
                                                                                readonly
                                                                                data-stock="{{ $stock }}"
                                                                                data-purchase="{{ $purchase['status'] }}"
                                                                                data-p_qty="{{ $purchase['p_qty'] }}">
                                                                            <button class="quantity-up" type="button"
                                                                                id="quantityUP">
                                                                                +
                                                                            </button>
                                                                        </div>
                                                                        @if ($ticket->early_bird_discount == 'enable')
                                                                            @php
                                                                                $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                                                            @endphp
                                                                            @if (!$discount_date->isPast())
                                                                                <p>{{ __('Discount available') . ' ' }} :
                                                                                    ({{ __('till') . ' ' }} :
                                                                                    <span
                                                                                        dir="ltr">{{ date_format($discount_date, 'Y/m/d h:i a') }}</span>)
                                                                                </p>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <p
                                                                        class="text-warning max_error_{{ $ticket->id }}{{ $item->v_max_ticket_buy }} ">
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
                                                                <p class="mb-0">
                                                                    <strong>{{ @$ticket_content->title }}</strong>
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
                                                                    <h6>
                                                                        <span class="">{{ __('free') }}</span>
                                                                    </h6>
                                                                    <div class="quantity-input">
                                                                        <button class="quantity-down" type="button"
                                                                            id="quantityDown">
                                                                            -
                                                                        </button>
                                                                        <input class="quantity"
                                                                            data-max_buy_ticket="{{ $ticket->max_buy_ticket }}"
                                                                            type="number" value="0"
                                                                            data-price="{{ $ticket->price }}"
                                                                            name="quantity[]"
                                                                            data-ticket_id="{{ $ticket->id }}"
                                                                            readonly data-stock="{{ $stock }}"
                                                                            data-purchase="{{ $purchase['status'] }}"
                                                                            data-p_qty="{{ $purchase['p_qty'] }}">
                                                                        <button class="quantity-up" type="button"
                                                                            id="quantityUP">
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
                                                    <button class="theme-btn w-100 mt-20"
                                                        type="submit">{{ __('Book Now') }}</button>
                                                @endif
                                            </div>
                                        </aside>
                                    </div>
                                </section>
                            </section>

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
                                    <img class="lazy"
                                        data-src="{{ asset('assets/admin/img/event/thumbnail/' . $event->thumbnail) }}"
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
                center: {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                },
                zoom: 10, // Adjust the zoom level as needed
            };

            const map = new google.maps.Map(document.getElementById("map"), mapOptions);



        }
        window.onload = initMap;
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDB8ahpPhHkuWIh0JyxSg3uALTj9ynOS74&libraries=places&callback=initMap"
        async defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/6e76ce4fba.js" crossorigin="anonymous"></script>

@endsection

@section('modals')
    @includeIf('frontend.partials.modals')
@endsection
