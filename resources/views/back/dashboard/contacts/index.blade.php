@extends('back.layouts.app')
@section('content')
    <div class="py-4">

        <h1>Contacts</h1>

    </div>
    <div class="row justify-content-lg-center">
        <div id="message" class="col-12 col-lg-4 text-center">

        </div>
        <div class="col-12 mb-4">
            <div class="card">
                <div class="table-responsive py-4">
                    <table class="table table-flush" id="datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($contacts) > 0)
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $contact->first_name ." ". $contact->last_name }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->phone_number }}</td>
                                        <td>{{ Str::limit($contact->message, 50) }}</td>
                                        <td class="text-capitalize @if ($contact->status == 'read')
                                            text-success
                                        @endif">{{ $contact->status }}</td>
                                        <td class="d-flex gap-3">
                                            <a href="{{ route('admin.contact.show', $contact->id) }}"
                                                class="btn btn-primary">Show</a>
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = () => {
            let formSubmit = document.querySelectorAll('#formSubmit');
            let form = document.querySelectorAll('#forms');
            formSubmit.forEach((item, key) => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    let formData = new FormData(form[key]);
                    let jsonData = {};
                    formData.forEach((value, key) => {
                        jsonData[key] = value;
                    })
                    let jsonString = JSON.stringify(jsonData);
                    let xhr = new XMLHttpRequest();
                    xhr.open("delete", form[key].action);
                    xhr.setRequestHeader("X-CSRF-TOKEN", form[key]._token.value);
                    xhr.setRequestHeader("Content-Type", "application/json");
                    xhr.setRequestHeader("Accept", "application/json");
                    xhr.send(jsonString);
                    xhr.onload = () => {
                        if (xhr.status == 200) {
                            item.parentElement.parentElement.remove();
                            let messageContaner = document.querySelector('#message');
                            let res = JSON.parse(xhr.responseText);
                            messageContaner.innerHTML =
                                `<div class="alert alert-success">${res.message}</div>`

                        }
                    }
                })

            })

        }
    </script>
@endsection
