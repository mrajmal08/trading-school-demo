@extends('layout.app')

@section('app-content')
    <div>
        <form method="post" action="{{ route('teacher.faq.update', $row->id) }}" enctype='multipart/form-data'>
            @csrf
            <div class="row gy-3 mb-24">
                <div class="col-sm-12">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Title</span>
                    </label>
                    <input class="form-control input-control" type="text" name="title" value="{{ $row->title }}">
                    @if($errors->has('title'))
                        <div class="error">{{ $errors->first('title') }}</div>
                    @endif
                </div>
                <div class="col-sm-12">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Description</span>
                    </label>
                    <input class="form-control input-control" type="text" name="description" value="{{ $row->description }}">
                    @if($errors->has('description'))
                        <div class="error">{{ $errors->first('description') }}</div>
                    @endif
                </div>
            </div>
            <div class="row gy-3 justify-content-center mb-24">
                <div class="col-sm-6">
                    <input class="btn-teacher-submit btn-submit w-100" type="submit" value="Submit">
                </div>
            </div>
        </form>
    </div>
    <style>
        .error{
            color: #a94442;
        }
    </style>
@endsection
