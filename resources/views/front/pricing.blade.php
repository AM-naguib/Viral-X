@extends('front.layouts.app')
@section('pricing', 'bg-gray-900')
@section('content')
    <div class="isolate overflow-hidden bg-gray-900">
        <div class="mx-auto max-w-7xl px-6 pb-96 pt-24 text-center sm:pt-32 lg:px-8">
            <div class="mx-auto max-w-4xl">
                <h2 class="text-base font-semibold leading-7 text-indigo-400">Pricing</h2>
                <p class="mt-2 text-4xl font-bold tracking-tight text-white sm:text-5xl">The right price for you, <br
                        class="hidden sm:inline lg:hidden">whoever you are</p>
            </div>

        </div>
        <div class="flow-root bg-white pb-24 sm:pb-32">
            <div class="-mt-80">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto grid max-w-md grid-cols-1 gap-8 lg:max-w-4xl lg:grid-cols-2">
                        @if (count($plans) > 0)
                            @foreach ($plans as $plan)
                                <div
                                    class="flex flex-col justify-between rounded-3xl bg-white p-8 shadow-xl ring-1 ring-gray-900/10 sm:p-10">
                                    <div>
                                        <h3 id="tier-hobby" class="text-base font-semibold leading-7 text-indigo-600">
                                            {{ $plan->name }}</h3>
                                        <div class="mt-4 flex items-baseline gap-x-2">
                                            <span
                                                class="text-5xl font-bold tracking-tight text-gray-900">{{ $plan->price }}</span>
                                            <span class="text-base font-semibold leading-7 text-gray-600">/month</span>
                                        </div>
                                        <p class="mt-6 text-base leading-7 text-gray-600">{{ $plan->description }}.</p>
                                        <ul role="list" class="mt-10 space-y-4 text-sm leading-6 text-gray-600">
                                            <?php
                                            $features = explode(',', $plan->features);
                                            ?>
                                            @foreach ($features as $feature)
                                                <li class="flex gap-x-3">
                                                    <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20"
                                                        fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @if (auth()->check())
                                        <a href="#" aria-describedby="tier-hobby"
                                            class="mt-8 block rounded-md bg-indigo-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            Get started today
                                        </a>
                                    @else
                                        <a href="{{ route('register') }}" aria-describedby="tier-hobby"
                                            class="mt-8 block rounded-md bg-indigo-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            Sign up Now
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
