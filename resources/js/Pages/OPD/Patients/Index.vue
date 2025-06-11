<template>
  <Head title="Patients" />
  <AppLayout title="Patients">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between"
          >
            <h1 class="font-semibold text-3xl text-primary">Patients</h1>
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
                :href="route('patients.create')"
                class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                >Create patient</inertia-link
              >
            </div>
          </div>

          <div class="mt-6 flow-root">
            <div
              class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden"
            >
              <table
                class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto"
              >
                <thead>
                  <tr class="divide-x divide-gray-200">
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      ID
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Mr
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Name
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      CNIC
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      DOB
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Gender
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Address
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Phone
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Email
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Blood group
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      RH Factor
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-6 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                    >
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    class="divide-x divide-gray-200"
                    v-for="(patient, index) in patients.data"
                    :key="patient.id"
                    :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                  >
                    <td
                      class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.id }}
                    </td>
                    <td
                      class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.mr_number }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.name }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.cnic }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.dob }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.gender }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.address }} {{ patient?.city?.name || "" }}
                      {{ patient?.state?.name || "" }}
                      {{ patient?.country?.name || "" }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.phone }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.email }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.blood_group }}
                    </td>
                    <td
                      class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient.rh_factor }}
                    </td>
                    <td class="relative p-4 border-b border-gray-200">
                      <div
                        class="flex items-center justify-center space-x-1.5 md:space-x-3" v-if="role && role.role_id === 1"
                      >
                        <InertiaLink :href="route('patients.edit', patient.id)">
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
                          @click.prevent="deletePatient(patient.id)"
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
            <!-- <div class="pagination-container py-4 flex justify-between px-1">
                      <div class="pagination-info flex ">
                            Page <span class="font-semibold px-1 text-gray-900 report">{{ patients.current_page }}</span> of
                              <span class="font-semibold px-1 text-gray-900 report">{{ patients.last_page }}</span>
                            <div >
                              <p class="pagination-info">
                                Showing
                                <span class="font-semibold text-gray-900 report">{{ patients.from }}</span>
                                to
                                <span class="font-semibold text-gray-900 report">{{ patients.to }}</span>
                                of
                                <span class="font-semibold text-gray-900 report">{{ patients.total }}</span>
                                entries
                              </p>
                            </div>
                          </div>

                      <div class="pagination-boxes">
                            <template v-if="patients.prev_page_url">
                                <InertiaLink
                                    :key="'prev'"
                                    :href="patients.prev_page_url"
                                    class="pagination-box"
                                >
                                    <span>Previous</span>
                                </InertiaLink>
                            </template>

                            <template v-for="page in patients.last_page">
                                <InertiaLink
                                    :key="`page-${page}`"
                                    v-if="page === patients.current_page"
                                    :href="
                                        route('patients.index', {
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
                                        route('patients.index', {
                                            page: page,
                                            search: filters.search,
                                        })
                                    "
                                    class="pagination-box"
                                >
                                    <span>{{ page }}</span>
                                </InertiaLink>
                            </template>
                            <template v-if="patients.next_page_url">
                                <InertiaLink
                                    :key="'next'"
                                    :href="patients.next_page_url"
                                    class="pagination-box"
                                >
                                    <span>Next</span>
                                </InertiaLink>
                            </template>
                        </div>


                    </div> -->
                    <div class="pagination-container py-4 flex justify-between px-4">
                            <div class="pagination-info flex">
                                Page
                                <span class="font-semibold px-2 text-gray-900 report">{{
                                    patients.current_page }}</span>
                                of
                                <span class="font-semibold px-2 text-gray-900 report">{{ patients.last_page
                                    }}</span>
                                <div>
                                    <p class="pagination-info">
                                        Showing
                                        <span class="font-semibold px-1  text-gray-900 report">{{
                                            patients.from }}</span>
                                        to
                                        <span class="font-semibold px-1  text-gray-900 report">{{
                                            patients.to }}</span>
                                        of
                                        <span class="font-semibold px-1  text-gray-900 report">{{
                                            patients.total }}</span>
                                        entries
                                    </p>
                                </div>
                            </div>
                            <div class="pagination-links">
                                <template v-if="patients.links">
                                    <template v-for="(link, index) in patients.links" :key="index">
                                        <InertiaLink v-if="link.url" :href="link.url" v-html="link.label"
                                            :class="['pagination-box', { 'pagination-box-current': link.active }]">
                                        </InertiaLink>
                                    </template>
                                </template>
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
import { Head, Link as InertiaLink , router } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import Swal from 'sweetalert2';

const deletePatient = (id) => {

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
            Inertia.delete(route("patients.destroy", id));
        }
    });
};

const searchItem = async (val) => {
  try {
    router.visit(
        route("patients.index", {
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
    patients: Array,
    filters: Array,
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
.pagination-box {
    padding: 10px 16px;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
    margin: 0 0.25rem;
    text-decoration: none;
}

.pagination-box-current {
    background-color: #6D4C41;
    color: #fff;
}

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1rem;
}

.pagination-info {
    display: flex;
    align-items: center;
}

.pagination-links {
    display: flex;
}
</style>
