<header id="page-topbar">
<div class="navbar-header">
<div class="d-flex">
<div class="navbar-brand-box">
    <a href="#" class="logo logo-dark">
        <span class="logo-sm">
            <img src="{{asset('/assets')}}/img/banoun.png" alt="" style="width: 100%;" height="22">
        </span>
        <span class="logo-lg">
            <img src="{{asset('/assets')}}/img/banoun.png" alt="" height="38">
        </span>
    </a>
    <a href="#" class="logo logo-light">
        <span class="logo-sm">
            <img src="{{asset('/assets')}}/img/banoun.png" alt="" style="width: 100%;" height="22">
        </span>
        <span class="logo-lg">
            <img src="{{asset('/assets')}}/img/banoun.png" alt="" height="38">
        </span>
    </a>
</div>
<button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
    <i class="fa fa-fw fa-bars"></i>
</button>

<form class="app-search d-none d-lg-block">
    <div class="position-relative">
        <input type="text" class="form-control" placeholder="Search...">
        <span class="bx bx-search-alt"></span>
    </div>
</form>
</div>

<div class="d-flex">

<div class="dropdown d-inline-block d-lg-none ms-2">
    <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-magnify"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
        aria-labelledby="page-header-search-dropdown">
        <form class="p-3">
            <div class="form-group m-0">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="dropdown d-none d-lg-inline-block ms-1">
    <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
        <i class="bx bx-fullscreen"></i>
    </button>
</div>

<div class="dropdown d-inline-block">
    <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bx bx-bell bx-tada"></i>
        <span class="badge bg-danger rounded-pill" id="notification-badge" style="display: none;">0</span>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
        aria-labelledby="page-header-notifications-dropdown">
        <div class="p-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0" key="t-notifications"> Notifications </h6>
                </div>
                <!-- <div class="col-auto">
                    <a href="#!" class="small" key="t-view-all"> View All</a>
                </div> -->
            </div>
        </div>
        <div data-simplebar style="max-height: 230px;" id="notification-container">
            <!-- Notifications will be loaded here -->
        </div>
        <div class="p-2 border-top d-grid">
            <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('order') }}">
                <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View More..</span> 
            </a>
        </div>
    </div>
</div>

<div class="dropdown d-inline-block">
    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img class="rounded-circle header-profile-user" src="{{asset('/assets')}}/img/avatar-2.png"
            alt="Header Avatar">
        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> 
            <span key="t-logout">{{ __('Logout') }}</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>

</div>
</div>
</header>

<script>
    function loadNotifications() {
        fetch('/admin/notifications', {  // Changed to web route
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'  // Important for session cookies
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationUI(data.notifications, data.unread_count);
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
        });
    }

    function updateNotificationUI(notifications, unread_count) {
        const badge = document.getElementById('notification-badge');
        if (unread_count > 0) {
            badge.textContent = unread_count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
        
        const container = document.getElementById('notification-container');
        
        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="text-center p-4">
                    <p class="text-muted mb-0">No notifications</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = '';
        
        notifications.forEach(notif => {
            const notifElement = document.createElement('a');
            notifElement.href = notif.link || 'javascript:void(0);';
            notifElement.className = `text-reset notification-item ${notif.is_read ? '' : 'bg-light'}`;
            notifElement.onclick = function(e) {
                if (!notif.is_read) {
                    e.preventDefault();
                    markAsRead(notif.id, notif.link);
                }
            };
            
            notifElement.innerHTML = `
                <div class="d-flex">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                            <i class="bx ${notif.icon || 'bx-bell'}"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${notif.title}</h6>
                        <div class="font-size-12 text-muted">
                            <p class="mb-1">${notif.message}</p>
                            <p class="mb-0">
                                <i class="mdi mdi-clock-outline"></i> 
                                <span>${notif.time_ago}</span>
                            </p>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(notifElement);
        });
    }

    function markAsRead(notificationId, link) {
        fetch('/admin/notifications/read', {  // Changed to web route
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ notification_id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                if (link && link !== 'javascript:void(0);') {
                    window.location.href = link;
                }
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }

    // Load notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    
    // Initial load
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
    });
</script>