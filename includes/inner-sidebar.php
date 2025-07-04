<style>
    .inner-sidebar {
            width: 250px;
            background-color: transparent;
            /* border-right: 1px solid #ddd; */
            padding: 0; /* Remove padding */
        }

        .sidebar-menu {
            list-style-type: none; /* Remove bullets */
            padding: 0; /* Remove padding from the list */
            margin: 0; /* Remove margin from the list */
        }

        .menu-item {
            margin-bottom: 5px; /* No margin between items */
        }

        .menu-item:hover {
            background-color: none;
        }

        .menu-link {
            display: block; /* Make the link take full width */
            padding: 10px 15px; /* Adjust padding for compact look */
            font-weight: bold;
            color: #333;
            text-decoration: none; /* Remove underline */
            border-radius: 2px;
            line-height: 1.2; /* Control line height */
            /* transition: background-color 0.3s; */
        }

        .menu-link:hover {
            background-color: #f5f5f5;
            color: black;
            border-radius: 10px;
        }

        /* Active link styling */
        .menu-link.active {
            text-decoration: underline;
            color: black; /* Highlight active link color */
        }
</style>

<!-- Left Sidebar -->
<div class="inner-sidebar">
    <ul class="sidebar-menu">
        <li class="menu-item">
            <a 
                class="menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'update-profile.php' ? 'active' : ''; ?>" 
                href="update-profile.php"
            >
                Kemaskini Profil
            </a>
        </li>
        <li class="menu-item">
            <a 
                class="menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'update-password.php' ? 'active' : ''; ?>" 
                href="update-password.php"
            >
                Kemaskini Katalaluan
            </a>
        </li>
    </ul>
</div>