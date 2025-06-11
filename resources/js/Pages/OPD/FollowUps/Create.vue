<template>
  <Head title="Create follow up" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ form?.id ? "Update" : "Create" }} follow up
            </h1>
            <!-- <form
              @submit.prevent="
                form?.id
                  ? form.put(route('follow-ups.update', { id: form.id }))
                  : form.post(route('follow-ups.store'), form)
              "
            > -->
            <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('follow-ups.update', { id: form.id }))
                : form.post(route('follow-ups.store'), form).finally(() => {
                    form.processing = false;
                })
            ">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-6 ">
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="appointment_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Appointment
                      <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 ">
                      <multiselect

                        v-model="selectedAppointment"
                        :options="appointments"
                        @select="onAppointmentSelect"
                        label="id"
                        track-by="id"
                        placeholder="Select an appointment"
                          class="custom-multiselect"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form?.errors?.appointment_id"
                      :message="form?.errors?.appointment_id"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="follow_up_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Follow up date
                      <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2">
                      <input
                        id="follow_up_date"
                        name="follow_up_date"
                        type="date"
                        autocomplete="follow_up_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.follow_up_date"
                      />
                    </div>
                    <InputError
                      v-if="form.errors?.follow_up_date"
                      :message="form.errors?.follow_up_date"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-4">
                    <label
                      for="notes"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Notes</label
                    >
                    <div class="mt-2">
                      <input
                        id="notes"
                        name="notes"
                        type="text"
                        autocomplete="notes"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.notes"
                      />
                    </div>
                    <InputError
                      v-if="form.errors?.notes"
                      :message="form.errors?.notes"
                    />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('follow-ups.index')"
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
import { ref, computed, defineComponent } from "vue";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  follow_up: Object,
  appointments: { type: Array, required: true, default: () => [] },
});

const form = useForm({
  id: props?.follow_up?.id || null,
  appointment_id: props?.follow_up?.appointment_id || null,
  follow_up_date: props?.follow_up?.follow_up_date || null,
  notes: props?.follow_up?.notes || null,
});

const appointments = ref(props.appointments || []);

const selectedAppointment = ref(null);
if (props?.follow_up) {
  const appointment = props.appointments.find(
    (c) => c.id == props.follow_up.appointment_id
  );
  if (appointment) {
    selectedAppointment.value = appointment;
  }
}

const onAppointmentSelect = async (selectedAppointment) => {
  form.appointment_id = selectedAppointment.id;
};

const cancel = () => {
  Inertia.visit(route("follow-ups.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
  <style src="vue-multiselect/dist/vue-multiselect.css"></style>
  <style scoped>
  .custom-multiselect {
      z-index: 10;
  }
  </style>
