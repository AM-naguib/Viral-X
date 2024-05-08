@extends('front.layouts.app')
@section('index', 'bg-gray-900')
@section('content')
    <section class="relative h-screen w-full">
        <img src="{{ asset('assets/img/') }}/1.webp" alt="ViralX Marketing" class="h-full w-full object-cover" width="1344"
            height="768" style="aspect-ratio: 1344 / 768; object-fit: cover" />
        <div
            class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-50 p-4 text-center text-white">
            <h1 class="text-6xl font-bold uppercase">Viral-X</h1>
            <p class="mt-4 text-xl">Expanding Your Digital Reach</p>
            <div class="mt-8 flex space-x-4">
                {{-- <button
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 bg-[#1DA1F2]">
                    Twitter</button><button
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 bg-[#4267B2]">
                    Facebook
                </button> --}}
            </div>
        </div>
    </section>


@endsection
