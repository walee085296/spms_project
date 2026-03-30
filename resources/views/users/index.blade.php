<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
        <a href="{{ route('users.create') }}">
            <x-button type="button" class="text-xs">
                {{ __('Create New User') }}
            </x-button>
        </a>
    </x-slot>
    <x-slot name="filters">
        <span>Filters:</span>
        <x-dropdown name="type" id="type">
            <x-slot name="trigger">
                <button
                    class="w-[95vw] sm:w-auto text-left rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm text-gray-600 p-4 bg-white border">
                    {{ isset(request()->spec) ? ucwords(request()->spec) : 'Select Specialization' }}
                    <i class="fa fa-angle-down ml-2"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                @foreach($specs as $spec)
                @if(!(request()->spec === $spec->name))
                <x-dropdown-link class="capitalize"
                    href="/users?spec={{ $spec->value }}&{{ http_build_query(request()->except('spec', 'page')) }}">{{
                    $spec->value }}
                </x-dropdown-link>
                @endif
                @endforeach
            </x-slot>
        </x-dropdown>
        <div class="relative mt-2 md:mt-0">
            <x-search>
                @if (request('spec'))
                <input type="hidden" name="spec" value="{{ request('spec') }}">
                @endif
                @if (request('roles'))
                <input type="hidden" name="roles" value="{{ request('roles') }}">
                @endif
            </x-search>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto scrollbar-none sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <x-flash-message />
                    <div class="shadow-lg overflow-hidden border border-gray-300 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bald text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bald text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bald text-gray-500 uppercase tracking-wider">
                                        Specialization
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bald text-gray-500 uppercase tracking-wider">
                                        Roles
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>

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
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <a href="{{ route('users.show',$user->id) }}">
                                                        <img class="h-10 w-10 rounded-full border border-gray-300"
                                                            src="/uploads/avatars/{{ $user->avatar }}" alt="profile">
                                                    </a>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('users.show',$user->id) }}">{{
                                                            $user->name }}</a>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <a href="{{ route('users.show',$user->id) }}">{{
                                                            $user->email }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <div class="">
                                                <div class="text-sm font-medium text-gray-900">
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $user->stdsn ?? 'NA' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="capitalize px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->spec->value }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        @foreach($user->getRoleNames() as $v)
                                        <label class=" px-2 py-0.5 bg-gray-100 rounded-full border border-gray-300">{{
                                            $v }}</label>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('users.edit',$user->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        No Results found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="py-8">
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>