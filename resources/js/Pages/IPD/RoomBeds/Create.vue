<template>
  <Head title="Create Room Bed" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Room Bed
          </h1>
          <form
            @submit.prevent="handleSubmit"
            >
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                <div>
                  <label
                    for="room_id"
                    class="block text-sm md:text-base font-medium text-gray-900"
                  >Room
                    <span class="text-red-500">*</span>
                  </label>
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedRoom"
                        :options="rooms"
                        @select="onRoomSelect"
                        label="room_number"
                        track-by="id"
                        placeholder="Select room"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form?.errors?.room_id"
                      :message="form?.errors?.room_id"
                    />
                  </div>
                  <div>
                    <label
                      for="bed_number"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Bed number
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="bed_number"
                        name="bed_number"
                        type="text"
                        autocomplete="bed_number"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.bed_number"
                      />
                    <InputError
                      v-if="form?.errors?.bed_number"
                      :message="form?.errors?.bed_number"
                    />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 pb-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('room-beds.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="submitting"
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
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { ref, defineComponent, watch } from "vue";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  room_bed: Object,
  rooms: Array,
});

const form = useForm({
  id: props?.room_bed?.id || null,
  bed_number: props?.room_bed?.bed_number || null,
  room_id: props?.room_bed?.room_id || null,
});


const submitting = ref(false);

const handleSubmit = () => {
  if (submitting.value) return;

  submitting.value = true;

  const method = form?.id ? 'put' : 'post';
  const url = form?.id
    ? route('room-beds.update', { id: form.id })
    : route('room-beds.store');

  form[method](url, {
    onFinish: () => {
      submitting.value = false;
    },
  });
};

const selectedRoom = ref(null);
if (props?.room_bed) {
  const group = props.rooms.find(
    (c) => c.id == props.room_bed.room_id
  );
  if (group) {
    selectedRoom.value = group;
  }
}

const onRoomSelect = async (selectedRoom) => {
  form.room_id = selectedRoom.id;
};

const cancel = () => {
  Inertia.visit(route("room-beds.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
    <style src="vue-multiselect/dist/vue-multiselect.css"></style>