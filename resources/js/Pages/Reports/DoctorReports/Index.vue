<template>
  <Head title="Doctor Report" />
  <AppLayout title="Doctor Report">
    <div class="py-4 px-4 mx-auto max-w-screen-xl lg:py-8">
        <div class="bg-blue-50 border border-gray-300 rounded-lg p-8 md:p-12 mb-8 shadow-md">
            <h1 class="text-gray-900 text-3xl md:text-4xl font-extrabold mb-2">Doctor Report</h1>
            <form @submit.prevent="form.get(route('doctor-reports.index'))" enctype="multipart/form-data">
                <div class="px-4 py-6 sm:p-8">
                    <label for="date" class="block text-lg font-semibold leading-6 text-gray-900">Department</label>
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
                <div class="flex gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                    <button type="submit" class="text-white bg-primary hover:bg-accent focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2" :disabled="form.processing">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5 me-2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
        <div class="inline-block min-w-full py-2 align-middle sm:px-6">
            <div class="ring-1 ring-gray-300">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr class="divide-x divide-gray-300">
                          <th
                            scope="col"
                            class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                          >
                          Name
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                        >
                          Email
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                        >
                          Mobile
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                        >
                          Department
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                        >
                          Designation
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                        >
                          Employee type
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 bg-white">
                        <tr class="divide-x divide-gray-300"
                        v-for="(employee, index) in employees"
                        :key="employee.id"
                        :class="index % 2 === 0 ? 'bg-blue-50' : 'bg-white'"
                      >
                        <td
                          class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                        >
                          {{ employee.name }}
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                        >
                          {{ employee.email }}
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                        >
                          {{ employee.mobile }}
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                        >
                          {{ employee?.department?.name }}
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                        >
                          {{ employee?.designation?.name }}
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                        >
                          {{ employee.employee_type }}
                        </td>
                      </tr>
                    </tbody>
                </table>
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
  department_id: new URLSearchParams(window.location.search).get("department_id") || null,
});

const selectedDepartment = ref(null);

const onDepartmentSelect = async (selectedDepartment) => {
  form.department_id = selectedDepartment.id;
};
</script>

<script>
export default {
  props: {
    employees: Array,
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
<style>
.p_4{
  padding: 16px;
}
table {
    width: 100%;
    height: auto;
    border-collapse: collapse;
}
table th, table tfoot td{
  padding: 12px 14px;
  font-size:18px;
}
table td{
  padding: 10px 12px;
  font-size: 14px;
}
.print-only {
    display: none;
}
.text_white{
  color: #fff;
}
  @media only screen and (min-width: 1536px) {
  table th, table tfoot td{
    padding: 14px 16px;
    font-size:20px;
  }
  table td{
  padding: 10px 12px;
  font-size: 18px;
}
}
/* CSS to show the element only when printing */
@media print {
  .print-only {
      display: block;
  }
  .print-hide {
      display: none;
  }
  table th{
    padding: 4px;
    font-size: 12px !important;
    line-height: 10px !important;
  }
  table tfoot td{
    padding: 4px;
    font-size: 12px !important;
    line-height: 14px !important;
  }
  table td{
    padding: 4px;
    font-size: 8px !important;
    line-height: 10px !important;
  }
  .p_4{
    padding: 4px !important;
  }
  .text_white{
    color: #000 !important;
  }
  @page { size: A4; margin: 0; }
  body { margin: 0; }
.a4-size {
    width: 210mm;
    height: auto;
}
}
</style>