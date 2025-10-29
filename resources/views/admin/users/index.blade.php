<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | MentorHub Admin</title>
    <style>
        :root{--primary:#4a90e2;--secondary:#5637d9}
        *{box-sizing:border-box}
        body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(rgba(255,255,255,.92),rgba(255,255,255,.92)),url('{{ asset('images/Uc-background.jpg') }}') no-repeat center/cover fixed;min-height:100vh}
        .topbar{background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff}
        .nav{max-width:1200px;margin:0 auto;display:flex;align-items:center;gap:16px;padding:14px 16px}
        .logo{display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none}
        .logo-img{height:70px}
        .tabs{display:flex;gap:8px;margin-left:auto}
        .tab{color:#eaf2ff;text-decoration:none;padding:8px 14px;border-radius:999px}
        .tab.active{background:rgba(255,255,255,.22)}
        main{max-width:1200px;margin:24px auto;padding:0 16px}
        h2{margin:0 0 14px;color:#1f2d3d}
        .toolbar{display:flex;gap:10px;align-items:center;margin-bottom:14px}
        input,select{padding:10px;border:1px solid #e5e7eb;border-radius:10px}
        .table{background:#fff;border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);overflow:auto}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #f1f5f9}
        th{font-size:12px;letter-spacing:.02em;color:#6b7280;background:#f8fafc}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px}
        .green{background:#e7f8ef;color:#0f9d58}
        .red{background:#fde7ea;color:#b00020}
        .btn{border:none;background:var(--primary);color:#fff;padding:8px 12px;border-radius:10px;cursor:pointer}
        .btn.alt{background:#ef4444}
    </style>
</head>
<body>
    <div class="topbar">
        <div class="nav">
            <a class="logo" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/MentorHub.png') }}" alt="logo" class="logo-img"></a>
            <div class="tabs">
                <a class="tab" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="tab active">Users</span>
            </div>
        </div>
    </div>

    <main>
        <h2>Users</h2>
        @if(session('status'))
            <div style="background:#e7f8ef;color:#0f9d58;padding:10px 12px;border-radius:10px;margin-bottom:10px">{{ session('status') }}</div>
        @endif
        <form class="toolbar" method="GET" action="{{ route('admin.users.index') }}">
            <input type="text" name="q" placeholder="Search name or email" value="{{ $q }}">
            <select name="type" onchange="this.form.submit()">
                <option value="all" {{ $type==='all'?'selected':'' }}>All</option>
                <option value="student" {{ $type==='student'?'selected':'' }}>Students</option>
                <option value="tutor" {{ $type==='tutor'?'selected':'' }}>Tutors</option>
            </select>
            <button class="btn" type="submit">Search</button>
        </form>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Email</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>
                                <button type="button" class="btn" style="background:#ffffff;color:#111827;border:none;box-shadow:none" onclick="openUserModal('{{ data_get($u,'type') }}','{{ data_get($u,'id') }}')">{{ data_get($u,'name') }}</button>
                            </td>
                            <td>{{ ucfirst(data_get($u,'type')) }}</td>
                            <td>{{ data_get($u,'email') }}</td>
                            <td>₱{{ number_format((float) data_get($u,'balance',0),2) }}</td>
                            <td>
                                @php $isActive = (bool) data_get($u,'active',true); @endphp
                                <span class="badge {{ $isActive ? 'green' : 'red' }}">{{ $isActive ? 'Active' : 'Deactivated' }}</span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.toggle') }}" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ data_get($u,'id') }}">
                                    <input type="hidden" name="type" value="{{ data_get($u,'type') }}">
                                    <button class="btn {{ $isActive ? 'alt' : '' }}" type="submit">{{ $isActive ? 'Deactivate' : 'Activate' }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <div id="userModal" style="position:fixed;inset:0;background:rgba(0,0,0,.35);display:none;align-items:center;justify-content:center;padding:16px">
        <div style="background:#fff;border-radius:14px;max-width:720px;width:100%;box-shadow:0 24px 60px rgba(20,33,61,.2);overflow:hidden">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:#f8fafc;border-bottom:1px solid #eef2f7">
                <h3 style="margin:0;font-size:18px;color:#111827">User Details</h3>
                <button class="btn" onclick="closeUserModal()" style="background:#ef4444">Close</button>
            </div>
            <div id="userContent" style="padding:16px"></div>
        </div>
    </div>

    <script>
        async function openUserModal(type, id) {
            try {
                const res = await fetch(`{{ url('/admin/users') }}/${type}/${id}/detail`);
                const data = await res.json();
                const content = `
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
                        <div>
                            <div style="margin:6px 0"><strong>Name:</strong> ${data.name}</div>
                            <div style="margin:6px 0"><strong>Type:</strong> ${data.type.charAt(0).toUpperCase()+data.type.slice(1)}</div>
                            <div style="margin:6px 0"><strong>Email:</strong> ${data.email ?? '—'}</div>
                            <div style="margin:6px 0"><strong>Phone:</strong> ${data.phone ?? '—'}</div>
                            <div style="margin:6px 0"><strong>Status:</strong> ${data.active ? '<span style="background:#e7f8ef;color:#0f9d58;padding:4px 8px;border-radius:999px;font-size:12px">Active</span>' : '<span style="background:#fde7ea;color:#b00020;padding:4px 8px;border-radius:999px;font-size:12px">Deactivated</span>'}</div>
                        </div>
                        <div>
                            <div style="margin:6px 0"><strong>Balance:</strong> ₱${Number(data.balance).toFixed(2)}</div>
                            ${data.type === 'student' ? `
                                <div style=\"margin:6px 0\"><strong>Student ID:</strong> ${data.extra.student_id ?? '—'}</div>
                                <div style=\"margin:6px 0\"><strong>Course:</strong> ${data.extra.course ?? '—'}</div>
                                <div style=\"margin:6px 0\"><strong>Year Level:</strong> ${data.extra.year_level ?? '—'}</div>
                            ` : `
                                <div style=\"margin:6px 0\"><strong>Tutor ID:</strong> ${data.extra.tutor_id ?? '—'}</div>
                                <div style=\"margin:6px 0\"><strong>Specialization:</strong> ${data.extra.specialization ?? '—'}</div>
                                <div style=\"margin:6px 0\"><strong>Rate:</strong> ₱${Number(data.extra.session_rate ?? 0).toFixed(2)}/hour</div>
                            `}
                        </div>
                    </div>
                `;
                document.getElementById('userContent').innerHTML = content;
                document.getElementById('userModal').style.display = 'flex';
            } catch (e) {
                document.getElementById('userContent').innerHTML = '<div style="color:#b00020">Failed to load details.</div>';
                document.getElementById('userModal').style.display = 'flex';
            }
        }
        function closeUserModal(){
            document.getElementById('userModal').style.display = 'none';
            document.getElementById('userContent').innerHTML = '';
        }
    </script>
</body>
</html>


