@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="form-group mb-3">
                        Employee List by Type
                    </h3>
                    <div class="form-group">
                        <label for="viewOption">View Option:</label>
                        <select class="form-control" id="viewOption">
                            <option value="">Select Please</option>
                            <option value="all">All Employees</option>
                            <option value="director_list">Only Director List</option>
                            <option value="coordinator_list">Only Co-ordinator List</option>
                            <option value="shareholder_list">Only Shareholder List</option>
                            <option value="outsider_list">Only Outsider List</option>
                            <option value="director_wise">Director Wise Employees</option>
                        </select>
                    </div>

                    <div id="directorWiseView" style="display: none;">
                        <div class="form-group">
                            <label for="directorSelect">Director নির্বাচন করুন:</label>
                            <select class="form-control" id="directorSelect">
                                <option value="">-- Director নির্বাচন করুন --</option>
                                @foreach ($directors as $director)
                                    <option value="{{ $director->id }}">{{ $director->employee_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="coordinatorGroup">
                            <label for="coordinatorSelect">Co-ordinator নির্বাচন করুন (ঐচ্ছিক):</label>
                            <select class="form-control" id="coordinatorSelect" disabled>
                                <option value="">-- প্রথমে Director নির্বাচন করুন --</option>
                            </select>
                        </div>
                    </div>

                    <button id="printButton" class="btn btn-primary float-right" style="display: none;">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>

                <div class="card-body table-responsive">
                    <div class="form-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search in all columns..." disabled>
                    </div>

                    <div id="printableArea">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Director</th>
                                    <th>Co-ordinator</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTableBody">
                                <tr><td colspan="9" class="text-center">অপশন নির্বাচন করুন</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // View option change handler
    document.getElementById('viewOption').addEventListener('change', function() {
        const viewOption = this.value;
        const directorWiseView = document.getElementById('directorWiseView');
        const tbody = document.getElementById('employeeTableBody');
        const printButton = document.getElementById('printButton');
        const searchInput = document.getElementById('searchInput');

        // Reset table and hide print button
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">লোড হচ্ছে...</td></tr>';
        printButton.style.display = 'none';
        searchInput.disabled = true;
        searchInput.value = '';

        // Show/hide director wise view
        if (viewOption === 'director_wise') {
            directorWiseView.style.display = 'block';
            tbody.innerHTML = '<tr><td colspan="9" class="text-center">ডিরেক্টর নির্বাচন করুন</td></tr>';
            return;
        } else {
            directorWiseView.style.display = 'none';
        }

        // Fetch data based on selected option
        let url = '';
        switch(viewOption) {
            case 'all':
                url = '/get-all-employees';
                break;
            case 'director_list':
                url = '/get-directors-list';
                break;
            case 'coordinator_list':
                url = '/get-coordinators-list';
                break;
            case 'shareholder_list':
                url = '/get-shareholders-list';
                break;
            case 'outsider_list':
                url = '/get-outsiders-list';
                break;
        }

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(employees => {
                if (employees.length > 0) {
                    tbody.innerHTML = '';
                    employees.forEach((emp, index) => {
                        tbody.innerHTML += `
                            <tr class="text-center">
                                <td>${index + 1}</td>
                                <td>${emp.employee_code || ''}</td>
                                <td>${emp.employee_name || ''}</td>
                                <td>${emp.designation || ''}</td>
                                <td>${emp.email || ''}</td>
                                <td>${emp.mobile_no || ''}</td>
                                <td>${emp.director ? emp.director.employee_name : 'N/A'}</td>
                                <td>${emp.coordinator ? emp.coordinator.employee_name : 'N/A'}</td>
                                <td>${emp.user_type ? emp.user_type.type : 'N/A'}</td>
                            </tr>
                        `;
                    });
                    printButton.style.display = 'block';
                    searchInput.disabled = false;
                    // Initialize search functionality
                    initSearch();
                } else {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">কোনো এমপ্লয়ি পাওয়া যায়নি</td></tr>';
                }
            })
            .catch(error => {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center">ডাটা লোড করতে সমস্যা হয়েছে</td></tr>';
                console.error('Error:', error);
            });
    });

    // Director select change handler
    document.getElementById('directorSelect').addEventListener('change', function () {
        let directorId = this.value;
        let coordinatorSelect = document.getElementById('coordinatorSelect');
        let tbody = document.getElementById('employeeTableBody');
        let printButton = document.getElementById('printButton');
        let searchInput = document.getElementById('searchInput');

        // Reset coordinator dropdown
        coordinatorSelect.innerHTML = '<option value="">-- Co-ordinator নির্বাচন করুন --</option>';
        coordinatorSelect.disabled = true;
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">লোড হচ্ছে...</td></tr>';
        printButton.style.display = 'none';
        searchInput.disabled = true;
        searchInput.value = '';

        if (directorId) {
            fetch(`/get-employees-by-director/${directorId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(employees => {
                    if (employees.length > 0) {
                        // Populate employee table
                        tbody.innerHTML = '';
                        employees.forEach((emp, index) => {
                            tbody.innerHTML += `
                                <tr class="text-center">
                                    <td>${index + 1}</td>
                                    <td>${emp.employee_code || ''}</td>
                                    <td>${emp.employee_name || ''}</td>
                                    <td>${emp.designation || ''}</td>
                                    <td>${emp.email || ''}</td>
                                    <td>${emp.mobile_no || ''}</td>
                                    <td>${emp.director ? emp.director.employee_name : ''}</td>
                                    <td>${emp.coordinator ? emp.coordinator.employee_name : 'N/A'}</td>
                                    <td>${emp.user_type ? emp.user_type.type : 'N/A'}</td>
                                </tr>
                            `;
                        });

                        // Populate coordinator dropdown with only coordinators
                        const coordinators = employees.filter(emp =>
                            emp.user_type && emp.user_type.type === 'Co-ordinator'
                        );

                        if (coordinators.length > 0) {
                            coordinators.forEach(coordinator => {
                                coordinatorSelect.innerHTML += `
                                    <option value="${coordinator.id}">${coordinator.employee_name}</option>
                                `;
                            });
                            coordinatorSelect.disabled = false;
                        }

                        printButton.style.display = 'block';
                        searchInput.disabled = false;
                        // Initialize search functionality
                        initSearch();
                    } else {
                        tbody.innerHTML = '<tr><td colspan="9" class="text-center">কোনো এমপ্লয়ি পাওয়া যায়নি</td></tr>';
                    }
                })
                .catch(error => {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">ডাটা লোড করতে সমস্যা হয়েছে</td></tr>';
                    console.error('Error:', error);
                });
        } else {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center">ডিরেক্টর নির্বাচন করুন</td></tr>';
        }
    });

    // Coordinator select change handler
    document.getElementById('coordinatorSelect').addEventListener('change', function () {
        let coordinatorId = this.value;
        let tbody = document.getElementById('employeeTableBody');
        let printButton = document.getElementById('printButton');
        let searchInput = document.getElementById('searchInput');

        if (!coordinatorId) {
            // If no coordinator selected, show all employees under director again
            let directorId = document.getElementById('directorSelect').value;
            if (directorId) {
                document.getElementById('directorSelect').dispatchEvent(new Event('change'));
            }
            return;
        }

        tbody.innerHTML = '<tr><td colspan="9" class="text-center">লোড হচ্ছে...</td></tr>';
        printButton.style.display = 'none';
        searchInput.disabled = true;
        searchInput.value = '';

        fetch(`/get-employees-by-coordinator/${coordinatorId}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(employees => {
                if (employees.length > 0) {
                    tbody.innerHTML = '';
                    employees.forEach((emp, index) => {
                        tbody.innerHTML += `
                            <tr class="text-center">
                                <td>${index + 1}</td>
                                <td>${emp.employee_code || ''}</td>
                                <td>${emp.employee_name || ''}</td>
                                <td>${emp.designation || ''}</td>
                                <td>${emp.email || ''}</td>
                                <td>${emp.mobile_no || ''}</td>
                                <td>${emp.director ? emp.director.employee_name : ''}</td>
                                <td>${emp.coordinator ? emp.coordinator.employee_name : ''}</td>
                                <td>${emp.user_type ? emp.user_type.type : 'N/A'}</td>
                            </tr>
                        `;
                    });
                    printButton.style.display = 'block';
                    searchInput.disabled = false;
                    // Initialize search functionality
                    initSearch();
                } else {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">কোনো এমপ্লয়ি পাওয়া যায়নি</td></tr>';
                }
            })
            .catch(error => {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center">ডাটা লোড করতে সমস্যা হয়েছে</td></tr>';
                console.error('Error:', error);
            });
    });

    // Initialize search functionality
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#employeeTableBody tr');

            rows.forEach(row => {
                let found = false;
                const cells = row.querySelectorAll('td');

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(filter)) {
                        found = true;
                    }
                });

                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Print functionality
    document.getElementById('printButton').addEventListener('click', function() {
        const printableArea = document.getElementById('printableArea');
        const printContents = printableArea.cloneNode(true);
        const printWindow = window.open('', '_blank');
        const viewOption = document.getElementById('viewOption').value;

        let title = '';
        switch(viewOption) {
            case 'all': title = 'All Employees List'; break;
            case 'director_list': title = 'Director List'; break;
            case 'coordinator_list': title = 'Co-ordinator List'; break;
            case 'shareholder_list': title = 'Shareholder List'; break;
            case 'outsider_list': title = 'Outsider List'; break;
            case 'director_wise': title = 'Director/Co-ordinator Wise Employees Report'; break;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
                <head>
                    <title>${title}</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: center;
                        }
                        th {
                            background-color: #17a2b8 !important;
                            color: white !important;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        .text-center {
                            text-align: center;
                        }
                        h2 {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        @page {
                            size: auto;
                            margin: 10mm;
                        }
                        @media print {
                            thead { display: table-header-group !important; }
                            tr { page-break-inside: avoid !important; }
                        }
                    </style>
                </head>
                <body>
                    <h2>${title}</h2>
                    ${printContents.innerHTML}
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                                window.close();
                            }, 100);
                        };
                    <\/script>
                </body>
            </html>
        `);

        printWindow.document.close();
    });
</script>

@endsection
