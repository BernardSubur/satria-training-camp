<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Member Area - STC' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/images/favicon-stc.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #e0e7ff;
            --dark: #0f172a;
            --light: #f8fafc;
            --text-muted: #64748b;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        .topbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1030;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--dark);
        }

        .topbar-brand img {
            width: 40px;
            border-radius: 8px;
        }

        .user-badge {
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .role-tag {
            font-size: 0.75rem;
            background: white;
            padding: 2px 8px;
            border-radius: 10px;
            color: var(--primary);
        }

        .sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - 70px);
            background: white;
            position: fixed;
            top: 70px;
            left: 0;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 1020;
        }

        .sidebar-menu {
            padding: 1.5rem 1rem;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .sidebar-menu a i {
            font-size: 1.25rem;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .btn-logout {
            width: 100%;
            background: #fef2f2;
            color: #ef4444;
            border: none;
            padding: 10px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: white;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }

        #chat-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: 50%;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 999;
            transition: transform 0.2s;
        }

        #chat-toggle:hover {
            transform: scale(1.1);
        }

        .chat-badge {
            position: absolute; top: -2px; right: -2px;
            width: 12px; height: 12px; background: #ef4444;
            border-radius: 50%; border: 2px solid white;
        }

        #chat-popup {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 380px;
            height: 520px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.18);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 1050;
            animation: chatSlideUp 0.3s ease;
            max-width: calc(100vw - 40px);
        }

        @media (max-width: 576px) {
            #chat-popup {
                right: 20px;
                bottom: 90px;
                width: calc(100vw - 40px);
                height: 70vh;
                border-radius: 16px;
            }
            #chat-toggle {
                right: 20px;
                bottom: 20px;
                width: 55px;
                height: 55px;
            }
        }

        @keyframes chatSlideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .chat-header {
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: white;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        #chat-box {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 10px;
            scroll-behavior: smooth;
        }

        .message {
            padding: 10px 14px;
            border-radius: 16px;
            max-width: 85%;
            font-size: 0.88rem;
            line-height: 1.55;
            animation: msgFadeIn 0.2s ease;
            white-space: pre-line;
            word-wrap: break-word;
        }

        @keyframes msgFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .user {
            background: var(--primary); color: white;
            align-self: flex-end; border-bottom-right-radius: 4px;
        }

        .bot {
            background: white; color: var(--dark);
            align-self: flex-start; border-bottom-left-radius: 4px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .bot-avatar-row {
            display: flex; align-items: center; gap: 6px; margin-bottom: 6px;
        }

        .bot-avatar {
            width: 22px; height: 22px; border-radius: 6px; background: #e0e7ff; padding: 2px;
        }

        .bot-label {
            font-size: 0.72rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;
        }

        .quick-replies {
            display: flex; flex-wrap: wrap; gap: 6px; align-self: flex-start;
        }

        .quick-replies button {
            background: white; color: var(--primary); border: 1px solid #c7d2fe;
            border-radius: 20px; padding: 6px 14px; font-size: 0.78rem;
            font-weight: 600; cursor: pointer; transition: all 0.15s; font-family: 'Outfit', sans-serif;
        }

        .quick-replies button:hover {
            background: var(--primary); color: white; border-color: var(--primary);
        }
/
        .typing-indicator {
            display: flex; align-items: center; gap: 5px;
            padding: 12px 16px; align-self: flex-start;
            background: white; border: 1px solid #e2e8f0;
            border-radius: 16px; border-bottom-left-radius: 4px;
        }

        .typing-indicator .dot {
            width: 7px; height: 7px; background: #94a3b8;
            border-radius: 50%; animation: dotBounce 1.4s infinite;
        }

        .typing-indicator .dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator .dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes dotBounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        .chat-input {
            display: flex; border-top: 1px solid #e2e8f0; padding: 10px; background: white; gap: 8px;
        }

        .chat-input input {
            flex: 1; border: 1px solid #e2e8f0; padding: 10px 16px;
            background: #f8fafc; border-radius: 24px; outline: none;
            font-family: 'Outfit', sans-serif; font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .chat-input input:focus { border-color: var(--primary); background: white; }

        .chat-input button {
            background: var(--primary); border: none; color: white;
            font-size: 1.1rem; padding: 0 14px; border-radius: 50%;
            width: 42px; height: 42px; display: flex; align-items: center;
            justify-content: center; cursor: pointer; transition: background 0.2s;
        }

        .chat-input button:hover { background: var(--primary-dark, #3730a3); }

        .sidebar-toggle-btn {
            background: rgba(79, 70, 229, 0.1);
            border: none;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.2s;
        }
        .sidebar-toggle-btn:hover {
            background: rgba(79, 70, 229, 0.2);
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1.5rem 1rem; }
            .sidebar-toggle-btn { display: flex; }
            .brand-text { display: none; }
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);
            margin-bottom: 24px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        @media (max-width: 768px) {
            .content-card {
                padding: 20px;
                border-radius: 16px;
                margin-bottom: 16px;
            }
            .row.g-4 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }
        }

        .custom-overlay {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px); z-index: 9999;
            display: flex; align-items: center; justify-content: center;
        }
    </style>
</head>
<body>

    <div id="mobileOverlay" class="custom-overlay" style="display: none; z-index: 1015;"></div>

    <nav class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle-btn" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <a href="#" class="topbar-brand">
                <img src="{{ asset('asset/images/logo-stc.png') }}" alt="Logo">
                <div class="brand-text">
                    <h6 class="mb-0 fw-bold">STC Dashboard</h6>
                </div>
            </a>
        </div>

        @auth
        <div class="user-badge">
            <i class="bi bi-person-circle fs-5"></i>
            <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
            <span class="role-tag d-none d-md-inline">
                {{ Auth::user()->role == 'member_private' ? 'Private' : 'Member' }}
            </span>
        </div>
        @endauth
    </nav>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <div class="text-muted small fw-bold mb-3 px-3">MENU UTAMA</div>
            
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Beranda
            </a>
            
            <a href="{{ route('reservasi') }}" class="{{ request()->routeIs('reservasi') ? 'active' : '' }}">
                <i class="bi bi-calendar2-check"></i> Reservasi Jadwal
            </a>

            <div class="text-muted small fw-bold mb-3 px-3 mt-4">PENGATURAN</div>

            <a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Profil Saya
            </a>
            
            <a href="{{ route('setting') }}" class="{{ request()->routeIs('setting') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Setelan Akun
            </a>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <div id="chat-toggle" title="Sobat STC (AI)">
        <i class="bi bi-chat-dots fs-3 text-white"></i>
        <span class="chat-badge" id="chatBadge" style="display:none;"></span>
    </div>

    <div id="chat-popup">

        <div class="chat-header">
            <img src="{{ asset('asset/images/logo-stc.png') }}" alt="STC" style="width: 36px; border-radius: 8px; background: white; padding: 3px;">
            <div>
                <div class="fw-bold" style="font-size: 0.95rem;">Sobat STC (AI)</div>
                <div style="font-size: 0.7rem; font-weight: 400; opacity: 0.75;">Asisten Pelatih Online</div>
            </div>
            <button type="button" class="btn-close btn-close-white ms-auto" id="closeChat" style="font-size: 0.7rem;"></button>
        </div>

        <div id="chat-box">
            <div class="message bot">
                <div class="bot-avatar-row">
                    <img src="{{ asset('asset/images/logo-stc.png') }}" class="bot-avatar" alt="AI">
                    <span class="bot-label">Sobat STC</span>
                </div>
                Halo {{ Auth::user()->name ?? '' }}! 👋<br>Saya <strong>Sobat STC</strong>, asisten pelatih online kamu. Ada yang bisa saya bantu? 💪
            </div>

            <div class="quick-replies">
                <button onclick="quickSend('Sisa sesi saya berapa?')">💪 Sisa Sesi</button>
                <button onclick="quickSend('Jadwal latihan saya')">📅 Jadwal Saya</button>
                <button onclick="quickSend('Info paket dan harga')">💰 Info Paket</button>
                <button onclick="quickSend('Cara reservasi')">📝 Cara Reservasi</button>
            </div>
        </div>

        <div class="chat-input">
            <input type="text" id="chat-input" placeholder="Ketik pertanyaan kamu..." autocomplete="off">
            <button onclick="sendMessage()" id="sendBtn" title="Kirim">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>

        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            if(sidebar.classList.contains('show')) {
                mobileOverlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            } else {
                mobileOverlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        mobileOverlay.addEventListener('click', toggleSidebar);

        const chatToggle = document.getElementById('chat-toggle');
        const chatPopup = document.getElementById('chat-popup');
        const closeChat = document.getElementById('closeChat');
        const chatInput = document.getElementById('chat-input');
        const chatBox = document.getElementById('chat-box');
        let isSending = false;

        chatToggle.onclick = () => {
            chatPopup.style.display = 'flex';
            document.getElementById('chatBadge').style.display = 'none';
            chatInput.focus();
        };
        closeChat.onclick = () => chatPopup.style.display = 'none';

        chatInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter" && !isSending) sendMessage();
        });

        function quickSend(text) {
            chatInput.value = text;
            const qr = document.querySelector('.quick-replies');
            if (qr) qr.remove();
            sendMessage();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatBotResponse(text) {

            text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/\n/g, '<br>');
            return text;
        }

        function addTypingIndicator() {
            const typing = document.createElement('div');
            typing.className = 'typing-indicator';
            typing.id = 'typingIndicator';
            typing.innerHTML = '<div class="dot"></div><div class="dot"></div><div class="dot"></div>';
            chatBox.appendChild(typing);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function removeTypingIndicator() {
            const typing = document.getElementById('typingIndicator');
            if (typing) typing.remove();
        }

        function addBotMessage(text) {
            const logoUrl = '{{ asset("asset/images/logo-stc.png") }}';
            const msg = document.createElement('div');
            msg.className = 'message bot';
            msg.innerHTML = `
                <div class="bot-avatar-row">
                    <img src="${logoUrl}" class="bot-avatar" alt="AI">
                    <span class="bot-label">Sobat STC</span>
                </div>
                ${formatBotResponse(text)}
            `;
            chatBox.appendChild(msg);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function sendMessage() {
            let message = chatInput.value.trim();
            if (!message || isSending) return;
            isSending = true;

            const userMsg = document.createElement('div');
            userMsg.className = 'message user';
            userMsg.textContent = message;
            chatBox.appendChild(userMsg);

            chatInput.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            addTypingIndicator();

            fetch('{{ route("chatbot") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message })
            })
            .then(async res => {
                if (!res.ok) {
                    const errorData = await res.json().catch(() => ({}));
                    throw new Error(errorData.reply || `HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                removeTypingIndicator();
                addBotMessage(data.reply);
                isSending = false;
            })
            .catch(err => {
                console.error('Chatbot Error:', err);
                removeTypingIndicator();
                addBotMessage(err.message || 'Maaf, terjadi kesalahan sistem. Coba lagi nanti ya 🙏');
                isSending = false;
            });
        }
    </script>

    @if(auth()->check() && !auth()->user()->is_profile_complete && !request()->routeIs('profil'))
    <div class="custom-overlay">
        <div class="content-card text-center" style="width: 400px; max-width: 90%; animation: slideDown 0.3s ease;">
            <div class="mb-3">
                <div class="d-inline-flex bg-warning bg-opacity-10 text-warning p-3 rounded-circle mb-3">
                    <i class="bi bi-person-exclamation fs-1"></i>
                </div>
                <h4 class="fw-bold">Lengkapi Profil Anda</h4>
            </div>
            <p class="text-muted mb-4">Mohon lengkapi data diri Anda secara benar sebelum dapat menggunakan fitur reservasi dan lainnya.</p>
            <a href="{{ route('profil') }}" class="btn btn-primary w-100 py-2 fw-bold">Isi Profil Sekarang</a>
        </div>
    </div>
    <script>document.body.style.overflow = 'hidden';</script>
    <style>@keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }</style>
    @endif

    @if(isset($popup) && $popup)
    <div class="custom-overlay" id="membershipPopupOverlay">
        <div class="content-card text-center" style="width: 400px; max-width: 90%; animation: slideDown 0.3s ease;">
            <div class="mb-3">
                <div class="d-inline-flex bg-danger bg-opacity-10 text-danger p-3 rounded-circle mb-3">
                    <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                </div>
                <h4 class="fw-bold">Akses Dibatasi</h4>
            </div>
            <p class="text-muted mb-2">{{ $popup['message'] }}</p>
            
            @if($popup['type'] == 'expired')
                <p class="fw-bold mb-4">Masa aktif paket Anda telah habis.</p>
            @elseif($popup['type'] == 'sesi_habis')
                <p class="fw-bold mb-4">Sesi latihan Anda telah habis (0).</p>
            @endif

            <a href="{{ route('paket') }}" class="btn btn-primary w-100 py-2 fw-bold mb-2">Beli Paket Baru</a>
            <button onclick="document.getElementById('membershipPopupOverlay').style.display='none'; document.body.style.overflow='';" class="btn btn-light w-100">Tutup</button>
        </div>
    </div>
    <script>document.body.style.overflow = 'hidden';</script>
    @endif

</body>
</html>