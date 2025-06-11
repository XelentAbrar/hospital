<template>
  <Head title="Create Attendance Machine" />
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ attendance_machine?.id ? "Update" : "Create" }} Attendance Machine
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form
              @submit.prevent="
                attendance_machine?.id
                  ? attendance_machine.put(
                      route('attendance-machines.update', {
                        id: attendance_machine.id,
                      })
                    )
                  : attendance_machine.post(route('attendance-machines.store'), attendance_machine)
              "
              class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2"
              enctype="multipart/form-data"
            >
              <div class="px-4 py-6 sm:p-8">
                <div
                  class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8"
                >
                  <div class="col-span-12 sm:col-span-3">
                    <label
                      for="location"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Location
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <input
                        type="text"
                        name="location"
                        id="location"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        v-model="attendance_machine.location"
                      />
                    </div>
                    <InputError
                      v-if="attendance_machine.errors?.location"
                      :message="attendance_machine.errors?.location"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-3">
                    <label
                      for="ip_address"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >IP Address
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <input
                        type="text"
                        name="ip_address"
                        id="ip_address"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        v-model="attendance_machine.ip_address"
                      />
                    </div>
                    <InputError
                      v-if="attendance_machine.errors?.ip_address"
                      :message="attendance_machine.errors?.ip_address"
                    />
                  </div>
                </div>
                
              </div>
              <div
                class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8"
              >
                <InertiaLink
                class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                  :href="route('attendance-machines.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="attendance_machine.processing"
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
  attendance_machine: {
    type: Object,
    default: null,
  },
});

const attendance_machine = useForm({
  id: props?.attendance_machine?.id || null,
  location: props?.attendance_machine?.location || null,
  ip_address: props?.attendance_machine?.ip_address || null,
});
</script>
