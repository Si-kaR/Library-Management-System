
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4f+6NB1iGFpO1D/1E5bXkQ6KOCl8Wr+Rpz4+AD5fRHW2npnlVJtQ0uWyljVB5txfU7pKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f4f4f9, #e8effc);
            display: flex;
            height: 100vh;
            color: #333;
        }

        .admin-dashboard {
            display: flex;
            width: 100%;
        }

        /* Side Panel */
        .side-panel {
            background: #20232a;
            color: #fff;
            width: 20%;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            box-shadow: 3px 0px 6px rgba(0, 0, 0, 0.1);
        }

        .side-panel h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #61dafb;
            text-align: center;
            width: 100%;
        }

        .side-panel ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .side-panel ul li {
            margin: 20px 0;
            width: 100%;
        }

        .side-panel ul li a {
            text-decoration: none;
            font-weight: 500;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .side-panel ul li a:hover {
            background: #61dafb;
            color: #20232a;
        }

        /* Dashboard Content */
        .dashboard-content {
            flex: 1;
            padding: 30px;
            box-sizing: border-box;
            background: #fff;
            border-radius: 10px;
            margin: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #20232a;
        }

        .dashboard-content h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #555;
        }

        /* Form for Adding Items */
        .form-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            margin-bottom: 30px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container input {
            flex: 1;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .form-container input:focus {
            outline: none;
            border-color: #61dafb;
            box-shadow: 0px 0px 5px #61dafb;
        }

        .form-container button {
            padding: 12px 25px;
            background: #61dafb;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container button:hover {
            background: #21a1f1;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            color: #555;
            font-size: 1rem;
        }

        table th {
            background: #61dafb;
            color: #fff;
            font-weight: bold;
        }

        table tr {
            transition: all 0.3s ease;
        }

        table tr:hover {
            background: #f1f1f1;
        }

        /* Delete Button */
        table button {
            background: #ff6b6b;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        table button:hover {
            background: #e63946;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-dashboard {
                flex-direction: column;
            }

            .side-panel {
                width: 100%;
                flex-direction: row;
                justify-content: space-around;
            }

            .dashboard-content h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<div class="admin-dashboard">
    <!-- Side Panel -->
    <aside class="side-panel">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#" onclick="showSection('catalog'); return false;">Manage Catalog</a></li>
            <li><a href="#" onclick="showSection('users'); return false;">Manage Users</a></li>
            <li><a href="#" onclick="showSection('events'); return false;">Manage Events</a></li>
            <li><a href="#" onclick="showSection('events'); return false;">Logout</a></li>
        </ul>
    </aside>

    <!-- Dashboard Content -->
    <main class="dashboard-content">
        <h1>Welcome to Admin Dashboard</h1>
        <h2 id="section-title">Manage Items</h2>

        <!-- Dynamic Form -->
        <div class="form-container" id="form-container">
            <!-- Input fields will be dynamically added here -->
        </div>

        <!-- Dynamic Table -->
        <table>
            <thead id="table-head">
                <!-- Table headers will be dynamically added here -->
            </thead>
            <tbody id="table-body">
                <!-- Table rows will be dynamically added here -->
            </tbody>
        </table>
    </main>
</div>

<script>
    let idCounter = { catalog: 1, users: 1, events: 1 };
    const data = { catalog: [], users: [], events: [] };

    const fields = {
        catalog: ['Name', 'Description'],
        users: ['Username', 'Email', 'Role'],
        events: ['Event Name', 'Date', 'Location']
    };

    function showSection(section) {
        const sectionTitle = document.getElementById('section-title');
        const formContainer = document.getElementById('form-container');
        const tableHead = document.getElementById('table-head');
        const tableBody = document.getElementById('table-body');

        // Update Section Title
        sectionTitle.textContent = `Manage ${section.charAt(0).toUpperCase() + section.slice(1)}`;

        // Clear previous inputs and table headers
        formContainer.innerHTML = '';
        tableHead.innerHTML = '';
        tableBody.innerHTML = '';

        // Create dynamic input fields based on section
        fields[section].forEach(field => {
            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = field;
            input.id = `input-${field.toLowerCase()}`;
            formContainer.appendChild(input);
        });

        // Add Add Item Button
        const addButton = document.createElement('button');
        addButton.textContent = 'Add Item';
        addButton.onclick = () => addItem(section);
        formContainer.appendChild(addButton);

        // Create Table Headers
        const headerRow = document.createElement('tr');
        fields[section].forEach(field => {
            const th = document.createElement('th');
            th.textContent = field;
            headerRow.appendChild(th);
        });
        const actionsTh = document.createElement('th');
        actionsTh.textContent = 'Actions';
        headerRow.appendChild(actionsTh);
        tableHead.appendChild(headerRow);

        // Populate Table Rows
        data[section].forEach((item, index) => addTableRow(section, item, index));
    }

    function addItem(section) {
        const newItem = {};
        fields[section].forEach(field => {
            const value = document.getElementById(`input-${field.toLowerCase()}`).value;
            if (!value) {
                alert('Please fill in all fields.');
                return;
            }
            newItem[field.toLowerCase()] = value;
        });
        newItem.id = idCounter[section]++;
        data[section].push(newItem);
        addTableRow(section, newItem, data[section].length - 1);
    }

    function addTableRow(section, item, index) {
        const tableBody = document.getElementById('table-body');
        const row = document.createElement('tr');

        fields[section].forEach(field => {
            const td = document.createElement('td');
            td.textContent = item[field.toLowerCase()];
            row.appendChild(td);
        });

        // Add delete button
        const actionsTd = document.createElement('td');
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.onclick = () => deleteItem(section, index);
        actionsTd.appendChild(deleteButton);
        row.appendChild(actionsTd);

        tableBody.appendChild(row);
    }

    function deleteItem(section, index) {
        data[section].splice(index, 1);
        showSection(section); // Re-render section to update rows
    }
</script>
</body>
</html>
