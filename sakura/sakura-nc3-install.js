var querystring = require('querystring');
var nc3send = function(urlPath, postParams) {
	var querystring = require('querystring');
	var http = require('http');
	var net = require('net');

	var postOptions = {
		host: process.env.NC3URI,
		method: 'POST',
		path: urlPath,
		headers: {
			'Connection': 'keep-alive',
			'Content-Type': 'application/x-www-form-urlencoded',
			'Accept-Language': 'ja',
			'Content-Length': postParams.length,
			'Authorization': 'Basic ' + new Buffer(process.env.BASIC_USER + ':' + process.env.BASIC_PASS).toString('base64')
		}
	};
	console.log(postOptions);

	var postReq = http.request(postOptions, function(res) {
		res.setEncoding('utf8');
		if (process.env.DEBUG) {
			res.on('data', function (chunk) {
				//console.log(chunk);
			});
		}
	});

	// post the data
	if (process.env.DEBUG) {
		//console.log(postParams);
	}
	postReq.write(postParams);
	postReq.end();
};


var INTERVAL=3000
var i = 0;

//index
i = i + 1;
setTimeout(function() {
	var postData = querystring.stringify({
		'_method': 'POST',
		'data[language]': 'ja',
		'data[term]': ''
	});
	nc3send('/', postData);
}, INTERVAL * i);



//init_permission
i = i + 1;
setTimeout(function() {
	var postData = querystring.stringify({
		'_method': 'POST'
	});
	nc3send('/install/init_permission', postData);
}, INTERVAL * i);


//init_db
i = i + 1;
setTimeout(function() {
	var postData = querystring.stringify({
		'_method': 'POST',
		'data[DatabaseConfiguration][database]': process.env.DBNAME,
		'data[DatabaseConfiguration][datasource]': 'Database/Mysql',
		'data[DatabaseConfiguration][host]': process.env.DBHOST,
		'data[DatabaseConfiguration][login]': process.env.DBUSER,
		'data[DatabaseConfiguration][password]': process.env.DBPASS,
		'data[DatabaseConfiguration][port]': '3306',
		'data[DatabaseConfiguration][prefix]': '',
		'data[DatabaseConfiguration][schema]': 'public'
	});
	nc3send('/install/init_db', postData);
}, INTERVAL * i);


//init_admin_user
i = i + 1;
setTimeout(function() {
	var postData = querystring.stringify({
		'_method': 'POST',
		'data[User][handlename]': process.env.NC3HANDLE,
		'data[User][password]': process.env.NC3PASS,
		'data[User][password_again]': process.env.NC3PASS,
		'data[User][username]': process.env.NC3UID
	});
	nc3send('/install/init_admin_user', postData);
}, INTERVAL * i);


//init_permission
i = i + 1;
setTimeout(function() {
	var postData = querystring.stringify({
		'_method': 'POST'
	});
	nc3send('/install/finish', postData);
}, INTERVAL * i);
