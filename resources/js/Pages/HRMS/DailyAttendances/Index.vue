<template>
  <Head title="Daily Attendance" />

  <AppLayout title="Daily Attendance">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white overflow-hidden shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
          <h1 class="font-semibold text-3xl text-primary">
            Daily Attendance
          </h1>
          <!-- <inertia-link :href="route('departments.create')" class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer">Create Department</inertia-link> -->
        </div>
        <div class="mt-4 flow-root">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 items-center">
              <div>
                <label
                  for="deduction_type"
                  class="block text-sm font-medium leading-6 text-gray-900"
                  >Attendance Date</label
                >
                <flat-pickr
                  class="block cursor-pointer w-full rounded border-0 py-2.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  v-model="filters.date"
                  :config="dateConfig"
                ></flat-pickr>
              </div>
              <div>
                <label
                  for="deduction_type"
                  class="block text-sm font-medium leading-6 text-gray-900"
                  >Department</label
                >
                <multiselect
                  v-model="filters.department"
                  :options="departments"
                  label="name"
                  track-by="id"
                  placeholder="Select Department"
                ></multiselect>
              </div>
              <div>
                <label
                  for="deduction_type"
                  class="block text-sm font-medium leading-6 text-gray-900"
                  >Employee</label
                >
                <multiselect
                  v-model="filters.employee"
                  :options="employees.data"
                  label="name"
                  track-by="id"
                  placeholder="Select Employee"
                ></multiselect>
              </div>
              <div class="mt-5">
                <button
                  class="block rounded bg-primary w-fit px-4 py-2.5 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                  @click="applyFilters"
                >
                  Apply Filters
                </button>
              </div>
            </div>
          <div class="shadow-md sm:rounded-lg overflow-x-auto overflow-y-hidden mt-6">
             <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-gray-800">
                    Department
                  </th>
                  <th
                    scope="col"
                    class="whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-gray-800"
                  >
                    Employee
                  </th>
                  <th
                    scope="col"
                    class="whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-gray-800"
                  >
                    Designation
                  </th>
                  <th
                    scope="col"
                    class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800"
                  >
                    Check In
                  </th>
                  <th
                    scope="col"
                    class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800"
                  >
                    Check Out
                  </th>
                  <th
                    scope="col"
                    class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800"
                  >
                    Remarks
                  </th>
                  <th
                    scope="col"
                    class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800"
                  >
                    Update
                  </th>
                </tr>
              </thead>
               <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:shadow-md transition-shadow duration-200"
                    v-for="(employee) in employees.data"
                    :key="employee.id"
                  >
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      {{ employee.department.name }}
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      {{ employee.name }}
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      {{ employee?.designation?.name }}
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <input
                        v-if="employee.daily_attendances?.[0]"
                        v-model="employee.daily_attendances[0].check_in"
                        type="time"
                        name="check_in"
                        :id="'check_in_' + employee.id"
                        class="found block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      />
                      <input
                        v-else
                        v-model="getNewAttendance(employee).check_in"
                        type="time"
                        name="check_in"
                        :id="'check_in_' + employee.id"
                        class="not-found block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      />
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <input
                        v-if="employee.daily_attendances?.[0]"
                        v-model="employee.daily_attendances[0].check_out"
                        type="time"
                        name="check_out"
                        :id="'check_out_' + employee.id"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      />
                      <input
                        v-else
                        v-model="getNewAttendance(employee).check_out"
                        type="time"
                        name="check_out"
                        :id="'check_out_' + employee.id"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      />
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <input
                        v-if="employee.daily_attendances?.[0]"
                        v-model="employee.daily_attendances[0].remarks"
                        type="text"
                        name="remarks"
                        :id="'remarks_' + employee.id"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      />
                      <input
                        v-else
                        v-model="getNewAttendance(employee).remarks"
                        type="text"
                        name="remarks"
                        :id="'remarks_' + employee.id"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      />
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <button
                        class="px-3 py-1.5 bg-[#6FA3D8] text-white mx-auto rounded"
                        @click="saveAttendance(employee)"
                      >
                        Save
                      </button>
                    </td>
                  </tr>
                <!-- <tr v-if="dailyAttendances.length === 0">
              <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8" colspan="9">No records found</td>
            </tr> -->
                    </tbody>
                  </table>
                </div>
                <div class="pagination-container py-4 flex justify-between px-1">
                      <div class="pagination-info flex ">
                            Page <span class="font-semibold px-1 text-gray-900 report">{{ employees.current_page }}</span> of
                              <span class="font-semibold px-1 text-gray-900 report">{{ employees.last_page }}</span>
                            <div >
                              <p class="pagination-info">
                                Showing
                                <span class="font-semibold text-gray-900 report">{{ employees.from }}</span>
                                to
                                <span class="font-semibold text-gray-900 report">{{ employees.to }}</span>
                                of
                                <span class="font-semibold text-gray-900 report">{{ employees.total }}</span>
                                entries
                              </p>
                            </div>
                          </div>

                      <div class="pagination-boxes">
                            <template v-if="employees.prev_page_url">
                                <InertiaLink
                                    :key="'prev'"
                                    :href="employees.prev_page_url"
                                    class="pagination-box"
                                >
                                    <span>Previous</span>
                                </InertiaLink>
                            </template>

                            <template v-for="page in employees.last_page">
                                <InertiaLink
                                    :key="`page-${page}`"
                                    v-if="page === employees.current_page"
                                    :href="
                                        route('daily-attendances.index', {
                                            page: page,

                                        })
                                    "
                                    class="pagination-box current"
                                >
                                    <span>{{ page }}</span>
                                </InertiaLink>
                                <InertiaLink
                                    :key="`page-${page}`"
                                    v-else
                                    :href="
                                        route('daily-attendances.index', {
                                            page: page,

                                        })
                                    "
                                    class="pagination-box"
                                >
                                    <span>{{ page }}</span>
                                </InertiaLink>
                            </template>
                            <template v-if="employees.next_page_url">
                                <InertiaLink
                                    :key="'next'"
                                    :href="employees.next_page_url"
                                    class="pagination-box"
                                >
                                    <span>Next</span>
                                </InertiaLink>
                            </template>
                        </div>


                    </div>
            </div>
          <!-- <button @click="saveAttendance(employee)">Save</button> -->
        </div>
      </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink,router } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Multiselect from "vue-multiselect";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import "flatpickr/dist/themes/airbnb.css";
import axios from "axios";
import Swal from 'sweetalert2';

const props = defineProps({
  departments: Array,
  employees: Array,
  message: String,
});

let filters = ref({
  date:
    new URLSearchParams(window.location.search).get("date") ||
    new Date().toISOString().substring(0, 10),
  department: null,
  employee: null,
});

let dateConfig = {
  altFormat: "F j, Y",
  dateFormat: "Y-m-d",
};

const applyFilters = () => {
  const url = new URL(window.location);
  url.searchParams.set("date", filters.value.date);

  if (filters.value.department) {
    url.searchParams.set("department_id", filters.value.department.id);
  } else {
    url.searchParams.delete("department_id");
  }

  if (filters.value.employee) {
    console.log(filters.value.employee);
    url.searchParams.set("employee_id", filters.value.employee.id);
  } else {
    url.searchParams.delete("employee_id");
  }

  history.pushState({}, "", url);
  console.log(url.toString());
//   Inertia.get(url).catch((error) => {
//     console.error("An error occurred:", error);
//   });
Inertia.get(url.toString(), {}, { preserveState: true })
    .then(response => {
      console.log(response);
        employees.value = response.data.employees;
    })
    .catch(error => {
      console.error("An error occurred:", error);
    });
};
let newAttendance = ref({
  check_in: null,
  check_out: null,
  remarks: null,
});

const getNewAttendance = (employee) => {
  if (!employee.newAttendance) {
    employee.newAttendance = reactive({ check_in: '', check_out: '', remarks: '' });
  }
  return employee.newAttendance;
};

const saveAttendance = (employee) => {
  const attendances = Array.isArray(employee.daily_attendances)
    ? employee.daily_attendances
    : [];

  const newAttendance = getNewAttendance(employee);

  const data = {
    employee_id: employee.id,
    date: filters.value.date,
    check_in: attendances[0]?.check_in || newAttendance.check_in || '',
    check_out: attendances[0]?.check_out || newAttendance.check_out || '',
    remarks: attendances[0]?.remarks || newAttendance.remarks || '',
  };
  axios
    .post("/update-attendance", data)
    .then((response) => {
        Swal.fire({
        title: 'Success!',
        text: 'Attendance saved successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
      });
    })
    .catch((error) => {
      console.error("An error occurred:", error);
      Swal.fire({
        title: 'Error!',
        text: 'There was an error saving attendance.',
        icon: 'error',
        confirmButtonText: 'OK',
      });
    });
};

onMounted(() => {
  if (!new URLSearchParams(window.location.search).get("date")) {
    filters.value.date = new Date().toISOString().substring(0, 10);
  }
});

watch(
  () => props.message,
  (newValue) => {
    if (newValue) {
      console.log(newValue); // Or show the message to the user in some other way...
    }
  }
);

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
<style scoped>
.pagination-container {
    align-items: center;
}
.pagination-boxes {
  margin-top: 0px;
    display: flex;
    gap: 8px;
}
.pagination-box {
    display: inline-block;
    padding: 10px 16px;
    background-color: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    text-decoration: none;
    color: #4a4a4a;
    transition: background-color 0.3s, color 0.3s;
}
.pagination-box:hover {
    background-color: #e5e7eb;
    color: #333;
}
.current {
    background-color: #3b7a99;
    color: white;
}
.pagination-info {
    margin-top: 0px;
    color: #6b7280;
    font-size: 16px;
    padding-left: 4px;
}
.multiselect__tags{
  border-radius: 4px !important;
}
</style>
