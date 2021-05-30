<x-guest-layout>

    <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">

        <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md sm:max-w-md sm:rounded-lg">
            <x-jet-validation-errors class="mb-4" />
            <form method="POST" action="{{ route('web.xml-upload') }}" enctype="multipart/form-data">
                @csrf

                <div>
                    <x-jet-label for="people" value="{{ __('People') }}" />
                    <x-jet-input id="people" class="block w-full mt-1" type="file" name="people" required autofocus />
                </div>

                <div class="mt-4">
                    <x-jet-label for="shiporders" value="{{ __('Ship Orders') }}" />
                    <x-jet-input id="shiporders" class="block w-full mt-1" type="file" name="shiporders" required />
                </div>

                <div class="mt-4">
                    <x-jet-label for="async" value="{{ __('Process in the background') }}" />
                    <x-jet-input id="async" class="block mt-1" type="checkbox" name="async"/>
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
