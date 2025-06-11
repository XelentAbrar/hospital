<template>
  <Head title="Create Country" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ country?.id ? "Update" : "Create" }} Country
            </h1>
            <form
              @submit.prevent="
              country?.id
                  ? country.put(
                      route('countries.update', {
                        id: country.id,
                      })
                    )
                  : country.post(route('countries.store'), country)
              "
              class="md:col-span-2"
              enctype="multipart/form-data"
            >
              <div class="py-6">
                <div
                  class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4"
                >
                  <div class="">
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
                        v-model="country.name"
                      />
                    </div>
                    <InputError
                      v-if="country.errors?.name"
                      :message="country.errors?.name"
                    />
                  </div>
                  <div class="">
                    <label
                      for="abrv"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Abrv
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <input
                        id="abrv"
                        name="abrv"
                        type="text"
                        autocomplete="abrv"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="country.abrv"
                      />
                      <InputError
                      v-if="country.errors?.abrv"
                      :message="country.errors?.abrv"
                    />
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="flex items-center justify-center md:justify-end space-x-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('countries.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="country.processing"
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
  country: {
    type: Object,
    required: false,
    default: null,
  },
});

const country = useForm({
  id: props?.country?.id || null,
  name: props?.country?.name || null,
  abrv: props?.country?.abrv || null,
});

</script>
