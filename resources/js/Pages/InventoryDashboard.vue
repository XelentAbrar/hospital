


<script setup>
import { ref } from "vue";
import { Link as InertiaLink, router } from "@inertiajs/vue3";
import { onMounted, onUnmounted ,computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
const props = defineProps(["inventory_dashboard"]);

const expiryProducts = ref([]);
const insulinProducts = ref([]);


const fetchData = () => {
    router.get(
        "/dashboard",
        {},
        {
            onSuccess: (response) => {
                const dashboardData = response.props.inventory_dashboard;
                expiryProducts.value = dashboardData.month_wise_expiry;
                insulinProducts.value = dashboardData.insulin_products;
                checkExpiryAlerts(dashboardData.current_month_expiry);            },
        }
    );
};
const checkExpiryAlerts = () => {
    const products = props.inventory_dashboard["current_month_expiry"];
    console.log('Products for expiry check:', products);
    if (!Array.isArray(products) || products.length === 0) {
        console.warn('No products to check for expiry');
        return;
    }
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    products.forEach(product => {
        if (!product.expiry_date || !product.product_name) {
            console.warn('Product data is incomplete:', product);
            return;
        }

        const expiryDate = new Date(product.expiry_date);
        expiryDate.setHours(0, 0, 0, 0);

        const diffTime = expiryDate - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays === 0) {
            toastr.warning(`Product ${product.product_name} expires today!`);
        } else if (diffDays === 1) {
            toastr.warning(`Product ${product.product_name} is expiring tomorrow!`);
        }
    });
};

toastr.options = {
    closeButton: true,
    positionClass: "toast-top-right",
    timeOut: "5000",
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

const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};

</script>

<template>
    <main>
        <div class="relative isolate overflow-hidden">
            <header>
                <div
                    class="mx-auto flex flex-wrap items-center gap-6 sm:flex-nowrap"
                >
                    <h1 class="text-base font-semibold leading-7 text-gray-900">
                        Inventory flow
                    </h1>
                    <div
                        class="order-last flex w-full gap-x-8 text-sm font-semibold leading-6 sm:order-none sm:w-auto sm:border-l sm:border-gray-200 sm:pl-6 sm:leading-7"
                    >
                        <InertiaLink
                            :href="route('dashboard')"
                            :class="
                                inventory_dashboard['filterDate'] == ''
                                    ? 'text-indigo-600'
                                    : 'text-gray-700'
                            "
                            >today</InertiaLink
                        >
                        <InertiaLink
                            :href="route('dashboard', { filterDate: 'weekly' })"
                            :class="
                                inventory_dashboard['filterDate'] == 'weekly'
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
                                inventory_dashboard['filterDate'] == 'monthly'
                                    ? 'text-indigo-600'
                                    : 'text-gray-700'
                            "
                            >Last 30 days</InertiaLink
                        >
                        <InertiaLink
                            :href="route('dashboard', { filterDate: 'all' })"
                            :class="
                                inventory_dashboard['filterDate'] == 'all'
                                    ? 'text-indigo-600'
                                    : 'text-gray-700'
                            "
                            >All-time</InertiaLink
                        >
                    </div>

                    <div class="ml-auto flex items-center justify-between">
                        <div class="digital-clock bg-gray-900 text-white px-4 py-2 rounded-xl shadow-lg text-right flex flex-row items-center">
                            <h2 class="text-3xl font-semibold text-white mr-4">{{ formattedDate }}</h2>
                            <h1 class="text-4xl font-semibold tracking-wider">{{ formattedTime }}</h1>
                        </div>
                    </div>
                </div>

            </header>

            <!-- Stats -->
            <div>
                <dl
                    class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <!-- OPD Card -->
                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-green-200 via-green-500 to-green-400 px-4 py-8 rounded-lg shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-bar-graph w-6 h-6 stroke-2" viewBox="0 0 16 16">
                                  <path d="M10 13.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-6a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm-2.5.5a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5zm-3 0a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5z"/>
                                  <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                                </svg>
                                <!-- <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/revenue.svg"/> -->
                            </div>
                            <h2 class="text-xl font-bold ml-2">Total Purchase</h2>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                {{ formatNumber(inventory_dashboard["total_inward_gatepass_value"])}}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    {{ formatNumber(inventory_dashboard["prev_day_inward_gatepass_value"])}}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>
                    <!-- IPD Card -->
                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-yellow-200 via-yellow-500 to-yellow-400 px-4 py-8 rounded-lg shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt-cutoff w-6 h-6 stroke-2" viewBox="0 0 16 16">
                                  <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5M11.5 4a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                                  <path d="M2.354.646a.5.5 0 0 0-.801.13l-.5 1A.5.5 0 0 0 1 2v13H.5a.5.5 0 0 0 0 1h15a.5.5 0 0 0 0-1H15V2a.5.5 0 0 0-.053-.224l-.5-1a.5.5 0 0 0-.8-.13L13 1.293l-.646-.647a.5.5 0 0 0-.708 0L11 1.293l-.646-.647a.5.5 0 0 0-.708 0L9 1.293 8.354.646a.5.5 0 0 0-.708 0L7 1.293 6.354.646a.5.5 0 0 0-.708 0L5 1.293 4.354.646a.5.5 0 0 0-.708 0L3 1.293zm-.217 1.198.51.51a.5.5 0 0 0 .707 0L4 1.707l.646.647a.5.5 0 0 0 .708 0L6 1.707l.646.647a.5.5 0 0 0 .708 0L8 1.707l.646.647a.5.5 0 0 0 .708 0L10 1.707l.646.647a.5.5 0 0 0 .708 0L12 1.707l.646.647a.5.5 0 0 0 .708 0l.509-.51.137.274V15H2V2.118z"/>
                            </svg>
                                <!-- <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/bed.svg"/> -->
                            </div>
                            <h2 class="text-xl font-bold ml-2">Total Good Receipt</h2>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                {{ formatNumber(inventory_dashboard["total_good_receipt_count"])}}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    {{ formatNumber(inventory_dashboard["prev_day_good_receipt_count"])}}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>
                    <!-- Lab Card -->
                    <div class="bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-lime-200 via-lime-600 to-lime-500 px-4 py-8 rounded-lg shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="bg-white/20 p-1 lg:p-2 rounded-full">
                                <img class="w-6 h-6 lg:w-8 lg:h-8" src="@/icons/laboratory.svg"/>
                            </div>
                            <h2 class="text-xl font-bold ml-2">Total Material Issue</h2>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-xl sm:text-2xl xl:text-3xl font-bold">
                                {{ formatNumber(inventory_dashboard["total_material_issue_count"])}}
                            </p>
                        </div>
                        <div class="flex justify-between items-center mt-3 relative">
                                <p class="text-lg font-semibold mt-2">
                                    {{ formatNumber(inventory_dashboard["prev_day_material_issue_count"])}}
                                </p>
                                <p class="text-xs">Yesterday</p>
                                <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white to-transparent"></div>
                            </div>
                    </div>


                </dl>
            </div>
        </div>

        <div class="space-y-16 xl:space-y-20">
            <div class="relative isolate overflow-hidden">
            <!-- Stats and Products Display -->
            <div class="space-y-16 py-10 xl:space-y-20">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 h-full overflow-y-auto">
            <!-- Expiry Products Section -->
            <div class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                <div class="flex items-center justify-between border-b border-gray-200 bg-lightGrey px-4 py-2 sm:px-6">
                    <h3 class="text-xl font-semibold leading-6 text-white">Expiry Products</h3>
                </div>
                <div class="h-96 overflow-auto scrollbar-thin">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Product Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="product in inventory_dashboard['current_month_expiry']" :key="product.product_id">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ product.product_name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ product.expiry_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Insulin Products Section -->
            <div class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                <div class="flex items-center justify-between border-b border-gray-200 bg-lightGrey px-4 py-2 sm:px-6">
                    <h3 class="text-xl font-semibold leading-6 text-white">Common Products</h3>
                </div>
                <div class="h-96 overflow-auto scrollbar-thin">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Product Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Current Stock</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Min Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="product in inventory_dashboard['insulin_products']" :key="product.product_id">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ product.product_name }}</td>
                                <td
                                    class="whitespace-nowrap px-3 py-4 text-sm"
                                    :class="{'text-red-600': product.current_stock === product.min_qty, 'text-gray-500': product.current_stock !== product.min_qty}"
                                >
                                    {{ product.current_stock }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ product.min_qty }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                <div class="flex items-center justify-between border-b border-gray-200 bg-lightGrey px-4 py-2 sm:px-6">
                    <h3 class="text-xl font-semibold leading-6 text-white">Most Issued Products</h3>
                </div>
                <div class="h-96 overflow-auto scrollbar-thin">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Sr#</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Product Name</th>
                                <!-- <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Min Quantity</th> -->
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="product in inventory_dashboard['top_products']" :key="product.product_id">
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ product.product_id }}</td>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ product.product_name }}</td>
                                <!-- <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ product.min_qty }}</td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
</style>
