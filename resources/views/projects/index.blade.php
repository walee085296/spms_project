<x-app-layout>
    <!-- Layout الرئيسي للتطبيق -->

    <x-slot name="header">
        <!-- رأس الصفحة -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }} <!-- عنوان الصفحة -->
        </h2>

        <!-- زر إنشاء مشروع جديد يظهر فقط إذا كان للمستخدم صلاحية create -->
        @can('create', App\Models\Project::class)
        <a href="{{ route('projects.create') }}">
            <x-button class="text-xs" type="button">
                {{ __('Create New project') }}
            </x-button>
        </a>
        @endcan
    </x-slot>

    <!-- قسم الفلاتر -->
    <x-slot name="filters">
        <div class="space-y-2 md:space-y-0 transition-padding" x-data="{ more: false }">
            <!-- الفلاتر الأساسية (Type, Spec, State, Search) -->
            <div class="flex flex-col md:flex-row justify-center items-center space-y-2 md:space-y-0 sm:space-x-4">
                <span>Filters:</span>

                <!-- Dropdown Type -->
                <x-dropdown name="type" id="type">
                    <x-slot name="trigger">
                        <button class="w-[95vw] md:w-auto ...">
                            {{ isset(request()->type) ? ucwords(request()->type) : 'Select Type' }}
                            <i class="fa fa-angle-down ml-2"></i>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @foreach($types as $type)
                        @if(!(request()->type === $type->name))
                        <x-dropdown-link class="capitalize"
                            href="/projects?type={{ $type->value }}&{{ http_build_query(request()->except('type', 'page')) }}">
                            {{ $type->value }}
                        </x-dropdown-link>
                        @endif
                        @endforeach
                    </x-slot>
                </x-dropdown>

                <!-- Dropdown Specialization -->
                <x-dropdown name="spec" id="spec">
                    <x-slot name="trigger">
                        <button class="w-[95vw] md:w-auto ...">
                            {{ isset(request()->spec) ? ucwords(request()->spec) : 'Select Specialization' }}
                            <i class="fa fa-angle-down ml-2"></i>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @foreach($specs as $spec)
                        @if(!(request()->spec === $spec->name))
                        <x-dropdown-link class="capitalize"
                            href="/projects?spec={{ $spec->value }}&{{ http_build_query(request()->except('spec', 'page')) }}">
                            {{ $spec->value }}
                        </x-dropdown-link>
                        @endif
                        @endforeach
                    </x-slot>
                </x-dropdown>

                <!-- Dropdown State -->
                <x-dropdown name="state" id="state">
                    <x-slot name="trigger">
                        <button class="w-[95vw] md:w-auto ...">
                            {{ isset(request()->state) ? ucwords(request()->state) : 'Select State' }}
                            <i class="fa fa-angle-down ml-2"></i>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @foreach($states as $state)
                        @if(!(request()->state === $state->name))
                        <x-dropdown-link class="capitalize"
                            href="/projects?state={{ $state->value }}&{{ http_build_query(request()->except('state', 'page')) }}">
                            {{ $state->value }}
                        </x-dropdown-link>
                        @endif
                        @endforeach
                    </x-slot>
                </x-dropdown>

                <!-- حقل البحث -->
                <div class="relative mt-2 md:mt-0 flex">
                    <x-search>
                        <!-- الاحتفاظ بقيم الفلاتر في البحث -->
                        @if (request('spec')) <input type="hidden" name="spec" value="{{ request('spec') }}"> @endif
                        @if (request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                        @if (request('state')) <input type="hidden" name="state" value="{{ request('state') }}"> @endif
                    </x-search>
                </div>

                <!-- زر لإظهار المزيد من الفلاتر -->
                <div class="flex items-center">
                    <span @click="more = ! more" class="cursor-pointer">
                        <i class="fa fa-arrow-down transform transition" :class="{'rotate-180': more}" title="More filters"></i>
                    </span>
                </div>
            </div>

            <!-- الفلاتر الإضافية (Date range) -->
            <form id="search" action="{{ route('projects.index') }}" method="GET" role="search">
                <div x-cloak x-show="more" class="sm:flex flex-wrap justify-center items-center sm:space-x-2 ...">
                    <!-- Created at date range -->
                    <div date-rangepicker class="sm:flex space-y-2 md:space-y-0 justify-center items-center">
                        <span class="flex justify-center">Created at:</span>
                        <div class="flex justify-center items-center space-x-2 mx-2.5">
                            <x-input id="created_from" name="created_from" datepicker type="text" placeholder="From" value="{{ request('created_from') }}" />
                            <div class="relative"><i class="fa fa-arrow-right"></i></div>
                            <x-input id="created_to" name="created_to" datepicker type="text" placeholder="To" value="{{ request('created_to') }}" />
                        </div>
                    </div>

                    <!-- Updated at date range -->
                    <div date-rangepicker class="sm:flex space-y-2 md:space-y-0 justify-center items-center">
                        <span class="flex justify-center">Updated at:</span>
                        <div class="flex justify-center items-center space-x-2 mx-2.5">
                            <x-input id="updated_from" name="updated_from" datepicker type="text" placeholder="From" value="{{ request('updated_from') }}" />
                            <div class="relative"><i class="fa fa-arrow-right"></i></div>
                            <x-input id="updated_to" name="updated_to" datepicker type="text" placeholder="To" value="{{ request('updated_to') }}" />
                        </div>
                    </div>

                    <!-- احتفاظ بقيم الفلاتر المخفية -->
                    @if (request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                    <!-- أزرار تطبيق الفلاتر وإعادة التعيين -->
                    <div class="flex justify-center items-center space-x-2 ">
                        <x-button class="py-4 text-xs">Apply</x-button>
                        <a class="inline-flex items-center px-4 py-4 bg-gray-800 text-white ..." href="{{ route('projects.index') }}">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </x-slot>

    <!-- جدول عرض المشاريع -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto ...">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <x-flash-message /> <!-- رسائل نجاح / أخطاء -->

                    <div class="shadow-lg overflow-hidden border border-gray-300 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <!-- رؤوس الأعمدة -->
                                    <th>Project's Title</th>
                                    <th>Project's type</th>
                                    <th>Specialization</th>
                                    <th>Project's Supervisor</th>
                                    <th>Project's Group</th>
                                    <th>State</th>
                                    <th>Last Updated</th>
                                    <th class="text-right">
                                        <!-- زر تصدير يظهر إذا كان للمستخدم صلاحية export -->
                                        @can('export', App\Models\Project::class)
                                        <form action="{{ route('projects.export') }}" method="GET">
                                            <!-- الاحتفاظ بالفلاتر عند التصدير -->
                                            @foreach(['spec','type','state','search','created_from','created_to','updated_from','updated_to'] as $field)
                                                @if(request($field)) <input type="hidden" name="{{ $field }}" value="{{ request($field) }}"> @endif
                                            @endforeach
                                            <button class="hover:text-indigo-700" title="Export to excel file">
                                                <i class="fas fa-file-export"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($projects as $project)
                                <tr class="border-b border-gray-200 align-text-top">
                                    <!-- عنوان المشروع -->
                                    <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a></td>
                                    <!-- نوع المشروع -->
                                    <td>{{ $project->type->value }}</td>
                                    <!-- التخصص -->
                                    <td>{{ $project->spec->value }}</td>
                                    <!-- المشرف -->
                                    <td>
                                        @if($project->supervisor_id)
                                        <a href="{{ route('users.show',$project->supervisor_id)}}">{{ $project->supervisor->first_name }} {{ $project->supervisor->last_name }}</a>
                                        @else No supervisor yet @endif
                                    </td>
                                    <!-- أعضاء المجموعة -->
                                    <td>
                                        @forelse($project->group->developers as $user)
                                            <a href="{{ route('users.show',$user->id)}}">{{ $user->name }}</a>
                                        @empty No assigned group yet @endforelse
                                    </td>
                                    <!-- الحالة -->
                                    <td>{{ $project->state->value }}</td>
                                    <!-- آخر تعديل -->
                                    <td>{{ $project->updated_at->diffforhumans() }}</td>
                                    <!-- زر تعديل يظهر إذا كان للمستخدم صلاحية edit -->
                                    <td class="text-right">
                                        @can('edit',$project)
                                        <a href="{{ route('projects.edit',$project->id) }}">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">No Results found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="py-8">
                    @if ($projects->hasMorePages())
                        {!! $projects->links() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
