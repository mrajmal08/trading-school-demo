<div class="col-sm-10">
  <h5>
     Challenge Details for {{ $user->userDetail->fullName() }} {{ $user->email }} -
     @if ($user->packagePurchaseAccountDetail->isNotEmpty())
        {{ $user->packagePurchaseAccountDetail->last()->cardChallenge?->title }}
     @else
        No Challenges
     @endif
  </h5>
</div>