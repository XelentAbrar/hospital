<template>
    <Head title="Procedure Type Reports" />
    <AppLayout title="Procedure Type Reports">
        <div class="w-full mx-auto pt-6">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                    <div
                        class="flex flex-col md:flex-row items-start md:items-center justify-between"
                    >
                        <h1 class="font-semibold text-3xl text-primary">
                            Procedure Type Reports
                        </h1>
                        <div class="mt-2 ml-auto sm:mt-0">
                            <form
                                @submit.prevent="
                                    report.get(
                                        route('ot.report-procedure-type'),
                                        report
                                    )
                                "
                            >
                                <div
                                    class="grid grid-cols-1 gap-4 md:grid-cols-3"
                                >
                                <div>
                                        <label for="search_term" class="block text-sm md:text-base font-medium text-gray-900">Search</label>
                                        <input
                                            id="search_term"
                                            name="search_term"
                                            type="text"
                                            v-model="report.search_term"
                                            class="mt-2 block w-full rounded py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300"
                                            placeholder="Search by procedure, patient, surgeon, file no, OT number, or amounts"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="from_date"
                                            class="block text-sm md:text-base font-medium text-gray-900"
                                            >From Date</label
                                        >
                                        <div class="mt-2">
                                            <input
                                                id="from_date"
                                                name="from_date"
                                                type="date"
                                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                v-model="report.from_date"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            for="to_date"
                                            class="block text-sm md:text-base font-medium text-gray-900"
                                            >To Date</label
                                        >
                                        <div class="mt-2">
                                            <input
                                                id="to_date"
                                                name="to_date"
                                                type="date"
                                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                v-model="report.to_date"
                                            />
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label
                                            for="search"
                                            class="block text-sm md:text-base font-medium text-gray-900"
                                            >&nbsp;</label
                                        >
                                        <button
                                            type="submit"
                                            class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                                        >
                                            Search
                                        </button>
                                        <button
                                            class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2"
                                            @click="print()"
                                        >
                                            Print
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-6 flow-root" id="printData">
                        <div
                            style="
                                font-family: Arial, Helvetica, sans-serif;
                                padding: 20px;
                                display: flex;
                                flex-direction: column;
                            "
                        >
                            <div class="print-only">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="flex items-center relative space-x-4 mt-2 mb-4 ml-4 pb-2 border-b-4 border-[#FFC470] w-3/5"
                                    >
                                        <img
                                            class="h-auto w-16"
                                            src="/build/assets/logo-20b0503a.png"
                                            alt=""
                                        />
                                        <h1
                                            class="doctor-info text-2xl font-bold"
                                        >
                                            CHINIOT GENERAL HOSPITAL
                                        </h1>
                                        <div
                                            class="border border-[#8B322C] w-1/2 h-0.5 absolute right-0 -bottom-[3px]"
                                        ></div>
                                    </div>
                                    <div class="space-y-3 ml-4 mt-2 w-2/5">
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <div
                                                class="border border-[#FFC470] p-1 rounded-full"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="w-4 h-4 text-[#FFC470]"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </div>
                                            <p v-if="projectType === 'hms'">
                                                Ph: 041-8848060 | 8787231
                                            </p>
                                            <p v-else>
                                                Ph: 041-2619764 | 2634890
                                            </p>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="border border-[#FFC470] p-1 rounded-full"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="w-4 h-4 text-[#FFC470]"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </div>
                                            <p v-if="projectType === 'hms'">
                                                Nawaz Town Sargodha Road,
                                                Faisalabad
                                            </p>
                                            <p v-else>
                                                209 Jinnah Colony , Faisalabad
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="border-dashed border-2 border-gray-400 rounded px-4 mt-5"
                                >
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="flex items-center">
                                                <h4
                                                    class="font-semibold text-sm"
                                                >
                                                    User:
                                                </h4>
                                                <p class="ml-2">All</p>
                                            </div>
                                        </div>
                                        <div class="flex justify-start">
                                            <div class="flex items-center">
                                                <h4
                                                    class="font-semibold text-sm"
                                                >
                                                    Date & Time:
                                                </h4>
                                                <p class="ml-2">
                                                    {{
                                                        new Date().toLocaleString()
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between mt-3">
                                        <div>
                                            <div class="flex items-center">
                                                <h4
                                                    class="font-semibold text-sm"
                                                >
                                                    From:
                                                </h4>
                                                <p class="ml-2">
                                                    {{
                                                        new Date(from_date)
                                                            .toLocaleDateString(
                                                                "en-GB"
                                                            )
                                                            .replace(/\//g, "-")
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex justify-start">
                                            <div class="flex items-center">
                                                <h4
                                                    class="font-semibold text-sm"
                                                >
                                                    To:
                                                </h4>
                                                <p class="ml-2">
                                                    {{
                                                        new Date(to_date)
                                                            .toLocaleDateString(
                                                                "en-GB"
                                                            )
                                                            .replace(/\//g, "-")
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="mt-5 text-center font-semibold text-lg"
                                >
                                    Procedure Type Reports
                                </div>
                            </div>

                            <div
                                class="overflow-auto ring-1 ring-gray-300 mt-4"
                            >
                                <table
                                    class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300"
                                >
                                    <thead>
                                        <tr class="divide-x divide-gray-300">
                                            <th
                                                scope="col"
                                                class="bg-primary text-white text-center font-bold p-4 sticky top-0 z-10"
                                            >
                                                Operation Date
                                            </th>
                                            <th
                                                scope="col"
                                                class="bg-primary text-white text-center font-bold p-4 sticky top-0 z-10"
                                            >
                                                Procedure Type
                                            </th>
                                            <th
                                                scope="col"
                                                class="bg-primary text-white text-center font-bold p-4 sticky top-0 z-10"
                                            >
                                                File No
                                            </th>
                                            <th
                                                scope="col"
                                                class="bg-primary text-white text-center font-bold p-4 sticky top-0 z-10"
                                            >
                                                OT Number
                                            </th>
                                            <th
                                                scope="col"
                                                class="bg-primary text-white text-center font-bold p-4 sticky top-0 z-10"
                                            >
                                                Patient Name
                                            </th>
                                            <th
                                                scope="col"
                                                class="bg-primary text-white text-center font-bold p-4 sticky top-0 z-10"
                                            >
                                                Surgeon Name
                                            </th>

                                            <th
                                                scope="col"
                                                class="bg-primary text-white text-center font-bold p-4 sticky top-0 z-10"
                                            >
                                                Advance Amount
                                            </th>
                                            <th
                                                scope="col"
                                                class="bg-primary text-white text-center font-bold p-4 sticky top-0 z-10"
                                            >
                                                Received Amount
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(report, index) in reports"
                                            :key="index"
                                            :class="
                                                index % 2 === 0
                                                    ? 'bg-gray-50'
                                                    : 'bg-white'
                                            "
                                        >
                                            <td
                                                class="border border-gray-200 text-center text-gray-900 p-4"
                                            >
                                                {{  new Date(report.operation_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                            </td>
                                            <td
                                                class="border border-gray-200 text-center text-gray-900 p-4"
                                            >
                                                {{ report.procedure_type }}
                                            </td>
                                            <td
                                                class="border border-gray-200 text-center text-gray-900 p-4"
                                            >
                                                {{ report.file_no }}
                                            </td>
                                            <td
                                                class="border border-gray-200 text-center text-gray-900 p-4"
                                            >
                                                {{ report.ot_number }}
                                            </td>
                                            <td
                                                class="border border-gray-200 text-center text-gray-900 p-4"
                                            >
                                                {{ report.patient_name }}
                                            </td>
                                            <td
                                                class="border border-gray-200 text-center text-gray-900 p-4"
                                            >
                                                {{ report.surgeon_name }}
                                            </td>
                                            <td
                                                class="border border-gray-200 text-center text-gray-900 p-4"
                                            >
                                                {{ report.advance_amount }}
                                            </td>
                                            <td
                                                class="border border-gray-200 text-center text-gray-900 p-4"
                                            >
                                                {{ report.received_amount }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
const projectType = import.meta.env.VITE_PROJECT_TYPE;
const props = defineProps({
    reports: Array,
    from_date: String,
    to_date: String,
    search_term: String,
});

const report = useForm({
    from_date: props?.from_date || null,
    to_date: props?.to_date || null,
    search_term: props?.search_term || null,
});

const print = () => {
    var header_str =
        "<html><head><title>" + document.title + "</title></head><body>";
    var footer_str = "</body></html>";
    var new_str = document.getElementById("printData").innerHTML;
    var old_str = document.body.innerHTML;
    document.body.innerHTML = header_str + new_str + footer_str;
    window.print();
    window.onafterprint = () => {
        document.body.innerHTML = old_str;
        window.location.reload();
    };
    return false;
};
</script>
<script>
import Multiselect from "vue-multiselect";
export default {
    components: {
        Multiselect,
        AppLayout,
        Head,
        InertiaLink,
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style>
td {
    text-align: left;
    padding: 10px 8px;
    font-size: 14px;
}

th {
    font-size: 14px;
    font-weight: 600;
    color: black;
    padding: 4px 8px;
    text-align: center;
    height: 24px;
}

table {
    width: 100%;
    height: auto;
    border-collapse: collapse;
}
table th,
table tfoot td {
    padding: 12px 14px;
    font-size: 16px;
}
table td {
    padding: 10px 12px;
    font-size: 14px;
}

.total_row {
    border-left: 1px solid;
    border-right: 1px solid;
}

.total_row td {
    border: none;
    font-weight: 600;
}

table tfoot tr {
    border: 1px solid;
}

table tfoot td {
    border: none;
    font-size: 14px;
    font-weight: 600;
    text-align: left;
}
.print-only {
    display: none;
}
.text_white {
    color: #fff;
}
@media only screen and (min-width: 1600px) {
    table th,
    table tfoot td {
        padding: 14px 16px;
        font-size: 18px;
    }
}
/* CSS to show the element only when printing */
@media print {
    .print-only {
        display: block;
    }
    table th,
    table tfoot td {
        padding: 4px;
        font-size: 10px !important;
        line-height: 10px !important;
    }
    table td {
        padding: 4px;
        font-size: 8px !important;
        line-height: 10px !important;
    }
    .text_white {
        color: #000 !important;
    }
}
</style>
