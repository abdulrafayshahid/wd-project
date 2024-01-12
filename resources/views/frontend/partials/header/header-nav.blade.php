<!-- <header class="main-header">

 
  <div class="header-upper py-25">
    <div class="container clearfix">

      <div class="header-inner">
        <div class="logo-outer">
          <div class="logo"><a href="{{ route('index') }}"><img
                src="{{ asset('assets/admin/img/' . $websiteInfo->logo) }}" alt="Logo"></a></div>
        </div>

        <div class="nav-outer clearfix ml-lg-auto">
         
          <nav class="main-menu navbar-expand-xl">
            <div class="navbar-header">
              <div class="logo-mobile"><a href="{{ route('index') }}"><img
                    src="{{ asset('assets/admin/img/' . $websiteInfo->logo) }}" alt="Logo"></a></div>
              
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"
                aria-controls="main-menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>

            <div class="navbar-collapse collapse clearfix" id="main-menu">
              @php
                $links = json_decode($menuInfos, true);
              @endphp
              
                @if (!Auth::guard('customer')->check())
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle mr-1"
                      data-toggle="dropdown">{{ __('Customer') }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="{{ route('customer.login') }}">{{ __('Login') }}</a>
                      <a class="dropdown-item" href="{{ route('customer.signup') }}">{{ __('Signup') }}</a>
                    </div>
                  </div>
                @else
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle mr-1"
                      data-toggle="dropdown">{{ Auth::guard('customer')->user()->username }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="{{ route('customer.dashboard') }}">{{ __('Dashboard') }}</a>
                      <a class="dropdown-item" href="{{ route('customer.logout') }}">{{ __('Logout') }}</a>
                    </div>
                  </div>
                @endif
                @if (!Auth::guard('organizer')->check())
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle"
                      data-toggle="dropdown">{{ __('Organizer') }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                      <a class="dropdown-item" href="{{ route('organizer.login') }}">{{ __('Login') }}</a>
                      <a class="dropdown-item" href="{{ route('organizer.signup') }}">{{ __('Signup') }}</a>
                    </div>
                  </div>
                @else
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle mr-1"
                      data-toggle="dropdown">{{ Auth::guard('organizer')->user()->username }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="{{ route('organizer.dashboard') }}">{{ __('Dashboard') }}</a>
                      <a class="dropdown-item" href="{{ route('organizer.logout') }}">{{ __('Logout') }}</a>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </nav>
         
        </div>
      </div>
    </div>
  </div>
  

   
   <div class="header-lower py-25">
    <div class="container clearfix">

        <div class="nav-outer clearfix ml-lg-auto">
        
          <nav class="main-menu navbar-expand-xl">
            <div class="navbar-header">
              
          
              
            </div>

            <div class="navbar-collapse collapse clearfix" id="main-menu">
              @php
                $links = json_decode($menuInfos, true);
              @endphp
              <ul class="navigation clearfix">
                @foreach ($links as $link)
                  @php
                    $href = get_href($link, $currentLanguageInfo->id);
                  @endphp
                  @if (!array_key_exists('children', $link))
                    <li><a href="{{ $href }}" target="{{ $link['target'] }}">{{ $link['text'] }}</a></li>
                  @else
                    <li class="dropdown">
                      <a href="{{ $href }}" target="{{ $link['target'] }}">
                        {{ $link['text'] }}
                        <i class="fa fa-angle-down"></i>
                      </a>
                      <ul>
                        @foreach ($link['children'] as $level2)
                          @php
                            $l2Href = get_href($level2, $currentLanguageInfo->id);
                          @endphp
                          <li>
                            <a href="{{ $l2Href }}" target="{{ $level2['target'] }}">{{ $level2['text'] }}</a>
                          </li>
                        @endforeach
                      </ul>
                    </li>
                  @endif
                @endforeach
              </ul>

              <div class="menu-right lang-change">
                <form action="{{ route('change_language') }}" method="get">
                  <select name="lang_code" id="language" class="form-control" onchange="this.form.submit()">
                    @foreach ($allLanguageInfos as $item)
                      <option value="{{ $item->code }}"
                        {{ $item->code == $currentLanguageInfo->code ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                  </select>
                </form>
              
              </div>
            </div>
          </nav>
          
        </div>
      </div>
    </div>
  </div>
  

</header> -->

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

header {
    background: #ff7e5f;
}

.top-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
}

.logo img {
    width: 100px; /* Adjust as needed */
    height: auto;
}

.navbar {
    display: flex;
    justify-content: center;
    background-color: white; /* This color should match the second part of your gradient */
    padding: 10px 0;
    gap: 60px

}

.navbar a {
    text-decoration: none;
    color: grey;
    margin: 0 10px;
    font-weight: bold;
   
}

.header-right {
    display: flex;
    align-items: center;
}

.header-right a {
    text-decoration: none;
    color: white;
    margin-left: 20px;
    font-size: 14px;
}

.header-right a.deals {
    
    padding: 5px 12px;
    border-radius: 5px;
    font-weight: bold;
    margin-right: 200px;
}

.header-right .wishlist,
.header-right .cart {
    width: 24px; /* Adjust as needed */
    height: 24px;
}

.header-right .wishlist img,
.header-right .cart img {
    width: 100%;
    height: auto;
}

/* Media queries can be added for responsiveness */
@media (max-width: 768px) {
    .navbar {
        display: none;
    }

    .logo img {
        width: 
80px; /* Adjust for smaller screens */
}

.top-header {
    flex-direction: column;
    align-items: flex-start;
}

.header-right {
    width: 100%;
    justify-content: space-around;
    margin-top: 10px;
}

.header-right a {
    margin-left: 0;
    margin-right: 10px;
    font-size: 12px; /* Adjust for smaller screens */
}
}

.rf-btn {
    padding: 10px 20px;
    border: 2px solid white !important;
    background-color: transparent;
    color: white !important;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

.rf-btn:hover {
    background-color: white;
    color: white;
}

.rf-btn{
    border-color: #f36b21;
    color: #f36b21;
}

.rf-btn:hover {
    background-color: #f36b21;
    color: white;
}

.rf-btn2 {
    padding: 10px 20px;
    margin-left: 20px;
    border: 2px solid white !important;
    background-color: white;
    color: #f36b21 !important;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

.rf-btn2:hover {
    background-color: white;
    color: white;
}

.rf-btn2{
    border-color: white;
    color: #f36b21;
}

.rf-btn2:hover {
    background-color: white;
    color: white;
}
.dropdown-menu {
  display: none;
  position: absolute;
  color: #f36b21 !important;
}

.dropdown:hover .dropdown-menu {
  display: block;
}

.rf-dropdown-item{
  display: block; 
  color: #f36b21 !important;
}

.rf-dropdown-item:hover {
    background-color: #f36b21; /* This changes the background on hover */
    color: white !important; /* Optional: Change text color on hover for better readability */
}

</style>
<header>
    <div class="top-header">
        <div class="logo">
            <img src="{{ asset('assets/admin/img/header.png')}}" alt="Logo"> <!-- Replace # with your image path -->
        </div>
        <div class="header-right">
            <a href="#" class="deals">Deals Of The Week: BIG Island Hopping & Sailing Deals! Up To 25% Off</a>
            <div class="menu-right lang-change">
                <form action="{{ route('change_language') }}" method="get">
                  <select name="lang_code" id="language" class="form-control" onchange="this.form.submit()">
                    @foreach ($allLanguageInfos as $item)
                      <option value="{{ $item->code }}"
                        {{ $item->code == $currentLanguageInfo->code ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                  </select>
                </form>
              
              </div>
            <div class="dropdown">
            <button type="button" class="btn rf-btn">Customer</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <a class="rf-dropdown-item" href="#">Login</a>
    <a class="rf-dropdown-item" href="#">Signup</a>
  </div>
</div>
<div class="dropdown">
            <button type="button" class="btn rf-btn2">Organizer</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <a class="rf-dropdown-item" href="#">Login</a>
    <a class="rf-dropdown-item" href="#">Signup</a>
  </div>
</div>

        </div>
    </div>
    <nav class="navbar">
        <a href="#">Home</a>
        <a href="#">Events</a>
        <a href="#">Organizers</a>
        <a href="#">Pages</a>
        <a href="#">Shop</a>
        <a href="#">Contact</a>
        <a href="#">Blog</a>
    </nav>
</header>