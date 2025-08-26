const salesData = [{
    month: 'Jan',
    value: 23000
},
{
    month: 'Feb',
    value: 11000
},
{
    month: 'Mar',
    value: 22000
},
{
    month: 'Apr',
    value: 27000
},
{
    month: 'May',
    value: 13000
},
{
    month: 'Jun',
    value: 22000
},
{
    month: 'Jul',
    value: 37000
},
{
    month: 'Aug',
    value: 21000
},
{
    month: 'Sep',
    value: 44000
},
{
    month: 'Oct',
    value: 22000
},
{
    month: 'Nov',
    value: 30000
},
{
    month: 'Dec',
    value: 45000
}
];

function initChart() {
    const barContainer = document.getElementById('bar-container');
    const chartGrid = document.getElementById('chart-grid');

    const maxValue = Math.max(...salesData.map(d => d.value));
    const chartHeight = 420; // Available height for bars

    // Create grid lines
    for (let i = 0; i <= 5; i++) {
        const gridLine = document.createElement('div');
        gridLine.className = 'grid-line';
        gridLine.style.bottom = `${(i / 5) * chartHeight}px`;

        const gridLabel = document.createElement('div');
        gridLabel.className = 'grid-label';
        gridLabel.style.bottom = `${(i / 5) * chartHeight - 10}px`;
        gridLabel.textContent = `Rs. ${((maxValue / 5) * i / 1000).toFixed(0)}K`;

        chartGrid.appendChild(gridLine);
        chartGrid.appendChild(gridLabel);
    }

    // Create bars
    salesData.forEach((data, index) => {
        const barWrapper = document.createElement('div');
        barWrapper.className = 'bar-wrapper';

        const bar = document.createElement('div');
        bar.className = 'bar';
        const barHeight = (data.value / maxValue) * chartHeight;
        bar.style.setProperty('--height', `${barHeight}px`);
        bar.style.setProperty('--index', index);

        // Add pulse effect to highest bar
        if (data.value === maxValue) {
            bar.classList.add('pulse');
        }

        const barValue = document.createElement('div');
        barValue.className = 'bar-value';
        barValue.textContent = `Rs. ${data.value.toLocaleString()}`;

        const barLabel = document.createElement('div');
        barLabel.className = 'bar-label';
        barLabel.textContent = data.month;

        bar.appendChild(barValue);
        barWrapper.appendChild(bar);
        barWrapper.appendChild(barLabel);
        barContainer.appendChild(barWrapper);

        // Add click animation
        barWrapper.addEventListener('click', () => {
            bar.style.animation = 'none';
            setTimeout(() => {
                bar.style.animation = 'barGrow 0.6s ease-out';
            }, 10);
        });
    });

    // Calculate and display statistics
    updateStatistics();
}

function updateStatistics() {
    const totalSales = salesData.reduce((sum, data) => sum + data.value, 0);
    const avgSales = totalSales / salesData.length;
    const bestMonth = salesData.reduce((max, data) =>
        data.value > max.value ? data : max, salesData[0]);

    // Animate counting up
    animateValue('total-sales', 0, totalSales, 2000, (val) => `Rs. ${val.toLocaleString()}`);
    animateValue('avg-sales', 0, avgSales, 2000, (val) => `Rs. ${Math.round(val).toLocaleString()}`);

    setTimeout(() => {
        document.getElementById('best-month').textContent = bestMonth.month;
    }, 1000);
}

function animateValue(elementId, start, end, duration, formatter) {
    const element = document.getElementById(elementId);
    const startTime = Date.now();

    function update() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const current = start + (end - start) * easeOutCubic(progress);

        element.textContent = formatter ? formatter(current) : Math.round(current);

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    update();
}

function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
}

// Initialize the chart when the page loads
document.addEventListener('DOMContentLoaded', initChart);

// Add responsive behavior
let resizeTimeout;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        document.getElementById('bar-container').innerHTML = '';
        document.getElementById('chart-grid').innerHTML = '';
        initChart();
    }, 300);
});