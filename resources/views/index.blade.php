<x-guest-layout>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <x-jet-validation-errors class="mb-4" />
            <form method="POST" action="{{ route('web.xml-upload') }}" enctype="multipart/form-data">
                @csrf

                <div>
                    <x-jet-label for="persons" value="{{ __('Persons') }}" />
                    <x-jet-input id="persons" class="block mt-1 w-full" type="file" name="persons" required autofocus />
                </div>

                <div class="mt-4">
                    <x-jet-label for="shiporders" value="{{ __('Ship Orders') }}" />
                    <x-jet-input id="shiporders" class="block mt-1 w-full" type="file" name="shiporders" required />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-jet-button class="ml-4">
                        {{ __('Submit') }}
                    </x-jet-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
