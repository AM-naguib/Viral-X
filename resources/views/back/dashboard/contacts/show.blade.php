@extends('back.layouts.app')
@section('content')

<div class="row justify-content-center mt-3">

   <div class="col-12">
      <div class="card border-0 shadow p-4 mb-4">
         <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="font-small">
             <span class="fw-bold">
                Name{{ $contact->first_name ." ". $contact->last_name }}
            </span>
              <span class="fw-normal ms-2">
                {{ $contact->created_at }}
              </span>
            </span>
            <div class="d-none text-capitalize d-sm-block @if ($contact->status == 'read')
                text-success
                @else
                text-danger
            @endif">
                {{$contact->status}}
            </div>
         </div>
         <p>{{$contact->email}}  / {{$contact->phone_number}}</p>
         <div class="message mt-4">

             <p class="m-0">Message: </p>
             <p >
                {{$contact->message}}
             </p>
         </div>
      </div>
   </div>
</div>
@endsection
