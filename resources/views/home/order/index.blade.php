@extends('layouts.app')

@section('content')
<div class="container" id="orders" v-cloak>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Всі замовлення ({{ $user->name }} - {{ $user->role->display_name }})</div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10%;">id</th>
                                <th>Назва страви</th>
                                <th>Офіціант</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders">
                                <td>@{{ order.id }}</td>
                                <td>@{{ order.name }}</td>
                                <td>@{{ order.user.name }}</td>
                                <td>@{{ order.status.display_name }}</td>
                            </tr>
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.0.3/vue-resource.min.js"></script>

<script>
    var orders = new Vue({
        el: '#orders',

        http: {
            root: '{{ URL::to('/') }}/home'
        },

        data: {
            orders: [],
            timers: [],
        },

        methods: {

        },

        created: function() {
            this.$http.get('order/all').then(
                function(r) {
                    console.log(r.data);
                    this.orders = r.data;
                },
                function(r) {
                    console.log(r);
                    console.log('Error retrieving Orders');
                }
            );
        }
    });

    console.log('Admin or Kitchen is here');
</script>
@endsection