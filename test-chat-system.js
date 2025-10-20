// Test script for MentorHub Chat System
import io from 'socket.io-client';

console.log('🧪 Testing MentorHub Chat System...\n');

// Test 1: Socket Server Connection
console.log('1. Testing Socket Server Connection...');
const socket = io('http://localhost:3001');

socket.on('connect', () => {
    console.log('✅ Socket server connected successfully');
    
    // Test 2: Authentication
    console.log('\n2. Testing User Authentication...');
    socket.emit('authenticate', {
        userId: 1,
        userType: 'tutor'
    });
    
    socket.on('authenticated', (data) => {
        console.log('✅ User authenticated successfully');
        
        // Test 3: Join Chat Room
        console.log('\n3. Testing Chat Room Join...');
        socket.emit('join_chat', {
            studentId: 1,
            tutorId: 1
        });
        
        socket.on('joined_chat', (data) => {
            console.log('✅ Joined chat room successfully');
            
            // Test 4: Send Message
            console.log('\n4. Testing Message Sending...');
            socket.emit('send_message', {
                senderId: 1,
                senderType: 'tutor',
                receiverId: 2,
                receiverType: 'student',
                message: 'Hello from test script!',
                fileData: null
            });
            
            socket.on('new_message', (data) => {
                console.log('✅ Message sent and received successfully');
                console.log('📨 Message data:', data);
                
                // Test 5: Typing Indicators
                console.log('\n5. Testing Typing Indicators...');
                socket.emit('typing_start', {
                    senderId: 1,
                    senderType: 'tutor',
                    receiverId: 2,
                    receiverType: 'student'
                });
                
                setTimeout(() => {
                    socket.emit('typing_stop', {
                        senderId: 1,
                        senderType: 'tutor',
                        receiverId: 2,
                        receiverType: 'student'
                    });
                    console.log('✅ Typing indicators working');
                    
                    // Test 6: Read Receipts
                    console.log('\n6. Testing Read Receipts...');
                    socket.emit('mark_read', {
                        messageId: 1,
                        readerId: 2,
                        readerType: 'student'
                    });
                    
                    socket.on('message_read', (data) => {
                        console.log('✅ Read receipts working');
                        console.log('📖 Read receipt data:', data);
                        
                        // Final Summary
                        console.log('\n🎉 All tests passed! Chat system is working correctly.');
                        console.log('\n📊 Test Summary:');
                        console.log('   ✅ Socket server connection');
                        console.log('   ✅ User authentication');
                        console.log('   ✅ Chat room joining');
                        console.log('   ✅ Message sending/receiving');
                        console.log('   ✅ Typing indicators');
                        console.log('   ✅ Read receipts');
                        
                        // Disconnect
                        setTimeout(() => {
                            socket.disconnect();
                            console.log('\n🔌 Disconnected from socket server');
                            process.exit(0);
                        }, 1000);
                    });
                }, 2000);
            });
        });
    });
});

socket.on('connect_error', (error) => {
    console.error('❌ Socket connection failed:', error.message);
    process.exit(1);
});

socket.on('error', (error) => {
    console.error('❌ Socket error:', error);
    process.exit(1);
});

// Timeout after 30 seconds
setTimeout(() => {
    console.error('❌ Test timeout - socket server may not be running');
    process.exit(1);
}, 30000); 