@extends('layout.app')

@section('app-content')
   <div class="teachers-page">
      <div class="row mb-4">
         <div class="col-sm-6 text-start">
            <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
               <i class="bi bi-arrow-left"></i> <span>Back</span>
            </a>
         </div>
         <div class="col-sm-6 text-end"></div>
      </div>

      <div class="row justify-content-center">
         <div class="col-md-12">
            <form method="post" action="{{ route('teacher.blog.store') }}" enctype='multipart/form-data'>
               @csrf
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Author Name</span>
                     </label>
                     <input class="form-control input-control" name="user_name" placeholder="Name">
                     @if ($errors->has('user_name'))
                        <div class="error">{{ $errors->first('user_name') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Author Image</span>
                     </label>
                     <input class="form-control input-control" type="file" name="user_image">
                     @if ($errors->has('user_image'))
                        <div class="error">{{ $errors->first('user_image') }}</div>
                     @endif
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                    <div class="col-sm-12">
                        <label for="title" class="ts-label"><span class="text-danger required">*</span>
                            <span>Title</span>
                        </label>
                        <input class="form-control input-control" type="text" name="title" placeholder="title">
                        @if ($errors->has('title'))
                            <div class="error">{{ $errors->first('title') }}</div>
                        @endif
                    </div>
               </div>
               <div class="row gy-3 mb-24">
                    <div class="col-sm-12" id="ckEditor">
                        <label for="detail" class="ts-label">
                            <span>Description</span>
                        </label>
                        <textarea name="detail" id="detail" class="form-control input-control select-control"></textarea>
                        @if ($errors->has('detail'))
                            <div class="error">{{ $errors->first('detail') }}</div>
                        @endif
                    </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6">
                     <label for="account" class="ts-label">
                        <span>Blog Image</span>
                     </label>
                     <input class="form-control input-control" type="file" name="picture">
                     @if ($errors->has('picture'))
                        <div class="error">{{ $errors->first('picture') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label">
                        <span>Publish Date</span>
                     </label>
                     <input class="form-control input-control" type="date" name="date">
                     @if ($errors->has('date'))
                        <div class="error">{{ $errors->first('date') }}</div>
                     @endif
                  </div>
               </div>
               <div class="row gy-3 mb-24">
               <div class="col-sm-6">
                     <label for="multiple_user" class="ts-label">
                        <span>Select Blog Tags</span>
                     </label>
                     <select name="tags[]" id="tags" class="form-control input-control" multiple>
                           <option value="">Multiple User</option>
                         @if($tags)
                             @foreach($tags as $tag)
                                 <option  value="{{ $tag['name'] }}">{{ $tag['name'] }}</option>
                             @endforeach
                         @endif
                     </select>
                  </div>
                    <div class="col-sm-6">
                        <label for="account" class="ts-label"><span class="text-danger required">*</span>
                            <span>Status</span>
                        </label>
                        <select name="publish" class="form-control input-control">
                            <option value="1">Publish</option>
                            <option value="0">unpublish</option>
                        </select>
                        @if ($errors->has('publish'))
                            <div class="error">{{ $errors->first('publish') }}</div>
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
      </div>
   </div>
   <style>
      .error {
         color: red;
      }
   </style>

@push('scripts')
   <script>
      $(document).ready(function(){
         $('#tags').select2({
            tags:true
         });
      });
   </script>
@endpush
@endsection
