<template>
    <Head title="Activity Log" />
    <AppLayout title="Activity Log">
      <div class="w-full mx-auto pt-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              Activity Log
            </h1>

            <!-- Search and Filter Controls -->
            <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-6 mt-4">
              <div class="mt-2 sm:mt-0">
                <input
                  ref="fromDateInput"
                  id="from_date"
                  type="text"
                  autocomplete="off"
                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                  placeholder="From Date"
                  v-model="filters.from_date"
                />
              </div>
              <div class="mt-2 sm:mt-0">
                <input
                  ref="toDateInput"
                  id="to_date"
                  type="text"
                  autocomplete="off"
                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                  placeholder="To Date"
                  v-model="filters.to_date"
                />
              </div>
              <div class="mt-2 sm:mt-0 md:col-span-2">
                <input
                  id="search"
                  type="text"
                  autocomplete="off"
                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                  placeholder="Search by name"
                  v-model="filters.search"
                  @keyup.enter="applyFilters"
                />
              </div>
              <div class="mt-2 sm:mt-0">
                <button
                  @click="applyFilters"
                  class="w-full rounded bg-primary px-4 py-2 text-sm md:text-base font-medium text-white shadow-sm hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                  :disabled="isLoading"
                >
                  <span v-if="!isLoading">Search</span>
                  <span v-else>Searching...</span>
                </button>
              </div>
              <div class="mt-2 sm:mt-0">
                <button
                  @click="resetFilters"
                  class="w-full rounded bg-gray-200 px-4 py-2 text-sm md:text-base font-medium text-gray-700 shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                >
                  Reset
                </button>
              </div>
            </div>

            <!-- Activity Log Table -->
            <div class="mt-6 flow-root">
              <div class="ring-1 ring-gray-200 rounded-lg overflow-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-primary">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-center text-sm md:text-base font-medium text-white">
                        Name
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-sm md:text-base font-medium text-white">
                        Activity
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-sm md:text-base font-medium text-white">
                        Activity Time
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr
                      v-for="(activity, index) in activityLogs.data"
                      :key="activity.id"
                      :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                    >
                      <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-900">
                        {{ activity.user?.name || "N/A" }}
                      </td>
                      <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-900">
                        {{ activity.activity_type }}
                      </td>
                      <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-900">
                        {{ formatDateTime(activity.activity_time) }}
                      </td>
                    </tr>
                    <tr v-if="activityLogs.data.length === 0">
                      <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                        No activity logs found
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div class="pagination-container py-4 flex justify-between px-4">
                <div class="pagination-info flex items-center">
                  Page
                  <span class="font-semibold px-2 text-gray-900">{{ activityLogs.current_page }}</span>
                  of
                  <span class="font-semibold px-2 text-gray-900">{{ activityLogs.last_page }}</span>
                  <p class="ml-4">
                    Showing
                    <span class="font-semibold px-1 text-gray-900">{{ activityLogs.from }}</span>
                    to
                    <span class="font-semibold px-1 text-gray-900">{{ activityLogs.to }}</span>
                    of
                    <span class="font-semibold px-1 text-gray-900">{{ activityLogs.total }}</span>
                    entries
                  </p>
                </div>
                <div class="pagination-links">
                  <template v-if="activityLogs.links">
                    <template v-for="(link, index) in activityLogs.links" :key="index">
                      <InertiaLink
                        v-if="link.url"
                        :href="link.url + (filters.search || filters.from_date || filters.to_date ? '&' + getFilterQueryString() : '')"
                        v-html="link.label"
                        :class="[
                          'pagination-box',
                          {
                            'pagination-box-current': link.active,
                          },
                        ]"
                        preserve-scroll
                      />
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
  import { ref, onMounted } from "vue";
  import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
  import AppLayout from "@/Layouts/AppLayout.vue";
  import flatpickr from "flatpickr";
  import "flatpickr/dist/flatpickr.min.css";

  const props = defineProps({
    activityLogs: Object,
    filters: Object,
  });

  const isLoading = ref(false);
  const fromDateInput = ref(null);
  const toDateInput = ref(null);

  const filters = useForm({
    search: props.filters.search || "",
    from_date: props.filters.from_date || "",
    to_date: props.filters.to_date || "",
  });

  const formatDateTime = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date
      .toLocaleString("en-GB", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      })
      .replace(/,/g, "");
  };

  const getFilterQueryString = () => {
    const params = new URLSearchParams();
    if (filters.search) params.append('search', filters.search);
    if (filters.from_date) params.append('from_date', filters.from_date);
    if (filters.to_date) params.append('to_date', filters.to_date);
    return params.toString();
  };

  const applyFilters = () => {
    isLoading.value = true;
    filters.get(route("activity-log"), {
      preserveState: true,
      preserveScroll: true,
      onFinish: () => {
        isLoading.value = false;
      },
    });
  };

  const resetFilters = () => {
    filters.search = "";
    filters.from_date = "";
    filters.to_date = "";
    applyFilters();
  };

  onMounted(() => {
    flatpickr(fromDateInput.value, {
      dateFormat: "d-m-Y",
      allowInput: true,
      onChange: (selectedDates, dateStr) => {
        filters.from_date = dateStr;
      },
    });

    flatpickr(toDateInput.value, {
      dateFormat: "d-m-Y",
      allowInput: true,
      onChange: (selectedDates, dateStr) => {
        filters.to_date = dateStr;
      },
    });
  });
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
    background-color: #6d4c41;
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
