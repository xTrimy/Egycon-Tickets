@extends('layouts.app')
@section('page')
sub_tickets
@endsection
@section('title')
Sub Tickets
@endsection
@section('content')
        <main class="h-full pb-16 overflow-y-auto">
          <div class="container grid px-6 mx-auto">
              <div class="flex justify-between items-center">

            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Sub Tickets
            </h2>
            <a href="{{ route('admin.sub_tickets.add',$event_id) }}"><button class="bg-purple-600 text-white py-2 px-8 rounded-md">
                Add Sub Ticket Type
            </button></a>
              </div>

            <div class="w-full overflow-hidden rounded-lg shadow-xs">
              <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap" id="images">
                  <thead>
                    <tr
                      class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                    >
                      <th class="px-4 py-3">Sub Ticket Type</th>
                      <th class="px-4 py-3">Parent Ticket</th>
                      <th class="px-4 py-3">Persons</th>
                      <th class="px-4 py-3">Price</th>
                      <th class="px-4 py-3">Additional Price</th>
                      <th class="px-4 py-3">Actions</th>
                    </tr>
                  </thead>
                  <tbody
                    class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                  >
                    @foreach ($ticket_types as $ticket_type)
                        @if($ticket_type->sub_ticket_types()->count() > 0)
                            @foreach($ticket_type->sub_ticket_types()->withTrashed()->get() as $sub_ticket_type)
                                <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $sub_ticket_type->name }}</p>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $ticket_type->name }}</p>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3">
                                    <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $ticket_type->person }}</p>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3">
                                    <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $ticket_type->price }} EGP</p>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3">
                                    <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">+{{ $sub_ticket_type->price }} EGP</p>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">

                                    @if($sub_ticket_type->trashed())
                                    <a href="{{ route('admin.sub_tickets.restore', ['id'=>$sub_ticket_type->id,'event_id'=>$event_id]) }}"><button class="bg-green-500 text-white py-2 px-8 rounded-md">
                                        Restore
                                    </button></a>
                                    @else
                                    <a href="{{ route('admin.sub_tickets.delete', ['id'=>$sub_ticket_type->id,'event_id'=>$event_id]) }}">
                                    <button class="bg-red-600 text-white py-2 px-8 rounded-md">
                                        Delete
                                    </button></a>
                                    @endif
                                    <a href="{{ route('admin.sub_tickets.requests', ['id'=>$sub_ticket_type->id,'event_id'=>$event_id]) }}">
                                    <button class="bg-transparent hover:bg-neutral-200 transition-colors text-purple-500 py-2 px-4 rounded-md">
                                        <i class="las la-eye text-xl"></i>
                                    </button></a>
                                </td>
                                </tr>
                        @endforeach
                        @endif
                    @endforeach

                  </tbody>
                </table>
              </div>
            <div class="mt-4">
             {{$ticket_types->links('pagination::tailwind')}}
            </div>
          </div>
        </main>

@endsection
