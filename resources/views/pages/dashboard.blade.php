@extends('layout.app')

@section('app-content')
   <div class="history-page">
      <div class="card card-box history-table">
      <div class="cardbox-header">
            <h3 class="cardbox-title">User List</h3>
            <div class="filter-wrapper">
               <form method="get" action="{{ route('home') }}" class="filter-form">
                  <div class="row justify-content-end">
                     <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                        <input type="search" class="input-control" name="search" placeholder="Search...">
                     </div>
                     <!-- <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                        <input type="text" class="input-control" name="dates" id="date-range" placeholder="Choose Duration">
                     </div> -->
                     <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
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
                     <th scope="col">Account</th>
                     <th scope="col">Name</th>
                     <th scope="col">Email</th>
                     <th scope="col">Net %</th>
                     <!-- <th scope="col">Fees</th>
                     <th scope="col">Risk Status</th>
                     <th scope="col">Achivments</th> -->
                     <th scope="col">P/L</th>
                     <th scope="col">Status</th>
                     <th scope="col">Action</th>
                  </tr>
               </thead>
               <tbody>

                  @forelse ($users as $user)
                  <tr>
                     <td sscope="row">{{ $user['account_name'] }}</td>
                     <td scope="row">{{ $user->userDetail->first_name }} {{ $user->userDetail->last_name }}</td>
                     <td scope="row">{{ $user['email'] }}</td>
                     <td scope="row"><?= !empty($user->allTrades[0]) ? $user->allTrades[0]['percent_profitable'].'%' : '--' ?></td>
                     <!-- <td scope="row">{{ $user['fees'] }}</td>
                     <td scope="row">{{ $user['risk_status'] }}</td>
                     <td scope="row">{{ $user['achievements'] }}</td> -->
                     <td scope="row"><?= !empty($user->allTrades[0]) ? $user->allTrades[0]['total_pl'] : '--' ?></td>
                     <td scope="row">
                        <?php
                        if ($user->userDetail->status === 1) {
                              $status = 'success';
                              $label = 'active';
                        } elseif ($user->userDetail->status === 0) {
                              $status = 'danger';
                              $label = 'inactive';
                        }
                        ?>
                        <span class="badge text-bg-{{ $status }}">{{ $label }}</span>
                     </td>
                     <td scope="row">
                        <div class="action-cell">
                            <a href="{{ route('teacher.user.details',$user->uuid) }}">
                             <button class="raw-btn matrix-btn">Matrix</button>
                            </a>
                           <button class="raw-btn details-btn" data-bs-toggle="modal"
                              data-bs-target="#details-modal-{{ $user['id'] }}">
                              Details
                           </button>
                           <!-- Details Modal -->
                           @includeIf('partials.history-detail-modal', ['user' => $user])
                        </div>
                     </td>
                  </tr>
                  @empty
                  <tr> No Data Found</tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
      <div class="tr-pagination">
         {!! $users->links() !!}
      </div>
   </div>
@endsection
