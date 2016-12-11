@extends('layouts.app')

@section('content')
<div class="container" id="orders" v-cloak>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Мої замовлення ({{ $user->name }} - {{ $user->role->display_name }})</div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10%;">id</th>
                                <th>Назва страви</th>
                                <th>Статус</th>
                                <th>Дії</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders" v-bind:class="{ 'danger': isFinished(order) }">
                                <td>@{{ order.id }}</td>
                                <td>@{{ order.name }}</td>
                                <td>
                                    @{{ order.status.display_name }}
                                    <!-- counter -->
                                    <span v-if="order.status.name === 'in_process'"
                                        v-html="renderCounter(order)"
                                    >
                                    </span>
                                </td>
                                <td>
                                    <a href="#!" class="btn btn-success"
                                        v-if="order.status.id == 1"
                                        @click="passOrder(order)"
                                    >
                                        Передати!
                                    </a>
                                </td>
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
            
            passOrder: function(order) {
                var submissionData = {
                        id: order.id,
                        action: 'pass',
                        data: '',
                    };

                this.$http.post('order/change', submissionData).then(
                        function(r) {
                            console.log(r.data);
                            console.log('Order ', order.id, ' passed');
                        },
                        function(r) {
                            console.log(r);
                            console.log('Error while passing Order');
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

            isFinished: function(order) {
                return order.status.name === 'done';
            }
        },

        created: function() {
            this.fetchOrdersList();

            socket.on('orders-channel:newOrder', this.fetchOrdersList);
            socket.on('orders-channel:passOrder', this.fetchOrdersList);
            socket.on('orders-channel:finishOrder', this.fetchOrdersList);
            socket.on('orders-channel:processingOrder', this.fetchOrdersList);
        }
    });

    console.log('Waiter is here');
    socket.on('timers', function(t) {
        orders.timers = t;
    });
</script>
@endsection