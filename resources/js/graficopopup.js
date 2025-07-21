export function modalChartComponent() {
    return {
        openModal: false,
        modalChartLabels: [],
        modalChartData: [],
        modalTitle: '',
        showModalChart(title, labels, data) {
            this.modalTitle = title;
            this.modalChartLabels = labels;
            this.modalChartData = data;
            this.openModal = true;

            this.$nextTick(() => {
                const ctx = document.getElementById('modalChart').getContext('2d');

                if (window.modalChartInstance) {
                    window.modalChartInstance.destroy();
                }

                window.modalChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: this.modalChartLabels,
                        datasets: [{
                            label: 'Evaluaciones',
                            data: this.modalChartData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: 25,
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                display: true
                            }
                        }
                    }
                });
            });
        }
    }
}
