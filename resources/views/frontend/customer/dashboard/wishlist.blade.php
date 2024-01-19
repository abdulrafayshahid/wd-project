@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_wishlist_page_title ?? __('My Wishlist') }}
  @else
    {{ __('My Wishlist') }}
  @endif
@endsection
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">
        
          @if (!empty($pageHeading))
            {{ $pageHeading->customer_wishlist_page_title ?? __('My Wishlist') }}
          @else
            {{ __('My Wishlist') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">
                @if (!empty($pageHeading))
                  {{ $pageHeading->customer_dashboard_page_title ?? __('Dashboard') }}
                @else
                  {{ __('Dashboard') }}
                @endif
              </a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->customer_wishlist_page_title ?? __('My Wishlist') }}
              @else
                {{ __('My Wishlist') }}
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
  <!--====== Start Dashboard Section ======-->
  <section class="user-dashbord">
    <div class="container">
      <div class="row">
        @includeIf('frontend.customer.partials.sidebar')
        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info">
                  <div class="title">
                  <i class="fa fa-heart" style="font-size: 50px;"></i>
                    <h2>{{ __('Wishlist') }}</h2>
                  </div>
                  <div class="main-info">
                    @if (count($wishlist) > 0)
                      <div class="main-table">
                        <div class="table-responsiv">
                          <table class="dataTables_wrapper dt-responsive dt-bootstrap4 w-100 myTableStyle">
                            <thead>
                              <tr>
                                <th style="font-size: 28px; padding-bottom: 10px;">{{ __('Event Name') }}</th>
                                <th style="font-size: 28px; padding-bottom: 10px;">Dates</th>
                                <th style="font-size: 28px; padding-bottom: 10px;"></th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($wishlist as $item)
                              @php
                              $content = DB::table('event_contents')
                              ->join('events', 'event_contents.event_id', '=', 'events.id')
                              ->where('event_contents.event_id', $item->event_id)
                              ->select('event_contents.title', 'event_contents.slug', 'events.start_date', 'events.thumbnail') // Select image_path from event_images
                              ->first();
    @endphp
                                @if ($content)
                                  <tr>
                                    <td>
                                    <div class="wishlist-item">
                                    <a href="{{ route('remove.wishlist', $item->event_id) }}" class="fa fa-trash mr-2" aria-hidden="true"></a>
                                    <img src="{{ asset('assets/admin/img/event/thumbnail/' . $content->thumbnail) }}" style="width: 60px; height: 60px;">
                                    
                                    <a target="_blank"
                                        href="{{ route('event.details', [$content->slug, $item->event_id]) }}" class="rf-title-margin">{{ $content->title }}</a>
</div>
                                      </td>
                                    

                                    <td>
                                    @if($content->start_date)
    {{ date_format(new DateTime($content->start_date), 'd/m/Y') }}
@endif

                                    </td>
                                    <td>
                                      <a href="{{ route('event.details', [$content->slug, $item->event_id]) }}"
                                        class="btn mb-1">{{ __('Details') }}</a>
                                      <!-- <a href="{{ route('remove.wishlist', $item->event_id) }}"
                                        class="btn btn-danger bg-danger text-white  mb-1">{{ __('Remove') }}</a> -->
                                    </td>
                                  </tr>
                                @endif
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    @else
                      <p class="text-center">{{ __('No Event Found') . '.' }}</p>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Dashboard Section ======-->
@endsection

