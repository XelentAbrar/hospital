<template>
  <Head title="Create Donations" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ form?.id ? "Update" : "Create" }} Donations
            </h1>
            <!-- <form
              @submit.prevent="
                form?.id
                  ? form.put(route('donations.update', { id: form.id }))
              "
            > -->
            <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('donations.update', { id: form.id }))
                : form.post(route('donations.store'), form).finally(() => {
                    form.processing = false;
                })
            ">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3 py-6">
                  <div>
                    <label
                      for="charges"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Receivable Date
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="donor_date"
                        name="donor_date"
                        type="date"
                        autocomplete="donor_date"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.donor_date"
                      />
                    <InputError
                      v-if="form.errors?.donor_date"
                      :message="form.errors?.donor_date"
                    />
                  </div>
                  <div>
                    <label
                      for="charges"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Charges
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="charges"
                        name="charges"
                        type="text"
                        autocomplete="charges"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.charges"
                      />
                    <InputError
                      v-if="form.errors?.charges"
                      :message="form.errors?.charges"
                    />
                  </div>
                  <div class="">
                      <label
                        for="Donation"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Donor</label
                      >
                      <div class="mt-1">
                        <input
                        id="donor"
                        name="donor"
                        type="text"
                        autocomplete="donor"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.donor"
                      />
                      </div>
                      <InputError
                        v-if="form?.errors?.donor"
                        :message="form?.errors?.donor"
                      />
                    </div>
                    <div>
                    <label
                      for="account_on"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Received By User
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="account_on"
                        name="account_on"
                        type="text"
                        autocomplete="account_on"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.account_on"
                      />
                    <InputError
                      v-if="form.errors?.account_on"
                      :message="form.errors?.account_on"
                    />
                  </div>
                  <div>
                    <label
                      for="account_on"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Narration
                      <span class="text-red-500">*</span>
                    </label>
                      <textarea
                        id="narration"
                        name="narration"
                        type="text"
                        autocomplete="narration"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.narration"
                      />
                    <InputError
                      v-if="form.errors?.narration"
                      :message="form.errors?.narration"
                    />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('donations.index')"
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
import { ref, computed, defineComponent, watch } from "vue";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  donation: Object,
  donations: { type: Array, required: true, default: () => [] },
});
const getCurrentDateInPakistanTimezone = () => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
  const currentDate = new Date().toLocaleDateString('en-CA', options); // 'en-CA' formats the date as 'YYYY-MM-DD'
  return currentDate;
};
const form = useForm({
  id: props?.donation?.id || null,
  charges: props?.donation?.charges || null,
  account_on: props?.donation?.account_on || null,
  donor_date: props?.donation?.donor_date || getCurrentDateInPakistanTimezone(),
  narration: props?.donation?.narration || null,
  donor: props?.donation?.donor || null,
});


const donations = ref(props.donations || []);
const selectedDonation = ref(null);
if (props?.donation) {
  const donation = props.donations.find(
    (c) => c.id === props.donation.donor_id
  );
  if (donation) {
    selectedDonation.value = donation;
  }
}

const donorOptions = computed(() => {
  return donations.value && donations.value.length ? donations.value : [];
});

const onDonationSelect = async (selectedDonation) => {
  form.donor_id = selectedDonation.id;
};

const cancel = () => {
  Inertia.visit(route("donations.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
  <style src="vue-multiselect/dist/vue-multiselect.css"></style>
