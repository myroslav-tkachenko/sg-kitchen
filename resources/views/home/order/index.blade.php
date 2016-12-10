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
                                <th>Номер столу</th>
                                <th>Назва страви</th>
                                <th>Офіціант</th>
                                <th>Статус</th>
                                @if ($user->isCook())
                                    <th>Дії</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders">
                                <td>@{{ order.id }}</td>
                                <td>@{{ order.table_id }}</td>
                                <td>@{{ order.name }}</td>
                                <td>@{{ order.user.name }}</td>
                                <td>@{{ order.status.display_name }}</td>
                                @if ($user->isCook())
                                <td>
                                    <a href="#!" class="btn btn-success"
                                        v-if="order.status.id != 4"
                                        @click="finishOrder(order)"
                                    >
                                        Виконано!
                                    </a>
                                </td>
                                @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.1/socket.io.js"></script>

<script>
    var socket = io('{{ URL::to('/') }}:3000');

    Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="_token"]').getAttribute('value');

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
            fetchOrdersList: function() {
                this.$http.get('order/all').then(
                    function(r) {
                        this.orders = r.data;
                    },
                    function(r) {
                        console.log(r);
                        console.log('Error retrieving Orders');
                    }
                );
            },
            
            finishOrder: function(order) {
                var submissionData = {
                        id: order.id,
                        action: 'finish',
                    };

                this.$http.post('order/pass', submissionData).then(
                        function(r) {
                            console.log(r.data);
                            console.log('Order ', order.id, ' was finished');
                        },
                        function(r) {
                            console.log(r);
                            console.log('Error while finishing Order');
                        }

                    )
            }
        },

        created: function() {
            this.fetchOrdersList();

            socket.on('orders-channel:newOrder', this.fetchOrdersList);
            socket.on('orders-channel:passOrder', this.fetchOrdersList);
            socket.on('orders-channel:finishOrder', this.fetchOrdersList);
        }
    });

    console.log('Admin or Kitchen is here');
</script>
@endsection