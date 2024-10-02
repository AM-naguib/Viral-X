@extends('front.layouts.app')
@section('refund-policy', 'bg-gray-900')
@section('content')
    <section class="bg-gray-100 dark:bg-gray-900 flex flex-col items-center justify-center min-h-screen py-12">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg max-w-md">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Viral-X
                Refund Policy:</h1>

            <p class="text-base text-gray-700 dark:text-gray-300 mb-4">Thank you for choosing our services at Code Solutions. We understand that there may be situations that warrant a refund, so we offer you a flexible refund policy to ensure your comfort and confidence when using our services.</p>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Refund Policy:</h3>

            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 mb-4">
                <li>Customers can request a full refund within 14 days of the purchase date if they are not satisfied with the provided service.</li>
                <li>Refunds will be issued using the same payment method used for the original transaction.</li>
                <li>To request a refund, please contact our customer service team through the <a href="{{ route('front.contact') }}" class="text-blue-500 hover:underline">Contact</a> page, providing details of the order and reasons for dissatisfaction.</li>
                <li>Code Solutions reserves the right to review cases and offer alternative solutions before processing refunds.</li>
                <li>Please note that refund requests will not be accepted after the 14-day period from the date of purchase has elapsed.</li>
            </ul>

            <p class="text-base text-gray-700 dark:text-gray-300">At Code Solutions, we are always striving to provide the best services to our customers, and we welcome any inquiries or feedback that help us improve our services.</p>

            <p class="text-base text-gray-700 dark:text-gray-300 mt-4">Best regards,<br>Code Solutions Team</p>
        </div>
    </section>
@endsection
