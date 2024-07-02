<aside class="sidebar-wrapper">
   <div class="sidebar-top">
      <a class="navbar-brand wow fadeInLeft" data-wow-duration=".5s" href="{{ route('admin.home') }}">
         <?php $logo = \App\Models\WebSetting::pluck('logo')->first(); ?>
         @empty($logo)
            <img class="logo" src="{{ asset('assets/images/logo.png') }}" alt="logo" loading="lazy" style="width: 110px; height: 65px"/>
         @else
            <img class="logo" src="{{ asset('/assets/images/') }}/{{ ($logo) }}" alt="logo" loading="lazy" style="width: 110px; height: 65px"/>
         @endif
      </a>
   </div>

   <div class="sidebar-nav">
      <div class="sidebar-menu">
         <ul class="section-list school-management">
            <li class="section-title"><span>School Management</span></li>
            <li class="{{ strpos(Route::currentRouteName(), 'admin.risk_manage') === 0 ? 'active' : '' }}">
               <a href="{{ route('admin.risk_manage') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Risk Management View">
                  <img src="{{ asset('assets/images/menu/risk_manage_fill.svg') }}" alt="Risk Management View" />
                  <span class="menu-text">Risk Management View</span>
               </a>
            </li>
            <li class="{{ strpos(Route::currentRouteName(), 'user.list') === 0 ? 'active' : '' }}">
               <a href="{{ route('user.list') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Manage Traders">
                  <img src="{{ asset('assets/images/menu/traders.svg') }}" alt="User Lists" />
                  <span class="menu-text">Manage Traders</span>
               </a>
            </li>
            <li class="{{ strpos(Route::currentRouteName(), 'account.list') === 0 ? 'active' : '' }}">
               <a style="color:white" href="{{ route('account.list') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Challenges Purchased">
                  <img src="{{ asset('assets/images/menu/challenges_purchased.svg') }}" alt="Challenges Purchased" />
                  <span class="menu-text">Challenges Purchased</span>
               </a>
            </li>
            <li class="{{ strpos(Route::currentRouteName(), 'marketDataPurchaseList') === 0 ? 'active' : '' }}">
               <a style="color:white" href="{{ route('marketDataPurchaseList') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Market Data Upgrades">
                  <img src="{{ asset('assets/images/menu/data_upgrades.svg') }}" alt="Market Data Upgrades" />
                  <span class="menu-text">Market Data Upgrades</span>
               </a>
            </li>
            <li class="{{ strpos(Route::currentRouteName(), 'admin.historical_challenges') === 0 ? 'active' : '' }}">
               <a href="{{ route('admin.historical_challenges') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Historical Challenges">
                  <img src="{{ asset('assets/images/menu/historical_challenge.svg') }}" alt="Historical Challenges" />
                  <span class="menu-text">Historical Challenges</span>
               </a>
            </li>
            <li class="{{ strpos(Route::currentRouteName(), 'notification.create') === 0 ? 'active' : '' }}">
               <a href="{{ route('notification.create') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Send Notifications">
                  <img src="{{ asset('assets/images/menu/notification_bell.svg') }}" alt="Send Notifications" />
                  <span class="menu-text">Send Notifications</span>
               </a>
            </li>
         </ul>

         {{-- School Setup --}}
         <ul class="section-list school-setup">
            <li class="section-title"><span>School Setup</span></li>
            <li class="{{ strpos(Route::currentRouteName(), 'admin.challenges') === 0 ? 'active' : '' }}">
               <a href="{{ route('admin.challenges') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Challenge Design">
                  <img src="{{ asset('assets/images/menu/challenge.svg') }}" alt="Challenge Design" />
                  <span class="menu-text">Challenge Design</span>
               </a>
            </li>
            <li class="{{ strpos(Route::currentRouteName(), 'market-data-feed.index') === 0 ? 'active' : '' }}">
               <a href="{{ route('market-data-feed.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Market Data Feeds">
                  <img src="{{ asset('assets/images/menu/market_data_feeds.svg') }}" alt="Market Data Feeds" />
                  <span class="menu-text">Market Data Feeds</span>
               </a>
            </li>

            <li class="{{ strpos(Route::currentRouteName(), 'rules.index') === 0 ? 'active' : '' }}">
               <a href="{{ route('rules.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Terms & Conditionst">
                  <img src="{{ asset('assets/images/menu/terms.svg') }}" alt="Terms & Conditions" />
                  <span class="menu-text">Terms & Conditions</span>
               </a>
            </li>

            <li class="{{ strpos(Route::currentRouteName(), 'faq.index') === 0 ? 'active' : '' }}">
               <a href="{{ route('faq.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Fequently Asked Questions">
                  <img src="{{ asset('assets/images/menu/faq.svg') }}" alt="Fequently Asked Questions" />
                  <span class="menu-text">Frequently Asked Questions</span>
               </a>
            </li>

            <li class="{{ strpos(Route::currentRouteName(), 'admin.staff') === 0 ? 'active' : '' }}">
               <a href="{{ route('admin.staff') }}" data-bs-toggle="tooltip" data-bs-placement="right"data-bs-title="Staff Members">
                  <img src="{{ asset('assets/images/menu/staff.svg') }}" alt="Staff Members" />
                  <span class="menu-text">Staff Members</span>
               </a>
            </li>
         </ul>

         {{-- Billing & Payments --}}
         <ul class="section-list billing-payment">
            <li class="section-title"><span>Billing & Payment</span></li>
            <li class="{{ strpos(Route::currentRouteName(), 'admin.payment') === 0 ? 'active' : '' }}">
               <a href="{{ route('admin.payment') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Platform Fees">
                  <img src="{{ asset('assets/images/menu/fees.svg') }}" alt="Platform Fees" />
                  <span class="menu-text">Platform Fees</span>
               </a>
            </li>
            <li class="{{ strpos(Route::currentRouteName(), 'platform.payment.index') === 0 ? 'active' : '' }}">
               <a href="{{ route('platform.payment.index') }}" data-bs-toggle="tooltip" data-bs-placement="right"
                  data-bs-title="Revenue Management">
                  <img src="{{ asset('assets/images/menu/revenue.svg') }}" alt="Revenue Management" />
                  <span class="menu-text">Revenue Management</span>
               </a>
            </li>
            <li class="{{ strpos(Route::currentRouteName(), 'coupon-code.index') === 0 ? 'active' : '' }}">
               <a href="{{ route('coupon-code.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Coupons & Promotions">
                  <img src="{{ asset('assets/images/menu/coupon.svg') }}" alt="Coupons & Promotions" />
                  <span class="menu-text">Coupons & Promotions</span>
               </a>
            </li>
         </ul>

         {{-- Marketing --}}
         <ul class="section-list marketing">
            <li class="section-title"><span>Marketing</span></li>
            <li class="{{ strpos(Route::currentRouteName(), 'blog.index') === 0 ? 'active' : '' }}">
               <a href="{{ route('blog.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Blog Articles">
                  <img src="{{ asset('assets/images/menu/blogs.svg') }}" alt="Blog Articles" />
                  <span class="menu-text">Blog Articles</span>
               </a>
            </li>
            <li class="{{ (strpos(Route::currentRouteName(), 'web.setting.create')) || (strpos(Route::currentRouteName(), 'web.setting.edit')) === 0 ? 'active' : '' }}">
               <a href="{{ route('web.setting.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Home Page Setup">
                  <img src="{{ asset('assets/images/menu/settings.svg') }}" alt="Home Page Setup" />
                  <span class="menu-text">Home Page Setup</span>
               </a>
            </li>
         </ul>

      </div>
   </div>
</aside>