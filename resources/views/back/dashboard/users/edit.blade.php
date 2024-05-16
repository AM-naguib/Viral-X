@extends('back.layouts.app')
@section('content')
    <div class="py-4">
        <h1>Edit User</h1>
    </div>
    <div class="row justify-content-lg-center">
        <div class="col-12">
            @include('back.dashboard.inc.message')
        </div>
        <div class="col-12 col-lg-4">
            <div id="message">
                <div class="alert">

                </div>
            </div>
            <form action="{{ route('admin.users.update', $user->id) }}" class="form" id="form" method="post">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name"
                        value="{{ $user->name }}">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"
                        value="{{ $user->email }}">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"
                        value="">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role_id" id="" class="form-select">
                        @if (count($roles) > 0)
                            @foreach ($roles as $role)
                                <option @selected($role->id == $user->role_id) value="{{ $role->id }}">{{ $role->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label for="plan" class="form-label">Plan</label>
                    <select name="plan_id" id="" class="form-select">
                        @if (count($plans) > 0)
                            @foreach ($plans as $plan)
                                <option @selected($plan->id == $user->plan_id) value="{{ $plan->id }}">{{ $plan->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <button type="submit" class="btn form-control bg-success text-white">Edit User</button>
            </form>

            {{-- <script>
                let form = document.querySelector('#form');
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    let formData = new FormData(form);
                    let jsonData = {};
                    formData.forEach((value, key) => {
                        jsonData[key] = value;
                    });
                    let jsonString = JSON.stringify(jsonData);
                    let xhr = new XMLHttpRequest();
                    xhr.open(form.method, form.action);
                    xhr.setRequestHeader("X-CSRF-TOKEN", form._token.value);
                    xhr.setRequestHeader("Content-Type", "application/json");
                    xhr.setRequestHeader("Accept", "application/json");
                    xhr.onload = () => {
                        let messageContaner = document.querySelector('#message');
                        let res = JSON.parse(xhr.responseText);
                        const errorsArray = [];
                        if (xhr.status == 200) {
                            messageContaner.innerHTML = `<div class="alert alert-success">${res.message}</div>`
                            form.reset();
                        } else {
                            className = "alert-danger";
                            Object.keys(res.errors).forEach(key => {
                                res.errors[key].forEach(error => {
                                    errorsArray.push(error);

                                });
                            });
                            let htmlErrors = "<ul>";
                            errorsArray.forEach(item => {
                                htmlErrors += `<li>${item}</li>`;
                            });
                            htmlErrors += "</ul>";
                            messageContaner.innerHTML = `<div class="alert alert-danger">${htmlErrors}</div>`
                        }

                    }
                    xhr.send(jsonString);


                });
            </script> --}}


        </div>
    </div>
@endsection
