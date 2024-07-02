@extends('layout.admin.app')

@section('admin.app-content')
    <div class="history-page">
    <div class="row mb-4">
         <div class="col-sm-6 text-start"></div>
         <div class="col-sm-6 text-end">
            <a class="btn btn-primary create-btn" href="{{ route('coupon-code.create') }}">Create Coupon</a>
         </div>
      </div>
        <div class="card card-box history-table">
            <div class="cardbox-header">
                <h3 class="cardbox-title">Coupons & Promotions</h3>
                <div class="filter-wrapper">
                    <form action="{{route('coupon-code.index')}}" class="filter-form">
                        <div class="row justify-content-end">
                            <div class="col-sm-4">
                                <input type="search" class="input-control" name="search" placeholder="Search...">
                            </div>
                            {{-- <div class="col-sm-2">
                                <input type="submit" class="filter-submit" name="date" value="Filter">
                            </div> --}}
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-borderless trade-table table">
                    <thead>
                    <tr>
                        <th scope="col">Coupon Code</th>
                        <th scope="col">Promotion Name</th>
                        <th scope="col" class="text-end">Amount</th>
                        <th scope="col">Date Range</th>
                        <th scope="col">Units Available</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($couponCodeList as $value)
                            <tr>
                                <td scope="row">{{ $value->coupon_id }}</td>
                                <td scope="row">{{ $value->promotion_name}}</td>
                                <td scope="row" class="text-end">${{ number_format($value->amount, 2) }}</td>
                                <td scope="row">{{ $value->date_range }}</td>
                                <td scope="row" class="text-capitalize">
                                    @if ($value->max_use === 'forever' && $value->total_number > 0)
                                        {{ $value->total_number - $value->total_apply .' / '. $value->total_number }}
                                    @elseif ($value->max_use === 'forever' && $value->total_number == 0)
                                        Unlimited
                                    @else
                                        Once - ({{ $value->total_number - $value->total_apply .' / '. $value->total_number }})
                                    @endif
                                    {{-- {{ $value->max_use === 'forever' ? 'Unlimited' : $value->total_apply .' / '. $value->total_number }} --}}
                                </td>
                                <td scope="row">
                                <?php
                                    if ($value->status === 1) {
                                        $status = 'success';
                                        $label = 'Active';
                                    } elseif ($value->status === 0) {
                                        $status = 'danger';
                                        $label = 'In-Active';
                                    }
                                ?>
                                    <span class="badge text-bg-{{ $status }}">{{ $label }}</span>
                                </td>
                                <td scope="row">
                                    <div class="action-cell">
                                        {{-- <a href="{{ route('coupon-code.edit', $value->uuid) }}">
                                            <button class="raw-btn edit-btn">
                                                edit
                                            </button>
                                        </a> --}}
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
         {!! $couponCodeList->links() !!}
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
               axios.post(`coupon-code-destroy/${id}`, {
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
