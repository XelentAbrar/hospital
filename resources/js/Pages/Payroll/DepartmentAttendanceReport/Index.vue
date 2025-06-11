<template>
    <Head title="Department Attendance Report" />
    <AppLayout title="Department Attendance Report">
        <div class="py-4 px-4 mx-auto max-w-screen-xl lg:py-8">
            <div
                class="bg-blue-50 border border-gray-200 rounded-lg p-8 md:p-12 mb-8 shadow-md"
            >
                <h1
                    class="text-gray-900 text-3xl md:text-4xl font-extrabold mb-2"
                >
                    Department wise Attendance Report
                </h1>
                <form
                    @submit.prevent="form.get(route('department-attendance-report.index'))"
                    enctype="multipart/form-data"
                >
                    <div class="px-4 py-6 sm:p-8">
                        <label
                            for="date"
                            class="block text-lg font-semibold leading-6 text-gray-900"
                            >Department</label
                        >
                        <div class="mt-2">
                            <multiselect
                                v-model="selectedDepartment"
                                :options="departments"
                                :searchable="true"
                                :close-on-select="true"
                                :show-labels="false"
                                @select="onDepartmentSelect"
                                placeholder="Select department"
                                label="name"
                                track-by="id"
                            />
                        </div>
                    </div>
                    <div
                        class="flex gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8"
                    >
                        <button
                            type="submit"
                            class="text-white bg-primary hover:bg-accent focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2"
                            :disabled="form.processing"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-3.5 h-3.5 me-2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                                />
                            </svg>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
                class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
            >
                <div
                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg"
                >
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr class="divide-x divide-gray-200">
                                <th
                                    scope="col"
                                    class="bg-primary px-4 py-3.5 text-left text-sm font-semibold text-white"
                                >
                                    ID
                                </th>
                                <th
                                    scope="col"
                                    class="bg-primary px-4 py-3.5 text-left text-sm font-semibold text-white"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="bg-primary px-4 py-3.5 text-left text-sm font-semibold text-white"
                                >
                                    Check in
                                </th>
                                <th
                                    scope="col"
                                    class="bg-primary px-4 py-3.5 text-left text-sm font-semibold text-white"
                                >
                                    Check out
                                </th>
                                <th
                                    scope="col"
                                    class="bg-primary px-4 py-3.5 text-left text-sm font-semibold text-white"
                                >
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr
                                class="divide-x divide-gray-200"
                                v-for="(attendance, index) in attendances"
                                :key="attendance.id"
                                :class="
                                    index % 2 === 0 ? 'bg-white' : 'bg-blue-50'
                                "
                            >
                                <td
                                    class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900"
                                >
                                    {{ attendance.id }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900"
                                >
                                    {{ attendance.employee.name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900"
                                >
                                    {{ attendance.check_in }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900"
                                >
                                    {{ attendance.check_out }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900"
                                >
                                {{ new Date(attendance.date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Multiselect from "vue-multiselect";

const form = useForm({
    department_id:
        new URLSearchParams(window.location.search).get("department_id") ||
        null,
});

const selectedDepartment = ref(null);

const onDepartmentSelect = async (selectedDepartment) => {
    form.department_id = selectedDepartment.id;
};
</script>

<script>
export default {
    props: {
        attendances: Array,
        departments: Array,
    },
    components: {
        AppLayout,
        Head,
        InertiaLink,
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
