<template>
  <Head title="Create Patient" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ form?.id ? "Update" : "Create" }} Patient
            </h1>
            <!-- <form
              @submit.prevent="
                form?.id
                  ? form.put(route('patients.update', { id: form.id }))
                  : form.post(route('patients.store'), form)
              "
            > -->
            <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('patients.update', { id: form.id }))
                : form.post(route('patients.store'), form).finally(() => {
                    form.processing = false;
                })
            ">
            <div class="py-6">
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                  <div>
                    <label
                      for="name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >MR Number
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="mr_number"
                        name="mr_number"
                        type="text"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        :value="mr_number"
                        @input="generateMRNumber"
                      />
                    <InputError
                      v-if="form.errors?.mr_number"
                      :message="form.errors?.mr_number"
                    />
                  </div>
                  <div>
                    <label
                      for="name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Name
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="name"
                        name="name"
                        type="text"
                        autocomplete="name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.name"
                      />
                    <InputError
                      v-if="form.errors?.name"
                      :message="form.errors?.name"
                    />
                  </div>
                  <div>
                    <label
                      for="cnic"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >CNIC
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="cnic"
                        name="cnic"
                        type="text"
                        autocomplete="cnic"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.cnic"
                         @input="formatCNIC" maxlength="15"
                      />
                    <InputError
                      v-if="form.errors?.cnic"
                      :message="form.errors?.cnic"
                    />
                  </div>
                  <div>
                    <label
                      for="dob"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >DOB</label
                    >
                      <input
                        id="dob"
                        name="dob"
                        type="date"
                        autocomplete="dob"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.dob"
                      />
                    <InputError
                      v-if="form.errors?.dob"
                      :message="form.errors?.dob"
                    />
                  </div>
                  <div>
                    <label
                      for="gender"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Gender</label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedGender"
                        :options="genderOptions"
                        :custom-label="genderCustomLabel"
                        @select="onGenderSelect"
                        label="type"
                        track-by="value"
                        placeholder="Select Gender"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form.errors?.gender"
                      :message="form.errors?.gender"
                    />
                  </div>
                  <div>
                    <label
                      for="age"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Age</label
                    >
                      <input
                        id="age"
                        name="age"
                        type="text"
                        autocomplete="age"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.age"
                      />
                    <InputError
                      v-if="form.errors?.age"
                      :message="form.errors?.age"
                    />
                  </div>
                  <div>
                    <label
                      for="relation_name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Relation name</label
                    >
                      <input
                        id="relation_name"
                        name="relation_name"
                        type="text"
                        autocomplete="relation_name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.relation_name"
                      />
                    <InputError
                      v-if="form.errors?.relation_name"
                      :message="form.errors?.relation_name"
                    />
                  </div>
                  <div>
                    <label
                      for="relation_type"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Relation type</label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedRelationType"
                        :options="relationTypeOptions"
                        :custom-label="relationTypeCustomLabel"
                        @select="onRelationTypeSelect"
                        label="type"
                        track-by="value"
                        placeholder="Select relation type"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form.errors?.relation_type"
                      :message="form.errors?.relation_type"
                    />
                  </div>

                  <div>
                    <label
                      for="address"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Address</label
                    >
                      <input
                        id="address"
                        name="address"
                        type="text"
                        autocomplete="address"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.address"
                      />
                    <InputError
                      v-if="form.errors?.address"
                      :message="form.errors?.address"
                    />
                  </div>
                  <div>
                    <label
                      for="country"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Country</label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedCountry"
                        :options="countryOptions"
                        @select="onCountrySelect"
                        label="name"
                        track-by="id"
                        placeholder="Select a Country"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form.errors.country_id"
                      :message="form.errors.country_id"
                    />
                  </div>
                  <div>
                    <label
                      for="state"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >State</label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedState"
                        :options="stateOptions"
                        @select="onStateSelect"
                        label="name"
                        track-by="id"
                        placeholder="Select a State"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form.errors.state_id"
                      :message="form.errors.state_id"
                    />
                  </div>
                  <div>
                    <label
                      for="city"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >City</label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedCity"
                        :options="cityOptions"
                        @select="onCitySelect"
                        label="name"
                        track-by="id"
                        placeholder="Select a City"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form.errors.city_id"
                      :message="form.errors.city_id"
                    />
                  </div>
                  <div>
                    <label
                      for="phone"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Phone</label
                    >
                      <input
                        id="phone"
                        name="phone"
                        type="text"
                        autocomplete="phone"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.phone"
                        @input="formatPhone" maxlength="12"
                      />
                    <InputError
                      v-if="form.errors?.phone"
                      :message="form.errors?.phone"
                    />
                  </div>
                  <div>
                    <label
                      for="email"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Email</label
                    >
                      <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.email"
                      />
                    <InputError
                      v-if="form.errors?.email"
                      :message="form.errors?.email"
                    />
                  </div>
                  <div>
                    <label
                      for="blood_group"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Blood Group</label
                    >
                    <div class="mt-2">
                    <multiselect
                      v-model="selectedBloodGroup"
                      :options="bloodGroupOptions"
                      @select="onBloodGroupSelect"
                      label="type"
                      track-by="type"
                      placeholder="Select Blood Group"
                    ></multiselect>
                  </div>
                    <InputError
                      v-if="form.errors.blood_group"
                      :message="form.errors.blood_group"
                    />
                  </div>
                  <div>
                    <label
                      for="rh_factor"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >RH Factor</label
                    >
                      <input
                        id="rh_factor"
                        name="rh_factor"
                        type="text"
                        autocomplete="rh_factor"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.rh_factor"
                      />
                    <InputError
                      v-if="form.errors?.rh_factor"
                      :message="form.errors?.rh_factor"
                    />
                  </div>
                </div>
              </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('patients.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit" :disabled="form.processing"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
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
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { ref, computed, defineComponent, watch,onMounted } from "vue";
import InputError from "../../Components/InputError.vue";
import axios from "axios";

const props = defineProps({
  patient: Object,
  mr_number: String,
  countries: { type: Array, required: true, default: () => [] },
  states: { type: Array, required: false, default: () => [] },
  cities: { type: Array, required: false, default: () => [] },
});

const form = useForm({
  id: props?.patient?.id || null,
  name: props?.patient?.name || null,
  cnic: props?.patient?.cnic || null,
  mr_number: props?.patient?.mr_number || null,
  dob: props?.patient?.dob || null,
  gender: props?.patient?.gender || null,
  relation_name: props?.patient?.relation_name || null,
  age: props?.patient?.age || null,
  relation_type: props?.patient?.relation_type || null,
  address: props?.patient?.address || null,
  state_id: props?.patient?.state_id || null,
  city_id: props?.patient?.city_id || null,
  country_id: props?.patient?.country_id || null,
  phone: props?.patient?.phone || null,
  email: props?.patient?.email || null,
  blood_group: props?.patient?.blood_group || null,
  rh_factor: props?.patient?.rh_factor || null,
});

// Gender
const selectedGender = ref();
const genderOptions = computed(() => {
  return [
    { value: "Male", label: "Male" },
    { value: "Female", label: "Female" },
    { value: "Children", label: "Children" },
  ];
});

const genderCustomLabel = (option) => option.label;
const onGenderSelect = async (selectedGender) => {
  form.gender = selectedGender.value;
};
if (props?.patient?.gender) {
  selectedGender.value = genderOptions.value.find(
    (option) => option.value === props.patient.gender
  );
}
onMounted(() => {
  form.mr_number = mr_number || "";
  });
// relation type
const selectedRelationType = ref();
const relationTypeOptions = computed(() => {
  return [
    { value: "Guardian", label: "Guardian" },
    { value: "Spouse", label: "Spouse" },
    { value: "Parent", label: "Parent" },
    { value: "Sibling", label: "Sibling" },
    { value: "Child", label: "Child" },
    { value: "Friend", label: "Friend" },
    { value: "Relative", label: "Relative" },
  ];
});
const formatPhone = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 4) {
    input = input.replace(/^(\d{4})(\d+)/, "$1-$2");
  }
  form.phone = input;
};
const formatCNIC = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 5) {
    input = input.replace(/^(\d{5})(\d{7})(\d{1})$/, "$1-$2-$3");
  }
  form.cnic = input;
};
const generateMRNumber = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  let number = input ? parseInt(input) : 1;
  let formattedNumber = number.toString().padStart(5, '0');
  form.mr_number = formattedNumber;
};
const relationTypeCustomLabel = (option) => option.label;
const onRelationTypeSelect = async (selectedRelationType) => {
  form.relation_type = selectedRelationType.value;
};
if (props?.patient?.relation_type) {
  selectedRelationType.value = relationTypeOptions.value.find(
    (option) => option.value === props.patient.relation_type
  );
}

const countries = ref(props.countries || []);

const selectedCountry = ref(null);
if (props?.patient) {
  const country = props.countries.find(
    (c) => c.id == props.patient.country_id
  );
  if (country) {
    selectedCountry.value = country;
  }
}

const countryOptions = computed(() => {
  return countries.value && countries.value.length ? countries.value : [];
});

const onCountrySelect = async (selectedCountry) => {
  form.country_id = selectedCountry.id;
  selectedState.value = null;
  form.state_id = null;
  states.value = [];
  selectedCity.value = null;
  form.city_id = null;
  cities.value = [];
  await loadStates(selectedCountry.id);
};

const loadStates = async (countryId) => {
  try {
    const response = await axios.get(`/states/${countryId}`);
    states.value = response.data.states;
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};

const states = ref(props.states || []);
const selectedState = ref(null);
if (props?.patient) {
  const state = props.states.find((s) => s.id == props.patient.state_id);
  if (state) {
    selectedState.value = state;
  }
}

const stateOptions = computed(() => {
  return states.value && states.value.length ? states.value : [];
});

const onStateSelect = async (selectedState) => {
  form.state_id = selectedState.id;
  selectedCity.value = null;
  form.city_id = null;
  cities.value = [];
  await loadCities(selectedState.id);
};

const loadCities = async (stateId) => {
  try {
    const response = await axios.get(`/cities-by-state/${stateId}`);
    cities.value = response.data.cities;
  } catch (error) {
    console.error("Error fetching cities:", error);
  }
};

const cities = ref(props.cities || []);
const selectedCity = ref(null);
if (props?.patient) {
  const city = props.cities.find((c) => c.id == props.patient.city_id);
  if (city) {
    selectedCity.value = city;
  }
}

const cityOptions = computed(() => {
  return cities.value && cities.value.length ? cities.value : [];
});

const onCitySelect = (selectedCity) => {
  form.city_id = selectedCity.id;
};

const bloodGroupOptions = computed(() => {
  const bloodGroup = [
    { type: "A+" },
    { type: "A-" },
    { type: "B+" },
    { type: "B-" },
    { type: "AB+" },
    { type: "AB-" },
    { type: "O+" },
    { type: "O-" },
  ];
  return bloodGroup;
});

const selectedBloodGroup = ref(null);
if (props?.patient?.blood_group) {
  selectedBloodGroup.value = bloodGroupOptions.value.find(
    (option) => option.type === props.patient.blood_group
  );
}
const onBloodGroupSelect = async (selectedBloodGroup) => {
  form.blood_group = selectedBloodGroup.type;
};

const cancel = () => {
  Inertia.visit(route("patients.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
  <style src="vue-multiselect/dist/vue-multiselect.css"></style>
