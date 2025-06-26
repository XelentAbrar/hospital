<template>
  <Head title="Create Rooms" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Rooms
          </h1>
          <form
            @submit.prevent="handleSubmit"
          >
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-9 py-6">
              <div class="col-span-12 sm:col-span-3">
                <label
                  for="room_type_id"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Room type
                  <span class="text-red-500">*</span>
                  </label
                >
                <div class="mt-1">
                  <multiselect
                    v-model="selectedRoomType"
                    :options="room_types"
                    @select="onRoomTypeSelect"
                    label="name"
                    track-by="id"
                    placeholder="Select room type"
                    ></multiselect>
                  </div>
                  <InputError
                    v-if="form?.errors?.room_type_id"
                    :message="form?.errors?.room_type_id"
                  />
                </div>
                <div class="col-span-12 sm:col-span-3">
                  <label
                    for="room_number"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Room number
                    <span class="text-red-500">*</span>
                    </label
                  >
                  <input
                    id="room_number"
                    name="room_number"
                    type="text"
                    autocomplete="room_number"
                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    v-model="form.room_number"
                  />
                  <InputError
                    v-if="form?.errors?.room_number"
                    :message="form?.errors?.room_number"
                  />
                </div>
                <div class="col-span-12 sm:col-span-3">
                  <label
                    for="charges"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Charges
                    <span class="text-red-500">*</span>
                    </label
                  >
                  <input
                    id="charges"
                    name="charges"
                    type="number"
                    autocomplete="charges"
                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    step="0.01"
                    v-model="form.charges"
                  />
                  <InputError
                    v-if="form?.errors?.charges"
                    :message="form?.errors?.charges"
                  />
                </div>
              </div>
              <div
              class="flex items-center justify-center md:justify-end gap-4 pb-4">
              <InertiaLink
              class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                :href="route('rooms.index')"
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
  room: Object,
  room_types: Array,
});

const form = useForm({
  id: props?.room?.id || null,
  room_number: props?.room?.room_number || null,
  room_type_id: props?.room?.room_type_id || null,
  charges: props?.room?.charges || null,
});


const submitting = ref(false);

const handleSubmit = () => {
  if (submitting.value) return;

  submitting.value = true;

  const method = form?.id ? 'put' : 'post';
  const url = form?.id
    ? route('rooms.update', { id: form.id })
    : route('rooms.store');

  form[method](url, {
    onFinish: () => {
      submitting.value = false;
    },
  });
};

const selectedRoomType = ref(null);
if (props?.room) {
  const group = props.room_types.find(
    (c) => c.id == props.room.room_type_id
  );
  if (group) {
    selectedRoomType.value = group;
  }
}

const onRoomTypeSelect = async (selectedRoomType) => {
  form.room_type_id = selectedRoomType.id;
};

const cancel = () => {
  Inertia.visit(route("rooms.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
    <style src="vue-multiselect/dist/vue-multiselect.css"></style>