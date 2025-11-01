<!-- نافبار رئيسية للتطبيق باستخدام Tailwind & Alpine.js -->
<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-100">
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between h-24">
            
            <!-- القسم الأيسر: الشعار وروابط التنقل -->
            <div class="flex">
                
                <!-- Logo (الشعار) -->
                <div class="ml-2 flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"
                       class="focus:outline-none focus:scale-110 transform transition hover:scale-110">
                        <x-application-logo
                            class="block h-20 w-auto fill-current text-gray-300 hover:text-white transition duration-150 ease-in-out" />
                    </a>
                </div>

                <!-- Navigation Links (روابط التنقل) -->
                <div class="hidden sm:-my-px sm:ml-10 sm:flex">
                    <!-- الرابط الخاص بالمستخدمين -->
                    @can('user-list')
                        <x-nav-link :href="route('users.index')" 
                                    :active="str_contains(Route::currentRouteName(), 'users')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endcan

                    <!-- الرابط الخاص بالأدوار -->
                    @can('role-list')
                        <x-nav-link :href="route('roles.index')" 
                                    :active="str_contains(Route::currentRouteName(), 'roles')">
                            {{ __('Roles') }}
                        </x-nav-link>
                    @endcan

                    <!-- الرابط الخاص بالمجموعات -->
                    @can('group-list')
                        <x-nav-link :href="route('groups.index')" 
                                    :active="str_contains(Route::currentRouteName(), 'groups')">
                            {{ __('Groups') }}
                        </x-nav-link>
                    @endcan

                    <!-- الرابط الخاص بالمشاريع -->
                    @can('project-list')
                        <x-nav-link :href="route('projects.index')" 
                                    :active="str_contains(Route::currentRouteName(), 'projects')">
                            {{ __('Projects') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- القسم الأيمن: الإشعارات وقائمة المستخدم -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Notifications Dropdown -->
                <x-notification-dropdown />

                <!-- User Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <!-- Trigger (زر إظهار القائمة) -->
                    <x-slot name="trigger">
                        <button class="group flex items-center text-sm font-medium text-gray-300 hover:text-white hover:border-gray-300 focus:outline-none focus:text-white focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-2 group-hover:pl-4 transition-padding border-gray-400">
                                <img class="h-10 w-10 transform transition ease-in-out duration-150 group-hover:scale-125 rounded-full"
                                     src="/uploads/avatars/{{ Auth::user()->avatar }}" alt="profile">
                            </div>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content (خيارات القائمة) -->
                    <x-slot name="content">
                        <!-- رابط الملف الشخصي -->
                        <x-dropdown-link class="border-b pb-3 border-gray-200" :href="route('profile.show')">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </x-dropdown-link>

                        <!-- رابط مجموعتي -->
                        @if(Auth::user()->group)
                            <x-dropdown-link :href="route('groups.show',Auth::user()->group)">
                                {{ __('My Group') }}
                            </x-dropdown-link>

                            <!-- رابط مشروعي إذا كان موجود -->
                            @if (Auth::user()->group->project_id)
                                <x-dropdown-link class="border-b border-gray-200"
                                                 :href="route('projects.show',Auth::user()->group->project_id)">
                                    {{ __('My Project') }}
                                </x-dropdown-link>
                            @endif
                        @endif

                        <!-- زر تسجيل الخروج -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link class="pt-3" :href="route('logout')" 
                                             onclick="event.preventDefault();
                                             this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- القائمة للعرض على الشاشات الصغيرة -->
            <div class="mr-2 flex items-center sm:hidden">
                <!-- Notifications Dropdown -->
                <x-notification-dropdown />

                <!-- Hamburger button (زر القائمة المنسدلة) -->
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-200 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <!-- خطوط الهامبرغر -->
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <!-- أيقونة إغلاق -->
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @can('user-list')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
            @endcan
            @can('role-list')
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                    {{ __('Roles') }}
                </x-responsive-nav-link>
            @endcan
            @can('group-list')
                <x-responsive-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.index')">
                    {{ __('Groups') }}
                </x-responsive-nav-link>
            @endcan
            @can('project-list')
                <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')">
                    {{ __('Projects') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <!-- بيانات المستخدم -->
                <a href="route('profile.show')">
                    <div class="font-medium text-base text-gray-200">{{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </a>
            </div>

            <div class="mt-3 space-y-1">
                <!-- زر تسجيل الخروج -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
