<template>
<Head title="Create Attendance" />
<AppLayout title="Create Attendance">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Attendance</h2>
    </template>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900">Create Attendance</h1>
                        <p class="mt-2 text-sm text-gray-700">Setup attendance for date.</p>
                        </div>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle">
                                <form @submit.prevent="applyFilters">
                                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-12 items-center px-4">
                                    <div class="sm:col-span-3">
                                        <label for="deduction_type" class="block text-sm font-medium leading-6 text-gray-900">Attendance Date</label>
                                        <flat-pickr class="block cursor-pointer w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6" v-model="filters.date" :config="dateConfig.value"></flat-pickr>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="deduction_type" class="block text-sm font-medium leading-6 text-gray-900">Department</label>
                                        <multiselect v-model="filters.department" :options="departments" label="name" track-by="id" placeholder="Select Department"></multiselect>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="deduction_type" class="block text-sm font-medium leading-6 text-gray-900">Employee</label>
                                        <multiselect v-model="filters.employee" :options="employees" label="name" track-by="id" placeholder="Select Employee"></multiselect>
                                    </div>
                                    <div class="sm:col-span-3 mt-5">
                                        <button type="submit" class="block rounded-md bg-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer">Apply Filters</button>
                                    </div>
                                </div>
                                 </form>
                                <table class="min-w-full border-separate border-spacing-0">
                                    <thead>
                                        <tr>
                                        <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">Date</th>
                                        <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">Department</th>
                                        <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">Employee</th>
                                        <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">Check In</th>
                                        <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">Check Out</th>
                                        <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">Remarks</th>
                                        </tr>
                                    </thead>
                                    <form @submit.prevent="submitAttendances">
                                    <tbody>
                                        <tr v-for="(attendance, index) in dailyAttendances" :key="attendance.id" :class="(index % 2 === 0) ? 'bg-blue-50' : 'bg-white'">
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                                <p class="text-sm font-medium leading-6 text-gray-900">{{attendance.date}}</p>
                                            </td>
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                                <p class="text-sm font-medium leading-6 text-gray-900">{{attendance.department.name}}</p>
                                            </td>
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                                <p class="text-sm font-medium leading-6 text-gray-900">{{ attendance.employee.name }}</p>
                                            </td>
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                                <div>
                                                    <label for="check_in" class="sr-only">Check In</label>
                                                    <input type="time" name="check_in" id="check_in" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                                <div>
                                                    <label for="check_out" class="sr-only">Check Out</label>
                                                    <input type="time" name="check_out" id="check_out" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                                <div>
                                                    <label for="remarks" class="sr-only">Remarks</label>
                                                    <input type="text" name="remarks" id="remarks" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <div class="sm:col-span-3 mt-5">
                                                    <button type="submit" class="block rounded-md bg-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer">Save Attendance</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                    </form>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link as InertiaLink } from '@inertiajs/vue3';
import { ref, onMounted  } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Multiselect from 'vue-multiselect'
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import 'flatpickr/dist/themes/airbnb.css';

const props = defineProps({
  dailyAttendances: Array,
  departments: Array,
  employees: Array,
});

let filters = ref({
  date: new URLSearchParams(window.location.search).get('date') || new Date().toISOString().substring(0, 10),
  department: null,
  employee: null,
});

let dateConfig = ref({
  dateFormat: "Y-m-d",
  defaultDate: new Date(),
});

const applyFilters = () => {
  Inertia.get('/daily-attendances/create', { ...filters.value });
};


const submitAttendances = () => {
  Inertia.post('/daily-attendances', props.dailyAttendances);
};

onMounted(() => {
  if (!new URLSearchParams(window.location.search).get('date')) {
    filters.value.date = new Date().toISOString().substring(0, 10);
  }
});

const components = {
  flatPickr,
  Multiselect,
};

</script>

<script>
export default {
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style src="flatpickr/dist/flatpickr.css"></style>