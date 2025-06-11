<template>
  <Head title="Create State" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ state?.id ? "Update" : "Create" }} State
            </h1>
            <form
              @submit.prevent="
                state?.id
                  ? state.put(
                      route('states.update', {
                        id: state.id,
                      })
                    )
                  : state.post(route('states.store'), state)
              "
              enctype="multipart/form-data"
            >
              <div class="py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
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
                        v-model="state.name"
                      />
                      <InputError
                        v-if="state.errors?.name"
                        :message="state.errors?.name"
                      />
                    </div>
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
                        v-model="state.abrv"
                      />
                    </div>
                    <InputError
                      v-if="state.errors?.abrv"
                      :message="state.errors?.abrv"
                    />
                  </div>
                  <div class="">
                    <label
                      for="abrv"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Country
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <multiselect
                        class=" text-sm sm:text-base"
                        id="country_id"
                        v-model="selectedCountry"
                        :options="countries"
                        :searchable="true"
                        :close-on-select="true"
                        :allow-empty="false"
                        placeholder="Select a country"
                        label="name"
                        track-by="id"
                        @select="onCountrySelect"
                      />
                    </div>
                    <InputError
                      v-if="state.errors?.country_id"
                      :message="state.errors?.country_id"
                    />
                  </div>
                </div>
              </div>
              <div
                class="flex items-center justify-center md:justify-end space-x-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('states.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="state.processing"
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
import { ref, watchEffect } from "vue";
import Multiselect from "vue-multiselect";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  state: { type: Object, required: false, default: null },
  countries: { type: Array, required: true, default: () => [] },
});

const countries = ref(props?.countries || []);
const selectedCountry = ref(null);
if (props?.state?.country_id) {
  const country = props.countries.find((c) => c.id === props.state.country_id);
  if (country) {
    selectedCountry.value = country;
  }
}
const state = useForm({
  id: props?.state?.id || null,
  name: props?.state?.name || null,
  abrv: props?.state?.abrv || null,
  country_id: props?.state?.country_id || null,
});

const onCountrySelect = (selectedCountry) => {
  state.country_id = selectedCountry.id;
};

watchEffect(() => {
  countries.value = props.countries;
});
</script>

<script>
export default {
  components: {
    Multiselect,
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
