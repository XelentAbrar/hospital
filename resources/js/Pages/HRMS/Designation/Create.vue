<template>
  <Head title="Create Designation" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ designation?.id ? "Update" : "Create" }} Designation
          </h1>
            <!-- <form
              @submit.prevent="
              designation?.id
                  ? designation.put(
                      route('designations.update', { id: designation.id })
                    )
                  : designation.post(route('designations.store'), designation)
              "
              enctype="multipart/form-data"
            > -->
            <form  @submit.prevent="
            designation.processing = true;
            designation?.id
                ? designation.put(route('designations.update', { id: designation.id }))
                : designation.post(route('designations.store'), designation).finally(() => {
                    designation.processing = false;
                })
            ">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 py-6">
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
                    v-model="designation.name"
                  />
                <InputError
                    v-if="designation.errors?.name"
                    :message="designation.errors?.name"
                  />
              </div>
              <div>
                <div class="px-4 sm:px-6">
                  <ul role="list" class="mt-2 divide-y divide-gray-200">
                    <li class="flex items-center justify-between py-4">
                      <div class="flex flex-col">
                        <p
                          class="block text-sm md:text-base font-medium text-gray-900"
                          id="privacy-option-3-label"
                        >
                        Is the designation a doctor?
                        </p>
                        <p
                          class="text-sm text-gray-900"
                          id="privacy-option-3-description"
                        >
                        If the designation is "doctor", then toggle the switch to "yes".
                        </p>
                      </div>
                      <button
                        type="button"
                        :class="[
                          designation.is_doctor
                            ? 'bg-primary'
                            : 'bg-gray-200',
                          'relative ml-4 inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2  focus:ring-primary focus:ring-offset-2',
                        ]"
                        role="switch"
                        :aria-checked="designation.is_doctor"
                        @click="
                          designation.is_doctor = !designation.is_doctor
                        "
                      >
                        <span
                          aria-hidden="true"
                          :class="[
                            designation.is_doctor
                              ? 'translate-x-5'
                              : 'translate-x-0',
                            'inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                          ]"
                        ></span>
                      </button>
                    </li>
                    </ul>
                    </div>
                    <InputError
                        v-if="designation.errors?.is_doctor"
                        :message="designation.errors?.is_doctor"
                      />
                  </div>

                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('designations.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="designation.processing"
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
  designation: {
    type: Object,
    required: false,
    default: null,
  },
});

const designation = useForm({
  id: props?.designation?.id || null,
  name: props?.designation?.name || null,
  is_doctor: props?.designation?.is_doctor || false,
});

const cancel = () => {
  Inertia.visit(route('designations.index'));
};
</script>
