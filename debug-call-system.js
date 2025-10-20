// Debug script for call system
const io = require('socket.io-client');

// Test 1: Connect as Tutor
console.log('=== Testing Tutor Connection ===');
const tutorSocket = io('http://localhost:3001');

tutorSocket.on('connect', () => {
    console.log('Tutor connected to socket server');
    
    // Authenticate as tutor
    tutorSocket.emit('authenticate', {
        userId: 1,
        userType: 'tutor'
    });
    
    console.log('Tutor authentication sent');
});

tutorSocket.on('authenticated', () => {
    console.log('Tutor authenticated successfully');
});

// Test 2: Connect as Student
console.log('=== Testing Student Connection ===');
const studentSocket = io('http://localhost:3001');

studentSocket.on('connect', () => {
    console.log('Student connected to socket server');
    
    // Authenticate as student
    studentSocket.emit('authenticate', {
        userId: 2,
        userType: 'student'
    });
    
    console.log('Student authentication sent');
});

studentSocket.on('authenticated', () => {
    console.log('Student authenticated successfully');
});

// Test 3: Listen for incoming calls (student)
studentSocket.on('incoming_call', (data) => {
    console.log('Student received incoming call:', data);
});

// Test 4: Initiate call from tutor
setTimeout(() => {
    console.log('=== Testing Call Initiation ===');
    
    const callData = {
        roomId: 'test_room_' + Date.now(),
        callType: 'video',
        callerId: 1,
        callerName: 'Test Tutor',
        receiverId: 2,
        receiverType: 'student'
    };
    
    console.log('Tutor initiating call:', callData);
    tutorSocket.emit('call_initiated', callData);
    
}, 3000); // Wait 3 seconds for both to authenticate

// Test 5: Check health endpoint
setTimeout(() => {
    console.log('=== Checking Server Health ===');
    const http = require('http');
    
    const options = {
        hostname: 'localhost',
        port: 3001,
        path: '/health',
        method: 'GET'
    };
    
    const req = http.request(options, (res) => {
        let data = '';
        res.on('data', (chunk) => {
            data += chunk;
        });
        res.on('end', () => {
            console.log('Server health response:', JSON.parse(data));
        });
    });
    
    req.on('error', (err) => {
        console.error('Health check failed:', err.message);
    });
    
    req.end();
    
}, 5000);

// Cleanup after 10 seconds
setTimeout(() => {
    console.log('=== Cleanup ===');
    tutorSocket.disconnect();
    studentSocket.disconnect();
    process.exit(0);
}, 10000);
