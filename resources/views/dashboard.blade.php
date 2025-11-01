<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                  <!-- Main Content -->
                       <div class="flex-1 p-8 bg-gray-100">
                                     <x-flash-message />

                                 <div class="bg-white shadow rounded-lg p-6">
                                     <h3 class="text-xl font-semibold mb-4">Welcome to Dashboard Motatawera</h3>
                
                                 </div>
                                 
         
                        </div>
                          <img  src="D:\SPMS-main\SPMS-main\Logo.png"/>
            </div>
        </div>
    </div>
</x-app-layout>