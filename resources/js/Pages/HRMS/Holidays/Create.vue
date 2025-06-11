<template>
  <Head title="Create Holiday" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ holiday?.id ? "Update" : "Create" }} Holidays
            </h1>
            <form
              @submit.prevent="
              holiday?.id
                  ? holiday.put(
                      route('holidays.update', {
                        id: holiday.id,
                      })
                    )
                  : holiday.post(route('holidays.store'), holiday)
              "
              class="md:col-span-2"
              enctype="multipart/form-data"
            >
              <div class="py-6">
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-6">
                  <div class="sm:col-span-3 md:col-span-2 col-span-12">
                    <label
                      for="name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Name
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <input
                        type="text"
                        name="name"
                        id="name"
                        autocomplete="given-name"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="holiday.name"
                      />
                    </div>
                    <InputError
                      v-if="holiday.errors?.name"
                      :message="holiday.errors?.name"
                    />
                  </div>
                  <div class="sm:col-span-3 md:col-span-2 col-span-12">
                    <label
                      for="start_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Start Date
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <input
                        id="start_date"
                        name="start_date"
                        type="date"
                        autocomplete="start_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="holiday.start_date"
                      />
                    </div>
                    <InputError
                      v-if="holiday.errors?.start_date"
                      :message="holiday.errors?.start_date"
                    />
                  </div>
                  <div class="sm:col-span-3 md:col-span-2 col-span-12">
                    <label
                      for="end_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >End Date
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <input
                        id="end_date"
                        name="end_date"
                        type="date"
                        autocomplete="end_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="holiday.end_date"
                      />
                    </div>
                    <InputError
                      v-if="holiday.errors?.end_date"
                      :message="holiday.errors?.end_date"
                    />
                  </div>
                  <div class="sm:col-span-3 md:col-span-6 col-span-12">
                    <label
                      for="notes"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Notes</label
                    >
                    <div class="mt-2">
                      <input
                        id="notes"
                        name="notes"
                        type="text"
                        autocomplete="notes"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="holiday.notes"
                      />
                    </div>
                    <InputError
                      v-if="holiday.errors?.notes"
                      :message="holiday.errors?.notes"
                    />
                  </div>
                </div>
              </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('holidays.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="holiday.processing"
                >
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  holiday: {
    type: Object,
    required: false,
    default: null
  },
});

const holiday = useForm({
    id: props?.holiday?.id || null,
  name: props?.holiday?.name || null,
  start_date: props?.holiday?.start_date || null,
  end_date: props?.holiday?.end_date || null,
  notes: props?.holiday?.notes || null,
});

</script>
