<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">



<!-- <script>
function change_lang() {
    // Select all elements with the class .header-right a.deals
    var elements = document.querySelectorAll('.header-right a.deals');

    // Iterate over each element and remove the margin-right property
    elements.forEach(function(element) {
      element.style.marginRight = '5px !important';
    });
}
</script> -->

<style>

  @media screen and (max-width: 540px) {
    .top-header{
      display: none;
    }
  body {
    margin: 0;
    font-family: Arial, sans-serif;
}

header {
    text-align: center;
}

.upper-header {
    background-color: white;
    color: black;
    font-size: 10px;
    border-color: whitesmoke;
}

.lower-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-left: 10px;
    padding-right: 10px;
    background-color: whitesmoke;
}

.logo {
    height: 50px; /* Adjust as needed */
}

.menu-icon {
    font-size: 24px; /* Adjust as needed */
}



/* The sidebar menu */
.sidebar {
  height: 100%;
  width: 0px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: white;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

/* The close button */
.closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

.sidebar ul {
  list-style: none;
  padding: 0;
}

.sidebar a {
  padding: 8px 8px 8px 8px;
  text-decoration: none;
  font-size: 25px;
  color: #6b6b6b;
  display: block;
  transition: 0.3s;
}

.sidebar a:hover {
  color: #f1f1f1;
}

  }

</style>
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
    background: #f36b21;

    
}

</style>
<header>
    <div class="top-header">
        <div class="logo">
            <img src="{{ asset('assets/admin/img/header.png')}}" alt="Logo"> <!-- Replace # with your image path -->
            <a href="#" class="deals">Deals Of The Week: BIG Island Hopping & Sailing Deals! Up To 25% Off</a>
        </div>
        <div class="header-right">
            <!-- <div class="dropdown">
            
            <a class="rf-a rf-a-hide" href="{{ route('organizer.login') }}">{{ __('Become an Organizer') }}</a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <a class="rf-dropdown-item" href="{{ route('organizer.login') }}">Login</a>
    <a class="rf-dropdown-item" href="{{ route('organizer.signup') }}">Signup</a>
  </div>
</div> -->

@if (!Auth::guard('organizer')->check())
<div class="dropdown" style="z-index: 999">
<a class="rf-a rf-a-hide" href="{{ route('organizer.login') }}">{{ __('Become an Organizer') }}</a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <a class="rf-dropdown-item" href="{{ route('organizer.login') }}">{{ __('Login') }}</a>
    <a class="rf-dropdown-item" href="{{ route('organizer.signup') }}">{{ __('Signup') }}</a>
  </div>
</div>
@else
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle mr-1"
                      data-toggle="dropdown">{{ Auth::guard('organizer')->user()->username }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="rf-dropdown-item" href="{{ route('organizer.dashboard') }}">{{ __('Dashboard') }}</a>
                      <a class="rf-dropdown-item" href="{{ route('organizer.logout') }}">{{ __('Logout') }}</a>
                    </div>
                  </div>
                @endif


            <i class="fa fa-heart rf-a-hide" aria-hidden="true" style="color: white"></i>
            <a class="rf-a rf-a-hide" href="{{ route('customer.wishlist') }}">{{ __('Wishlist') }}</a>
            @foreach ($links as $link)
        <!-- Check if link has children -->
        @if (!array_key_exists('children', $link))
            <!-- <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li> -->
        @else
            <li class="">
                <!-- <a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a> -->
                <div class="">
                    @foreach ($link['children'] as $level2)
                    @if (in_array($level2['text'], ['Cart','عربة التسوق']))
                    <i class="fa fa-shopping-cart rf-a-hide" aria-hidden="true" style="color: white"></i>
        <a class="rf-a rf-a-hide" href="{{ get_href($level2, $currentLanguageInfo->id) }}">{{ $level2['text'] }}</a>
    @endif
                    @endforeach
                </div>
            </li>
        @endif
    @endforeach
              @if (!Auth::guard('customer')->check())
              <i href="{{ route('customer.login') }}" class="fa fa-user rf-a-hide" style="color: white;"></i><a class="rf-a rf-a-hide" href="{{ route('customer.login') }}" style="color: white">{{ __('Login') }}</a>
            <!-- <div class="dropdown">
            <i class="fa-light fa-user" style="color: #B197FC;"></i>
            <a class="rf-a rf-a-hide" href="{{ route('organizer.login') }}">{{ __('Become an Organizer') }}</a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <a class="rf-dropdown-item" href="{{ route('customer.login') }}">Login</a>
    <a class="rf-dropdown-item" href="{{ route('customer.signup') }}">Signup</a>
  </div> -->



@else
<style>
  .header-right a.deals {
    margin-right: 80px;
}
</style>
<div class="dropdown">
            <button type="button" class="btn rf-btn" style="padding-right: 20px">{{ Auth::guard('customer')->user()->username }}</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <a class="rf-dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a>
    <a class="rf-dropdown-item" href="{{ route('customer.logout') }}">Logout</a>
  </div>
</div>
@endif
<!-- @if (!Auth::guard('organizer')->check())
<div class="dropdown">
            <button type="button" class="btn rf-btn2">Organizer</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <a class="rf-dropdown-item" href="{{ route('organizer.login') }}">Login</a>
    <a class="rf-dropdown-item" href="{{ route('organizer.signup') }}">Signup</a>
  </div>
</div>
@else
<div class="dropdown">
            <button type="button" class="btn rf-btn2">{{ Auth::guard('organizer')->user()->username }}</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <a class="rf-dropdown-item" href="{{ route('organizer.dashboard') }}">Dashboard</a>
    <a class="rf-dropdown-item" href="{{ route('organizer.logout') }}">Logout</a>
  </div>
</div>
@endif -->
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
        
    </div>
    <nav class="navbar rf-navigation clearfix">
    <!-- Loop through links -->
    @foreach ($links as $link)
        <!-- Check if link has children -->
        @if (!array_key_exists('children', $link))
        @if (in_array($link['text'], ['Home','بيت','Events','الأحداث',]))
            <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li>
        @endif
        
        <!-- @if (in_array($link['text'], ['Blog', 'Contact']))
            <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li>
        @endif -->
            @else
            <!-- <li class="dropdown">
                <a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a>
                <div class="dropdown-content">
                    @foreach ($link['children'] as $level2)
             
                        <a href="{{ get_href($level2, $currentLanguageInfo->id) }}">{{ $level2['text'] }}</a>
                 
                        @endforeach
                </div>
            </li> -->
        @endif
    @endforeach
    <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
    @foreach ($links as $link)
        <!-- Check if link has children -->
        @if (!array_key_exists('children', $link))
        @if (in_array($link['text'], ['Blog','مدونة', 'Contact','اتصال']))
            <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li>
        @endif
        
        <!-- @if (in_array($link['text'], ['Blog', 'Contact']))
            <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li>
        @endif -->
            @else
            <!-- <li class="dropdown">
                <a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a>
                <div class="dropdown-content">
                    @foreach ($link['children'] as $level2)
             
                        <a href="{{ get_href($level2, $currentLanguageInfo->id) }}">{{ $level2['text'] }}</a>
                 
                        @endforeach
                </div>
            </li> -->
        @endif
    @endforeach
</nav>
</header>

<header class="responsive-header rf-header">
        <!-- Upper Header -->
        <div class="upper-header">
            Deals Of The Week: BIG Island Hopping & Sailing Deals! <strong>Up To 25% Off</strong>
        </div>

        <!-- Lower Header -->
        <div class="lower-header">
        <img src="{{ asset('assets/admin/img/footer_logo/footer.png')}}" alt="Logo" class="logo">
            <i class="fa-solid fa-bars menu-icon" style="color: #f36b21"></i>
        </div>
        <!-- Sidebar -->
<div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    
    @foreach ($links as $link)
        <!-- Check if link has children -->
        @if (!array_key_exists('children', $link))
        @if (in_array($link['text'], ['Home','بيت','Events','الأحداث',]))
            <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li>
        @endif
        
        <!-- @if (in_array($link['text'], ['Blog', 'Contact']))
            <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li>
        @endif -->
            @else
            <!-- <li class="dropdown">
                <a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a>
                <div class="dropdown-content">
                    @foreach ($link['children'] as $level2)
             
                        <a href="{{ get_href($level2, $currentLanguageInfo->id) }}">{{ $level2['text'] }}</a>
                 
                        @endforeach
                </div>
            </li> -->
        @endif
    @endforeach
    <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
    @foreach ($links as $link)
        <!-- Check if link has children -->
        @if (!array_key_exists('children', $link))
        @if (in_array($link['text'], ['Blog','مدونة', 'Contact','اتصال']))
            <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li>
        @endif
        
        <!-- @if (in_array($link['text'], ['Blog', 'Contact']))
            <li><a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a></li>
        @endif -->
            @else
            <!-- <li class="dropdown">
                <a href="{{ get_href($link, $currentLanguageInfo->id) }}">{{ $link['text'] }}</a>
                <div class="dropdown-content">
                    @foreach ($link['children'] as $level2)
             
                        <a href="{{ get_href($level2, $currentLanguageInfo->id) }}">{{ $level2['text'] }}</a>
                 
                        @endforeach
                </div>
            </li> -->
        @endif
    @endforeach
    
</div>
    </header>
    <script>
  function updateLayout() {
  var mediaQuery = window.matchMedia('(max-width: 540px)');
  var header = document.querySelector('.rf-header');
  var largemediaQuery = window.matchMedia('(min-width: 540px)');

  if (mediaQuery.matches) {
    // For smaller screens
    header.classList.remove('responsive-header');
    
  } else {
    // For larger screens, revert to original classes if needed
    header.classList.add('responsive-header');
    
  }
}

window.addEventListener('resize', updateLayout);
window.addEventListener('DOMContentLoaded', updateLayout); // Ensures this runs after the DOM is fully loaded
updateLayout();
</script>
    <script>
  // Define openNav and closeNav in the global scope
  function openNav() {
    document.getElementById("mySidebar").style.width = "200px";
  }

  function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
  }

  document.addEventListener('DOMContentLoaded', function() {
  var menuIcon = document.querySelector('.menu-icon');
  if (menuIcon) {
    menuIcon.addEventListener('click', openNav);
  }
});
</script>


