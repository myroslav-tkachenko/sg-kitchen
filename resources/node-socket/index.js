var server = require('http').Server();

var io = require('socket.io')(server);

var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('orders-channel');

redis.on('message', function(channel, message) {
	var received = JSON.parse(message);

	io.emit(channel + ':' + received.message, received.data);
	console.log(channel, received);

});

server.listen(3000);