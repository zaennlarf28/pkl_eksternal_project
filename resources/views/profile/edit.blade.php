<x-app-layout>
    <div class="pt-4 pb-10">

     {{-- Title Page --}}
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Profil Pengguna</h1>
            <p class="text-gray-500 mt-1">Kelola informasi akun dan keamanan Anda</p>
        </div>
    
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
