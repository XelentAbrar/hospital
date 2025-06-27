<template>
    <Head title="Employees" />

    <AppLayout title="Employees">
        <div class="w-full mx-auto pt-6">
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6"
            >
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between"
                >
                    <h1 class="font-semibold text-3xl text-primary">
                        Employees
                    </h1>
                    <div class="mt-2 ml-auto sm:mt-0">
                        <input
                            id="name"
                            name="name"
                            type="text"
                            autocomplete="name"
                            class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                            step="0.01"
                            placeholder="Search"
                            :value="filters?.search"
                            @keyup.enter="searchItem($event.target.value)"
                        />
                    </div>
                    <div class="mt-2 ml-auto sm:mt-0">
                        <inertia-link
                            :href="route('employees.create')"
                            class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                            >Create Employee</inertia-link
                        >
                    </div>
                </div>
                <div class="mt-6 flow-root">
                    <div
                        class="sm:rounded-lg shadow-md overflow-x-auto overflow-y-hidden"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                              <tr>
                                <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800">
                                  ID
                                </th>
                                <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-gray-800">
                                  Name
                                </th>
                                <!-- <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800">
                                  Email	
                                </th> -->
                                <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-gray-800">
                                  Mobile
                                </th>
                                <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800">
                                  Designation
                                </th>
                                <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800">
                                    Department
                                </th>
                                <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800">
                                  Employee type	
                                </th>
                                <th scope="col" class="whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-gray-800">
                                  Actions
                                </th>
                              </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                              <tr class="hover:shadow-md transition-shadow duration-200"
                                    v-for="(employee) in employees.data"
                                    :key="employee.id"
                                >
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                      {{ employee.id }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                  <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                      <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60" alt="">
                                    </div>
                                    <div class="ml-4">
                                      <div class="text-sm font-medium text-gray-900">
                                        {{ employee.name }}
                                      </div>
                                      <div class="text-sm text-gray-500">
                                           {{ employee?.email }}
                                      </div>
                                    </div>
                                  </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                     {{ employee.mobile }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-center"> 
                                    {{ employee?.designation?.name }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                    {{ employee?.department?.name }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                  <span class="px-2 inline-flex text-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ employee.employee_type }}
                                  </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-sm text-gray-500">
                                   <div
                                            class="flex items-center justify-center space-x-3"  v-if="role && role.role_id === 1 || role && role.role_id === 7"
                                        >
                                            <InertiaLink
                                                :href="
                                                    route(
                                                        'employees.edit',
                                                        employee.id
                                                    )
                                                "
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
                                            <!-- <InertiaLink :href="`/employees/${employee.id}/edit`" class="text-primary hover:text-accent">Edit</InertiaLink> -->
                                            <button v-if="role && role.role_id === 1 || role && role.role_id === 7"
                                                @click.prevent="
                                                    deleteEmployee(employee.id)
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
                                        route('employees.index', {
                                            page: page,
                                            search: filters.search,
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
                                        route('employees.index', {
                                            page: page,
                                            search: filters.search,
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
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, router } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
// const deleteEmployee = (id) => {
//     if (confirm("Are you sure you want to delete this employee?")) {
//         return Inertia.delete(route("employees.destroy", id));
//     }
// };
const deleteEmployee = (id) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Proceed with the deletion
      Inertia.delete(route("employees.destroy", id));
    }
  });
};
const searchItem = async (val) => {
  try {
    router.visit(
      route("employees.index", {
        search: val,
      }),
      {
        preserveState: true,
        preserveScroll: true,
      }
    );
  } catch (error) {
    console.error("Error fetching cities:", error);
  }
};
</script>
<script>
export default {
  props: {
    employees: Array,
    filters: Array,
    pagination: Object,
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

/* .current {
  background-color: #6d4c41;
  color: white;
} */
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
.custom_shadow {
  box-shadow: rgba(67, 71, 85, 0.27) 0px 0px 0.25em,
    rgba(90, 125, 188, 0.05) 0px 0.25em 1em;
}
.bottom_shadow {
  box-shadow: rgba(33, 35, 38, 0.1) 0px 10px 10px -10px;
}
</style>
