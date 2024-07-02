@extends('layout.app')
@section('app-content')
   <div class="teachers-page">
      <div class="row mb-4">
         <div class="col-sm-6 text-start"></div>
         <div class="col-sm-6 text-end">
            <a class="btn btn-primary create-btn" href="{{ route('staff.create') }}">Create New</a>
         </div>
      </div>
      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Staff Members</h3>
            <div class="filter-wrapper">
               <form action="{{ route('staffs') }}" class="filter-form">
                  <div class="row justify-content-end">
                     <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
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
                     <th scope="col">Email</th>
                     <th scope="col">Company</th>
                     <th scope="col">Title</th>
                     <th scope="col">Country</th>
                     <th scope="col">State</th>
                     <th scope="col">Status</th>
                     <th scope="col" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @if (count($allTeacher) > 0)
                     @foreach ($allTeacher as $teacher)
                        <tr>
                           <td sscope="row">{{ $teacher['first_name'] }} {{ $teacher['last_name'] }}</td>
                           <td scope="row">{{ $teacher['email'] }}</td>
                           <td scope="row">{{ $teacher['organisation'] }}</td>
                           <td scope="row">{{ $teacher['designation'] }}</td>
                           <td scope="row">{{ $teacher['country'] }}</td>
                           <td scope="row">{{ $teacher['state'] }}</td>
                           <td scope="row">
                              <?php
                              if ($teacher['status'] === 1) {
                                  $status = 'success';
                                  $label = 'Active';
                              } elseif ($teacher['status'] === 2) {
                                  $status = 'primary';
                                  $label = 'Pending';
                              } elseif ($teacher['status'] === 0) {
                                  $status = 'danger';
                                  $label = 'In-Active';
                              }
                              ?>
                              <span class="badge text-bg-{{ $status }}">{{ $label }}</span>
                           </td>
                           <td scope="row">
                              <div class="action-cell">
                                 <a href="{{ route('staff.edit', $teacher['id']) }}">
                                    <button class="raw-btn edit-btn">
                                       edit
                                    </button>
                                 </a>
                                 {{-- onClick="return confirm('Are you sure to delete this item?')" --}}
                                 {{-- {{ route('teacher.destroy', $teacher['id']) }} --}}
                                 <a href="javascript:false" class="teacher-delete-btn"
                                    data-teacher-id="{{ $teacher['id'] }}">
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
         {!! $allTeacher->withQueryString()->links() !!}
      </div>
   </div>

   @includeIf('partials.matrix', ['some' => 'data'])
@endsection

@push('remove_teacher_script')
   <script>
      $(".teacher-delete-btn").on("click", function() {
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
               const id = $(this).data("teacher-id");
               console.log(id);
               axios.post(`staff/destroy/${id}`, {
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
