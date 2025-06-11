<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import AppLayout from "@/Layouts/AppLayout.vue";
const props = defineProps({
    summaries: Array,
    users: Array,
    role: Object,
    filters: Object,
});

const filterStatus = ref(props.filters.status || 'pending');
const searchQuery = ref(props.filters.search || '');
const fromDate = ref(props.filters.from_date || '');
const toDate = ref(props.filters.to_date || new Date().toISOString().split('T')[0]);

const filterSummaries = (status) => {
    filterStatus.value = status;
    applyFilters();
};

const applyFilters = () => {
    const params = {
        status: filterStatus.value,
        search: searchQuery.value,
        from_date: fromDate.value,
        to_date: toDate.value,
    };

    router.get('/user-cash-summaries', params, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    filterStatus.value = 'pending';
    searchQuery.value = '';
    fromDate.value = '';
    toDate.value = new Date().toISOString().split('T')[0];
    applyFilters();
};

const editSummary = (summary) => {
    console.log(summary);
    Swal.fire({
        title: 'Update Cash Receipt',
        html: `
            <div class="space-y-4">
                <div>
                      <h2 class="font-bold flex justify-end">Total Amount ${ summary.total_amount } </h2>
                    <label class="block text-sm font-medium text-gray-700 text-left">Collected Amount</label>
                    <input id="collected_amount" type="number"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                           value="${summary.collected_amount}"
                           min="0" max="${summary.total_amount}" step="0.01">
                </div>

                <label class="block text-sm font-medium text-gray-700 text-left">Assigned User</label>
              <select id="updated_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select User</option>
                ${props.users && props.users.map(approver => `
                    <option value="${approver.id}"
                        ${summary?.updated_by?.id === approver.id ? 'selected' : ''}>
                        ${approver.name || 'Unknown User'}
                    </option>
                `).join('')}
            </select>

            </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm',
        preConfirm: () => {
            const collectedAmount = parseFloat(document.getElementById('collected_amount').value);
            const updatedBy = document.getElementById('updated_by').value;

            if (!collectedAmount || collectedAmount < 0) {
                Swal.showValidationMessage('Please enter a valid collected amount');
                return false;
            }

            if (!updatedBy) {
                Swal.showValidationMessage('Please select a user to assign amount');
                return false;
            }

            return {
                collected_amount: collectedAmount,
                updated_by: updatedBy,
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(`/user-cash-summaries/${summary.id}`, result.value, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire('Success!', 'Cash receipt updated successfully.', 'success');
                },
                onError: (errors) => {
                    Swal.fire('Error!', Object.values(errors).join('\n'), 'error');
                }
            });
        }
    });
};

const viewUserDetails = (userId) => {
    const params = {
        user_id: userId,
        from_date: fromDate.value,
        to_date: toDate.value,
    };

    router.get('/user-cash-details', params, {
        preserveState: true,
    });
};
</script>

<template>
<Head title="User Cash Summary" />
<AppLayout title="User Cash Summary" >
    <div class="p-6 bg-white rounded-lg shadow m-6">
        <h1 class="font-semibold text-3xl pt-4 text-primary">User Cash Summary</h1>

        <!-- Filter Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Search User</label>
                <input type="text" v-model="searchQuery" @change="applyFilters"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" v-model="fromDate" @change="applyFilters"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" v-model="toDate" @change="applyFilters"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
            <div class="flex items-end space-x-2">
                <button @click="applyFilters" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700">
                    Apply
                </button>
                <button @click="resetFilters" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Reset
                </button>
            </div>
        </div>

        <div class="flex justify-start space-x-4 pt-6">
            <button
                @click="filterSummaries('pending')"
                :class="{
                    'px-4 py-2 rounded-md': true,
                    'bg-primary text-white': filterStatus === 'pending',
                    'bg-gray-200 text-gray-700': filterStatus !== 'pending'
                }"
            >
                Pending
            </button>
            <button
                @click="filterSummaries('complete')"
                :class="{
                    'px-4 py-2 rounded-md': true,
                    'bg-primary text-white': filterStatus === 'complete',
                    'bg-gray-200 text-gray-700': filterStatus !== 'complete'
                }"
            >
                Complete
            </button>
        </div>

        <div class="pt-6">
            <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                <thead class="bg-primary rounded">
                    <tr class="divide-x divide-gray-200">
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">#</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">User</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Receipt Created</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Total Amount</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Collected Amount</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Remaining Amount</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Opening Balance</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Last Activity Time</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Created By</th>
                        <!-- <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Updated By</th> -->
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Status</th>
                        <th class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="divide-x divide-gray-200" v-for="summary in summaries" :key="summary.id">
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200">{{ summary.id }}</td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200">

                                {{ summary.user?.name }}

                        </td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200">{{ summary.receipt_count }}</td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200 text-right">{{ summary.total_amount }}</td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200 text-right">{{ summary.collected_amount }}</td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200 text-right">{{ summary.remaining_amount }}</td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200 text-right">{{ summary.opening_balance }}</td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200">{{ summary.activity_time }}</td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200">{{ summary.created_by?.name }}</td>
                        <!-- <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200">{{ summary.updated_by?.name }}</td> -->
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200">
                            <span :class="{
                                'px-2 py-1 text-xs rounded-full': true,
                                'bg-blue-100 p-2 text-blue-800': summary.status === 'pending',
                                'bg-green-100 text-green-800': summary.status === 'complete'
                            }">
                                {{ summary.status }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200">
                           <div class="flex justify-center">
                            <button
                                @click="editSummary(summary)" v-if="role && role.role_id === 1"
                                class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Cash Received
                            </button>
                            <button   @click="viewUserDetails(summary.user_id)"  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 ml-2">View Detail</button>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</AppLayout>
</template>
