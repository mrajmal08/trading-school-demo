@extends('layout.app')

@section('app-content')
    <div class="history-page">
        <div class="card card-box history-table">
            <div class="cardbox-header">
                <h3 class="cardbox-title">User List</h3>
            </div>
            <div class="table-responsive">
                <table class="table-borderless trade-table table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col" style="text-align: left">Account Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($allUser as $user)
                        <tr>
                            <td sscope="row">{{optional($user->userDetail)->first_name}} {{optional($user->userDetail)->last_name}}</td>
                            <td scope="row">{{ $user['email'] }}</td>
                            <td scope="row">{{ $user['account_name'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
