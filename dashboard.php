<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            color: #2c3e50;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            background: white;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        .welcome {
            font-size: 28px;
            font-weight: 600;
            background: linear-gradient(135deg, #e74c3c, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .logout-btn {
            padding: 12px 30px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .stat-card.total {
            border-top: 3px solid #3498db;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
        }
        
        .stat-card.courtship {
            border-top: 3px solid #e74c3c;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2);
        }
        
        .stat-card.engagement {
            border-top: 3px solid #9b59b6;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.2);
        }
        
        .stat-card.physical {
            border-top: 3px solid #e67e22;
            box-shadow: 0 4px 15px rgba(230, 126, 34, 0.2);
        }
        
        .stat-card.online {
            border-top: 3px solid #1abc9c;
            box-shadow: 0 4px 15px rgba(26, 188, 156, 0.2);
        }
        
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        
        .stat-item {
            text-align: left;
            padding: 10px 0;
        }
        
        .stat-item-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .stat-item-value {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .stat-label {
            font-size: 14px;
            color: #a0a0a0;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        /* Search Bar */
        .search-container {
            margin-bottom: 20px;
        }
        
        .search-input {
            width: 100%;
            padding: 15px 20px;
            font-size: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            color: #2c3e50;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .search-input::placeholder {
            color: #95a5a6;
        }
        
        /* Table Section */
        .table-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        .table-header {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        
        th {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: #ffffff;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            color: #34495e;
        }
        
        tbody tr {
            transition: all 0.2s ease;
        }
        
        tbody tr:hover {
            background: rgba(52, 152, 219, 0.1);
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .welcome {
                font-size: 22px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div id="stats" class="stats-grid"></div>

    <div class="table-section">
        <div class="table-header">All Registrations</div>
        <div class="search-container">
            <input type="text" id="search-input" class="search-input" placeholder="Search by name, email, phone, category...">
        </div>
        <div class="table-container">
            <table id="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Category</th>
                        <th>Attendance Mode</th>
                        <th>Partner Name</th>
                        <th>Message</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
let allRecords = [];

function loadDashboard(){
    fetch("dashboard_data.php")
    .then(res => res.json())
    .then(data => {
        // Store all records globally for searching
        allRecords = data.records;
        
        // ------- STATS -------
        const totalCategories = (data.stats.courtship || 0) + 
                               (data.stats.soon_to_wed || 0) + 
                               (data.stats.newly_married || 0) + 
                               (data.stats.mature_single || 0);
        
        document.getElementById("stats").innerHTML = `
            <div class="stat-card total">
                <div class="stat-label">Total Registrations</div>
                <div class="stat-value">${data.stats.total}</div>
            </div>
            <div class="stat-card courtship">
                <div class="stat-label">By Category</div>
                <div class="stat-grid">
                    <div class="stat-item">
                        <div class="stat-item-label">Courtship</div>
                        <div class="stat-item-value">${data.stats.courtship || 0}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-label">Soon to Wed</div>
                        <div class="stat-item-value">${data.stats.soon_to_wed || 0}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-label">Newly Married</div>
                        <div class="stat-item-value">${data.stats.newly_married || 0}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-label">Mature Single</div>
                        <div class="stat-item-value">${data.stats.mature_single || 0}</div>
                    </div>
                </div>
            </div>
            <div class="stat-card engagement">
                <div class="stat-label">By Attendance Mode</div>
                <div class="stat-grid">
                    <div class="stat-item">
                        <div class="stat-item-label">Physical</div>
                        <div class="stat-item-value">${data.stats.physical || 0}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-label">Virtual</div>
                        <div class="stat-item-value">${data.stats.virtual || 0}</div>
                    </div>
                </div>
            </div>
        `;

        // ------- TABLE -------
        displayRecords(allRecords);
    })
    .catch(error => {
        console.error('Error loading dashboard:', error);
    });
}

function displayRecords(records) {
    let tbody = '';
    records.forEach(row => {
        tbody += `
            <tr>
                <td>${row.id}</td>
                <td>${row.first_name} ${row.last_name}</td>
                <td>${row.email}</td>
                <td>${row.phone}</td>
                <td>${row.category}</td>
                <td>${row.attendance_mode}</td>
                <td>${row.partner_name || '-'}</td>
                <td>${row.message || '-'}</td>
                <td>${row.registration_date}</td>
            </tr>
        `;
    });

    document.querySelector("#data-table tbody").innerHTML = tbody || '<tr><td colspan="9" style="text-align:center; padding:30px; color:#95a5a6;">No records found</td></tr>';
}

// Real-time search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            displayRecords(allRecords);
            return;
        }
        
        const filteredRecords = allRecords.filter(row => {
            const fullName = `${row.first_name} ${row.last_name}`.toLowerCase();
            const email = (row.email || '').toLowerCase();
            const phone = (row.phone || '').toLowerCase();
            const category = (row.category || '').toLowerCase();
            const attendanceMode = (row.attendance_mode || '').toLowerCase();
            const partnerName = (row.partner_name || '').toLowerCase();
            const message = (row.message || '').toLowerCase();
            
            return fullName.includes(searchTerm) ||
                   email.includes(searchTerm) ||
                   phone.includes(searchTerm) ||
                   category.includes(searchTerm) ||
                   attendanceMode.includes(searchTerm) ||
                   partnerName.includes(searchTerm) ||
                   message.includes(searchTerm);
        });
        
        displayRecords(filteredRecords);
    });
});

// Load data on page load
loadDashboard();
</script>

</body>
</html>