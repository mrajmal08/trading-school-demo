@extends('layout.app')

@section('app-content')
    <div class="history-page">
    <div class="row mb-4">
         <div class="col-sm-6 text-start"></div>
         <div class="col-sm-6 text-end">
            <a class="btn btn-primary create-btn" href="{{ route('teacher.rules.create') }}">Create T&C</a>
         </div>
      </div>
        <div class="card card-box history-table">
            <div class="cardbox-header">
                <h3 class="cardbox-title">Terms & Conditions</h3>
                <div class="filter-wrapper">
                    {{-- <form action="{{route('rules.index')}}" class="filter-form">
                        <div class="row justify-content-end">
                            <div class="col-sm-4">
                                <input type="search" class="input-control" name="search" placeholder="Search...">
                            </div>
                            <div class="col-sm-2">
                                <input type="submit" class="filter-submit" name="date" value="Filter">
                            </div>
                        </div>
                    </form> --}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-borderless trade-table table">
                    <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($rulesList) > 0)
                        @foreach ($rulesList as $value)
                            <tr>
                                <td scope="row">{{$value->title}}</td>
                                <td scope="row">{{$value->description }}</td>
                                <td scope="row">
                                    <div class="action-cell">
                                        <a href="{{ route('teacher.rules.edit', $value->id) }}">
                                            <button class="raw-btn edit-btn">
                                                edit
                                            </button>
                                        </a>
                                        <a href="javascript:false" class="blog-delete-btn"
                                            data-blog-id="{{ $value->id }}">
                                            <button class="raw-btn delete-btn">
                                                Delete
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                        <td colspan="10" scope="row" class="text-center">Data not found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tr-pagination">
         {!! $rulesList->links() !!}
        </div>
    </div>
@endsection

@push('remove_teacher_script')
   <script>
      $(".blog-delete-btn").on("click", function() {
         Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete'
         }).then((result) => {
            if (result.isConfirmed) {
               const id = $(this).data("blog-id");
               console.log(id);
               axios.post(`rules-destroy/${id}`, {
                  "id": id,
                  "_method": "delete"
               }).then(res => {
                  console.log(res)
                  if (res.status === 200) {
                     Swal.fire(
                        'Deleted!',
                        'Your record has been deleted.',
                        'success'
                     ).then((result) => {
                        console.log(result)
                        if (result.isConfirmed) {
                           window.location.reload();
                        }
                     })
                  }
               }).catch()
            }
         })
      })
   </script>
@endpush
