@extends('layout.app')
@section('app-content')
    <div class="history-page">
    <div class="row mb-4">
      </div>
        <div class="card card-box history-table">
            <div class="cardbox-header">
                <h3 class="cardbox-title">Market Data Feeds</h3>
                <div class="filter-wrapper">
                    <form action="{{route('teacher.market-data-feed.index')}}" class="filter-form">
                        <div class="row justify-content-end">
                            <div class="col-sm-4">
                                <input type="search" class="input-control" name="search" placeholder="Search...">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-borderless trade-table table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col" class="text-end">Monthly Price</th>
                        <th scope="col" class="text-end">Markup</th>
                        <th scope="col" class="text-end">Total Price</th>
                        {{-- <th scope="col" class="text-center">Action</th> --}}
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($marketDataList as $value)
                            <tr>
                                <td scope="row">{{$value->name}}</td>
                                <td scope="row" class="text-end">${{number_format($value->original_price, 2)}}</td>
                                <td scope="row" class="text-end">${{number_format($value->buffer_price, 2)}}</td>
                                <td scope="row" class="text-end">${{number_format($value->price, 2)}}</td>
                                <td scope="row">
                                    <div class="action-cell">
                                        {{-- <a href="{{ route('teacher.market-data-feed.edit', $value->uuid) }}">
                                            <button class="raw-btn edit-btn">
                                                edit
                                            </button>
                                        </a> --}}
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
