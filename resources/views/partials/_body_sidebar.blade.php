@php
    $MyNavBar = \Menu::make('MenuList', function ($menu) {
        // Dashboard
        $menu->add(
            '<span>' . __('messages.dashboard') . '</span>',
            ['route' => 'home']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.5 15.5C13.5 13.6144 13.5 12.6716 14.0858 12.0858C14.6716 11.5 15.6144 11.5 17.5 11.5C19.3856 11.5 20.3284 11.5 20.9142 12.0858C21.5 12.6716 21.5 13.6144 21.5 15.5V17.5C21.5 19.3856 21.5 20.3284 20.9142 20.9142C20.3284 21.5 19.3856 21.5 17.5 21.5C15.6144 21.5 14.6716 21.5 14.0858 20.9142C13.5 20.3284 13.5 19.3856 13.5 17.5V15.5Z" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        ');

        // Bookings
        $menu->add(
            '<span>' . __('messages.bookings') . '</span>',
            ['route' => 'booking.index']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12Z" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        ')->data('permission', 'booking list');

        // Services Section
        $menu->add('<span>' . __('messages.service') . '</span>', ['class' => 'category-main'])
             ->data('permission', ['category list', 'subcategory list', 'service list']);

        // Categories
        $menu->add(
            '<span>' . __('messages.category') . '</span>',
            ['route' => 'category.index']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.70588 12.9268H9.11765C10.1979 12.9268 11.0735 13.8024 11.0735 14.8826V20.2944C11.0735 21.3746 10.1979 22.2503 9.11765 22.2503H3.70588C2.62568 22.2503 1.75 21.3746 1.75 20.2944V14.8826C1.75 13.8024 2.62568 12.9268 3.70588 12.9268Z" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        ')->data('permission', 'category list');

        // Services
        $menu->add(
            '<span>' . __('messages.services') . '</span>',
            ['route' => 'service.index']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.5 6.5C2.5 4.29086 4.29086 2.5 6.5 2.5C8.70914 2.5 10.5 4.29086 10.5 6.5V9.16667C10.5 9.47666 10.5 9.63165 10.4659 9.75882C10.3735 10.1039 10.1039 10.3735 9.75882 10.4659C9.63165 10.5 9.47666 10.5 9.16667 10.5H6.5C4.29086 10.5 2.5 8.70914 2.5 6.5Z" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        ')->data('permission', 'service list');

        // Users Section
        $menu->add('<span>' . __('messages.user') . '</span>', ['class' => 'category-main'])
             ->data('permission', ['provider list', 'handyman list', 'user list']);

        // Providers
        if (auth()->check() && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'demo_admin')) {
            $menu->add(
                '<span>' . __('messages.providers') . '</span>',
                ['route' => 'provider.index']
            )->prepend('
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"/>
                    <ellipse cx="12" cy="17" rx="7" ry="4" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            ')->data('permission', 'provider list');
        }

        // Handymen
        $menu->add(
            '<span>' . __('messages.handymen') . '</span>',
            ['route' => 'handyman.index']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="17" cy="18" r="4" stroke="currentColor" stroke-width="1.5"/>
                <path d="M17 16.667V19.3337" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15.6665 18L18.3332 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 20.8344C13.3663 20.9421 12.695 21 12 21C8.13401 21 5 19.2091 5 17C5 14.7909 8.13401 13 12 13C13.7135 13 15.2832 13.3518 16.5 13.9359" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        ')->data('permission', 'handyman list');

        // Customers
        $menu->add(
            '<span>' . __('messages.customers') . '</span>',
            ['route' => 'user.index']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="9" cy="6" r="4" stroke="currentColor" stroke-width="1.5"/>
                <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <ellipse cx="9" cy="17" rx="7" ry="4" stroke="currentColor" stroke-width="1.5"/>
                <path d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        ')->data('permission', 'user list');

        // Transactions Section
        $menu->add('<span>' . __('messages.transactions') . '</span>', ['class' => 'category-main'])
             ->data('permission', ['payment list', 'earning list']);

        // Payments
        $menu->add(
            '<span>' . __('messages.payments') . '</span>',
            ['route' => 'payment.index']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" stroke="currentColor" stroke-width="1.5"/>
                <path d="M10 16H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M14 16H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M2 10L22 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        ')->data('permission', 'payment list');

        // Earnings
        $menu->add(
            '<span>' . __('messages.earnings') . '</span>',
            ['route' => 'earning']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                <path d="M12 6V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        ')->data('permission', 'earning list');

        // System Section
        $menu->add('<span>' . __('messages.system') . '</span>', ['class' => 'category-main'])
             ->data('permission', ['terms condition', 'privacy policy']);

        // Settings
        $menu->add(
            '<span>' . __('messages.Settings') . '</span>',
            ['route' => 'setting.index']
        )->prepend('
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/>
                <path d="M13.7654 2.15224C13.3978 2 12.9319 2 12 2C11.0681 2 10.6022 2 10.2346 2.15224C9.74457 2.35523 9.35522 2.74458 9.15223 3.23463C9.05957 3.45834 9.0233 3.7185 9.00911 4.09799C8.98826 4.65568 8.70226 5.17189 8.21894 5.45093C7.73564 5.72996 7.14559 5.71954 6.65219 5.45876C6.31645 5.2813 6.07301 5.18262 5.83294 5.15102C5.30704 5.08178 4.77518 5.22429 4.35436 5.5472C4.03874 5.78938 3.80577 6.1929 3.33983 6.99993C2.87389 7.80697 2.64092 8.21048 2.58899 8.60491C2.51976 9.1308 2.66227 9.66266 2.98518 10.0835C3.13256 10.2756 3.3397 10.437 3.66119 10.639C4.1338 10.936 4.43789 11.4419 4.43786 12C4.43783 12.5581 4.13375 13.0639 3.66118 13.3608C3.33965 13.5629 3.13248 13.7244 2.98508 13.9165C2.66217 14.3373 2.51966 14.8691 2.5889 15.395C2.64082 15.7894 2.87379 16.193 3.33973 17C3.80568 17.807 4.03865 18.2106 4.35426 18.4527C4.77508 18.7756 5.30694 18.9181 5.83284 18.8489C6.07289 18.8173 6.31632 18.7186 6.65204 18.5412C7.14547 18.2804 7.73556 18.27 8.2189 18.549C8.70224 18.8281 8.98826 19.3443 9.00911 19.9021C9.02331 20.2815 9.05957 20.5417 9.15223 20.7654C9.35522 21.2554 9.74457 21.6448 10.2346 21.8478C10.6022 22 11.0681 22 12 22C12.9319 22 13.3978 22 13.7654 21.8478C14.2554 21.6448 14.6448 21.2554 14.8477 20.7654C14.9404 20.5417 14.9767 20.2815 14.9909 19.902C15.0117 19.3443 15.2977 18.8281 15.781 18.549C16.2643 18.2699 16.8544 18.2804 17.3479 18.5412C17.6836 18.7186 17.927 18.8172 18.167 18.8488C18.6929 18.9181 19.2248 18.7756 19.6456 18.4527C19.9612 18.2105 20.1942 17.807 20.6601 16.9999C21.1261 16.1929 21.3591 15.7894 21.411 15.395C21.4802 14.8691 21.3377 14.3372 21.0148 13.9164C20.8674 13.7243 20.6602 13.5628 20.3387 13.3608C19.8662 13.0639 19.5621 12.558 19.5621 11.9999C19.5621 11.4418 19.8662 10.9361 20.3387 10.6392C20.6603 10.4371 20.8675 10.2757 21.0149 10.0835C21.3378 9.66273 21.4803 9.13087 21.4111 8.60497C21.3592 8.21055 21.1262 7.80703 20.6602 7C20.1943 6.19297 19.9613 5.78945 19.6457 5.54727C19.2249 5.22436 18.693 5.08185 18.1671 5.15109C17.9271 5.18269 17.6837 5.28136 17.3479 5.4588C16.8545 5.71959 16.2644 5.73002 15.7811 5.45096C15.2977 5.17191 15.0117 4.65566 14.9909 4.09794C14.9767 3.71848 14.9404 3.45833 14.8477 3.23463C14.6448 2.74458 14.2554 2.35523 13.7654 2.15224Z" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        ')->data('role', ['admin', 'demo_admin']);

    })->filter(function ($item) {
        return checkMenuRoleAndPermission($item);
    });
@endphp

<div class="iq-sidebar sidebar-default">
    <div class="iq-sidebar-logo">
        <a href="{{ route('home') }}" class="header-logo">
            <img src="{{ getSingleMedia(imageSession('get'), 'logo', null) }}"
                class="img-fluid rounded-normal light-logo site_logo_preview d-none site-logo" alt="logo">
            <span class="white-space-no-wrap">{{ auth()->check() ? ucfirst(str_replace('_', ' ', auth()->user()->user_type)) : 'Visiteur' }}</span>
        </a>
        <div class="side-menu-bt-sidebar-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-light wrapper-menu" width="30" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>

    <div class="side-menu-bt-sidebar wide-device-toggle">
        <span class="iq-toggle-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon arrow-active wrapper-menu" height="14"
                width="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </span>
    </div>

    <div class="data-scrollbar" data-scroll="1">
        <div class="user-profile">
            <div class="user-img">
                <img class="avatar-50 rounded-circle bg-light" alt="user-icon"
                    src="{{ auth()->check() ? getSingleMedia(auth()->user(), 'profile_image', null) : '/images/default-user.png' }}">
            </div>
            <div class="user-info">
                <h5 class="user-email">{{ optional(auth()->user())->email ?? '--' }}</h5>
                <span class="user-name">
                    {{ optional(auth()->user())->first_name ?? '--' }}
                    {{ optional(auth()->user())->last_name ?? '--' }}
                </span>
            </div>
        </div>

        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="side-menu">
                @include(config('laravel-menu.views.bootstrap-items'), ['items' => $MyNavBar->roots()])
            </ul>
        </nav>
        <div class="pt-5 pb-5"></div>
    </div>
</div>
