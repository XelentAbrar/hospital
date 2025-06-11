<script>
// import { defineComponent } from "vue";

// export default defineComponent({
//   props: {
//     admin_dashboard:Array,
//   },
// });
</script>

<script setup>
import { ref } from "vue";
import { Link as InertiaLink, router } from "@inertiajs/vue3";
import { onMounted, onUnmounted ,computed } from "vue";
import VueApexCharts from "vue3-apexcharts";
import { Inertia } from "@inertiajs/inertia";
import toastr from 'toastr';
import axios from 'axios';
import 'toastr/build/toastr.min.css';
const props = defineProps(["admin_dashboard","backupMessage","hasLabModule","hasExpenseModule","hasDonationModule","hasInventoryModule"]);

const series = ref([44, 55, 41]);
const chartOptions = ref({
    chart: {
        type: "donut",
    },
    labels: ["Male", "Female", "Children"],
    colors: ["#1E90FF", "#FF69B4", "#B05E27"],
    responsive: [
        {
            breakpoint: 480,
            options: {
                chart: {
                    width: 200,
                },
                legend: {
                    position: "bottom",
                },
            },
        },
    ],
});
const adminDashboard = ref({ male: 0, fe_male: 0, child: 0 });
const fetchData = () => {
    router.get(
        "/dashboard",
        {},
        {
            onSuccess: (response) => {
                // console.log("response", response);
                const dashboardData = response.props.admin_dashboard || {};

                adminDashboard.value = {
                    male: dashboardData.male || 0,
                    fe_male: dashboardData.fe_male || 0,
                    child: dashboardData.child || 0,
                };
            },
        }
    );
};
const currentTime = ref(new Date());

const formattedTime = computed(() => {
  const hours = String(currentTime.value.getHours()).padStart(2, "0");
  const minutes = String(currentTime.value.getMinutes()).padStart(2, "0");
  const seconds = String(currentTime.value.getSeconds()).padStart(2, "0");
  return `${hours}:${minutes}:${seconds}`;
});

const formattedDate = computed(() => {
  const day = String(currentTime.value.getDate()).padStart(2, "0");
  const month = String(currentTime.value.getMonth() + 1).padStart(2, "0");
  const year = currentTime.value.getFullYear();
  return `${day}-${month}-${year}`;
});
const backupMessage = ref("");
const isLoading = ref(false);
const projectType = import.meta.env.VITE_PROJECT_TYPE;

const triggerBackup = async () => {
    isLoading.value = true;
    try {
        const response = await axios.post('/backup');

        const message = response.data?.backupMessage || "Backup completed successfully";
        toastr.success(message);
    } catch (error) {
        const message = "An error occurred during backup!";
        toastr.error(message);
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

let intervalId;
let timeIntervalId;
onMounted(() => {
    intervalId = setInterval(() => {
        fetchData();
    }, 68000);
    timeIntervalId = setInterval(() => {
    currentTime.value = new Date();
  }, 1000);
});

onUnmounted(() => {
    clearInterval(timeIntervalId);
    clearInterval(intervalId);

});

series.value = [
    props.admin_dashboard["male"],
    props.admin_dashboard["fe_male"],
    props.admin_dashboard["child"],
];
const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};
</script>

<template>
    <main>
        <div class="relative isolate overflow-hidden">
            <header>

                <div class="flex items-center justify-between">
                <div>
                    <button @click="triggerBackup" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center" :disabled="isLoading">
                        <span v-if="isLoading" class="flex items-center text-xl font-semibold">

                            <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                            </svg>
                            Backup in Progress...
                        </span>
                        <span class="flex items-center text-xl font-semibold" v-else>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 me-2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                            Initiate Backup
                        </span>
                    </button>
                </div>
                <div v-if="projectType === 'jinnah'" class="ml-auto flex items-center justify-center">
                     <h2 class="font-bold text-4xl text-transparent bg-clip-text bg-gradient-to-r from-primary via-primary to-black">CHINIOT HOSPITAL JC</h2>
                </div>
                <div v-if="projectType === 'hms'" class="ml-auto flex items-center justify-center text-center pt-2">
                        <h2 class="font-bold text-4xl text-transparent bg-clip-text bg-gradient-to-r from-primary via-primary to-black">CHINIOT HOSPITAL SGD RD</h2>
                </div>

                <div class="ml-auto flex items-center justify-between">
                        <div class="digital-clock bg-gray-900 text-white px-4 py-2 rounded-xl shadow-lg text-right flex flex-row items-center">
                            <h2 class="text-3xl font-semibold text-white mr-4">{{ formattedDate }}</h2>
                            <h1 class="text-4xl font-semibold tracking-wider">{{ formattedTime }}</h1>
                        </div>
                    </div>
            </div>
                <div
                    class="mx-auto flex flex-wrap items-center gap-6 px-4 sm:flex-nowrap sm:px-6 lg:px-8"
                >
                    <h1 class="text-base font-semibold leading-7 text-gray-900">
                        Hospital flow
                    </h1>
                    <div
                        class="order-last flex w-full gap-x-8 text-sm font-semibold leading-6 sm:order-none sm:w-auto sm:border-l sm:border-gray-200 sm:pl-6 sm:leading-7"
                    >
                        <InertiaLink
                            :href="route('dashboard')"
                            :class="
                                admin_dashboard['filterDate'] == ''
                                    ? 'text-indigo-600'
                                    : 'text-gray-700'
                            "
                            >today</InertiaLink
                        >
                        <InertiaLink
                            :href="route('dashboard', { filterDate: 'weekly' })"
                            :class="
                                admin_dashboard['filterDate'] == 'weekly'
                                    ? 'text-indigo-600'
                                    : 'text-gray-700'
                            "
                            >Last 7 days</InertiaLink
                        >
                        <InertiaLink
                            :href="
                                route('dashboard', { filterDate: 'monthly' })
                            "
                            :class="
                                admin_dashboard['filterDate'] == 'monthly'
                                    ? 'text-indigo-600'
                                    : 'text-gray-700'
                            "
                            >Last 30 days</InertiaLink
                        >
                        <InertiaLink
                            :href="route('dashboard', { filterDate: 'all' })"
                            :class="
                                admin_dashboard['filterDate'] == 'all'
                                    ? 'text-indigo-600'
                                    : 'text-gray-700'
                            "
                            >All-time</InertiaLink
                        >
                    </div>



                </div>

            </header>

            <!-- Stats -->
            <div>
                <dl
                    class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 lg:px-2 xl:px-0 gap-4"
                >
                    <!-- OPD Card -->
                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-green-200 via-green-500 to-green-400 px-4 py-8 rounded-lg shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/ambulance.svg"/>
                            </div>
                            <h2 class="text-xl font-bold ml-2">Outdoor Patients</h2>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                {{ formatNumber(admin_dashboard["current_opd_cout"])}}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    {{ formatNumber(admin_dashboard["yesterday_opd_cout"])}}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>
                    <!-- IPD Card -->
                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-yellow-200 via-yellow-500 to-yellow-400 px-4 py-8 rounded-lg shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/bed.svg"/>
                            </div>
                            <h2 class="text-xl font-bold ml-2">Indoor Patients</h2>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                {{ formatNumber(admin_dashboard["current_ipd_cout"])}}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    {{ formatNumber(admin_dashboard["yesterday_ipd_cout"])}}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>
                    <!-- Lab Card -->
                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-lime-200 via-lime-600 to-lime-500 px-4 py-8 rounded-lg shadow-lg" v-if="hasLabModule">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/laboratory.svg"/>
                            </div>
                            <h2 class="text-xl font-bold ml-2">Lab Patients</h2>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                {{ formatNumber(admin_dashboard["current_lab_cout"])}}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    {{ formatNumber(admin_dashboard["yesterday_lab_cout"])}}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>

                    <!-- Revenue Card -->
                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-indigo-200 via-indigo-500 to-indigo-400 px-4 py-8 rounded-lg shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/revenue.svg"/>
                            </div>
                            <h2 class="text-xl font-bold ml-2">Revenue</h2>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                <span class="text-sm">Rs</span>
                                {{
                                    formatNumber(
                                        +admin_dashboard["current_opd_sum"] +
                                            +admin_dashboard[
                                                "current_ipd_sum"
                                            ] +
                                            +admin_dashboard["current_lab_sum"]+
                                            +admin_dashboard["donor_sum"]
                                    )
                                }}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    <span class="text-xs">Rs</span>

                                    {{
                                        formatNumber(
                                            +admin_dashboard[
                                                "yesterday_opd_sum"
                                            ] +
                                                +admin_dashboard[
                                                    "yesterday_ipd_sum"
                                                ] +
                                                +admin_dashboard[
                                                    "yesterday_donor_sum"
                                                ] +
                                                +admin_dashboard[
                                                    "yesterday_lab_sum"
                                                ]
                                        )
                                    }}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>
                    <!-- Expense Card -->

                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-red-200 via-red-400 to-red-400 px-4 py-8 rounded-lg shadow-lg" v-if="hasExpenseModule">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/expense.svg"/>
                            </div>
                            <h2 class="text-xl font-bold ml-2">Expense</h2>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                <span class="text-sm">Rs</span>
                                {{
                                    formatNumber(+admin_dashboard["current_expense_sum"])
                                }}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    <span class="text-xs">Rs</span>

                                    {{
                                        formatNumber(+admin_dashboard["yesterday_expense_sum"])
                                    }}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>

                    <!-- Cash Card -->

                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-purple-200 via-purple-400 to-purple-400 px-4 py-8 rounded-lg shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/cash.svg"/>
                            </div>
                            <h2 class="text-xl font-bold ml-2">Cash In Hand</h2>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                <span class="text-sm">Rs</span>
                                {{
                                    formatNumber(
                                        +admin_dashboard["current_opd_sum"] +
                                            +admin_dashboard[
                                                "current_ipd_sum"
                                            ] +
                                            +admin_dashboard[
                                                "current_lab_sum"
                                            ]
                                             +admin_dashboard[
                                                "donor_sum"
                                            ] -
                                            +admin_dashboard[
                                                "current_expense_sum"
                                            ]
                                    )
                                }}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    <span class="text-xs">Rs</span>

                                    {{
                                        formatNumber(
                                            +admin_dashboard[
                                                "yesterday_opd_sum"
                                            ] +
                                                +admin_dashboard[
                                                    "yesterday_ipd_sum"
                                                ] +
                                                +admin_dashboard[
                                                    "yesterday_lab_sum"
                                                ]
                                                +admin_dashboard[
                                                    "yesterday_donor_sum"
                                                ] -
                                                +admin_dashboard[
                                                    "yesterday_expense_sum"
                                                ]
                                        )
                                    }}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>
                </dl>
            </div>
        </div>

        <div class="space-y-16 py-16 xl:space-y-20">
            <div
                class="grid grid-cols-1 gap-6 sm:grid-cols-3 h-full overflow-y-auto"
            >

                <div
                    class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl col-span-2"
                >
                    <div
                        class="flex items-center justify-between border-b border-gray-200 bg-lightGrey px-4 py-2 sm:px-6 rounded-t-xl"
                    >
                        <h3 class="text-xl font-semibold leading-6 text-white">
                            Available Doctors ({{ admin_dashboard["online_doctors"]?.filter(doctor => doctor.check_in !== null && doctor.check_out === null).length }})

                        </h3>

                    </div>
                    <div class="h-96 overflow-auto scrollbar-thin">

                        <table class="overflow-auto w-full h-96">
                            <thead>
                                <tr
                                    class="border border-slate-300 border-b-1 border-r-0 border-l-0 w-full"
                                >
                                    <th
                                        scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                    >
                                        Doctor
                                    </th>

                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    >
                                        Patients
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    >
                                        Check In
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    >
                                        Check Out
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="online_doctors in admin_dashboard[
                                        'online_doctors'
                                    ]"
                                    class="border border-slate-300 border-b-1 border-r-0 border-l-0 w-full"
                                >
                                    <td class="whitespace-nowrap py-2 text-black">
                                        <div class="flex items-center gap-2">
                                            <!-- <span class="inline-block   h-12 w-12 overflow-hidden rounded-full bg-gray-100"> -->
                                            <!-- <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg> -->
                                            <div
                                                class="flex-none rounded-full bg-emerald-500/20 p-1"
                                                v-if="
                                                    online_doctors?.check_out ==
                                                    null &&   online_doctors?.check_in !=null
                                                "
                                            >
                                                <div
                                                    class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                                                ></div>
                                            </div>
                                            <div
                                                class="flex-none rounded-full bg-gray-500/20 p-1"
                                                v-if="
                                                    online_doctors?.check_out !=
                                                    null &&   online_doctors?.check_in !=null
                                                "
                                            >
                                                <div
                                                    class="h-1.5 w-1.5 rounded-full bg-gray-500"
                                                ></div>
                                            </div>
                                            <div
                                                class="flex-none rounded-full bg-red-500/20 p-1"
                                                v-if="
                                                    online_doctors?.check_in ==
                                                    null
                                                "
                                            >
                                                <div
                                                    class="h-1.5 w-1.5 rounded-full bg-red-500"
                                                ></div>
                                            </div>
                                            <!-- </span> -->
                                            <span class="text-gray-900">{{
                                                online_doctors?.employee?.name
                                            }}</span>
                                            <!-- <p
                                                class="text-sm leading-6 text-gray-900"
                                            >
                                                ({{
                                                    online_doctors?.employee
                                                        ?.doctor_type
                                                }})
                                            </p> -->
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-center"
                                    >

                                        <span class="text-center" v-if="online_doctors.check_in != null">{{
                                            online_doctors?.employee
                                                ?.appointment_count
                                       + online_doctors?.employee?.admission_count }}
                                          </span>
                                    </td>
                                    <td class="text-right justify-content-end pr-4">
                                        <div
                                            class="hidden sm:flex sm:flex-col sm:items-end"
                                        >
                                            <!-- <p class="text-sm leading-6 text-gray-900">{{online_doctors?.employee?.doctor_type}}</p> -->
                                            <div
                                                class="mt-1 flex items-center gap-x-1.5"
                                            >
                                                <p
                                                    class="text-xs leading-5 text-gray-500"
                                                >
                                                    {{ online_doctors?.check_in }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right justify-content-end pr-4">
                                        <div
                                            class="hidden sm:flex sm:flex-col sm:items-end"
                                        >
                                            <!-- <p class="text-sm leading-6 text-gray-900">{{online_doctors?.employee?.doctor_type}}</p> -->
                                            <div
                                                class="mt-1 flex items-center gap-x-1.5"
                                            >
                                                <!-- <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                                    </div> -->
                                                <p
                                                    class="text-xs leading-5 text-gray-500"
                                                >
                                                    {{ online_doctors?.check_out }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div
                    class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl"
                >
                    <div
                        class="flex items-center justify-between border-b border-gray-200 bg-lightGrey px-4 py-2 sm:px-6"
                    >
                        <h3 class="text-xl font-semibold leading-6 text-white">
                            Recent Patients
                        </h3>
                        <!-- <InertiaLink
                            :href="route('appointments.index')"
                            class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-darkGrey rounded-lg hover:bg-darkGrey/70 focus:ring-4 focus:outline-none focus:ring-blue-300"
                        >
                            View all
                        </InertiaLink> -->
                    </div>
                    <div class="flow-root">
                        <div
                            class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
                        >
                            <div
                                class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                            >
                                <div class="overflow-hidden">
                                    <table
                                        class="min-w-full divide-y divide-gray-300"
                                    >
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                >
                                                    Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Gender
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Type
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                                >
                                                    <span class="sr-only"
                                                        >Amount</span
                                                    >
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="divide-y divide-gray-200 bg-white"
                                        >
                                            <tr
                                                v-for="latest_opd_patient in admin_dashboard[
                                                    'latest_opd_patient'
                                                ]"
                                            >
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                                >
                                                    {{
                                                        latest_opd_patient.patient_name
                                                    }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{
                                                        latest_opd_patient.patient_gender
                                                    }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    Outdoor
                                                </td>
                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                                >
                                                    <span
                                                        class="text-indigo-600"
                                                        >Rs
                                                        {{
                                                            formatNumber(
                                                                latest_opd_patient
                                                                    ?.appointment_details[0]
                                                                    ?.fee
                                                            )
                                                        }}</span
                                                    >
                                                </td>
                                            </tr>
                                            <tr
                                                v-for="latest_ipd_patient in admin_dashboard[
                                                    'latest_ipd_patient'
                                                ]"
                                            >
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                                >
                                                    {{
                                                        latest_ipd_patient.name
                                                    }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{
                                                        latest_ipd_patient.gender
                                                    }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    Indoor
                                                </td>
                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                                >
                                                    <span
                                                        class="text-indigo-600"
                                                        >Rs
                                                        {{
                                                            formatNumber(
                                                                latest_ipd_patient?.total_amount
                                                            )
                                                        }}</span
                                                    >
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl col-span-2"
                >
                    <div
                        class="flex items-center justify-between border-b border-gray-200 bg-lightGrey px-4 py-2 sm:px-6"
                    >
                        <h3 class="text-xl font-semibold leading-6 text-white">
                            Patients by gender
                        </h3>
                        <!-- <button
                            type="button"
                            class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-darkGrey rounded-lg hover:bg-darkGrey/70 focus:ring-4 focus:outline-none focus:ring-blue-300"
                        >
                            View all
                        </button> -->
                    </div>
                    <div class="flex justify-center mt-16">
                        <apexchart
                            width="500"
                            type="donut"
                            :options="chartOptions"
                            :series="series"
                        ></apexchart>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>
.progress-ring__circle {
    transition: stroke-dashoffset 0.35s;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}
.digital-clock {
  font-family: 'Courier New', Courier, monospace;
  text-align: center;
  margin: 20px 0;
}

.time {
  font-size: 2.5rem;
  color: #333;
}

.date {
  font-size: 1.5rem;
  color: #666;
}

.spinner {
  animation: rotate 2s linear infinite;
}

@keyframes rotate {
  100% {
    transform: rotate(360deg);
  }
}

.path {
  animation: dash 1.5s ease-in-out infinite;
}

@keyframes dash {
  0% {
    stroke-dashoffset: 126;
  }
  50% {
    stroke-dashoffset: 0;
  }
  100% {
    stroke-dashoffset: 126;
  }
}

button:disabled {
  cursor: not-allowed;
  opacity: 0.7;
}
</style>
