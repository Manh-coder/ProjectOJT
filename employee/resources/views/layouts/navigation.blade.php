<!-- resources/views/layouts/navigation.blade.php -->
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if (auth()->user()->type == 1)
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                            {{ __('Employees') }}
                        </x-nav-link>
                        <x-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
                            {{ __('Departments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('user-attendance.index')" :active="request()->routeIs('user-attendance.*')">
                            {{ __('User Attendance') }}
                        </x-nav-link>
                        <x-nav-link :href="route('salary-levels.index')" :active="request()->routeIs('salary-levels.*')">
                            {{ __('Salary level') }}
                        </x-nav-link>
                        <x-nav-link :href="route('salary.index')" :active="request()->routeIs('salary.*')">
                            {{ __('Salary Management') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.email-schedule')" :active="request()->routeIs('admin.email-schedule*')">
                            {{ __('Time Controller') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.leave_requests.index')" :active="request()->routeIs('admin.leave_requests*')">
                            {{ __('Leave Controller') }}
                        </x-nav-link>

                        <x-nav-link :href="route('config-leave.index')" :active="request()->routeIs('config-leave*')">
                            {{ __('Config Leave') }}
                        </x-nav-link>

                        <x-nav-link :href="route('report.ratio_employees_departments')" :active="request()->routeIs('report.ratio_employees_departments*')">
                            {{ __('Report') }}
                        </x-nav-link>

                        
                    @endif

                    @if (auth()->user()->type == 2)
                        <x-nav-link :href="route('guest.notification_schedule')" :active="request()->routeIs('guest.')">
                            {{ __('Time Controller') }}
                        </x-nav-link>

                        <x-nav-link :href="route('leave_requests.index')" :active="request()->routeIs('leave_requests.')">
                            {{ __('Leave Requests') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                       this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
