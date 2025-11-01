<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem Report #{{ $report->id }} | Admin | MentorHub</title>
    <style>
        :root { --primary:#4a90e2; --secondary:#5637d9; --card:#ffffff; --muted:#6b7280; }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(rgba(255,255,255,.92),rgba(255,255,255,.92)),url('{{ asset('images/Uc-background.jpg') }}') no-repeat center/cover fixed;min-height:100vh}
        .topbar{background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff}
        .nav{max-width:1200px;margin:0 auto;display:flex;align-items:center;gap:16px;padding:14px 16px}
        .logo{display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none}
        .logo-img{height:70px}
        .tabs{display:flex;gap:8px;margin-left:auto;margin-right:12px}
        .tab{color:#eaf2ff;text-decoration:none;padding:8px 14px;border-radius:999px}
        .tab.active{background:rgba(255,255,255,.22);backdrop-filter:blur(4px)}
        .logout{border:none;border-radius:20px;background:#e74c3c;color:#fff;padding:8px 14px;cursor:pointer}
        main{max-width:900px;margin:28px auto;padding:0 16px}
        h2{margin:0 0 16px;color:#1f2d3d}
        .card{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);padding:24px;margin-bottom:18px}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px;white-space:nowrap}
        .badge.green{background:#e7f8ef;color:#0f9d58}
        .badge.orange{background:#fff1e6;color:#f47c1f}
        .badge.red{background:#fee2e2;color:#dc2626}
        .badge.yellow{background:#fef3c7;color:#d97706}
        .badge.blue{background:#dbeafe;color:#1d4ed8}
        .info-row{display:flex;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #f1f5f9}
        .info-label{font-weight:600;color:#374151;width:150px;flex-shrink:0}
        .info-value{color:#6b7280}
        .section-title{font-size:18px;font-weight:600;color:#1f2d3d;margin:24px 0 12px}
        .description{background:#f8fafc;padding:16px;border-radius:8px;border-left:4px solid var(--primary);line-height:1.6;color:#374151}
        .form-group{margin-bottom:18px}
        .form-label{display:block;font-weight:600;color:#374151;margin-bottom:8px}
        select,textarea{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;font-size:14px;font-family:inherit}
        textarea{resize:vertical;min-height:100px}
        .btn{display:inline-block;padding:10px 20px;border-radius:8px;text-decoration:none;font-size:14px;cursor:pointer;border:none;margin-right:10px}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{background:#3a7ccc}
        .btn-secondary{background:#6b7280;color:#fff}
        .btn-secondary:hover{background:#4b5563}
        .btn-back{background:transparent;border:1px solid #ddd;color:#374151}
        .btn-back:hover{background:#f8fafc}
        .alert{padding:14px 18px;border-radius:8px;margin-bottom:18px}
        .alert-success{background:#e7f8ef;color:#0f9d58;border-left:4px solid #0f9d58}
        .response-box{background:#fef3c7;padding:16px;border-radius:8px;border-left:4px solid #d97706;margin-top:16px}
        .response-box h4{margin:0 0 8px;color:#92400e}
        .response-box p{margin:0;color:#78350f;line-height:1.6}
    </style>
</head>
<body>
    <div class="topbar">
        <div class="nav">
            <a class="logo" href="#">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub" class="logo-img">
            </a>
            <div class="tabs">
                <a class="tab" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="tab" href="{{ route('admin.pending-tutors') }}">Pending Tutors</a>
                <a class="tab" href="{{ route('admin.ratings') }}">Ratings</a>
                <a class="tab active" href="{{ route('admin.problem-reports.index') }}">Problem Reports</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </div>

    <main>
        <div style="margin-bottom:24px">
            <a href="{{ route('admin.problem-reports.index') }}" class="btn btn-back">← Back to Reports</a>
        </div>

        <h2>Problem Report #{{ $report->id }}</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="info-row">
                <div class="info-label">Reporter:</div>
                <div class="info-value">
                    @if($report->student_id)
                        {{ $report->student->first_name }} {{ $report->student->last_name }} (Student ID: {{ $report->student->id }})
                    @else
                        {{ $report->tutor->first_name }} {{ $report->tutor->last_name }} (Tutor ID: {{ $report->tutor->id }})
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">
                    @if($report->student_id)
                        {{ $report->student->email }}
                    @else
                        {{ $report->tutor->email }}
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Problem Type:</div>
                <div class="info-value">
                    <span class="badge blue">{{ ucfirst(str_replace('_', ' ', $report->problem_type)) }}</span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    @if($report->status === 'pending')
                        <span class="badge yellow">Pending</span>
                    @elseif($report->status === 'in_progress')
                        <span class="badge orange">In Progress</span>
                    @elseif($report->status === 'resolved')
                        <span class="badge green">Resolved</span>
                    @else
                        <span class="badge red">Closed</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Submitted:</div>
                <div class="info-value">{{ $report->created_at->format('F j, Y \a\t g:i A') }}</div>
            </div>

            @if($report->resolved_at)
            <div class="info-row">
                <div class="info-label">Resolved:</div>
                <div class="info-value">{{ $report->resolved_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
            @endif

            <div class="section-title">Subject</div>
            <div style="color:#374151;font-size:16px;margin-bottom:16px">{{ $report->subject }}</div>

            <div class="section-title">Description</div>
            <div class="description">{{ $report->description }}</div>

            @if($report->admin_response)
            <div class="response-box">
                <h4>Admin Response</h4>
                <p>{{ $report->admin_response }}</p>
            </div>
            @endif
        </div>

        <div class="card">
            <h3 style="margin:0 0 18px;color:#1f2d3d">Update Report</h3>
            <form method="POST" action="{{ route('admin.problem-reports.update', $report->id) }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" required>
                        <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $report->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Admin Response</label>
                    <textarea name="admin_response" placeholder="Add a response to the student's report...">{{ old('admin_response', $report->admin_response) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Report</button>
                <a href="{{ route('admin.problem-reports.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </main>
</body>
</html>

