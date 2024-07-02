@extends('layout.app')

@section('app-content')
   <div class="challenge-page">
      <div class="row mb-4">
         <div class="col-sm-6 text-start">
            <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
               <i class="bi bi-arrow-left"></i> <span>Back</span>
            </a>
         </div>
         <div class="col-sm-6 text-end">
            {{-- <a class="btn btn-primary create-btn" href="{{ route('admin.challenges.create') }}">Create Challenge</a> --}}
         </div>
      </div>

      <div class="row justify-content-center pt-4">
         <div class="col-md-4" style="position: relative">
            @if ($challenge->is_feature)
               <div class="feature">Featured</div>
            @endif
            <div class="card card-box history-table">
               <div class="cardbox-header flex-column text-center {{ $challenge->is_feature ? ' pt-4' : '' }}">
                  <h3 class="cardbox-title">{{ $challenge->title }}</h3>
                  <div><span>BUYING POWER:</span> <span>{{ $challenge->buying_power }}</span></div>
               </div>
               <div class="cardbox-body text-center">
                  <h3>${{ $challenge->price }}</h3>
                  <div class="challenge_title_wrapper mb-4">
                     <h5 class="challenge_title">Contracts</h5>
                     <p class="challenge_text">
                        @forelse($challenge->cardHead as $ch)
                           @if (optional($ch->cardHeadTitle)->type === 'numberofcontracts')
                              @foreach ($ch->cardHeadSubTitle as $sub)
                                 {{ optional($sub->cardSubHeadTitle)->title }}
                              @endforeach
                           @endif
                        @empty
                           {{ '--- No Data ---' }}
                        @endforelse
                     </p>
                  </div>
                  <div class="challenge_title_wrapper mb-4">
                     <h5 class="challenge_title">Profit Target</h5>
                     <p class="challenge_text">
                        @forelse($challenge->cardHead as $ch)
                           @if ($ch->cardHeadTitle->type === 'profittarget')
                              @foreach ($ch->cardHeadSubTitle as $sub)
                                 {{ optional($sub->cardSubHeadTitle)->title }}
                              @endforeach
                           @endif
                        @empty
                           {{ '--- No Data ---' }}
                        @endforelse
                     </p>
                  </div>
                  <div class="challenge_title_wrapper mb-4">
                     <h5 class="challenge_title">Daily Loss Limit</h5>
                     <p class="challenge_text">
                        @forelse($challenge->cardHead as $ch)
                           @if ($ch->cardHeadTitle->type === 'dailylosslimit')
                              @foreach ($ch->cardHeadSubTitle as $sub)
                                 {{ optional($sub->cardSubHeadTitle)->title }}
                              @endforeach
                           @endif
                        @empty
                           {{ '--- No Data ---' }}
                        @endforelse
                     </p>
                  </div>
                  <div class="challenge_title_wrapper mb-4">
                     <h5 class="challenge_title">Maximum Drawdown</h5>
                     <p class="challenge_text">
                        @forelse($challenge->cardHead as $ch)
                           @if ($ch->cardHeadTitle->type === 'maximumdrawdown')
                              @foreach ($ch->cardHeadSubTitle as $sub)
                                 {{ optional($sub->cardSubHeadTitle)->title }}
                              @endforeach
                           @endif
                        @empty
                           {{ '--- No Data ---' }}
                        @endforelse
                     </p>
                  </div>
                  {{-- <div class="challenge_title_wrapper mb-4">
                     <h5 class="challenge_title">Challenge Duration</h5>
                     <p class="challenge_text">
                        @forelse($challenge->cardHead as $ch)
                           @if ($ch->cardHeadTitle->type === 'challengeduration')
                              @foreach ($ch->cardHeadSubTitle as $sub)
                                 {{ optional($sub->cardSubHeadTitle)->title }} - {{ optional($sub->cardSubHeadTitle)->value }} <br />
                              @endforeach
                           @endif
                        @empty
                           {{ '--- No Data ---' }}
                        @endforelse
                     </p>
                  </div> --}}
                  <div class="challenge_title_wrapper mb-4">
                     <h5 class="challenge_title">Minimum Challenge Days</h5>
                     <p class="challenge_text">
                        @forelse($challenge->cardHead as $ch)
                           @if ($ch->cardHeadTitle->type === 'minimumchallengedays')
                              @foreach ($ch->cardHeadSubTitle as $sub)
                                 {{ optional($sub->cardSubHeadTitle)->title }}
                              @endforeach
                           @endif
                        @empty
                           {{ '--- No Data ---' }}
                        @endforelse
                     </p>
                  </div>
                  {{-- <div class="challenge_title_wrapper mb-4">
                     <h5 class="challenge_title">Profit Share</h5>
                     <p class="challenge_text">
                        @forelse($challenge->cardHead as $ch)
                           @if ($ch->cardHeadTitle->type === 'profitshare')
                              @foreach ($ch->cardHeadSubTitle as $sub)
                                 {{ optional($sub->cardSubHeadTitle)->value }}
                              @endforeach
                           @endif
                        @empty
                           {{ '--- No Data ---' }}
                        @endforelse
                     </p>
                  </div> --}}
               </div>
            </div>

         </div>
      </div>
   </div>
@endsection
