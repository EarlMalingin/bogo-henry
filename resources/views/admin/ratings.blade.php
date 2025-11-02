<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Ratings | MentorHub</title>
    <style>
        :root { --primary:#4a90e2; --secondary:#5637d9; --card:#ffffff; --muted:#6b7280; }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(rgba(255,255,255,.92),rgba(255,255,255,.92)),url('{{ asset('images/Uc-background.jpg') }}') no-repeat center/cover fixed;min-height:100vh}
        .topbar{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
        .nav{max-width:1400px;margin:0 auto;display:flex;align-items:center;gap:20px;padding:16px 20px}
        .logo{display:flex;align-items:center;gap:12px;color:#fff;text-decoration:none;transition:transform 0.3s}
        .logo:hover{transform:scale(1.05)}
        .logo-img{height:70px;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2))}
        .tabs{display:flex;gap:4px;margin-left:auto;margin-right:16px;background:rgba(255,255,255,0.1);padding:4px;border-radius:10px;backdrop-filter:blur(10px)}
        .tab{color:#fff;text-decoration:none;padding:10px 18px;border-radius:8px;font-weight:500;transition:all 0.3s;position:relative}
        .tab:hover{background:rgba(255,255,255,0.15);transform:translateY(-2px)}
        .tab.active{background:rgba(255,255,255,0.25);font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,0.15)}
        .tab.active::before{content:'';position:absolute;bottom:-2px;left:50%;transform:translateX(-50%);width:80%;height:3px;background:#fff;border-radius:2px}
        .logout{border:none;border-radius:8px;background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;padding:10px 20px;cursor:pointer;font-weight:600;transition:all 0.3s;box-shadow:0 2px 6px rgba(231,76,60,0.3)}
        .logout:hover{background:linear-gradient(135deg,#c0392b,#a93226);transform:translateY(-2px);box-shadow:0 4px 12px rgba(231,76,60,0.4)}
        main{max-width:1200px;margin:28px auto;padding:0 16px}
        h2{margin:0 0 16px;color:#1f2d3d}
        .row{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:18px;margin-top:18px}
        .table{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);overflow:auto}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #f1f5f9}
        th{font-size:12px;letter-spacing:.02em;color:#6b7280;background:#f8fafc}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px;white-space:nowrap}
        .badge.green{background:#e7f8ef;color:#0f9d58}
        .badge.orange{background:#fff1e6;color:#f47c1f}
        .badge.red{background:#fee2e2;color:#dc2626}
        .badge.yellow{background:#fef3c7;color:#d97706}
        .btn-message{background:var(--primary);color:#fff;border:none;padding:6px 12px;border-radius:6px;cursor:pointer;font-size:12px;transition:opacity 0.2s}
        .btn-message:hover:not(:disabled){opacity:0.9}
        .btn-message:disabled{opacity:0.5;cursor:not-allowed}
        .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
        .modal-content{background:#fff;padding:24px;border-radius:14px;width:90%;max-width:500px;box-shadow:0 20px 60px rgba(0,0,0,.3)}
        .modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
        .modal-title{font-size:18px;font-weight:600;color:#111827}
        .close-modal{background:none;border:none;font-size:24px;cursor:pointer;color:#6b7280}
        .form-group{margin-bottom:16px}
        .form-group label{display:block;margin-bottom:6px;font-weight:500;color:#374151}
        .form-group textarea{width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;font-family:inherit;resize:vertical;min-height:100px}
        .modal-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:20px}
        .btn-primary{background:var(--primary);color:#fff;border:none;padding:10px 20px;border-radius:6px;cursor:pointer;font-weight:500}
        .btn-secondary{background:#6b7280;color:#fff;border:none;padding:10px 20px;border-radius:6px;cursor:pointer}
        .rating-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600}
        .rating-badge.excellent{background:#e7f8ef;color:#0f9d58}
        .rating-badge.good{background:#dbeafe;color:#2563eb}
        .rating-badge.average{background:#fef3c7;color:#d97706}
        .rating-badge.poor{background:#fff1e6;color:#f47c1f}
        .rating-badge.terrible{background:#fee2e2;color:#dc2626}
        .stars{color:#ffd700;font-size:14px}
        .review-card{background:#f9fafb;border-radius:8px;padding:12px;margin-top:8px;border-left:3px solid var(--primary)}
        .review-text{color:#374151;font-size:13px;margin-top:6px}
        .review-meta{font-size:11px;color:var(--muted);margin-top:8px}
        .filters{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);padding:18px;margin-bottom:18px}
        .filter-row{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
        .filter-group{display:flex;flex-direction:column;gap:6px}
        .filter-group label{font-size:12px;color:var(--muted);font-weight:500}
        .filter-group select,.filter-group input{padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;background:#fff;cursor:pointer}
        .filter-group input{min-width:200px}
        .btn-filter{background:var(--primary);color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px;font-weight:500}
        .btn-filter:hover{opacity:0.9}
        .btn-reset{background:#6b7280;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px;font-weight:500}
        .btn-reset:hover{opacity:0.9}
        .hidden{display:none}
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
                <a class="tab active" href="{{ route('admin.ratings') }}">Ratings</a>
                <a class="tab" href="{{ route('admin.problem-reports.index') }}">Problem Reports</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </div>

    <main>
        <h2>Tutor Ratings & Reviews</h2>

        <div class="filters">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Filter by Status</label>
                    <select id="statusFilter" onchange="filterTable()">
                        <option value="all">All Statuses</option>
                        <option value="excellent">Excellent (4.5+)</option>
                        <option value="good">Good (3.5-4.4)</option>
                        <option value="average">Average (2.5-3.4)</option>
                        <option value="poor">Poor (2.0-2.4)</option>
                        <option value="terrible">Terrible (&lt;2.0)</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Search Tutor</label>
                    <input type="text" id="searchFilter" placeholder="Search by name or email..." onkeyup="filterTable()">
                </div>
                <div class="filter-group" style="align-self:flex-end">
                    <button type="button" class="btn-reset" onclick="resetFilters()">Reset</button>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top:18px">
            <div class="table" style="grid-column:1/-1">
                <table>
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Average Rating</th>
                            <th>Total Reviews</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="ratingsTableBody">
                        @php $tutorsWithRatings = $tutorsWithRatings ?? collect(); @endphp
                        @forelse($tutorsWithRatings as $tutor)
                            @php
                                $rating = $tutor['average_rating'];
                                $count = $tutor['rating_count'];
                                $ratingClass = '';
                                $ratingText = '';
                                if ($rating >= 4.5) {
                                    $ratingClass = 'excellent';
                                    $ratingText = 'Excellent';
                                } elseif ($rating >= 3.5) {
                                    $ratingClass = 'good';
                                    $ratingText = 'Good';
                                } elseif ($rating >= 2.5) {
                                    $ratingClass = 'average';
                                    $ratingText = 'Average';
                                } elseif ($rating >= 2.0) {
                                    $ratingClass = 'poor';
                                    $ratingText = 'Poor';
                                } else {
                                    $ratingClass = 'terrible';
                                    $ratingText = 'Terrible';
                                }
                            @endphp
                            <tr data-status="{{ $ratingClass }}" data-name="{{ strtolower($tutor['name']) }}" data-email="{{ strtolower($tutor['email']) }}">
                                <td>
                                    <strong>{{ $tutor['name'] }}</strong><br>
                                    <small style="color:var(--muted)">{{ $tutor['email'] }}</small>
                                </td>
                                <td>
                                    <span class="rating-badge {{ $ratingClass }}">
                                        {{ number_format($rating, 1) }}/5.0
                                    </span>
                                </td>
                                <td>{{ $count }} {{ $count === 1 ? 'review' : 'reviews' }}</td>
                                <td>
                                    <span class="badge {{ $ratingClass === 'terrible' ? 'red' : ($ratingClass === 'poor' ? 'orange' : 'green') }}">
                                        {{ $ratingText }}
                                    </span>
                                </td>
                                <td>
                                    @if($tutor['is_terrible'])
                                        <button type="button" class="btn-message" onclick="openMessageModal({{ $tutor['id'] }}, '{{ addslashes($tutor['name']) }}')">
                                            Send Message
                                        </button>
                                    @else
                                        <button type="button" class="btn-message" disabled>
                                            Send Message
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--muted);padding:20px">No tutor ratings available yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @php $allReviews = $allReviews ?? collect(); @endphp
        @if($allReviews->count() > 0)
        <h2 style="margin-top:32px">All Reviews</h2>
        <div class="row" style="margin-top:18px">
            <div class="table" style="grid-column:1/-1">
                <table>
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Student</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allReviews as $review)
                            <tr>
                                <td>
                                    <strong>{{ $review->tutor ? $review->tutor->getFullName() : 'N/A' }}</strong>
                                </td>
                                <td>{{ $review->student ? $review->student->getFullName() : 'N/A' }}</td>
                                <td>
                                    <span class="rating-badge {{ $review->rating >= 4 ? 'excellent' : ($review->rating >= 3 ? 'good' : ($review->rating >= 2 ? 'average' : 'poor')) }}">
                                        {{ $review->rating }}/5
                                        <span class="stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                    </span>
                                </td>
                                <td>
                                    @if($review->comment)
                                        <div class="review-card">
                                            <div class="review-text">{{ $review->comment }}</div>
                                            @if($review->session)
                                                <div class="review-meta">Session: {{ \Carbon\Carbon::parse($review->session->date)->format('M d, Y') }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color:var(--muted)">No comment</span>
                                    @endif
                                </td>
                                <td>{{ $review->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--muted);padding:20px">No reviews yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </main>

    <!-- Message Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Send Message to Tutor</h3>
                <button class="close-modal" onclick="closeMessageModal()">&times;</button>
            </div>
            <form id="messageForm" onsubmit="sendMessage(event)">
                <input type="hidden" id="tutorId" name="tutor_id">
                <div class="form-group">
                    <label for="tutorName">Tutor:</label>
                    <input type="text" id="tutorName" readonly style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;background:#f9fafb">
                </div>
                <div class="form-group">
                    <label for="messageText">Message:</label>
                    <textarea id="messageText" name="message" required placeholder="Enter your message to the tutor regarding their ratings..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeMessageModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openMessageModal(tutorId, tutorName) {
            document.getElementById('tutorId').value = tutorId;
            document.getElementById('tutorName').value = tutorName;
            document.getElementById('messageModal').style.display = 'flex';
        }

        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
            document.getElementById('messageForm').reset();
        }

        function sendMessage(event) {
            event.preventDefault();
            const tutorId = document.getElementById('tutorId').value;
            const message = document.getElementById('messageText').value;

            fetch('{{ route("admin.message.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tutor_id: tutorId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Message sent successfully!');
                    closeMessageModal();
                } else {
                    alert('Error: ' + (data.message || 'Failed to send message'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the message');
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target == modal) {
                closeMessageModal();
            }
        }

        // Filter functionality
        function filterTable() {
            const statusFilter = document.getElementById('statusFilter').value;
            const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#ratingsTableBody tr');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                
                let showRow = true;

                // Filter by status
                if (statusFilter !== 'all' && status !== statusFilter) {
                    showRow = false;
                }

                // Filter by search
                if (searchFilter && !name.includes(searchFilter) && !email.includes(searchFilter)) {
                    showRow = false;
                }

                if (showRow) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        function resetFilters() {
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('searchFilter').value = '';
            filterTable();
        }
    </script>
</body>
</html>

