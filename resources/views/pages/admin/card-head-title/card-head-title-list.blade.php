@extends('layout.admin.app')

@section('admin.app-content')
    <div class="history-page">
    <div class="row mb-4">
         <div class="col-sm-6 text-start"></div>
         <div class="col-sm-6 text-end">
            <a class="btn btn-primary create-btn" href="{{ route('card-head-title.create') }}">Create Card Head Title</a>
         </div>
      </div>
        <div class="card card-box history-table">
            <div class="cardbox-header">
                <h3 class="cardbox-title">Card Head Title List</h3>
                <div class="filter-wrapper">
                    <form action="{{route('card-head-title.index')}}" class="filter-form">
                        <div class="row justify-content-end">
                            <div class="col-sm-4">
                                <input type="search" class="input-control" name="search" placeholder="Search...">
                            </div>
                            <div class="col-sm-2">
                                <input type="submit" class="filter-submit" name="date" value="Filter">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-borderless trade-table table">
                    <thead>
                    <tr>
                        <th scope="col">Serial No</th>
                        <th scope="col">Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($cardHeadTitleList as $value)
                            <tr>
                                <td scope="row">{{ $loop->index+1 }}</td>
                                <td scope="row">{{$value->title}}</td>
                                <td scope="row">{{$value->type}}</td>
                                <td scope="row">
                                    <div class="action-cell">
                                        <a href="{{ route('card-head-title.edit', $value->uuid) }}">
                                            <button class="raw-btn edit-btn">
                                                edit
                                            </button>
                                        </a>
                                        <a href="javascript:false" class="service-delete-btn"
                                            data-id="{{ $value->uuid }}">
                                            <button class="raw-btn delete-btn">Delete</button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" scope="row" class="text-center">Data not found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tr-pagination">
         {!! $cardHeadTitleList->links() !!}
        </div>
    </div>
@endsection

@push('remove_teacher_script')
   <script>
      $(".service-delete-btn").on("click", function() {
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
               const id = $(this).data("id");
               axios.post(`card-head-title-destroy/${id}`, {
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
