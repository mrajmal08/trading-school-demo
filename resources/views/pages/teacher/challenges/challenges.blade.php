@extends('layout.app')

@section('app-content')
   <div class="challenge-page">
      <div class="row mb-4">
         <div class="col-sm-6 text-start"></div>
      </div>
      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Challenge's Design</h3>
            <div class="filter-wrapper">
               <form action="{{route('teacher.challenges')}}" class="filter-form">
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
                     <th scope="col">Challenge Type</th>
                     <th scope="col">Challenge Name</th>
                     <th scope="col" class="text-end">Buying Power</th>
                     <th scope="col" class="text-end">Price</th>
                     <th scope="col" class="text-end">Profit Target</th>
                     <th scope="col" class="text-end">Minimum Days</th>
                     <th scope="col" class="text-center" >Action</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($challenges as $challenge)
                     <tr>
                        <td>{{ $challenge->service->name }}</td>
                        <td>{{ $challenge->title }}</td>
                        <td class="text-end">${{ number_format($challenge->buying_power, 2) }}</td>
                        <td class="text-end">${{ number_format($challenge->price, 2) }}</td>
                        <td class="text-end">
                           @forelse($challenge->cardHead as $ch)
                              @if (optional($ch->cardHeadTitle)->type === 'profittarget')
                                 @foreach ($ch->cardHeadSubTitle as $sub)
                                    {{ optional($sub->cardSubHeadTitle)->title }}
                                 @endforeach
                              @endif
                           @empty
                              {{ '--- No Data ---' }}
                           @endforelse
                        </td>
                        <td class="text-end">
                           @forelse($challenge->cardHead as $ch)
                              @if (optional($ch->cardHeadTitle)->type === 'minimumchallengedays')
                                 @foreach ($ch->cardHeadSubTitle as $sub)
                                    {{ optional($sub->cardSubHeadTitle)->title }}
                                 @endforeach
                              @endif
                           @empty
                              {{ '-' }}
                           @endforelse
                        </td>
                        <td>
                           <div class="action-cell">
                              <a href="{{ route('teacher.challenges.show', $challenge->id) }}">
                                 <button class="raw-btn details-btn">
                                    Preview
                                 </button>
                              </a>
                              {{-- <a href="{{ route('admin.challenges.edit', $challenge->id) }}">
                                 <button class="raw-btn edit-btn">
                                    Edit
                                 </button>
                              </a> --}}
                              {{-- <a href="javascript:false" data-id="{{ $challenge->id }}" class="challenge-delete">
                                 <button class="raw-btn delete-btn">
                                    Delete
                                 </button>
                              </a> --}}
                           </div>
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="0">No Challenges Data Found</td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
      <div class="tr-pagination">
         {!! $challenges->links() !!}
      </div>
   </div>
@endsection

@push('remove_teacher_script')
   <script>
      $(".challenge-delete").on("click", function() {
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
               const route = "{{ route('admin.challenges.destroy', 'id') }}";
               console.log(id, route);
               const path = route.replace("id", id);
               axios.post(path, {
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
