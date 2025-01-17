<footer class="footer-section bg-lighter pt-40"
  style="background-color: #2a2a2a">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-5 rf-center col-sm-6">
        <div class="footer-widget about-widget">
          <div class="footer-logo mb-30">
            @if (!is_null($footerInfo))
              <a href="{{ route('index') }}"><img
                  src="{{ asset('assets/admin/img/footer_logo/footer.png') }}" alt="Logo"></a>
            @endif
          </div>
          <p class="footer-hide">{!! $footerInfo ? $footerInfo->about_company : '' !!}</p>
         
        </div>
      </div>
      <div class="col-lg-3 footer-hide col-sm-6">
        <div class="footer-widget link-widget ml-sm-auto">
          <h5 class="footer-title">{{ __('Quick Links') }}</h5>
          <ul>
            @foreach ($quickLinkInfos as $quickLinkInfo)
              <li><a href="{{ $quickLinkInfo->url }}">{{ $quickLinkInfo->title }}</a></li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="col-lg-4 col-sm-6">
        <div class="footer-widget about-widget ml-sm-auto">
          <h5 class="footer-title">{{ __('Contact Us') }}</h5>
          @if (!is_null($bex))
            @php
              $addresses = explode(PHP_EOL, $bex->contact_addresses);
            @endphp
            @if (!empty($addresses))
              <p class="ip">
                <i class="fas fa-map-marker-alt"></i>
                @foreach ($addresses as $address)
                  {{ $address }}
                  @if (!$loop->last)
                    |
                  @endif
                @endforeach
              </p>
            @endif

            @php
              $mails = explode(',', $bex->contact_mails);
            @endphp
            @if (!empty($mails))
              <p class="ip">
                <i class="fas fa-envelope"></i>
                @foreach ($mails as $mail)
                  <a href="mailto:{{ $mail }}"
                    class="d-inline-block text-transform-normal">{{ $mail }}</a>
                  @if (!$loop->last)
                    ,
                  @endif
                @endforeach
              </p>
            @endif

            @php
              $phones = explode(',', $bex->contact_numbers);
            @endphp
            <p class="ip"><i class="fas fa-mobile-alt"></i>
              @foreach ($phones as $phone)
                <a href="tel:{{ $phone }}">{{ $phone }}</a>
                @if (!$loop->last)
                  ,
                @endif
              @endforeach
            </p>
          @endif
        </div>
      </div>
    </div>

    
  </div>
  <div class="container-fluid">
    <div class="row" style="
    background-color: #f36b21;
">
      <div class="col-md-12 rf-lower-footer">
        
      <div class="copyright-area">
      
      <!-- @php
        $date = Date('Y');
        if (!empty($footerInfo->copyright_text)) {
            $footer_text = str_replace('{year}', $date, $footerInfo->copyright_text);
        }
      @endphp -->
      <div class="copyright-text">
        <!-- {!! !empty($footerInfo->copyright_text) ? $footer_text : '' !!} -->
        <p class="rf-copyright">Copyright ©2024. All Rights Reserved.</p>
        <div class="social-style-one">
      
            @if (count($socialMediaInfos) > 0)
              @foreach ($socialMediaInfos as $socialMediaInfo)
                <a class="rf-social-link" href="{{ $socialMediaInfo->url }}"><i class="{{ $socialMediaInfo->icon }}"></i></a>
              @endforeach
            @endif
          </div>
    </div>
      <!-- Scroll Top Button -->
      <!-- <button class="scroll-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></button> -->
      
    
    </div>
      </div>
    </div>
  </div>
</footer>

