<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Tutor Registrations | MentorHub</title>
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
        .badge.pending{background:#fef3c7;color:#d97706}
        .btn{background:var(--primary);color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px;font-weight:500;text-decoration:none;display:inline-block;transition:opacity 0.2s}
        .btn:hover{opacity:0.9}
        .btn-approve{background:#0f9d58}
        .btn-reject{background:#dc2626}
        .btn-view-cv{background:#6b7280}
        .tutor-info{display:flex;flex-direction:column;gap:4px}
        .tutor-name{font-weight:600;color:#111827}
        .tutor-email{font-size:12px;color:var(--muted)}
        .alert{padding:12px 16px;border-radius:8px;margin-bottom:18px;background:#e7f8ef;color:#0f9d58;border:1px solid #c3e6cb}
        .alert-error{background:#fee2e2;color:#dc2626;border-color:#fecaca}
        .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
        .modal-content{background:#fff;padding:24px;border-radius:14px;width:90%;max-width:800px;box-shadow:0 20px 60px rgba(0,0,0,.3);max-height:90vh;overflow-y:auto}
        .modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
        .modal-title{font-size:18px;font-weight:600;color:#111827}
        .close-modal{background:none;border:none;font-size:24px;cursor:pointer;color:#6b7280}
        .cv-viewer{width:100%;height:600px;border:1px solid #d1d5db;border-radius:6px}
        .info-section{margin-bottom:16px;padding:12px;background:#f9fafb;border-radius:6px}
        .info-section strong{display:block;margin-bottom:4px;color:#374151}
        .info-section p{margin:0;color:#6b7280;font-size:14px}
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
                <a class="tab active" href="{{ route('admin.pending-tutors') }}">Pending Tutors</a>
                <a class="tab" href="{{ route('admin.ratings') }}">Ratings</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </div>

    <main>
        <h2>Pending Tutor Registrations</h2>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Tutor Information</th>
                        <th>Tutor ID</th>
                        <th>Specialization</th>
                        <th>Rate</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingTutors as $tutor)
                        <tr>
                            <td>
                                <div class="tutor-info">
                                    <span class="tutor-name">{{ $tutor->getFullName() }}</span>
                                    <span class="tutor-email">{{ $tutor->email }}</span>
                                    @if($tutor->phone)
                                        <span class="tutor-email">{{ $tutor->phone }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $tutor->tutor_id }}</td>
                            <td>{{ Str::limit($tutor->specialization, 50) }}</td>
                            <td>₱{{ number_format($tutor->session_rate, 2) }}/month</td>
                            <td>{{ $tutor->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex;gap:8px;flex-wrap:wrap">
                                    <button type="button" class="btn btn-view-cv" onclick="viewTutorDetails({{ $tutor->id }}, '{{ addslashes($tutor->getFullName()) }}', '{{ addslashes($tutor->email) }}', '{{ addslashes($tutor->tutor_id) }}', '{{ addslashes($tutor->specialization) }}', '{{ $tutor->session_rate }}', '{{ addslashes($tutor->phone ?? 'N/A') }}', '{{ addslashes($tutor->bio ?? 'N/A') }}', '{{ $tutor->cv ? asset('storage/' . $tutor->cv) : '' }}')">
                                        View Details
                                    </button>
                                    <form action="{{ route('admin.tutors.approve', $tutor->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-approve" onclick="return confirm('Are you sure you want to approve this tutor registration?')">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.tutors.reject', $tutor->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-reject" onclick="return confirm('Are you sure you want to reject this tutor registration?')">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:var(--muted);padding:40px">
                                No pending tutor registrations at this time.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <!-- Tutor Details Modal -->
    <div id="tutorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tutor Registration Details</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div id="tutorModalBody">
                <!-- Content will be dynamically inserted -->
            </div>
        </div>
    </div>

    <script>
        function viewTutorDetails(id, name, email, tutorId, specialization, rate, phone, bio, cvUrl) {
            const modal = document.getElementById('tutorModal');
            const modalBody = document.getElementById('tutorModalBody');
            
            // Set form actions with the tutor ID
            const approveUrl = '{{ url("/admin/tutors") }}/' + id + '/approve';
            const rejectUrl = '{{ url("/admin/tutors") }}/' + id + '/reject';
            
            let cvContent = '';
            if (cvUrl) {
                const fileExtension = cvUrl.split('.').pop().toLowerCase();
                if (fileExtension === 'pdf') {
                    cvContent = `<iframe src="${cvUrl}" class="cv-viewer"></iframe>`;
                } else {
                    cvContent = `<div style="padding:20px;text-align:center;color:var(--muted)">CV Preview not available. <a href="${cvUrl}" target="_blank" style="color:var(--primary)">Download CV</a></div>`;
                }
            } else {
                cvContent = '<div style="padding:20px;text-align:center;color:var(--muted)">No CV uploaded</div>';
            }

            modalBody.innerHTML = `
                <div class="info-section">
                    <strong>Name:</strong>
                    <p>${name}</p>
                </div>
                <div class="info-section">
                    <strong>Email:</strong>
                    <p>${email}</p>
                </div>
                <div class="info-section">
                    <strong>Tutor ID:</strong>
                    <p>${tutorId}</p>
                </div>
                <div class="info-section">
                    <strong>Phone:</strong>
                    <p>${phone}</p>
                </div>
                <div class="info-section">
                    <strong>Specialization:</strong>
                    <p>${specialization}</p>
                </div>
                <div class="info-section">
                    <strong>Monthly Rate:</strong>
                    <p>₱${parseFloat(rate).toFixed(2)}/month</p>
                </div>
                <div class="info-section">
                    <strong>Bio:</strong>
                    <p>${bio}</p>
                </div>
                <div class="info-section">
                    <strong>CV/Resume:</strong>
                    ${cvContent}
                </div>
                <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end">
                    <form id="approveForm" action="" method="POST" style="display:inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-approve" onclick="return confirm('Are you sure you want to approve this tutor registration?')">
                            Approve Registration
                        </button>
                    </form>
                    <form id="rejectForm" action="" method="POST" style="display:inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-reject" onclick="return confirm('Are you sure you want to reject this tutor registration?')">
                            Reject Registration
                        </button>
                    </form>
                </div>
            `;
            
            // Update form actions after setting innerHTML
            document.getElementById('approveForm').action = approveUrl;
            document.getElementById('rejectForm').action = rejectUrl;
            
            modal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('tutorModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('tutorModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>

