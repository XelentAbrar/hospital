<template>
  <Head title="Create City" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ city?.id ? "Update" : "Create" }} City
            </h1>
            <form
              @submit.prevent="
              city?.id
                  ? city.put(
                      route('cities.update', {
                        id: city.id,
                      })
                    )
                  : city.post(route('cities.store'), city)
              "
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
                        v-model="city.name"
                      />
                    </div>
                    <InputError
                        v-if="city.errors?.name"
                        :message="city.errors?.name"
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
                        v-model="city.abrv"
                      />
                    </div>
                    <InputError
                        v-if="city.errors?.abrv"
                        :message="city.errors?.abrv"
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
                        id="country_id"
                        class=" text-sm sm:text-base"
                        v-model="selectedCountry"
                        :options="countries"
                        :searchable="true"
                        :close-on-select="true"
                        :allow-empty="false"
                        placeholder="Select a country"
                        label="name"
                        track-by="id"
                        :custom-label="countryCustomLabel"
                        @select="onCountrySelect"
                      />
                    </div>
                    <InputError
                        v-if="city.errors?.country_id"
                        :message="city.errors?.country_id"
                      />
                  </div>
                  <div class="">
                    <label
                      for="state_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >State
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <multiselect
                        id="state_id"
                        class=" text-sm sm:text-base"
                        v-model="selectedState"
                        :options="states"
                        :searchable="true"
                        :close-on-select="true"
                        :allow-empty="false"
                        placeholder="Select a state"
                        label="name"
                        track-by="id"
                        :custom-label="stateCustomLabel"
                        @select="onStateSelect"
                      />
                    </div>
                    <InputError
                        v-if="city.errors?.state_id"
                        :message="city.errors?.state_id"
                      />
                  </div>
                </div>
              </div>
              <div
                class="flex items-center justify-center md:justify-end space-x-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('cities.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="city.processing"
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
import { ref } from "vue";
import Multiselect from "vue-multiselect";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  countries: { type: Array, required: true, default: () => [] },
  states: { type: Array, required: false, default: () => [] },
  city: { type: Object, required: false, default:null },
});

const city = useForm({
  id: props?.city?.id || null,
  name: props?.city?.name || null,
  abrv: props?.city?.abrv || null,
  country_id: props?.city?.country_id || null,
  state_id: props?.city?.state_id || null,
});

const countries = ref(props.countries || []);
const selectedCountry = ref(null);
if (props?.city?.country_id) {
  const country = props.countries.find(
    (c) => c.id === props.city.country_id
  );
  if (country) {
    selectedCountry.value = country;
  }
}

const states = ref(props.states || []);
const selectedState = ref(null);

if (props?.city?.state_id) {
  const state = props.states.find((c) => c.id === props.city.state_id);
  if (state) {
    selectedState.value = state;
  }
}

const onStateSelect = (selectedState) => {
  city.state_id = selectedState.id;
};

const stateCustomLabel = (state) => {
  return `${state.name}`;
};


const countryCustomLabel = (country) => {
  return `${country.name}`;
};

const onCountrySelect = async (selectedCountry) => {
  city.country_id = selectedCountry.id;
  await loadStates(selectedCountry.id);
};

const loadStates = async (countryId) => {
  try {
    const response = await axios.get(`/states-by-country/${countryId}`);
    states.value = response.data.states;
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};
</script>

<script>
export default {
  components: {
    Multiselect,
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>