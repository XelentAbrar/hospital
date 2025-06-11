<template>
  <Head title="Create Shift" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ shift?.id ? "Update" : "Create" }} Shift
          </h1>
            <form
              @submit.prevent="
                shift?.id
                  ? shift.put(route('shifts.update', { id: shift.id }))
                  : shift.post(route('shifts.store'), shift)
              "
              enctype="multipart/form-data"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 py-6">
                  <div>
                    <label
                      for="name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Name
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        type="text"
                        name="name"
                        id="name"
                        autocomplete="given-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="shift.name"
                      />
                    <InputError
                      v-if="shift.errors?.name"
                      :message="shift.errors?.name"
                    />
                  </div>
                  <div>
                    <label
                      for="start_time"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Start Time
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="start_time"
                        name="start_time"
                        type="time"
                        autocomplete="start_time"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="shift.start_time"
                      />
                    <InputError
                      v-if="shift.errors?.start_time"
                      :message="shift.errors?.start_time"
                    />
                  </div>
                  <div>
                    <label
                      for="end_time"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >End Time
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="end_time"
                        name="end_time"
                        type="time"
                        autocomplete="end_time"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="shift.end_time"
                      />
                    <InputError
                      v-if="shift.errors?.end_time"
                      :message="shift.errors?.end_time"
                    />
                  </div>
                  <div>
                    <label
                      for="late_count_time"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Late Count Time
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="late_count_time"
                        name="late_count_time"
                        type="number"
                        autocomplete="late_count_time"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="shift.late_count_time"
                      />
                    <InputError
                      v-if="shift.errors?.late_count_time"
                      :message="shift.errors?.late_count_time"
                    />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('shifts.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="shift.processing"
                >
                  Save
                </button>
              </div>
            </form>
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
  shift: {
    type: Object,
    default: null,
  },
});

const shift = useForm({
  id: props?.shift?.id || null,
  name: props?.shift?.name || null,
  start_time: props?.shift?.start_time
    ? props?.shift?.start_time.substring(0, 5)
    : null,
  end_time: props?.shift?.end_time
    ? props?.shift?.end_time.substring(0, 5)
    : null,
  late_count_time: props?.shift?.late_count_time || null,
});
</script>
