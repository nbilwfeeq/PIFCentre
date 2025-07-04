@import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");

:root {
    --header-height: 3rem;
    --nav-width: 68px;
    --body-font: "Nunito", sans-serif;
    --normal-font-size: 1rem;
    --z-fixed: 1030;
    --tortilla: #351E10;
    --cedar: #997950;
    --expresso: #4B382A;
    --orange: #f9a241;
    --backup-color: black;
    --white-main: white;
}

*, ::before, ::after {
    box-sizing: border-box;
}

body {
    position: relative;
    margin: var(--header-height) 0 0 0;
    padding: 0 1rem;
    transition: margin-left 0.5s, padding-left 0.5s;
    font-family: "Quicksand";
    background-image: url(images/bg.jpg);
}

video {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: -1;
}

a {
    text-decoration: none;
}

.header {
    width: 100%;
    height: var(--header-height);
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    background-image: url('images/banner.png');
    z-index: var(--z-fixed);
    transition: padding-left 0.5s;
}

.header .search-bar {
    width: 165vh;
    flex-grow: 1;
    margin-left: 0;
}

.header img {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    margin-right: 5vh;
}

.header .profile {
    display: flex;
    align-items: center;
}

.header .profile img {
    margin-right: 0vh;
}

.profile img:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.header_toggle {
    display: none;
}

.header_content {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: black;
    flex-wrap: wrap;
    justify-content: space-between;
    width: 100%;
    transition: margin-left 0.5s;
}

.header_text {
    font-size: 1vh;
    margin-right: 10px;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    border: none;
}
.btn-secondary:hover {
    background-color: #5a6268;
}

.btn-primary {
    background-color: #997950;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: transparent;
    color: #997950;
    border: 1px solid #997950;
}

.btn-primary:active {
    background-color: #997950;
    color: white;
    border: 1px solid #997950;
}

.header_img {
    width: 35px;
    height: 35px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    overflow: hidden;
}

.header_img img {
    width: 100%;
    height: auto;
}

.l-navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--nav-width);
    height: 100vh;
    background-color: white;
    padding: 0.5rem 1rem 0 0;
    transition: width 0.5s; 
    z-index: var(--z-fixed);
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.nav {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}

.nav_logo,
.nav_link,
.nav_link-notification {
    display: grid;
    grid-template-columns: max-content max-content;
    align-items: center;
    column-gap: 1rem;
    padding: 0.5rem 0 0.5rem 1.5rem;
}

.nav_logo {
    margin-bottom: 2rem;
}

.nav_logo-icon {
    font-size: 1.25rem;
    color: var(--light-cream);
}

.nav_logo-name {
    color: var(--light-cream);
    font-weight: 700;
}

.nav_link {
    position: relative; 
    display: flex;
    align-items: center;
    text-decoration: none;
    color: black;
    margin-bottom: 1.5rem;
    transition: 0.3s;
    border: 0;
    background-color: none;
    background: none;
}

.nav_link:hover {
    color: var(--light-cream);
}

.nav_link-notification {
    position: relative; 
    display: flex;
    align-items: center;
    text-decoration: none;
    color: black;
    margin-bottom: 1.5rem;
    transition: 0.3s;
    border: 0;
    background-color: none;
    background: none;
}

.nav_link-notification:hover {
    color: var(--light-cream);
}

.nav_icon {
    font-size: 1.25rem;
}

.nav_img {
    width: 20px;
}

.tooltip {
    position: absolute;
    top: 50%;
    left: 110%; 
    transform: translateY(-50%);
    background-color: var(--backup-color); 
    color: var(--white-main);
    font-size: 0.9rem;
    padding: 5px 8px; 
    border-radius: 4px; 
    white-space: nowrap;
    visibility: hidden; 
    opacity: 0; 
    z-index: 1000; 
    transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
}

.nav_link:hover .tooltip:hover {
    visibility: visible; 
    opacity: 1; 
}

.show {
    left: 0;
}

.active {
    color: var(--light-cream);
}

.active::before {
    content: "";
    position: absolute;
    left: 0;
    width: 2px;
    height: 32px;
    background-color: var(--light-cream);
}

.height-100 {
    height: 100vh;
}

.l-navbar.show {
    width: calc(var(--nav-width) + 156px); /* Adjust sidebar width on large screens */
}

.body-pd {
    padding-left: var(--nav-width);
    transition: padding-left 0.5s; /* Smooth transition for padding */
}

.l-navbar.show + .body-pd {
    padding-left: calc(var(--nav-width) + 156px); /* Adjust padding to account for expanded sidebar */
}

.header {
    transition: padding-left 0.5s;
}

.header_toggle {
    transition: transform 0.5s;
}

/* Dropdown button */
.dropdown-btn {
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.dropdown-btn img {
    border-radius: 50%;
    width: 40px;
    height: 40px;
}

.dropdown-btn i {
    font-size: 30px;
    color: black;
    transition: transform 0.3s ease;
}

.dropdown-btn i:hover {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 50%;
}

/* Rotate arrow when dropdown is active */
.dropdown-btn.active i {
    transform: rotate(180deg);
}

/* Dropdown content */
.dropdown-content {
    display: none;
    position: absolute;
    top: 110%;
    right: 0;
    margin-left: 150vh;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 20px;
    padding: 10px;
    z-index: 1000;
    width: 400px;
}

/* Current account styling */
.current-account {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 5px 10px 5px;
    border-radius: 5px;
}

.current-account img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
}

.current-account p {
    margin: 0;
    font-weight: bold;
    font-size: 2vh;
}

.current-account span {
    font-size: 0.9rem;
    color: gray;
}

.dropdown-content hr {
    margin: 10px 0;
    border: none;
    border-top: 1px solid #ddd;
}

/* Dropdown links */
.dropdown-content a {
    text-decoration: none;
    color: black;
    display: block;
    padding: 8px 0;
    transition: color 0.3s;
    font-size: 2vh;
    border-radius: 5px;
    padding: 10px 10px 10px 10px;
}

.dropdown-content a:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

/* Show dropdown on button click */
.dropdown-content.show {
    display: block;
}

.notification-btn {
    position: relative;
}

.notification-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: red;
    color: white;
    font-size: 12px;
    font-weight: bold;
    border-radius: 50%;
    padding: 2px 6px;
    line-height: 1;
    text-align: center;
    min-width: 20px;
}

/* Notification dropdown */
.notification-dropdown {
    position: absolute;
    top: 30%;
    left: calc(var(--nav-width) + 10px);
    transform: translateY(-50%);
    background-color: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    width: 300px;
    display: none;
    z-index: 1100;
    padding: 10px;
}

.notification-dropdown.show {
    display: block;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #ddd;
}

.notification-header button {
    background: none;
    border: none;
    color: var(--expresso);
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: bold;
}

.notification-list {
    list-style: none;
    margin: 0;
    padding: 0;
    max-height: 200px;
    overflow-y: auto;
}

.notification-list li {
    border-bottom: 1px solid #ddd;
    font-size: 0.9rem;
    color: var(--backup-color);
    padding: 10px 5px 10px 10px;
    border-radius: 10px;
    margin-top: 10px;
}

.notification-list li:last-child {
    border-bottom: none;
}

.notification-list li:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.delete-btn {
    background: none;
    border: none;
    color: red;
    font-size: 12px;
    cursor: pointer;
    float: right;
    margin-left: 10px;
}

.delete-btn:hover {
    color: darkred;
}

@media screen and (min-width: 768px) {
    body {
        margin: calc(var(--header-height) + 1rem) 0 0 0;
        padding-left: calc(var(--nav-width) + 2rem);
    }

    .header {
        height: calc(var(--header-height) + 1rem);
        padding-left: calc(var(--nav-width) + 2rem);
    }

    .header_content {
        font-size: 1.25rem;
    }

    .header_text {
        font-size: 1rem;
        margin-right: 30px;
        color: black;
    }

    .header_img {
        width: 60px;
        height: 60px;
    }

    .l-navbar.show {
        width: calc(var(--nav-width) + 156px);
    }

    .body-pd {
        padding-left: calc(var(--nav-width) + 156px);
    }
}

@media screen and (max-width: 767px) {
    .l-navbar {
        width: var(--nav-width);
        height: 100vh;
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        z-index: var(--z-fixed);
        transition: width 0.5s; /* Smooth transition for sidebar width */
    }

    .header {
        height: var(--header-height);
        padding-left: var(--nav-width);
    }

    .header .search-bar {
        display: none;
    }

    .header .profile {
        display: flex;
        align-items: center;
    }

    .header_toggle {
        display: block;
        position: absolute;
        font-size: 35px;
        top: 0;
        left: 0;
        transform: translateX(0); /* Initial position */
        transition: transform 0.5s; /* Smooth transition for button movement */
        z-index: var(--z-fixed);
    }

    .header_img {
        display: block;
        margin-left: auto;
    }

    /* Dropdown content */
    .dropdown-content {
        display: none;
        position: absolute;
        top: 110%;
        right: 0;
        margin-left: 150vh;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 20px;
        padding: 10px;
        z-index: 1000;
        width: 400px;
    }

    /* Current account styling */
    .current-account {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 5px 10px 5px;
        border-radius: 5px;
    }

    .current-account img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
    }

    .current-account p {
        margin: 0;
        font-weight: bold;
        font-size: 2vh;
    }

    .current-account span {
        font-size: 0.9rem;
        color: gray;
    }

    .dropdown-content hr {
        margin: 10px 0;
        border: none;
        border-top: 1px solid #ddd;
    }

    /* Dropdown links */
    .dropdown-content a {
        text-decoration: none;
        color: black;
        display: block;
        padding: 8px 0;
        transition: color 0.3s;
        font-size: 2vh;
        border-radius: 5px;
        padding: 10px 5px 10px 5px;
    }

    .dropdown-content a:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }

    /* Show dropdown on button click */
    .dropdown-content.show {
        display: block;
    }

    .l-navbar.show {
        display: block;
        width: var(--nav-width);
        transition: width 0.5s; /* Smooth transition for width */
    }

    .body-pd {
        padding-left: var(--nav-width);
        transition: padding-left 0.5s; /* Smooth transition for padding */
    }

    .l-navbar.show ~ .body-pd {
        padding-left: calc(var(--nav-width) + 10px); /* Adjust padding for expanded sidebar */
    }

    .l-navbar.show ~ .header_toggle {
        transform: translateX(calc(var(--nav-width) + 10px)); /* Move toggle button to accommodate expanded sidebar */
    }
}