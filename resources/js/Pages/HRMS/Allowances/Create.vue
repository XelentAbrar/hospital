<template>
  <Head title="Create Allowance" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ allowance?.id ? "Update" : "Create" }} Allowance
          </h1>
            <!-- <form
              @submit.prevent="
              allowance?.id
                  ? allowance.put(
                      route('allowances.update', {
                        id: allowance.id,
                      })
                    )
                  : allowance.post(route('allowances.store'), allowance)
              "
              enctype="multipart/form-data"
            > -->
            <form  @submit.prevent="
            allowance.processing = true;
            allowance?.id
                ? allowance.put(route('allowances.update', { id: allowance.id }))
                : allowance.post(route('allowances.store'), allowance).finally(() => {
                    allowance.processing = false;
                })
            ">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
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
                        v-model="allowance.name"
                      />
                    <InputError
                      v-if="allowance.errors?.name"
                      :message="allowance.errors?.name"
                    />
                  </div>
                  <div class="md:col-span-2">
                    <label
                      for="notes"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Notes</label
                    >
                      <input
                        id="notes"
                        name="notes"
                        type="text"
                        autocomplete="notes"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="allowance.notes"
                      />
                    <InputError
                      v-if="allowance.errors?.notes"
                      :message="allowance.errors?.notes"
                    />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('allowances.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="allowance.processing"
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
  allowance: {
    type: Object,
    default: null,
  },
});

const allowance = useForm({
  id: props?.allowance?.id || null,
  name: props?.allowance?.name || null,
  notes: props?.allowance?.notes || null,
});
</script>
