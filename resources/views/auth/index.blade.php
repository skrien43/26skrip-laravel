@extends('layouts.minimal')

@section('title', 'Login Akun')

@section('content')
    <div class="flex min-h-screen">
        <div class="hidden w-1/2 bg-gray-100 p-7 md:block">
            <img src="{{ asset('img/auth_img-sample-1.jpg') }}" class="h-full w-full object-cover" 
                alt="Login Images" title="Jepreted on March 17th, 2023">
        </div>
        <div class="flex w-full items-center justify-center px-6 py-12 md:w-1/2 md:px-12">
            <div class="w-full max-w-md">
                <h2 class="mb-5 text-26 font-semibold text-blue-light-700 dark:text-white/50">Login Akun</h2>
                <p class="mb-8 text-16 text-gray-500 dark:text-gray-300">
                    Login untuk mengakses fitur peminjaman perangkat dan pemindahan ruangan mengajar (Dosen).
                </p>
            {{-- error handle notif --}}
                @if (session('error'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-error-200 bg-error-50 px-4 py-3 dark:border-error-500/30 dark:bg-error-500/10">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-error-600 dark:text-error-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M12 3C7.03 3 3 7.03 3 12s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z"/>
                        </svg>
                        <p class="text-14 font-medium items-center text-error-700 dark:text-error-300">
                            {{ session('error') }}
                        </p>
                    </div>
                @endif
            {{-- login set form --}}
                <form method="POST" action="{{ route('login.post') }}" onsubmit="rembMe()" class="space-y-5">
                    @csrf
                    <div class=" text-16 font-medium">
                        <label class="ml-2 mb-1.5 block text-gray-600 dark:text-gray-400">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                            placeholder="NPM/Kode Dosen/Pegawai" required
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                    </div>
                    <div class=" text-16 font-medium">
                        <label class="ml-2 mb-1.5 block text-gray-600 dark:text-gray-400">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Password" required autocomplete="off"
                                class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 pr-11 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                            <button type="button" onclick="viewPass()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                👁
                            </button>
                        </div>
                    </div>
                    <div class=" text-16 font-medium">
                        <label class="ml-2 mb-1.5 block text-gray-600 dark:text-gray-400">Captcha</label>
                        <div class="flex items-center gap-3">
                            <span class="ml-2.5 text-18 font-medium text-gray-600 dark:text-gray-400">{{ $num1 }} + {{ $num2 }} =</span>
                            <input type="number" name="captcha" min="1" max="50" placeholder="....." required autocomplete="off"
                                class="h-11 w-24 rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                        </div>
                    </div>
                    <button type="submit" class="h-11 w-full rounded-lg bg-blue-light-700 text-14 sm:text-16 font-medium text-white transition-colors duration-150 hover:bg-blue-light-900">
                        Login
                    </button>
                </form>
                <a href="{{ url('/') }}" class="mt-6 block text-center text-14 sm:text-16 text-gray-500 hover:text-blue-light-700 dark:text-gray-400">
                    Home
                </a>
            </div>
        </div>
    </div>
@endsection
