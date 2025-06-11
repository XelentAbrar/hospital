<template>
  <Head title="Create Patient Case Study" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ form?.id ? "Update" : "Create" }} Patient Case Study
            </h1>
            <form
              @submit.prevent="
                form?.id
                  ? form.put(route('patient-case-studies.update', { id: form.id }))
                  : form.post(route('patient-case-studies.store'), form)
              "
            >
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-8 py-6">
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="patient_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Patient
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <multiselect
                        v-model="selectedPatient"
                        :options="patients"
                        @select="onPatientSelect"
                        label="name"
                        track-by="id"
                        placeholder="Select patient"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form?.errors?.patient_id"
                      :message="form?.errors?.patient_id"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-6">
                    <label
                      for="food_allergies"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Food allergies</label
                    >
                      <input
                        id="food_allergies"
                        name="food_allergies"
                        type="text"
                        autocomplete="food_allergies"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.food_allergies"
                      />
                    <InputError
                      v-if="form?.errors?.food_allergies"
                      :message="form?.errors?.food_allergies"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="tendency_bleed"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Tendency bleed</label
                    >
                      <input
                        id="tendency_bleed"
                        name="tendency_bleed"
                        type="text"
                        autocomplete="tendency_bleed"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.tendency_bleed"
                      />
                    <InputError
                      v-if="form?.errors?.tendency_bleed"
                      :message="form?.errors?.tendency_bleed"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="heart_disease"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Heart disease</label
                    >
                      <input
                        id="heart_disease"
                        name="heart_disease"
                        type="text"
                        autocomplete="heart_disease"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.heart_disease"
                      />
                    <InputError
                      v-if="form?.errors?.heart_disease"
                      :message="form?.errors?.heart_disease"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="high_blood_pressure"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >High blood pressure</label
                    >
                      <input
                        id="high_blood_pressure"
                        name="high_blood_pressure"
                        type="text"
                        autocomplete="high_blood_pressure"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.high_blood_pressure"
                      />
                    <InputError
                      v-if="form?.errors?.high_blood_pressure"
                      :message="form?.errors?.high_blood_pressure"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="diabetic"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Diabetic</label
                    >
                      <input
                        id="diabetic"
                        name="diabetic"
                        type="text"
                        autocomplete="diabetic"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.diabetic"
                      />
                    <InputError
                      v-if="form?.errors?.diabetic"
                      :message="form?.errors?.diabetic"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="surgery"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Surgery</label
                    >
                      <input
                        id="surgery"
                        name="surgery"
                        type="text"
                        autocomplete="surgery"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.surgery"
                      />
                    <InputError
                      v-if="form?.errors?.surgery"
                      :message="form?.errors?.surgery"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="accident"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Accident</label
                    >
                      <input
                        id="accident"
                        name="accident"
                        type="text"
                        autocomplete="accident"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.accident"
                      />
                    <InputError
                      v-if="form?.errors?.accident"
                      :message="form?.errors?.accident"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="family_medical_history"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Family medical history</label
                    >
                      <input
                        id="family_medical_history"
                        name="family_medical_history"
                        type="text"
                        autocomplete="family_medical_history"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.family_medical_history"
                      />
                    <InputError
                      v-if="form?.errors?.family_medical_history"
                      :message="form?.errors?.family_medical_history"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="current_medication"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Current medication</label
                    >
                      <input
                        id="current_medication"
                        name="current_medication"
                        type="text"
                        autocomplete="current_medication"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.current_medication"
                      />
                    <InputError
                      v-if="form?.errors?.current_medication"
                      :message="form?.errors?.current_medication"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="female_pregnancy"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Female pregnancy</label
                    >
                      <input
                        id="female_pregnancy"
                        name="female_pregnancy"
                        type="text"
                        autocomplete="female_pregnancy"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.female_pregnancy"
                      />
                    <InputError
                      v-if="form?.errors?.female_pregnancy"
                      :message="form?.errors?.female_pregnancy"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="breast_feeding"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Breast feeding</label
                    >
                      <input
                        id="breast_feeding"
                        name="breast_feeding"
                        type="text"
                        autocomplete="breast_feeding"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.breast_feeding"
                      />
                    <InputError
                      v-if="form?.errors?.breast_feeding"
                      :message="form?.errors?.breast_feeding"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="health_insurance"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Health insurance</label
                    >
                      <input
                        id="health_insurance"
                        name="health_insurance"
                        type="text"
                        autocomplete="health_insurance"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.health_insurance"
                      />
                    <InputError
                      v-if="form?.errors?.health_insurance"
                      :message="form?.errors?.health_insurance"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
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
                        v-model="form.notes"
                      />
                    <InputError
                      v-if="form?.errors?.notes"
                      :message="form?.errors?.notes"
                    />
                  </div>
                </div>
              <div
                class="flex items-center justify-end gap-x-4 py-4"
              >
                <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('patient-case-studies.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
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
import { ref, defineComponent, watch } from "vue";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  patient_case_study: Object,
  patients: Array,
});

const form = useForm({
  id: props?.patient_case_study?.id || null,
  patient_id: props?.patient_case_study?.patient_id || null,
  food_allergies: props?.patient_case_study?.food_allergies || null,
  tendency_bleed: props?.patient_case_study?.tendency_bleed || null,
  heart_disease: props?.patient_case_study?.heart_disease || null,
  high_blood_pressure: props?.patient_case_study?.high_blood_pressure || null,
  diabetic: props?.patient_case_study?.diabetic || null,
  surgery: props?.patient_case_study?.surgery || null,
  accident: props?.patient_case_study?.accident || null,
  family_medical_history: props?.patient_case_study?.family_medical_history || null,
  current_medication: props?.patient_case_study?.current_medication || null,
  female_pregnancy: props?.patient_case_study?.female_pregnancy || null,
  breast_feeding: props?.patient_case_study?.breast_feeding || null,
  health_insurance: props?.patient_case_study?.health_insurance || null,
  notes: props?.patient_case_study?.notes || null,
});

const selectedPatient = ref(null);
if (props?.patient_case_study) {
  const group = props.patients.find(
    (c) => c.id == props.patient_case_study.patient_id
  );
  if (group) {
    selectedPatient.value = group;
  }
}

const onPatientSelect = async (selectedPatient) => {
  form.patient_id = selectedPatient.id;
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
    <style src="vue-multiselect/dist/vue-multiselect.css"></style>