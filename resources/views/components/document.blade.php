@props(['document'])
<div x-data="{ open: false }" {{ $attributes }}>
    <div class="flex justify-between hover:bg-gray-200 py-2 px-2">
        <x-label class="flex justify-start items-start text-sm">
            <div class="inline-flex items-center">
                <svg class="ml-5 h-5 w-5 fill-current text-gray-600" stroke="currentColor"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                    <path
                        d="M78.8 25.5L56.7 3.2c-.8-.8-1.8-1.2-2.9-1.2H16c-2.2 0-4 1.8-4 4v80c0 2.2 1.8 4 4 4h60c2.2 0 4-1.8 4-4V28.3c0-1.1-.4-2.1-1.2-2.8zM72 30H52V10h.2L72 30zM20 82V10h24v23.9c0 2.2 1.7 4.1 3.9 4.1H72v44H20zm38.5-23.5c0 1.9-1.6 3.5-3.5 3.5H37c-1.9 0-3.5-1.6-3.5-3.5S35 55 37 55h18c2 0 3.5 1.6 3.5 3.5z" />
                </svg>
            </div>
            <span class="ml-1 text-gray-600">{{ $document->name }}.{{ $document->getExtensionAttribute() }}</span>
        </x-label>
        <div class="flex flex-row " x-data="{ requestMenu:false } " @click.away=" requestMenu=false ">
            <a href="{{ route('media.download',$document) }}"><svg
                    class="mr-1 h-4 w-4 fill-current text-opacity-75 cursor-pointer opacity-20 text-gray-700 hover:opacity-100 transition ease-in-out duration-150"
                    stroke="currentColor" xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                    <path
                        d="M89 59v27c0 3-2 5-5 5H8c-3 0-5-2-5-5V59c0-3 2-5 5-5s5 2 5 5v22h66V59c0-3 2-5 5-5s5 2 5 5zm-47 6l4 2 4-2 20-20c1-2 1-5-1-7s-5-2-7 0L51 49V6c0-3-2-5-5-5s-5 2-5 5v43L30 38c-2-2-5-2-7 0s-2 5-1 7l20 20z" />
                </svg>
            </a>
            <div @keydown.escape="requestMenu = false">
                <a href="#" @click.prevent=" requestMenu = !requestMenu">
                    <svg class="mr-1.5 h-5 w-5 fill-current text-opacity-75 cursor-pointer opacity-20 text-gray-700 hover:opacity-100 focus:opacity-100 transition ease-in-out duration-150"
                        stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                        <path
                            d="M21 53a6.97 6.97 0 01-7-7c0-1.8.8-3.6 2-5 1.3-1.3 3.1-2 5-2 1.8 0 3.6.8 4.9 2 1.3 1.3 2.1 3.1 2.1 5 0 1.8-.8 3.6-2.1 4.9A7.07 7.07 0 0121 53zm29.9-2.1c1.3-1.3 2.1-3.1 2.1-4.9 0-1.8-.8-3.6-2.1-5-1.3-1.3-3.1-2-4.9-2-1.8 0-3.7.8-5 2-1.3 1.3-2 3.1-2 5 0 1.8.8 3.6 2 4.9 1.3 1.3 3.1 2.1 5 2.1 1.8 0 3.6-.8 4.9-2.1zm25 0c1.3-1.3 2.1-3.1 2.1-4.9 0-1.8-.8-3.6-2.1-5-1.3-1.3-3.1-2-4.9-2-1.8 0-3.7.8-5 2-1.3 1.3-2 3.1-2 5 0 1.8.8 3.6 2 4.9 1.3 1.3 3.1 2.1 5 2.1 1.8 0 3.6-.8 4.9-2.1z" />
                    </svg>
                </a>
                <div x-show="requestMenu"
                    class="absolute z-50 mt-2 bg-white rounded-lg shadow-lg w-32 overflow-hidden text-xs ring-1 ring-black ring-opacity-5">
                    <a href="#"
                        class="block px-4 py-2 text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                        Update
                    </a>
                    <a href="{{ route('media.rename',$document) }}"
                        class="block px-4 py-2 text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                        Rename
                    </a>
                    <form method="POST" action="{{ route('media.destroy',$document->id) }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit"
                            class="flex w-full px-4 py-2 text-red-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                            Delete
                            </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>