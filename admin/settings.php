<?php 
    include 'database/config.php';
    $page = 'Tetapan | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">
    
    <!-- ==================CSS=================-->
    <link rel="stylesheet" href="styles/dashboard-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    

    <title><?php echo $page; ?></title>
</head>
<style>
    .content {
        position: relative;
        min-height: 100vh;
    }

    .calendar {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .calendar-header button {
        background-color: grey;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
    }
    .calendar-header button:hover {
        background-color: transparent;
        border: 1px solid grey;
        color: grey;
    }
    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        margin-top: 10px;
    }
    .day {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
    }
    .day:hover {
        background-color: #f0f0f0;
    }
    .day.empty {
        background-color: #f9f9f9;
        cursor: default;
    }
    .disabled-day {
        color: #d3d3d3;
        pointer-events: none; /* Prevent click interactions */
        background-color: #f9f9f9;
    }
    .disabled-day:hover {
        background-color: #f9f9f9; /* Keep the same style on hover */
    }
</style>

<body onload="document.body.classList.add('loaded')">
    <div class="loader"></div>

    <?php include('includes/topbar.php'); ?>

    <div class="content container p-3">
        <h1>Tetapan Admin</h1>
        <p>Selamat Datang, Admin!</p>
        <div class="hr"><hr></div>
            <div class="row justify-content-center">

                <div id="calendar-container" class="mb-3">
                    <div class="calendar">
                        <div class="calendar-header">
                        <button id="prevMonth" type="button">Kembali</button>
                        <h2 id="currentMonth"></h2>
                        <button id="nextMonth" type="button">Seterusnya</button>
                        </div>
                        <div class="calendar-days" id="calendarDays"></div>
                    </div>
                </div>
                
                <button id="saveBlockedDates" class="btn btn-primary">Simpan Tarikh Tutup</button>

            </div>

        <!-- <?php include('includes/footer.php'); ?> -->

    </div>
    <!--Container Main end-->

    <!--=============== Bootstrap 5.2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!--=============== jQuery ===============-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!--=============== Datatables ===============-->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!--=============== SweetAlert2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--============== Counter Effects =========-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById('saveBlockedDates').addEventListener('click', () => {
            fetch('backend/save-blocked-dates.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `blocked_dates=${JSON.stringify(blockedDates)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Blocked dates saved successfully!');
                } else {
                    alert('Failed to save blocked dates.');
                }
            });
        });
    
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const calendarDays = document.getElementById('calendarDays');
        const currentMonth = document.getElementById('currentMonth');
        const prevMonth = document.getElementById('prevMonth');
        const nextMonth = document.getElementById('nextMonth');

        let selectedDate = new Date();
        let blockedDates = []; // Array to store blocked dates fetched from the server.

        // Fetch blocked dates from the server
        function fetchBlockedDates() {
            return fetch('backend/get-blocked-dates.php')
                .then(response => response.json())
                .then(data => {
                    blockedDates = data; // Populate the blockedDates array
                    renderAdminCalendar();
                })
                .catch(err => {
                    console.error('Error fetching blocked dates:', err);
                    alert('Failed to load blocked dates.');
                });
        }

        function renderAdminCalendar() {
            const month = selectedDate.getMonth();
            const year = selectedDate.getFullYear();
            const today = new Date(); // Current date

            currentMonth.textContent = `${selectedDate.toLocaleString('default', { month: 'long' })} ${year}`;
            calendarDays.innerHTML = '';

            const firstDayIndex = new Date(year, month, 1).getDay();
            const lastDay = new Date(year, month + 1, 0).getDate();

            // Add empty days for the first row
            for (let i = 0; i < firstDayIndex; i++) {
                const emptyDiv = document.createElement('div');
                emptyDiv.classList.add('day', 'empty');
                calendarDays.appendChild(emptyDiv);
            }

            // Add days of the month
            for (let i = 1; i <= lastDay; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('day');
                dayDiv.textContent = i;

                const date = new Date(year, month, i);
                const formattedDate = formatDate(date);

                // Check if it's a weekend (Saturday: 6, Sunday: 0)
                const isWeekend = date.getDay() === 6 || date.getDay() === 0;

                if (date < today && !isSameDay(date, today) || isWeekend) {
                    // Disable past dates (except today) and weekends
                    dayDiv.classList.add('disabled-day');
                } else {
                    // Highlight blocked dates from server
                    if (blockedDates.includes(formattedDate)) {
                        dayDiv.style.backgroundColor = 'red';
                        dayDiv.style.color = 'white';
                    }

                    // Toggle block/unblock on click
                    dayDiv.addEventListener('click', () => {
                        toggleBlockDate(formattedDate, dayDiv);
                    });
                }

                calendarDays.appendChild(dayDiv);
            }
        }

        // Helper function to check if two dates are the same day
        function isSameDay(date1, date2) {
            return (
                date1.getDate() === date2.getDate() &&
                date1.getMonth() === date2.getMonth() &&
                date1.getFullYear() === date2.getFullYear()
            );
        }

        // Toggle the blocked status of a date
        function toggleBlockDate(date, dayDiv) {
            if (blockedDates.includes(date)) {
                blockedDates = blockedDates.filter(d => d !== date); // Unblock the date
                dayDiv.style.backgroundColor = ''; // Reset the style
                dayDiv.style.color = '';
            } else {
                blockedDates.push(date); // Block the date
                dayDiv.style.backgroundColor = 'red'; // Highlight the style
                dayDiv.style.color = 'white';
            }
        }

        // Save blocked dates to the server
        document.getElementById('saveBlockedDates').addEventListener('click', () => {
            fetch('backend/save-blocked-dates.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `blocked_dates=${JSON.stringify(blockedDates)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Blocked dates saved successfully!');
                } else {
                    alert('Failed to save blocked dates.');
                }
            })
            .catch(err => {
                console.error('Error saving blocked dates:', err);
                alert('An error occurred while saving blocked dates.');
            });
        });

        // Helper function to format date as YYYY-MM-DD
        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${year}-${month}-${day}`;
        }

        // Event listeners for navigation buttons
        prevMonth.addEventListener('click', () => {
            selectedDate.setMonth(selectedDate.getMonth() - 1);
            renderAdminCalendar();
        });

        nextMonth.addEventListener('click', () => {
            selectedDate.setMonth(selectedDate.getMonth() + 1);
            renderAdminCalendar();
        });

        // Initial fetch and render
        fetchBlockedDates();
    });

    </script>

</body>

</html>
