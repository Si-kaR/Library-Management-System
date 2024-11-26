<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sekondi Library - Events Calendar</title>
    <link rel="stylesheet" href="../assets/css/events.css">
</head>

<body>
    <header class="header" role="banner">
        <nav class="nav" role="navigation">
            <a href="#" class="nav-logo">Sekondi Library</a>
            <div class="nav-links">
                <a href="/Team_Project/index.php" class="nav-link">Home</a>
                <a href="../view/catalog.php" class="nav-link">Catalog</a>
                <a href="#" class="nav-link active">Events</a>
                <a href="../view/forum.php" class="nav-link">Forum</a>
                <a href="../view/my_account.php" class="nav-link">My Account</a>
            </div>
        </nav>
    </header>
    <main class="main-content" role="main">
        <h1 class="page-title">Library Events Calendar</h1>
        <p class="page-description">Browse and register for upcoming workshops, seminars, and activities</p>

        <div class="calendar-controls">
            <div class="view-toggle">
                <button class="btn active" id="calendarViewBtn">Calendar View</button>
                <button class="btn" id="listViewBtn">List View</button>
            </div>
            <select class="btn" id="eventFilter" aria-label="Filter events">
                <option value="all">All Events</option>
                <option value="workshop">Workshops</option>
                <option value="seminar">Seminars</option>
                <option value="reading">Book Readings</option>
                <option value="meetup">Study Group Meetups</option>
            </select>
            <div class="search-container">
                <input type="search" class="search-input" placeholder="Search events..." aria-label="Search events">
                <div id="searchResults" class="search-results"></div>
            </div>
        </div>

        <div class="calendar">
            <div class="calendar-nav">
                <button class="btn btn-nav" onclick="navigateMonth(-1)">&lt; Previous</button>
                <h3 class="calendar-title">November 2024</h3>
                <button class="btn btn-nav" onclick="navigateMonth(1)">Next &gt;</button>
            </div>
            <div class="calendar-header" role="rowgroup">
                <div role="columnheader">Sunday</div>
                <div role="columnheader">Monday</div>
                <div role="columnheader">Tuesday</div>
                <div role="columnheader">Wednesday</div>
                <div role="columnheader">Thursday</div>
                <div role="columnheader">Friday</div>
                <div role="columnheader">Saturday</div>
            </div>
            <div class="calendar-grid" role="grid" id="calendarGrid"></div>
        </div>

        <section class="upcoming-events">
            <h2 class="section-title">Upcoming Events</h2>
            <div class="events-grid" id="eventsGrid"></div>
        </section>
    </main>

    <div class="modal" id="registrationModal" role="dialog" aria-labelledby="modalTitle">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Event Registration</h2>
                <button class="close-modal" aria-label="Close registration form">&times;</button>
            </div>
            <form id="registrationForm">
                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" id="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="tel" id="phone" class="form-input">
                </div>
                <button type="submit" class="btn-primary">Register for Event</button>
            </form>
        </div>
    </div>
    <!-- <script>
        const getEvents = async () => {
            try {
                const response = await fetch("../actions/auth/fetch_events.php");
                const data = await response.json()
                console.log(data)
                return data.data
            } catch (error) {
                console.log(error)

            }

        }

        const data = getEvents()
        console.log(data)
    </script> -->

    <script src="../assets/js/events.js"></script>
</body>

</html>