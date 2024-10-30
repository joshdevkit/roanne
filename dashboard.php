<?php
$title = "Dashboard | Profiling and Mapping System";
include('includes/header.php');

// Load barangay JSON file
$barangayJson = file_get_contents('barangays.json');
$barangayData = json_decode($barangayJson, true);

// Extract the barangay names from the JSON file
$barangayNames = $barangayData['barangays'];

// Initialize pet counts to zero for each barangay
$petCounts = array_fill(0, count($barangayNames), 0);

// Database query to get pet counts by barangay
$stmt = $conn->prepare(" 
SELECT b.barangay AS barangay_name, COUNT(p.dog_id) AS pet_count
FROM owners o
INNER JOIN pets p ON p.owner_id = o.owner_id
INNER JOIN tbl_purok pu ON pu.owner_id = o.owner_id
INNER JOIN tbl_barangay b ON b.barangay_id = pu.barangay_id
LEFT JOIN tbl_municipality m ON m.municipality_id = b.municipality_id
LEFT JOIN tbl_province pr ON pr.province_id = m.province_id
GROUP BY b.barangay
");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $index = array_search($row['barangay_name'], $barangayNames);
    if ($index !== false) {
        $petCounts[$index] = (int) $row['pet_count'];
    }
}

$barangayNamesJson = json_encode($barangayNames);
$petCountsJson = json_encode($petCounts);
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer"></i> Dashboard</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    </ul>
</div>

<div class="border border-primary mb-3" id="barangay-chart"></div>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-text">DATA OF PUROK PER BARANGAY</h4>
        <select id="barangay-dropdown" class="form-select" style="width: 200px;">
            <option value="">Select Barangay</option>
        </select>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Barangay</th>
                    <th>Purok</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="table_data">
            </tbody>
        </table>
    </div>
</div>

<script src="js/apexcharts.js"></script>

<script>
    fetch('barangays.json')
        .then(response => response.json())
        .then(data => {
            const barangays = data.barangays;

            const barangayCounts = Array(barangays.length).fill(0);

            const options = {
                series: [{
                    name: 'Total Vaccinated Pets ',
                    data: barangayCounts
                }],
                chart: {
                    type: 'bar',
                    height: 400
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: barangays,
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    min: 0,
                    tickAmount: 5,
                    labels: {
                        formatter: function(value) {
                            return Math.round(value);
                        }
                    }
                },
                title: {
                    text: 'Total Vaccinated Pet of Barangays',
                    align: 'center'
                }
            };

            const chart = new ApexCharts(document.querySelector("#barangay-chart"), options);
            chart.render();

            $.getJSON('actions/get_barangay_data.php', function(actualData) {
                const updatedCounts = barangays.map(barangay => actualData.counts[barangay] || 0);
                chart.updateSeries([{
                    name: 'Total Vaccinated Pets ',
                    data: updatedCounts
                }]);
            }).fail(function(error) {
                console.error('Error loading actual count data:', error);
            });
        })
        .catch(error => console.error('Error loading barangays data:', error));
</script>

<script>
    $(document).ready(function() {
        $.getJSON('barangays.json', function(data) {
            const barangays = data.barangays;

            const dropdown = $('#barangay-dropdown');
            barangays.forEach(function(barangay) {
                const option = $('<option></option>')
                    .val(barangay)
                    .text(barangay);
                dropdown.append(option);
            });
        }).fail(function() {
            console.error('Error loading barangays data');
        });

        $('#barangay-dropdown').on('change', function() {
            var selectedBarangay = $(this).val();

            $.post('actions/get_barangay_data_by_purok.php', {
                    barangay: selectedBarangay
                })
                .done(function(response) {
                    console.log("Response:", response);

                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }

                    console.log("Parsed Response:", response);

                    if (Array.isArray(response)) {
                        const tableBody = $('#table_data');
                        tableBody.empty();

                        response.forEach(function(item) {
                            const row = $('<tr></tr>');
                            row.append($('<td></td>').text(item.barangay));
                            row.append($('<td></td>').text(item.purok));
                            row.append($('<td></td>').text(item.pet_count));
                            tableBody.append(row);
                        });
                    } else {
                        console.error("Expected an array but received:", response);
                    }
                })

                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error sending data:', textStatus, errorThrown);
                });
        });
    });
</script>






<?php include('includes/footer.php'); ?>