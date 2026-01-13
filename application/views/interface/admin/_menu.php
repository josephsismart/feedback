<style>
    /* Dark overlay */
    #menuOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        z-index: 1040;
    }

    /* Side menu */
    #sideMenu {
        position: fixed;
        top: 0;
        right: -300px;
        width: 300px;
        height: 100%;
        background: #fff;
        box-shadow: -4px 0 10px rgba(0, 0, 0, 0.15);
        transition: right 0.3s ease;
        z-index: 1050;
    }

    #sideMenu.active {
        right: 0;
    }

    /* Header */
    .menu-header {
        padding: 15px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #sideMenu a {
        display: block;
        width: 100%;
        text-decoration: none;
        color: #333;
    }

    #sideMenu .list-group-item:hover {
        background: #f8f9fa;
    }
</style>


<div id="menuOverlay"></div>

<!-- SIDE MENU -->
<div id="sideMenu">
    <div class="menu-header">
        <span class="font-weight-bold">Menu</span>
        <button class="btn btn-sm btn-light" id="closeMenu">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <ul class="list-group list-group-flush">
        <li class="list-group-item <?= $current_location == 'dashboard' ? 'bg-success' : '' ?>">
            <a href="<?= base_url('admin/dashboard') ?>" class="<?= $current_location == 'dashboard' ? 'text-white' : '' ?>">
                <i class="fas fa-chart-bar me-2"></i> Dashboard
            </a>
        </li>

        <li class="list-group-item <?= $current_location == 'report' ? 'bg-success' : '' ?>">
            <a href="<?= base_url('admin/report') ?>" class="<?= $current_location == 'report' ? 'text-white' : '' ?>">
                <i class="fas fa-chart-bar me-2"></i> Report
            </a>
        </li>

        <li class="list-group-item <?= $current_location == 'controller' ? 'bg-success' : '' ?>">
            <a href="<?= base_url('admin/controller') ?>" class="<?= $current_location == 'controller' ? 'text-white' : '' ?>">
                <i class="fas fa-cog fa-spin me-2"></i> Contoller
            </a>
        </li>


        <li class="list-group-item text-danger">
            <a href="<?= base_url('logout') ?>" class="text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>

</div>

<script>
    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    const sideMenu = document.getElementById('sideMenu');
    const overlay = document.getElementById('menuOverlay');

    openMenu.onclick = () => {
        sideMenu.classList.add('active');
        overlay.style.display = 'block';
    };

    closeMenu.onclick = closeAll;
    overlay.onclick = closeAll;


    function closeAll() {
        sideMenu.classList.remove('active');
        overlay.style.display = 'none';
    }
</script>