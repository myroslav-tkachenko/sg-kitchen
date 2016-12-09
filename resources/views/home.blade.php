@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Панель управління</div>

                <div class="panel-body">
                    Ви увійшли як {{ Auth::user()->role->display_name }}!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
