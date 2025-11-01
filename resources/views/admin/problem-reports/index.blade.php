<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem Reports | Admin | MentorHub</title>
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
        main{max-width:1200px;margin:28px auto;padding:0 16px}
        h2{margin:0 0 16px;color:#1f2d3d}
        .table{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);overflow:auto}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #f1f5f9}
        th{font-size:12px;letter-spacing:.02em;color:#6b7280;background:#f8fafc}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px;white-space:nowrap}
        .badge.green{background:#e7f8ef;color:#0f9d58}
        .badge.orange{background:#fff1e6;color:#f47c1f}
        .badge.red{background:#fee2e2;color:#dc2626}
        .badge.yellow{background:#fef3c7;color:#d97706}
        .badge.blue{background:#dbeafe;color:#1d4ed8}
        .btn{display:inline-block;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:14px;cursor:pointer;border:none}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{background:#3a7ccc}
        .alert{padding:14px 18px;border-radius:8px;margin-bottom:18px}
        .alert-success{background:#e7f8ef;color:#0f9d58;border-left:4px solid #0f9d58}
        .pagination{display:flex;gap:8px;margin-top:18px;justify-content:center}
        .pagination a,.pagination span{padding:8px 12px;border-radius:6px;text-decoration:none;color:#1f2d3d}
        .pagination a:hover{background:#f1f5f9}
        .pagination .active{background:var(--primary);color:#fff}
        .truncate{max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
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
        <h2>Problem Reports</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>#{{ $report->id }}</td>
                            <td>
                                @if($report->student_id)
                                    {{ $report->student->first_name }} {{ $report->student->last_name }} <span style="font-size:0.85em;color:#6b7280;">(Student)</span>
                                @else
                                    {{ $report->tutor->first_name }} {{ $report->tutor->last_name }} <span style="font-size:0.85em;color:#6b7280;">(Tutor)</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge blue">{{ ucfirst(str_replace('_', ' ', $report->problem_type)) }}</span>
                            </td>
                            <td>
                                <div class="truncate">{{ $report->subject }}</div>
                            </td>
                            <td>
                                @if($report->status === 'pending')
                                    <span class="badge yellow">Pending</span>
                                @elseif($report->status === 'in_progress')
                                    <span class="badge orange">In Progress</span>
                                @elseif($report->status === 'resolved')
                                    <span class="badge green">Resolved</span>
                                @else
                                    <span class="badge red">Closed</span>
                                @endif
                            </td>
                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.problem-reports.show', $report->id) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:40px;color:#6b7280">
                                No problem reports found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="pagination">
                {{ $reports->links() }}
            </div>
        @endif
    </main>
</body>
</html>

