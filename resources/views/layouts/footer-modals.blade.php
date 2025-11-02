<!-- Footer Modals -->
<!-- Privacy Policy Modal -->
<div id="privacy-modal" class="footer-modal">
    <div class="footer-modal-content">
        <div class="footer-modal-header">
            <h2>Privacy Policy</h2>
            <button class="footer-modal-close">&times;</button>
        </div>
        <div class="footer-modal-body">
            <p><strong>Last updated:</strong> May 30, 2025</p>
            
            <h3>1. Information We Collect</h3>
            <p>We collect the following types of information:</p>
            <ul>
                <li><strong>Personal Information:</strong> Name, email address, phone number, academic information</li>
                <li><strong>Usage Data:</strong> Session attendance, progress metrics, platform interactions</li>
                <li><strong>Technical Data:</strong> IP address, browser type, device information, cookies</li>
                <li><strong>Communication Data:</strong> Messages, feedback, and support requests</li>
            </ul>
            
            <h3>2. How We Use Your Information</h3>
            <p>We use your information to:</p>
            <ul>
                <li>Provide tutoring services and match you with appropriate tutors</li>
                <li>Process payments and manage your account</li>
                <li>Track your academic progress and generate reports</li>
                <li>Communicate with you about sessions and platform updates</li>
                <li>Improve our services and user experience</li>
                <li>Comply with legal obligations</li>
            </ul>
            
            <h3>3. Data Security</h3>
            <p>We implement industry-standard security measures to protect your personal information, including encryption, regular security audits, and access controls.</p>
            
            <h3>4. Your Rights</h3>
            <p>You have the right to access, correct, delete your personal information, and opt-out of marketing communications.</p>
            
            <h3>5. Contact Us</h3>
            <p>For questions about this Privacy Policy, contact us at:</p>
            <ul>
                <li>Email: <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a></li>
                <li>Phone: +63958667092</li>
                <li>Address: University of Cebu, Cebu City, Philippines</li>
            </ul>
        </div>
    </div>
</div>

<!-- Terms of Service Modal -->
<div id="terms-modal" class="footer-modal">
    <div class="footer-modal-content">
        <div class="footer-modal-header">
            <h2>Terms of Service</h2>
            <button class="footer-modal-close">&times;</button>
        </div>
        <div class="footer-modal-body">
            <p><strong>Last updated:</strong> May 30, 2025</p>
            
            <h3>1. Acceptance of Terms</h3>
            <p>By accessing and using MentorHub's services, you agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use our platform.</p>
            
            <h3>2. Description of Service</h3>
            <p>MentorHub is an online tutoring platform that connects students with qualified tutors. We provide:</p>
            <ul>
                <li>One-on-one tutoring sessions</li>
                <li>Group study sessions</li>
                <li>Educational resources and materials</li>
                <li>Progress tracking and reporting</li>
            </ul>
            
            <h3>3. User Responsibilities</h3>
            <p>As a user, you agree to:</p>
            <ul>
                <li>Provide accurate and complete registration information</li>
                <li>Maintain the confidentiality of your account credentials</li>
                <li>Treat tutors and other users with respect</li>
                <li>Use the platform only for educational purposes</li>
            </ul>
            
            <h3>4. Payment and Refunds</h3>
            <p>Payment for tutoring sessions is required in advance. Refunds may be provided in accordance with our refund policy, typically for sessions cancelled with at least 24 hours notice.</p>
            
            <h3>5. Contact Information</h3>
            <p>For questions about these Terms and Conditions, please contact us at <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a> or through our support channels.</p>
        </div>
    </div>
</div>

<!-- FAQ Modal -->
<div id="faq-modal" class="footer-modal">
    <div class="footer-modal-content">
        <div class="footer-modal-header">
            <h2>Frequently Asked Questions</h2>
            <button class="footer-modal-close">&times;</button>
        </div>
        <div class="footer-modal-body">
            @if(isset($userType) && $userType === 'tutor')
                <h3>How do I manage my bookings?</h3>
                <p>You can view and manage all your bookings in the "My Bookings" section. From there, you can accept, reject, or complete sessions.</p>
                
                <h3>How do I get paid for tutoring sessions?</h3>
                <p>Earnings from completed sessions are automatically added to your wallet. You can withdraw funds to your bank account through the Cash Out feature.</p>
                
                <h3>What happens if a student cancels a session?</h3>
                <p>Cancellations made more than 24 hours in advance are fully refunded. Cancellations within 24 hours may still compensate tutors depending on the circumstances.</p>
                
                <h3>How do I communicate with my students?</h3>
                <p>You can send messages directly to students through the messaging feature on the platform.</p>
                
                <h3>What should I do if I have a technical issue?</h3>
                <p>Please use the "Report a Problem" feature in your dashboard to submit a detailed report of any technical issues you encounter.</p>
            @else
                <h3>How do I book a tutoring session?</h3>
                <p>You can book a session by going to the "Book Session" page, selecting a tutor, choosing your preferred date and time, and confirming your booking.</p>
                
                <h3>What subjects are available for tutoring?</h3>
                <p>We offer tutoring in a wide range of subjects including Mathematics, Science, English, History, and more. Available subjects vary by tutor specialization.</p>
                
                <h3>Can I reschedule or cancel a session?</h3>
                <p>Yes, sessions can be rescheduled or cancelled up to 24 hours in advance. Cancellations made within 24 hours may be subject to charges.</p>
                
                <h3>How do payments work?</h3>
                <p>Payments are processed securely through our integrated payment system. You can add funds to your wallet and use them to pay for sessions and assignments.</p>
                
                <h3>What should I do if I have a technical issue?</h3>
                <p>Please use the "Report a Problem" feature in your dashboard to submit a detailed report of any technical issues you encounter.</p>
            @endif
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div id="contact-modal" class="footer-modal">
    <div class="footer-modal-content">
        <div class="footer-modal-header">
            <h2>Contact Us</h2>
            <button class="footer-modal-close">&times;</button>
        </div>
        <div class="footer-modal-body">
            <h3>Get in Touch</h3>
            <p>We're here to help! Reach out to us through any of the following channels:</p>
            
            <h3>Email</h3>
            <p>
                <strong>Email Us:</strong> <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a><br>
                <small style="color: #666;">We typically respond within 24 hours</small>
            </p>
            
            <h3>Phone</h3>
            <p>+63958667092</p>
            
            <h3>Address</h3>
            <p>University of Cebu<br>Cebu City, Philippines</p>
            
            <h3>Business Hours</h3>
            <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 3:00 PM<br>Sunday: Closed</p>
        </div>
    </div>
</div>
