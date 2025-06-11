<template>
  <Head title="Attendance machines" />

  <AppLayout title="Attendance machines">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white overflow-hidden shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
          <h1 class="font-semibold text-3xl text-primary">
            Attendance machines
          </h1>
          <inertia-link
            :href="route('attendance-machines.create')"
            class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
            >Create attendance machines</inertia-link
          >
        </div>
        <div class="mt-6 flow-root">
          <div class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
            <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
              <thead>
                <tr class="divide-x divide-gray-200">
                  <th
                    scope="col"
                    class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                  >
                    Location
                  </th>
                  <th
                    scope="col"
                    class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                  >
                    IP Address
                  </th>
                  <th
                    scope="col"
                    class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                  >
                    Attendance
                  </th>
                  <th
                    scope="col"
                    class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                  >
                    Actions
                  </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                  v-for="(
                    attendance_machine, index
                  ) in attendance_machines.data"
                  :key="attendance_machine.id"
                  :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                  class="divide-x divide-gray-200"
                      >
                  <td
                    class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                  >
                    {{ attendance_machine.location }}
                  </td>
                  <td
                    class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                  >
                    {{ attendance_machine.ip_address }}
                  </td>
                  <!-- <td
                    class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                  >
                    <InertiaLink
                      :href="
                        route(
                          'get-machine-attendance',
                          attendance_machine.id
                        )
                      "
                      class="bg-blue-900 px-2 py-2 rounded-md  text-white   hover:text-blue-600 mr-2"
                      >Get Attendance</InertiaLink
                    >
                  </td> -->
                  <td class="relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200 whitespace-nowrap">
                        <InertiaLink
                            :href="route('get-machine-attendance', attendance_machine.id)"
                            class="bg-blue-900 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-blue-700 transition-colors duration-300"
                            @click.prevent="setLoading(attendance_machine.id, true)"
                        >
                            Get Attendance
                        </InertiaLink>
                        <div v-if="loadingStates[attendance_machine.id]" class="absolute inset-0 flex justify-center items-center bg-white bg-opacity-60 rounded-md">
                            <div class="text-center">
                                <div role="status">
                                    <svg
                                        aria-hidden="true"
                                        class="inline w-8 h-8 text-gray-300 animate-spin fill-blue-600"
                                        viewBox="0 0 100 101"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                            fill="currentColor"
                                        />
                                        <path
                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                            fill="currentFill"
                                        />
                                    </svg>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </td>

                  <td class="relative whitespace-nowrap border-b border-gray-200 p-4">
                          <div class="flex items-center justify-center space-x-3" v-if="role && role.role_id === 1">
                    <InertiaLink
                      :href="
                        route(
                          'attendance-machines.edit',
                          attendance_machine.id
                        )
                      "
                      class="text-primary hover:text-accent"
                      >
                      <svg
                              xmlns="http://www.w3.org/2000/svg"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              class="w-5 lg:w-6 h-5 lg:h-6 text-primary hover:text-accent"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                              />
                            </svg>
                    </InertiaLink>
                    <button
                      @click.prevent="
                        deleteAttendanceMachine(attendance_machine.id)
                      "
                      type="button"
                    >
                    <svg
                              xmlns="http://www.w3.org/2000/svg"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              class="w-5 lg:w-6 h-5 lg:h-6 text-red-600 hover:text-red-900"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                              />
                            </svg>
                    </button>
                    </div>
                  </td>
                      </tr>
                    </tbody>
                  </table>
          </div>
          <div class="pagination-container py-4 flex justify-between px-1">
                      <div class="pagination-info flex ">
                            Page <span class="font-semibold px-1 text-gray-900 report">{{ attendance_machines.current_page }}</span> of
                              <span class="font-semibold px-1 text-gray-900 report">{{ attendance_machines.last_page }}</span>
                            <div >
                              <p class="pagination-info">
                                Showing
                                <span class="font-semibold text-gray-900 report">{{ attendance_machines.from }}</span>
                                to
                                <span class="font-semibold text-gray-900 report">{{ attendance_machines.to }}</span>
                                of
                                <span class="font-semibold text-gray-900 report">{{ attendance_machines.total }}</span>
                                entries
                              </p>
                            </div>
                          </div>

                      <div class="pagination-boxes">
                            <template v-if="attendance_machines.prev_page_url">
                                <InertiaLink
                                    :key="'prev'"
                                    :href="attendance_machines.prev_page_url"
                                    class="pagination-box"
                                >
                                    <span>Previous</span>
                                </InertiaLink>
                            </template>

                            <template v-for="page in attendance_machines.last_page">
                                <InertiaLink
                                    :key="`page-${page}`"
                                    v-if="page === attendance_machines.current_page"
                                    :href="
                                        route('attendance-machines.index', {
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
                                        route('attendance-machines.index', {
                                            page: page,

                                        })
                                    "
                                    class="pagination-box"
                                >
                                    <span>{{ page }}</span>
                                </InertiaLink>
                            </template>
                            <template v-if="attendance_machines.next_page_url">
                                <InertiaLink
                                    :key="'next'"
                                    :href="attendance_machines.next_page_url"
                                    class="pagination-box"
                                >
                                    <span>Next</span>
                                </InertiaLink>
                            </template>
                        </div>


                    </div>
        </div>
        </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink , router } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import Swal from 'sweetalert2';
import { ref } from 'vue';

const loadingStates = ref({});

const setLoading = (machineId, isLoading) => {
    loadingStates.value[machineId] = isLoading;
    if (isLoading) {
        router.visit(route('get-machine-attendance', machineId), {
            onFinish: () => setLoading(machineId, false)
        });
    }
};
const deleteAttendanceMachine = (id) => {
//   if (confirm("Are you sure you want to delete this attendance machine?")) {
//     return Inertia.delete(route("attendance-machines.destroy", id));
//   }
Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route("attendance-machines.destroy", id));
        }
    });
};
</script>
<script>
export default {
  props: {
    attendance_machines: Array,
    role: Object,
  },
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
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
    background-color: #6D4C41;
    color: white;
}

.pagination-info {
    margin-top: 0px;
    color: #6b7280;
    font-size: 16px;
    padding-left: 4px;
}
</style>
