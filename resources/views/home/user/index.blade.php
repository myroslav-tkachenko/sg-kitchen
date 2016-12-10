@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Всі користувачі</div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10%;">id</th>
                                <th>Користувач</th>
                                <th>Роль</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->role->display_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
