@extends('layout.admin.app')

@section('admin.app-content')
    <div>
        <div class="row mb-4">
            <div class="col-sm-6 text-start">
                <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
                <i class="bi bi-arrow-left"></i> <span>Back</span>
                </a>
            </div>
            <div class="col-sm-6 text-end"></div>
        </div>
        <form method="post" action="{{ route('service.store') }}" enctype='multipart/form-data'>
            @csrf
            <div class="row gy-3 mb-24">
                <div class="col-sm-12">
                    <label for="service" class="ts-label"><span class="text-danger required">*</span>
                        <span>Name</span>
                    </label>
                    <input class="form-control input-control" type="text" name="name">
                    @if($errors->has('name'))
                        <div class="error">{{ $errors->first('name') }}</div>
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
