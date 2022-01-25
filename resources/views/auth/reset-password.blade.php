<style type="text/css">
    .parsley-required{color: red; }
    .parsley-equalto{color: red; }
</style>
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img src="{{asset('images/logo.png')}}" alt="" height="70">
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />
                <b>{{$request->email}}</b>
                <x-input id="email" class="block mt-1 w-full" type="hidden" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required id="password"/>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required data-parsley-equalto="#password"  data-parsley-equalto-message="Password & confirm password should be the same"  />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button style="background-color: #f58d20;border-color: #f58d20;">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.js" integrity="sha512-Fq/wHuMI7AraoOK+juE5oYILKvSPe6GC5ZWZnvpOO/ZPdtyA29n+a5kVLP4XaLyDy9D1IBPYzdFycO33Ijd0Pg==" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('form').parsley();
});
</script>
