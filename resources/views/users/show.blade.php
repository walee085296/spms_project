<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Profile') }}
        </h2>
        @can('user-edit')
        <a href="{{ route('users.edit',$user->id) }}" class="flex justify-end">
            <x-button type="button">
                {{ __('Edit User') }}
            </x-button>
        </a>
        @endcan
    </x-slot>
    <div class="py-12 flex flex-col md:flex-row container justify-center gap-6">
        <div class="max-w-7xl">
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                <div class="p-6 bg-white border-b border-gray-200">
                    <img src="/uploads/avatars/{{ $user->avatar }}"
                        class="w-full md:h-96 md:w-96 rounded-full border-2 border-gray-300">
                    <div class="flex flex-col md:flex-row container justify-between border-b border-gray-300 py-4">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-2 flex items-center">
                            {{ $user->name }}
                        </h2>
                        <div class="text-xs text-gray-500 mt-3 md:ml-4">
                            <div class="container flex flex-row justify-between md:w-36">
                                <div>
                                    Last login:
                                </div>
                                <div>
                                    @if($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                    never
                                    @endif
                                </div>
                            </div>
                            <div class="container flex flex-row justify-between md:w-36">
                                <div>
                                    From IP:
                                </div>
                                <div>
                                    {{ $user->last_login_ip ?? '-'}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 grid-rows-1 border-b border-gray-300 w-full py-4 items-center">
                        <div class="text-xs text-gray-800">
                            {{ $user->email }}
                        </div>
                        <div class="text-xs text-gray-800 flex justify-end">
                            {{ $user->stdsn }}
                        </div>
                    </div>
                    <div class="grid grid-cols-2 grid-rows-1 border-gray-500 w-full">
                        <div class="text-xs text-gray-800 mt-3">
                            Github
                        </div>
                        <div class="text-xs mt-3 flex justify-end">
                            @if (!$git)
                            <span class="text-gray-800">
                                none
                            </span>
                            @else
                            <a class="text-blue-600" href="https://github.com/{{ $git->nickname }}" target="_blank">
                                <span>
                                    {{ $git->nickname }}
                                </span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col items-center justify-start">
            <div class="max-w-7xl w-full md:w-96">
                <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-2 flex items-center">
                            {{ __('Groups') }}
                        </h2>
                        <div class="flex flex-row justify-between py-4 border-b border-gray-300">
                            <div class="text-sm text-gray-800">
                                Current group
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($user->group)
                                <a class="text-indigo-500 hover:text-indigo-700" href="{{ route('groups.show',$user->group) }}">#{{ $user->group->id }}</a>
                                @else
                                None yet
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl w-full md:w-96 mt-4">
                <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-2 flex items-center">
                            {{ __('Projects') }}
                        </h2>
                        <div class="flex flex-row justify-between py-4 border-b border-gray-300">
                            <div class="text-sm text-gray-800">
                                Current project
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($user->group)
                                @if ($user->project)
                                <a class="text-indigo-500 hover:text-indigo-700" href="{{ route('projects.show',$user->project) }}">
                                    {{ $user->project->title }}
                                </a>
                                @else
                                None yet
                                @endif
                                @else
                                None yet
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl w-full md:w-96 mt-4">
                <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-2 flex items-center">
                            {{ __('Roles') }}
                        </h2>
                        <div class="flex flex-row justify-between py-4 border-b border-gray-300">
                            <div class="text-sm text-gray-800">
                                Roles
                            </div>
                            <div class="text-sm items-end text-gray-500">
                                @forelse($user->getRoleNames() as $roles)
                                {{ $roles }}
                                @empty
                                None yet
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-button>wertyu</x-button>
</x-app-layout>