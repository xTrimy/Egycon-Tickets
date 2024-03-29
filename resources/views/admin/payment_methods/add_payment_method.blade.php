@extends('layouts.app')
@section('page')
event-settings
@endsection
@section('title')
Add Payment Method
@endsection
@section('content')

<main class="h-full pb-16 overflow-y-auto">
          <div class="container px-6 mx-auto grid">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Add Payment Method
            </h2>
            
                        @include('admin.includes.alerts')

            <!-- General elements -->
            <form method="POST" enctype="multipart/form-data"
              class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800"
              action="{{ route('admin.payment_methods.store',$event_id) }}"
            >
            <span class="text-red-500 text-sm">* Is required</span>

            @csrf
            @if($errors->any())
                {!! implode('', $errors->all('<div class="text-red-500">:message</div>')) !!}
            @endif
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                Select Method <span class="text-red-500">*</span>
                </span>
                <select
                required
                name="payment_method_id"
                  class="block w-full mt-1 text-sm border dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                >
                <option disabled selected>Select Method</option>
                @foreach ($payment_methods as $payment_method)
                  <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                @endforeach
                </select>
              </label>
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                <i class="las la-signature text-xl"></i>
                Method Name
                </span>
                <input
                type="text"
                name="name"
                placeholder="Vodafone Cash"
                  class="block w-full mt-1 text-sm border dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                >
              </label>
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                <i class="las la-signature text-xl"></i>
                Account Name / Merchant ID <span class="text-red-500">*</span>
                </span>
                <input
                type="text"
                name="account_name"
                required
                placeholder="Jon Doe / MID-xxxxxx"
                  class="block w-full mt-1 text-sm border dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                >
              </label>
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                <i class="las la-signature text-xl"></i>
                Account Number / API Key <span class="text-red-500">*</span>
                </span>
                <input
                type="text"
                name="account_number"
                required
                placeholder="01012xxxxxx / 5442d5f8-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
                  class="block w-full mt-1 text-sm border dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                >
              </label>
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                Is Active?
                </span>
                <input
                type="checkbox"
                checked
                name="is_active"
                >
              </label>
              
              <button type="submit" class="table items-center mt-4 justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              Add Payment Method
              <span class="ml-2" aria-hidden="true">
                  <i class='las la-arrow-right'></i>
              </span>
            </button>
        </form>

          </div>
        </main>
@endsection
