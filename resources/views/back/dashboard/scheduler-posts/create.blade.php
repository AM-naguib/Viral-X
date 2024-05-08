@extends('back.layouts.app')
@section('posts', 'show')
@section('content')

    <div class="row justify-content-lg-center py-4">
        <div class="h1">Add Scheduler Post </div>
        @include('back.dashboard.inc.message')
        <div class="col-12 mb-4">
            <form action="{{ route('admin.scheduled-posts.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="textarea">Post</label>
                    <textarea class="form-control" placeholder="Enter your message..." id="textarea" name="content" rows="4">{{old('content')}}</textarea>
                </div>
                <div class="mb-3">
                    <label for="select-page">Select Page</label>
                    <select class="form-select" name="pages[]" multiple aria-label="multiple select example"
                        id="select-page" style="height: 200px">
                        @if (count($pages) > 0)
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Add image</label>
                    <input class="form-control" type="file" id="formFile" name="image">
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Add Date</label>
                    <input type="datetime-local" id="time" name="postTime" class="form-control">
                </div>
                <button class="btn form-control bg-success text-white" type="submit">Send Post</button>
            </form>
        </div>
    </div>
@endsection
