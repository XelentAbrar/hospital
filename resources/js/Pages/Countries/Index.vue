<template>
  <Head title="Countries" />

  <AppLayout title="Countries">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <h1 class="font-semibold text-3xl text-primary">Countries</h1>
            <div class="mt-2 ml-auto sm:mt-0">
              <inertia-link
                :href="route('countries.create')"
                class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                >Create Country</inertia-link
              >
            </div>
          </div>
          <div class="mt-6 flow-root">
            <div class="ring-1 ring-gray-200 sm:rounded-lg overflow-auto">
              <table
                class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto"
              >
                <thead>
                  <tr class="divide-x divide-gray-200">
                    <th
                      scope="col"
                      class="bg-primary backdrop-blur backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                    >
                      Name
                    </th>
                    <th
                      scope="col"
                      class="bg-primary backdrop-blur backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                    >
                      abrv
                    </th>
                    <th
                      scope="col"
                      class="bg-primary backdrop-blur backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                    >
                    Actions
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    class="divide-x divide-gray-200"
                    v-for="(country, index) in countries.data"
                    :key="country.id"
                    :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                  >
                    <td
                      class="whitespace-nowrap relative py-4 pl-4 pr-3 sm:pl-6 text-gray-900 text-sm md:text-base"
                    >
                      {{ country.name }}
                    </td>
                    <td
                      class="whitespace-nowrap relative py-4 pl-4 pr-3 sm:pl-6 text-gray-900 text-sm md:text-base"
                    >
                      {{ country.abrv }}
                    </td>
                    <td
                      class="relative whitespace-nowrap flex items-center justify-center space-x-1.5 md:space-x-3 py-4 pr-4 pl-3 text-right text-sm font-medium sm:pr-8"
                    >
                      <InertiaLink
                        :href="route('countries.edit', country.id)"
                        class="text-primary hover:text-accent"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 lg:w-6 h-5 lg:h-6 text-primary hover:text-accent">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                      </InertiaLink
                      >
                      <button
                        @click.prevent="deleteCountry(country.id)"
                        type="button"
                        class=""
                      >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 lg:w-6 h-5 lg:h-6 text-red-600 hover:text-red-900 ">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                      </svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="pagination-container py-4 flex justify-between px-2">
                      <div class="pagination-info flex ">
                            Page <span class="font-semibold px-1 text-gray-900 report">{{ countries.current_page }}</span> of
                              <span class="font-semibold px-1 text-gray-900 report">{{ countries.last_page }}</span>
                            <div >
                              <p class="pagination-info">
                                Showing
                                <span class="font-semibold text-gray-900 report">{{ countries.from }}</span>
                                to
                                <span class="font-semibold text-gray-900 report">{{ countries.to }}</span>
                                of
                                <span class="font-semibold text-gray-900 report">{{ countries.total }}</span>
                                entries
                              </p>
                            </div>
                          </div>  

                      <div class="pagination-boxes">
                            <template v-if="countries.prev_page_url">
                                <InertiaLink
                                    :key="'prev'"
                                    :href="countries.prev_page_url"
                                    class="pagination-box"
                                >
                                    <span>Previous</span>
                                </InertiaLink>
                            </template>

                            <template v-for="page in countries.last_page">
                                <InertiaLink
                                    :key="`page-${page}`"
                                    v-if="page === countries.current_page"
                                    :href="
                                        route('countries.index', {
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
                                        route('countries.index', {
                                            page: page,
                                           
                                        })
                                    "
                                    class="pagination-box"
                                >
                                    <span>{{ page }}</span>
                                </InertiaLink>
                            </template>
                            <template v-if="countries.next_page_url">
                                <InertiaLink
                                    :key="'next'"
                                    :href="countries.next_page_url"
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
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

const deleteCountry = (id) => {
  if (confirm("Are you sure you want to delete this country?")) {
    return Inertia.delete(route("countries.destroy", id));
  }
};
</script>
<script>
export default {
  props: {
    countries: Array,
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