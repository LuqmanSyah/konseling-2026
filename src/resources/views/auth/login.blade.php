<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Konseling Mahasiswa</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#eef4f3] text-zinc-950 antialiased">
    <main class="grid min-h-screen lg:grid-cols-[minmax(0,1fr)_480px]">
        <section class="relative hidden overflow-hidden bg-[#0f3f4d] text-white lg:block">
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(20,184,166,0.16)_0%,rgba(15,63,77,0)_42%),linear-gradient(90deg,rgba(255,255,255,0.08)_1px,transparent_1px),linear-gradient(0deg,rgba(255,255,255,0.08)_1px,transparent_1px)] bg-[length:auto,72px_72px,72px_72px]"></div>
            <div class="relative flex min-h-screen flex-col justify-between px-12 py-12 xl:px-16">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-white text-[#0f3f4d] shadow-lg shadow-black/10">
                            <span class="text-lg font-bold">K</span>
                        </div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-teal-100">Layanan Konseling</p>
                    </div>

                    <h1 class="mt-14 max-w-2xl text-5xl font-semibold leading-tight xl:text-6xl">
                        Sistem Booking dan Manajemen Layanan Konseling Mahasiswa
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-teal-50/85">
                        Ruang digital yang rapi untuk mengatur sesi, memantau proses, dan menjaga layanan tetap tertata.
                    </p>
                </div>

                <div class="mb-8 grid max-w-3xl grid-cols-[1.1fr_0.9fr] gap-5">
                    <div class="rounded-lg border border-white/15 bg-white/12 p-6 shadow-2xl shadow-black/20 backdrop-blur">
                        <div class="flex items-center justify-between border-b border-white/15 pb-5">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-100">Hari Ini</p>
                                <p class="mt-2 text-2xl font-semibold">Konseling Mahasiswa</p>
                            </div>
                            <div class="rounded-md bg-amber-300 px-3 py-1 text-sm font-semibold text-zinc-950">Aktif</div>
                        </div>
                        <div class="mt-6 space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-1 rounded-full bg-teal-300"></div>
                                <div>
                                    <p class="font-semibold">Pengajuan layanan</p>
                                    <p class="text-sm text-teal-50/75">Tersimpan dalam satu alur kerja</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-1 rounded-full bg-amber-300"></div>
                                <div>
                                    <p class="font-semibold">Jadwal sesi</p>
                                    <p class="text-sm text-teal-50/75">Mudah dipantau dan diperbarui</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 text-zinc-950 shadow-2xl shadow-black/20">
                        <p class="text-sm font-semibold text-[#0f6b78]">Status Sistem</p>
                        <div class="mt-6 space-y-5">
                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span>Verifikasi</span>
                                    <span class="font-semibold">82%</span>
                                </div>
                                <div class="h-2 rounded-full bg-zinc-100">
                                    <div class="h-2 w-[82%] rounded-full bg-[#0f6b78]"></div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span>Penjadwalan</span>
                                    <span class="font-semibold">64%</span>
                                </div>
                                <div class="h-2 rounded-full bg-zinc-100">
                                    <div class="h-2 w-[64%] rounded-full bg-amber-400"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex min-h-screen items-center justify-center px-6 py-10 sm:px-8">
            <div class="w-full max-w-sm">
                <div class="mb-8 lg:hidden">
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-[#0f3f4d] text-lg font-bold text-white shadow-lg shadow-teal-950/20">
                        K
                    </div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0f6b78]">Konseling Mahasiswa</p>
                    <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight">
                        Sistem Booking dan Manajemen Layanan Konseling Mahasiswa
                    </h1>
                </div>

                <div class="rounded-lg border border-white/80 bg-white/90 p-7 shadow-xl shadow-teal-950/10 backdrop-blur sm:p-8">
                    <div class="mb-7">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0f6b78]">Konseling Mahasiswa</p>
                        <h2 class="mt-3 text-3xl font-semibold tracking-tight">Masuk ke sistem</h2>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-800">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="mt-2 block w-full rounded-md border-zinc-300 bg-white px-3 py-2.5 text-zinc-950 shadow-sm transition focus:border-[#0f6b78] focus:ring-[#0f6b78]"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-800">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="mt-2 block w-full rounded-md border-zinc-300 bg-white px-3 py-2.5 text-zinc-950 shadow-sm transition focus:border-[#0f6b78] focus:ring-[#0f6b78]"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 text-sm text-zinc-700">
                        <input
                            name="remember"
                            type="checkbox"
                            value="1"
                            class="rounded border-zinc-300 text-[#0f6b78] focus:ring-[#0f6b78]"
                        >
                        Ingat sesi saya
                    </label>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center rounded-md bg-[#0f6b78] px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0b5360] focus:outline-none focus:ring-2 focus:ring-[#0f6b78] focus:ring-offset-2"
                    >
                        Masuk
                    </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
