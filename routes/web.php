<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\uiController\homeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TutorRegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TutorSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentSessionController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminUserController;

Route::get('/', [homeController::class, 'homePage'])->name('home');

Route::get('/signup', [homeController::class, 'signupPage'])->name('signup');

Route::get('/signup/tutor', function () {
    return view('tutor-signup');
})->name('tutor.signup');

Route::post('/register/student', [RegisterController::class, 'studentRegister'])->name('register.student');
Route::post('/register/tutor', [TutorRegisterController::class, 'tutorRegister'])->name('register.tutor');

// Email verification routes
Route::get('/verify-email', [App\Http\Controllers\EmailVerificationController::class, 'showVerificationForm'])->name('verify.email');
Route::post('/verify-email', [App\Http\Controllers\EmailVerificationController::class, 'verifyCode'])->name('verify.email');
Route::post('/resend-verification', [App\Http\Controllers\EmailVerificationController::class, 'resendCode'])->name('resend.verification');

Route::get('/login', [homeController::class, 'loginPage'])->name('login');
Route::get('/select-role', [homeController::class, 'selectRolePage'])->name('select-role');
Route::get('/select-role-login', function () {
    return view('select-role-login');
})->name('select-role-login');

Route::middleware('web')->group(function () {
    Route::get('/login/student', function () {
        return view('loginStudent');
    })->name('login.student');

    Route::post('/login/student', [LoginController::class, 'studentLogin'])->name('login.student.submit');

    Route::get('/login/tutor', function () {
        return view('loginTutor');
    })->name('login.tutor');

    Route::post('/login/tutor', [LoginController::class, 'tutorLogin'])->name('login.tutor.submit');
});

// Admin auth routes (ensure web middleware for sessions/CSRF)
Route::middleware('web')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/admin', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        // Admin users management
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users/toggle', [AdminUserController::class, 'toggleActive'])->name('admin.users.toggle');
        Route::get('/admin/users/{type}/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::get('/admin/users/{type}/{id}/detail', [AdminUserController::class, 'detail'])->name('admin.users.detail');

        // Admin wallet management
        Route::get('/admin/wallet', [App\Http\Controllers\AdminWalletController::class, 'index'])->name('admin.wallet.index');
        Route::get('/admin/wallet/transactions', [App\Http\Controllers\AdminWalletController::class, 'transactions'])->name('admin.wallet.transactions');
        Route::get('/admin/wallet/pending-payouts', [App\Http\Controllers\AdminWalletController::class, 'pendingPayouts'])->name('admin.wallet.pending-payouts');
        Route::post('/admin/wallet/payouts/{id}/approve', [App\Http\Controllers\AdminWalletController::class, 'approvePayout'])->name('admin.wallet.approve-payout');
        Route::post('/admin/wallet/payouts/{id}/reject', [App\Http\Controllers\AdminWalletController::class, 'rejectPayout'])->name('admin.wallet.reject-payout');
        Route::get('/admin/wallet/pending-cash-ins', [App\Http\Controllers\AdminWalletController::class, 'pendingCashIns'])->name('admin.wallet.pending-cash-ins');
        Route::post('/admin/wallet/cash-ins/{id}/approve', [App\Http\Controllers\AdminWalletController::class, 'approveCashIn'])->name('admin.wallet.approve-cash-in');
        Route::post('/admin/wallet/cash-ins/{id}/reject', [App\Http\Controllers\AdminWalletController::class, 'rejectCashIn'])->name('admin.wallet.reject-cash-in');
        Route::post('/admin/wallet/cash-ins/{id}/upload-proof', [App\Http\Controllers\AdminWalletController::class, 'uploadPaymentProof'])->name('admin.wallet.upload-payment-proof');
        Route::get('/admin/wallet/user-wallets', [App\Http\Controllers\AdminWalletController::class, 'userWallets'])->name('admin.wallet.user-wallets');
        Route::get('/admin/wallet/user-wallet/{userType}/{userId}', [App\Http\Controllers\AdminWalletController::class, 'showUserWallet'])->name('admin.wallet.user-wallet-detail');
        Route::post('/admin/wallet/manual-transaction', [App\Http\Controllers\AdminWalletController::class, 'manualTransaction'])->name('admin.wallet.manual-transaction');
    });
});

// Password reset routes
Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showForgotPassword'])->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendResetCode'])->name('password.email');

Route::get('/verify-code', [App\Http\Controllers\PasswordResetController::class, 'showVerifyCode'])->name('password.verify');

Route::post('/verify-code', [App\Http\Controllers\PasswordResetController::class, 'verifyCode'])->name('password.verify.submit');

Route::get('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'showResetPassword'])->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.update');

// Protected student routes
Route::middleware(['auth:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student-dashboard');
    })->name('student.dashboard');

    Route::get('/student/find-tutor', function () {
        return view('find-tutor');
    })->name('Findtutor');

    Route::get('/student/tutors', function () {
        return view('student.tutors.index');
    })->name('student.tutors.index');

    Route::get('/student/profile/edit', [StudentProfileController::class, 'edit'])->name('student.profile.edit');

    Route::put('/student/profile/update', [StudentProfileController::class, 'update'])->name('student.profile.update');

    Route::post('/student/logout', [LoginController::class, 'studentLogout'])->name('student.logout');

    // Booking routes
    Route::get('/student/book-session', [StudentSessionController::class, 'index'])->name('student.book-session');
    Route::post('/student/book-session/store', [StudentSessionController::class, 'store'])->name('student.book-session.store');
    Route::get('/student/my-bookings', [StudentSessionController::class, 'myBookings'])->name('student.my-bookings');
    Route::get('/student/tutor/{id}/details', [StudentSessionController::class, 'getTutorDetails'])->name('student.tutor.details');
    Route::get('/student/sessions/upcoming', [StudentSessionController::class, 'getUpcomingSessions'])->name('student.sessions.upcoming');
    Route::get('/student/schedule', [StudentSessionController::class, 'schedule'])->name('student.schedule');

    // Student messages route
    Route::get('/student/messages', [StudentSessionController::class, 'messages'])->name('student.messages');
    
    // Student activities routes
    Route::get('/student/my-sessions', [App\Http\Controllers\StudentActivityController::class, 'index'])->name('student.my-sessions');
    Route::get('/student/tutor/{tutor}/activities', [App\Http\Controllers\StudentActivityController::class, 'tutorActivities'])->name('student.tutor.activities');
    Route::get('/student/activities/{activity}', [App\Http\Controllers\StudentActivityController::class, 'show'])->name('student.activities.show');
    Route::post('/student/activities/{activity}/save-draft', [App\Http\Controllers\StudentActivityController::class, 'saveDraft'])->name('student.activities.save-draft');
    Route::post('/student/activities/{activity}/submit', [App\Http\Controllers\StudentActivityController::class, 'submit'])->name('student.activities.submit');
    Route::get('/student/activities/stats', [App\Http\Controllers\StudentActivityController::class, 'getProgressStats'])->name('student.activities.stats');
    Route::get('/student/tutor/{tutor}/progress', [App\Http\Controllers\StudentActivityController::class, 'getTutorProgress'])->name('student.tutor.progress');
    
        // Student wallet routes with enhanced security
        Route::middleware(['auth:student', 'wallet.security'])->group(function () {
            Route::get('/student/wallet', [App\Http\Controllers\SecureWalletController::class, 'index'])->name('student.wallet');
            Route::get('/student/wallet/cash-in', [App\Http\Controllers\SecureWalletController::class, 'showCashIn'])->name('student.wallet.cash-in');
            Route::post('/student/wallet/cash-in', [App\Http\Controllers\SecureWalletController::class, 'cashIn'])->name('student.wallet.cash-in.submit');
            Route::post('/student/wallet/internal-cash-in', [App\Http\Controllers\SecureWalletController::class, 'internalCashIn'])->name('student.wallet.internal-cash-in');
            Route::get('/student/wallet/cash-out', [App\Http\Controllers\SecureWalletController::class, 'showCashOut'])->name('student.wallet.cash-out');
            Route::post('/student/wallet/cash-out', [App\Http\Controllers\SecureWalletController::class, 'cashOut'])->name('student.wallet.cash-out.submit');
            Route::get('/student/wallet/balance', [App\Http\Controllers\SecureWalletController::class, 'getBalance'])->name('student.wallet.balance');
            Route::post('/student/wallet/upload-payment-proof', [App\Http\Controllers\SecureWalletController::class, 'uploadPaymentProof'])->name('student.wallet.upload-payment-proof');
        });
   
});

// Protected tutor routes
Route::middleware(['auth:tutor'])->group(function () {
    Route::get('/tutor/dashboard', function () {
        return view('tutor-dashboard', [
            'tutor' => Auth::guard('tutor')->user()
        ]);
    })->name('tutor.dashboard');

    Route::get('/tutor/profile/edit', [App\Http\Controllers\TutorProfileController::class, 'edit'])->name('tutor.profile.edit');
    Route::put('/tutor/profile/update', [App\Http\Controllers\TutorProfileController::class, 'update'])->name('tutor.profile.update');

    // Tutor booking management routes
    Route::get('/tutor/bookings', [TutorSessionController::class, 'index'])->name('tutor.bookings.index');
    Route::get('/tutor/bookings/{id}', [TutorSessionController::class, 'show'])->name('tutor.bookings.show');
    Route::post('/tutor/bookings/{id}/accept', [TutorSessionController::class, 'accept'])->name('tutor.bookings.accept');
    Route::post('/tutor/bookings/{id}/reject', [TutorSessionController::class, 'reject'])->name('tutor.bookings.reject');
    Route::post('/tutor/bookings/{id}/complete', [TutorSessionController::class, 'complete'])->name('tutor.bookings.complete');
    Route::post('/tutor/bookings/{id}/cancel', [TutorSessionController::class, 'cancel'])->name('tutor.bookings.cancel');
    
    // Tutor logout route
    Route::post('/tutor/logout', [LoginController::class, 'tutorLogout'])->name('tutor.logout');
    
    // API routes for dashboard
    Route::get('/tutor/sessions/today', [TutorSessionController::class, 'getTodaysSessions'])->name('tutor.sessions.today');
    Route::get('/tutor/sessions/upcoming', [TutorSessionController::class, 'getUpcomingSessions'])->name('tutor.sessions.upcoming');
    
    // Tutor messages route
    Route::get('/tutor/messages', [TutorSessionController::class, 'messages'])->name('tutor.messages');
    
    // Tutor My Sessions routes
    Route::get('/tutor/my-sessions', [App\Http\Controllers\TutorActivityController::class, 'index'])->name('tutor.my-sessions');
    Route::get('/tutor/activities/create', [App\Http\Controllers\TutorActivityController::class, 'create'])->name('tutor.activities.create');
    Route::post('/tutor/activities', [App\Http\Controllers\TutorActivityController::class, 'store'])->name('tutor.activities.store');
    Route::get('/tutor/activities/{activity}', [App\Http\Controllers\TutorActivityController::class, 'show'])->name('tutor.activities.show');
    Route::post('/tutor/activities/{activity}/grade', [App\Http\Controllers\TutorActivityController::class, 'grade'])->name('tutor.activities.grade');
    Route::get('/tutor/activities/stats', [App\Http\Controllers\TutorActivityController::class, 'getProgressStats'])->name('tutor.activities.stats');
    Route::get('/tutor/students', [App\Http\Controllers\TutorActivityController::class, 'students'])->name('tutor.students');
    Route::get('/tutor/students/{student}/progress', [App\Http\Controllers\TutorActivityController::class, 'getStudentProgress'])->name('tutor.students.progress');
    Route::get('/tutor/schedule', [App\Http\Controllers\TutorActivityController::class, 'schedule'])->name('tutor.schedule');
    
        // Tutor wallet routes with enhanced security
        Route::middleware(['auth:tutor', 'wallet.security'])->group(function () {
            Route::get('/tutor/wallet', [App\Http\Controllers\SecureWalletController::class, 'index'])->name('tutor.wallet');
            Route::get('/tutor/wallet/cash-in', [App\Http\Controllers\SecureWalletController::class, 'showCashIn'])->name('tutor.wallet.cash-in');
            Route::post('/tutor/wallet/cash-in', [App\Http\Controllers\SecureWalletController::class, 'cashIn'])->name('tutor.wallet.cash-in.submit');
            Route::post('/tutor/wallet/internal-cash-in', [App\Http\Controllers\SecureWalletController::class, 'internalCashIn'])->name('tutor.wallet.internal-cash-in');
            Route::get('/tutor/wallet/cash-out', [App\Http\Controllers\SecureWalletController::class, 'showCashOut'])->name('tutor.wallet.cash-out');
            Route::post('/tutor/wallet/cash-out', [App\Http\Controllers\SecureWalletController::class, 'cashOut'])->name('tutor.wallet.cash-out.submit');
            Route::get('/tutor/wallet/balance', [App\Http\Controllers\SecureWalletController::class, 'getBalance'])->name('tutor.wallet.balance');
            Route::post('/tutor/wallet/upload-payment-proof', [App\Http\Controllers\SecureWalletController::class, 'uploadPaymentProof'])->name('tutor.wallet.upload-payment-proof');
        });
});

// Test route to check sessions (remove in production)
Route::get('/test/sessions', function() {
    $sessions = \App\Models\Session::with(['student', 'tutor'])->get();
    return response()->json($sessions);
})->name('test.sessions');

// Debug route for messages (remove in production)
Route::get('/test/messages', function() {
    try {
        $tutorId = 1; // Assuming tutor ID 1 exists
        $students = \App\Models\Student::whereHas('sessions', function($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId);
        })->get();
        
        $messages = \App\Models\Message::where('sender_id', $tutorId)
            ->orWhere('receiver_id', $tutorId)
            ->get();
            
        return response()->json([
            'students' => $students,
            'messages' => $messages,
            'tutor_id' => $tutorId
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('test.messages');

// Payment callback routes (no auth required)
// Payment callback routes (no auth required for webhooks)
Route::get('/wallet/payment/success', [App\Http\Controllers\SecureWalletController::class, 'paymentSuccess'])->name('wallet.payment.success');
Route::get('/wallet/payment/failed', [App\Http\Controllers\SecureWalletController::class, 'paymentFailed'])->name('wallet.payment.failed');

// Webhook routes (no auth required)
Route::post('/webhooks/paymongo', [App\Http\Controllers\WebhookController::class, 'handlePayMongoWebhook'])->name('webhooks.paymongo');
Route::get('/webhooks/test', [App\Http\Controllers\WebhookController::class, 'testWebhook'])->name('webhooks.test');


