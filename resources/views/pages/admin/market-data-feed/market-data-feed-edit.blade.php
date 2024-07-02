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
        <form method="post" action="{{ route('market-data-feed.update', $marketData->uuid) }}" enctype='multipart/form-data'>
            @csrf
            <div class="row gy-3 mb-24">
                <div class="col-sm-6">
                    <label for="name" class="ts-label"><span class="text-danger required">*</span>
                        <span>Name</span>
                    </label>
                    <input class="form-control input-control" type="text" name="name" value="{{ $marketData->name }}">
                    @if($errors->has('name'))
                        <div class="error">{{ $errors->first('name') }}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <label for="original-price" class="ts-label"><span class="text-danger required">*</span>
                        <span>Original Price</span>
                    </label>
                    <input class="form-control input-control" type="text" name="original_price" value="{{ $marketData->original_price }}" disabled>
                    @if($errors->has('original_price'))
                        <div class="error">{{ $errors->first('original_price') }}</div>
                    @endif
                </div>
            </div>

            <div class="row gy-3 mb-24">
                <div class="col-sm-6">
                    <label for="buffer-price" class="ts-label"><span class="text-danger required">*</span>
                        <span>Buffer Price</span>
                    </label>
                    <input class="form-control input-control" type="text" name="buffer_price" value="{{ $marketData->buffer_price }}">
                    @if($errors->has('buffer_price'))
                        <div class="error">{{ $errors->first('buffer_price') }}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <label for="price" class="ts-label"><span class="text-danger required">*</span>
                        <span>Price</span>
                    </label>
                    <input class="form-control input-control" type="text" name="price" value="{{ $marketData->price }}">
                    @if($errors->has('price'))
                        <div class="error">{{ $errors->first('price') }}</div>
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
