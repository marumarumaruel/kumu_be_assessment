<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 px-6">
        <div class='p-6 text-gray-900 dark:text-gray-100' id='result'></div>
        <!-- <form method="" action="{{ route('github.getusers') }}" class="mt-6 space-y-6"> -->
        <form method="" action="" class="mt-6 space-y-6">
            @csrf
            <!-- @method('post') -->

            <div>
                <x-input-label for="name" :value="__('GitHub Usersame')" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
                <x-text-input name="github_username[]" type="text" class="mt-1 block w-full" />
            </div>

            <div class="flex items-center gap-4">
                <x-secondary-button>{{ __('Get Usernames') }}</x-secondary-button>
            </div>
        </form>

        <script>
            $("button").click(function(){
                var data = $('input[name="github_username[]"]').map(
                    function(){
                        return $(this).val();
                    }
                ).get();
                console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('[name="_token"]').val()
                    }
                });
                $.ajax({
                    url: "{{ route('github.getusers') }}",
                    type: "POST",
                    dataType: 'json',
                    data: JSON.stringify(data),
                    processData: false,
                    contentType: 'application/json',
                    CrossDomain:true,
                    async: false,
                    success: function (data) {
                        console.log(data);
                        $("#result").empty();
                        $.each(data, function(index, value){
                            $.each(value, function(i, v){
                                $("#result").append(i + ": " + v + '<br>');
                            });
                            $("#result").append('<br>');
                        });
                    }
                });
            });
        </script>
    </div>
    
</x-app-layout>
