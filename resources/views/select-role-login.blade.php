<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Choose Your Login Role - MentorHub</title>
    <link rel="stylesheet" href="{{asset('style/homePage.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .role-selection {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .role-container {
            position: relative;
            z-index: 1;
        }

        .role-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 600px;
            width: 90%;
        }

        .role-options {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .role-card {
            flex: 1;
            min-width: 200px;
            padding: 20px;
            border: 2px solid #eee;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .role-card:hover {
            border-color: #3498db;
            transform: translateY(-5px);
        }

        .role-icon {
            font-size: 48px;
            margin-bottom: 15px;
            color: #4a90e2;
        }

        h1 {
            color: white;
            margin-bottom: 30px;
        }

        .role-container h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .role-card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .role-card p {
            color: #666;
            font-size: 0.9em;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="role-selection">
        <canvas id="particles" class="particles"></canvas>

        <a href="/" class="back-btn">← Back to Home</a>
        <h1>Welcome to MentorHub</h1>
        <div class="role-container">
            <h2>Choose your Login Role</h2>
            <div class="role-options">
                <div class="role-card" onclick="window.location.href='/login/student'">
                    <div class="role-icon"><i class="fas fa-user-graduate"></i></div>
                    <h3>I'm a Student</h3>
                    <p>Log in as a student to access your learning dashboard</p>
                </div>
                <div class="role-card" onclick="window.location.href='/login/tutor'">
                    <div class="role-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <h3>I'm a Tutor</h3>
                    <p>Log in as a tutor to manage your tutoring sessions</p>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // Particle animation
    const canvas = document.getElementById('particles');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const particles = [];
    const particleCount = window.innerWidth < 768 ? 30 : 100;

    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1;
            this.speedX = Math.random() * 2 - 1;
            this.speedY = Math.random() * 2 - 1;
            this.opacity = Math.random() * 0.5 + 0.1;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            if (this.x > canvas.width || this.x < 0) this.speedX *= -1;
            if (this.y > canvas.height || this.y < 0) this.speedY *= -1;
        }

        draw() {
            ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function init() {
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();
        }

        requestAnimationFrame(animate);
    }

    window.addEventListener('resize', function() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });

    init();
    animate();
</script>
