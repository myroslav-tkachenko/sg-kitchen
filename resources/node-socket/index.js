var server = require('http').Server();

var io = require('socket.io')(server);

var Redis = require('ioredis');
var redis = new Redis();


redis.subscribe('orders-channel');

redis.on('message', function(channel, message) {
	var received = JSON.parse(message);

	io.emit(channel + ':' + received.message, received.content);

	if (received.message == 'processingOrder') {
		var newTimer = {
			order_id: received.content.order_id,
			time: received.content.data
		}
		timers.push(newTimer);
	}

});


// timers broadcasting routine
var timers = [];

var timersCountdown = setInterval(function() {
	timers.forEach(function(e, i, a) {
		if (e.time <= 0) {
			a.splice(i, 1);
			io.emit('orders-channel:counterIsOver', e)
		} else {
			a[i].time = e.time - 1;
		}
	});

	io.emit('timers', timers);
}, 1000);


server.listen(3000);