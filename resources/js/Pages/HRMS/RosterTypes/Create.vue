<template>
  <Head title="Create Roster Type" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ roster_type?.id ? "Update" : "Create" }} Roster Type
          </h1>
            <form
              @submit.prevent="
                roster_type?.id
                  ? roster_type.put(
                      route('roster-types.update', {
                        id: roster_type.id,
                      })
                    )
                  : roster_type.post(route('roster-types.store'), roster_type)
              "
              enctype="multipart/form-data"
            >
                <div
                  class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6"
                >
                  <div class="">
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
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="roster_type.name"
                      />
                    <InputError
                      v-if="roster_type.errors?.name"
                      :message="roster_type.errors?.name"
                    />
                  </div>
                </div>
                
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('roster-types.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="roster_type.processing"
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
  roster_type: {
    type: Object,
    default: null,
  },
});

const roster_type = useForm({
  id: props?.roster_type?.id || null,
  name: props?.roster_type?.name || null,
});
</script>
