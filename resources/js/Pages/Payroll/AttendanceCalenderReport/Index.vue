<template>
    <Head title="Attendance Calendar" />
    <AppLayout title="Attendance Calendar">
        <div class="p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <button
                    @click="goToPreviousMonth"
                    class="px-4 py-2 bg-primary text-white rounded hover:bg-accent"
                >
                    Previous Month
                </button>
                <h1 class="text-2xl font-bold">
                    Attendance Report for {{ formatMonth(month) }}
                </h1>
                <button
                    @click="goToNextMonth"
                    class="px-4 py-2 bg-primary text-white rounded hover:bg-accent"
                >
                    Next Month
                </button>
                <button
                    @click="printReport"
                    class="px-4 py-2 bg-primary text-white rounded hover:bg-accent"
                >
                    Print
                </button>
            </div>

            <div id="printData" class="overflow-x-auto">
                <div class="flex justify-center items-center">
                    <h1 class="text-2xl font-bold">
                        Attendance Report for {{ formatMonth(month) }}
                    </h1>
                </div>

                <table class="min-w-full bg-white">
                    <thead>
                        <tr
                            class="bg-primary whitespace-nowrap text-white rounded px-2 py-2"
                        >
                            <th class="py-2 px-4 border">Employee Name</th>
                            <th
                                v-for="day in daysInMonth"
                                :key="day"
                                class="py-2 px-4 border"
                            >
                                {{ formatDate(day) }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="employee in reportData" :key="employee.name">
                            <td class="py-2 px-4 border text-left">
                                {{ employee.name }}
                            </td>
                            <td
                                v-for="day in daysInMonth"
                                :key="day"
                                class="py-2 px-4 border"
                                :class="{
                                    'bg-red-100':
                                        employee.attendances[
                                            `${month}-${String(day).padStart(
                                                2,
                                                '0'
                                            )}`
                                        ]?.late,
                                }"
                            >
                                <div
                                    v-if="
                                        employee.attendances[
                                            `${month}-${String(day).padStart(
                                                2,
                                                '0'
                                            )}`
                                        ]
                                    "
                                >
                                    {{
                                        employee.attendances[
                                            `${month}-${String(day).padStart(
                                                2,
                                                "0"
                                            )}`
                                        ].check_in
                                    }}
                                    <span
                                        v-if="
                                            employee.attendances[
                                                `${month}-${String(
                                                    day
                                                ).padStart(2, '0')}`
                                            ].check_out
                                        "
                                    >
                                        to
                                        {{
                                            employee.attendances[
                                                `${month}-${String(
                                                    day
                                                ).padStart(2, "0")}`
                                            ].check_out
                                        }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";

export default {
    props: {
        reportData: Array,
        month: String,
    },
    components: {
        AppLayout,
        Head,
    },
    computed: {
        daysInMonth() {
            const [year, month] = this.month.split("-");
            return new Date(year, month, 0).getDate();
        },
    },
    methods: {
        formatDate(day) {
            const [year, month] = this.month.split("-");
            const paddedDay = String(day).padStart(2, "0");
            const paddedMonth = String(month).padStart(2, "0");
            return `${paddedDay}-${paddedMonth}-${year}`;
        },
        formatMonth(month) {
            const [year, monthNumber] = month.split("-");
            const paddedMonth = String(monthNumber).padStart(2, "0");
            return `${paddedMonth}-${year}`;
        },
        goToPreviousMonth() {
            const currentDate = new Date(`${this.month}-01`);
            const previousMonth = new Date(
                currentDate.setMonth(currentDate.getMonth() - 1)
            );
            this.navigateToMonth(previousMonth);
        },
        goToNextMonth() {
            const currentDate = new Date(`${this.month}-01`);
            const nextMonth = new Date(
                currentDate.setMonth(currentDate.getMonth() + 1)
            );
            this.navigateToMonth(nextMonth);
        },
        navigateToMonth(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const newMonth = `${year}-${month}`;
            router.get(
                route("attendance-calender-report.index", { month: newMonth })
            );
        },
        printReport() {
            const originalContent = document.body.innerHTML;
            const printContent = document.getElementById("printData").innerHTML;
            const printWindow = window.open("", "_blank");

            printWindow.document.open();
            printWindow.document.write(`
                <html>
                <head>
                    <title>Attendance Report</title>
                    <style>
                    @page {
                        size: landscape;
                        margin: 8mm;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 12px;
                    }
                    th, td {
                        padding: 6px;
                        border: 1px solid #000;
                        text-align: center;
                        word-wrap: break-word;
                    }
                    thead {
                        display: table-header-group;
                    }
                    tbody tr:nth-child(odd) {
                        background-color: #f3f4f6;
                    }
                    .overflow-x-auto {
                        padding: 8px;
                    }
                    </style>
                </head>
                <body>
                    ${printContent}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.onafterprint = () => {
                document.body.innerHTML = originalContent;
                window.location.reload();
            };

            printWindow.print();
        },
    },
};
</script>
