const app = require('express')();
const http = require('http').Server(app);
const io = require('socket.io')(http, {
cors: {
    origin: "*",
    methods: ["GET", "POST"]
}});
const axios = require('axios');

app.get('/', (req, res) => {
    res.sendFile(__dirname + '/index.html');
});

io.on('connection', (socket) => {
    console.log('a user connected');
    socket.on('disconnect', () => {
        console.log('user disconnected');
    });
});

let getTrakers = () => {
    console.log('fetching data from bitfinex');
    let url = 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL';
    axios.get(url)
    .then(function (trackers) {
        // handle success
        console.log('got data');
        process(trackers.data);
    })
    .catch(function (error) {
        // handle error
        console.log(error);
    })
    .then(function () {
        // always executed
    });
};

let process = (trackers) => {
    console.log('processing data');
    let usdTrackers = [];
    let regx = new RegExp('.*\USD$');
    for (let index = 0; index < trackers.length; index++) {
        let coin = trackers[index][0];
        // console.log(coin);
        if(regx.test(coin)){
            usdTrackers.push(trackers[index]);
        }    
    }

    console.log('sending data to client');
    io.emit('trackers', {trackers: usdTrackers});
    //setTimeout(getTrakers, 10000);
};

const PORT = 3000;
const IP = '0.0.0.0';

//setTimeout(getTrakers, 10000);
setInterval(getTrakers, 10000);

http.listen(PORT, IP, () => {
    console.log('socket server is running at '+IP+':'+PORT);
});
