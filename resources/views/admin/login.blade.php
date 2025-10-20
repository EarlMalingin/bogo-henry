<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | MentorHub</title>
    <style>
        :root {
            --primary: #4a90e2;
            --primary-dark: #3a7ccc;
            --bg1: #6a80ff;
            --bg2: #743bd9;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(255,255,255,.92), rgba(255,255,255,.92)), url('{{ asset('images/Uc-background.jpg') }}') no-repeat center/cover fixed;
        }
        .wrap {
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            padding: 28px 28px 24px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(20, 33, 61, .12);
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: "";
            position: absolute;
            inset: -60px -60px auto auto;
            width: 200px; height: 200px;
            background: radial-gradient(closest-side, var(--bg1), transparent 70%);
            opacity: .25;
        }
        .brand {
            display: flex; align-items: center; gap: 10px; margin-bottom: 18px;
        }
        .brand img { height: 40px; }
        .brand h2 { margin: 0; font-size: 22px; color: #1f2d3d; }
        .hint { color:#6b7280; font-size: 13px; margin-bottom: 16px; }
        .alert { background:#fde7ea; color:#b00020; padding:10px 12px; border-radius:10px; margin-bottom:12px; }
        .group { margin-bottom: 14px; }
        label { display:block; font-size: 13px; color:#374151; margin-bottom: 6px; }
        .input {
            display: flex; align-items: center; gap: 8px;
            border: 1px solid #e5e7eb; border-radius: 12px; padding: 10px 12px;
            transition: border-color .2s, box-shadow .2s;
        }
        .input:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(74,144,226,.15); }
        .input input { border: none; outline: none; width: 100%; font-size: 14px; }
        .actions { margin-top: 16px; display:flex; align-items:center; justify-content:space-between; gap: 12px; }
        .btn {
            width: 100%;
            border: none;
            padding: 12px 14px;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            border-radius: 999px;
            cursor: pointer;
            transition: background .2s, transform .02s;
        }
        .btn:hover { background: var(--primary-dark); }
        .btn:active { transform: translateY(1px); }
        .support { margin-top: 14px; text-align:center; color:#6b7280; font-size: 12px; }
        .support a { color: var(--primary); text-decoration: none; }
        .support a:hover { text-decoration: underline; }
        .icon { width:18px; height:18px; color:#9ca3af; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="brand">
            <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub">
            <h2>Admin Login</h2>
        </div>
        <div class="hint">Sign in to access the MentorHub admin dashboard.</div>

        @if(session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="group">
                <label>Email</label>
                <div class="input">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6"/><path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.6"/></svg>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="admin@example.com">
                </div>
            </div>
            <div class="group">
                <label>Password</label>
                <div class="input">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="10" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 10V7a4 4 0 118 0v3" stroke="currentColor" stroke-width="1.6"/></svg>
                    <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>
            </div>
            <div class="actions">
                <button type="submit" class="btn">Log In</button>
            </div>
        </form>
        <div class="support">Having trouble? Contact <a href="#">support</a>.</div>
    </div>
    </div>
</body>
</html>


