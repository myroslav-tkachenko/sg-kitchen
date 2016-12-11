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
                                    <th style="width: 15%;">Дії</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders">
                                <td>@{{ order.id }}</td>
                                <td>@{{ order.table_id }}</td>
                                <td>@{{ order.name }}</td>
                                <td>@{{ order.user.name }}</td>
                                <td>
                                    @{{ order.status.display_name }}
                                    <!-- counter -->
                                    <span v-if="order.status.name === 'in_process'">
                                        @{{ renderCounter(order) }}
                                    </span>
                                </td>

                                @if ($user->isCook())
                                <td class="text-right">
                                    <div class="form-inline" style="margin-bottom: 15px;"
                                        v-if="order.status.name === 'passed'"
                                    >
                                        <div class="form-group">
                                            <input type="number" class="form-control input-sm" style="width: 100px;"
                                                v-on:focus="selectedOrder = order"
                                                v-model="cookingTime"
                                            >
                                        </div>
                                        <button class="btn btn-primary input-sm"
                                            @click="setOrderTime"
                                            v-if="selectedOrder && order.id === selectedOrder.id && cookingTime"
                                        >
                                            Ok
                                        </button>
                                    </div>

                                    {{--
                                    <a href="#!" class="btn btn-success"
                                        v-if="order.status.name === 'in_process'"
                                        @click="finishOrder(order.id)"
                                    >
                                        Виконано!
                                    </a>
                                    --}}
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
    </div>

    <pre>
        @{{ timers }}
        @{{ selectedOrder }}
    </pre>
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

            selectedOrder: null,
            cookingTime: 0,
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
            
            setOrderTime: function() {
                var submissionData = {
                    id: this.selectedOrder.id,
                    action: 'settime',
                    data: this.cookingTime
                };

                this.$http.post('order/change', submissionData).then(
                    function(r) {
                        console.log(r.data);
                        console.log('Order ', this.selectedOrder.id, ' was set to cook!');
                        this.selectedOrder = null;
                        this.cookingTime = 0;
                    },
                    function(r) {
                        console.log(r);
                        console.log('Error while finishing Order');
                    }

                )
            },

            renderCounter: function(order) {
                var timer = this.timers.find(function(e) {
                    if (e.order_id === order.id) return e;
                    return false;
                });

                if ( ! timer ) return '0 сек.';
                return timer.time + ' сек.';
            },

            // TODO: consider to pass order.id only
            finishOrder: function(id) {
                var submissionData = {
                        id: id,
                        action: 'finish',
                        data: '',
                    };

                this.$http.post('order/change', submissionData).then(
                        function(r) {
                            console.log(r.data);
                            console.log('Order ', id, ' was finished');
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
            socket.on('orders-channel:processingOrder', this.fetchOrdersList);

            // TODO: consider to catch a message when counter is expired on the node's side
            socket.on('orders-channel:counterIsOver', function(data) {
                console.log(data);
                this.finishOrder(data.order_id);
            }.bind(this));
        }
    });

    console.log('Admin or Kitchen is here');
    socket.on('timers', function(t) {
        orders.timers = t;
    });

</script>
@endsection