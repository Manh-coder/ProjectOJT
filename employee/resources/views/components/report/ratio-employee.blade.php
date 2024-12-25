<div>
    <h3 class="text-2xl font-semibold text-gray-700">Statistics on personnel ratios between departments</h3>
    <canvas id="myChart"></canvas>
</div>

@push('scripts')
    <script>
        const departments = {{ Js::from($departments) }};
        const ctx = document.getElementById('myChart');
        const backgroundColors = departments.map(() => getRandomColor());

        const data = {
            labels: {{ Js::from($lables) }},
            datasets: [{
                data: {{ Js::from($dataNumber) }},
                backgroundColor: backgroundColors,
                hoverOffset: 4
            }]
        };
        new Chart(ctx, {
            type: 'doughnut',
            data: data
        });


        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
@endpush
