<template>
  <Head title="Create Category" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ category?.id ? "Update" : "Create" }} Category
            </h1>
            <form
              @submit.prevent="
                category?.id
                  ? category.put(
                      route('categories.update', {
                        id: category.id,
                      })
                    )
                  : category.post(route('categories.store'), category)
              "
              enctype="multipart/form-data"
            >
              <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 py-6">
                <div class="sm:col-span-3 md:col-span-2 col-span-12">
                  <label
                    for="name"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Name
                    <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="text"
                    name="name"
                    id="name"
                    autocomplete="given-name"
                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    v-model="category.name"
                  />
                  <InputError
                    v-if="category.errors?.name"
                    :message="category.errors?.name"
                  />
                </div>
                <div class="sm:col-span-3 md:col-span-2 col-span-12" v-if="hasAccountsModule">
                  <label
                    for="coa_id"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Account Head
                    <span class="text-red-500">*</span>
                  </label>
                  <multiselect
                    v-model="selectedCoa"
                    :options="coa"
                    @update:modelValue="onCoaSelect"
                    label="acc_desc"
                    track-by="id"
                    placeholder="Select a Account Head"
                  ></multiselect>
                  <InputError
                    v-if="category.errors?.coa_id"
                    :message="category.errors?.coa_id"
                  />
                </div>
                <div class="sm:col-span-3 md:col-span-2 col-span-12">
                  <label
                    for="description"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Description</label
                  >
                  <input
                    type="text"
                    name="description"
                    id="description"
                    autocomplete="description"
                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    v-model="category.description"
                  />
                  <InputError
                    v-if="category.errors?.description"
                    :message="category.errors?.description"
                  />
                </div>
                <div class="sm:col-span-3 md:col-span-3 col-span-12">
                  <label
                    for="about"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Picture
                    <span class="text-red-500">*</span>
                  </label>
                  <file-pond
                    name="image"
                    ref="pond"
                    credits="false"
                    v-bind:allow-multiple="false"
                    accepted-file-types="image/png, image/jpeg, image/jpg"
                    :server="{
                      url: '',
                      timeout: 7000,
                      process: {
                        url: '/upload-files',
                        method: 'POST',
                        headers: {
                          'X-CSRF-TOKEN': csrfToken,
                        },
                        withCredentials: false,
                        onload: handleFilePondLoad,
                        ondata: (fileData) => {
                          fileData.append('prefix', 'cat-');
                          fileData.append('name', 'image');
                          fileData.append('path', 'uploads/Inventory');
                          fileData.append('private', 0);
                          return fileData;
                        },
                      },
                    }"
                    v-bind:files="myFiles"
                    v-on:init="handleFilePondInit"
                  >
                  </file-pond>
                  <InputError
                    v-if="category.errors?.image"
                    :message="category.errors?.image"
                  />
                </div>
                <div class="sm:col-span-3 md:col-span-3 col-span-12">
                  <div class="mt-2">
                    <SwitchGroup
                      as="div"
                      class="flex items-center justify-between"
                    >
                      <span class="flex flex-grow flex-col">
                        <SwitchLabel
                          as="span"
                          class="block text-sm md:text-base font-medium text-gray-900"
                          passive
                          >Status</SwitchLabel
                        >
                        <SwitchDescription
                          as="span"
                          class="text-sm text-gray-900"
                          >Active when toggled on, Inactive when toggled
                          off.</SwitchDescription
                        >
                      </span>
                      <Switch
                        v-model="enabled"
                        :class="[
                          enabled ? 'bg-primary' : 'bg-gray-200',
                          'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
                        ]"
                      >
                        <span
                          aria-hidden="true"
                          :class="[
                            enabled ? 'translate-x-5' : 'translate-x-0',
                            'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                          ]"
                        />
                      </Switch>
                    </SwitchGroup>
                  </div>
                  <InputError
                    v-if="category.errors?.status"
                    :message="category.errors?.status"
                  />
                </div>
              </div>
              <div
                class="flex items-center justify-end gap-x-6 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('categories.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="category.processing"
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
import { ref, watch } from "vue";
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";
import "filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import InputError from "../../Components/InputError.vue";
import Multiselect from "vue-multiselect";
import {
  Switch,
  SwitchDescription,
  SwitchGroup,
  SwitchLabel,
} from "@headlessui/vue";

const props = defineProps({
  hasAccountsModule:Boolean,
  coa: Array,
  category: {
    type: Object,
    default: null,
  },
});

const category = useForm({
  id: props?.category?.id || null,
  name: props?.category?.name || null,
  description: props?.category?.description || null,
  image: props?.category?.image || null,
  coa_id: props?.category?.coa_id || null,
  status: props?.category?.status || "active",
  _method: props?.category?.id ? "put" : "post",
});

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFilePoster
);

const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
const init = ref(false);

const handleFilePondInit = () => {
  if (props?.category?.image) {
    myFiles.value = [
      {
        source: "/uploads/Inventory/Categories/" + category.image,
        options: {
          type: "local",
          metadata: {
            poster: "/uploads/Inventory/Categories/" + category.image,
          },
        },
      },
    ];
  } else {
    myFiles.value = [];
  }
};

const selectedCoa = ref(null);
if (props?.category) {
  const coas = props.coa.find(
    (c) => c.id === props.category.coa_id
  );
  if (coas) {
    selectedCoa.value = coas;
  }
}

const onCoaSelect = async (selectedCoa) => {
  category.coa_id = selectedCoa?.id ?? null;
};

const handleFilePondLoad = (response) => {
  const parsedResponse = JSON.parse(response);
  const filename = parsedResponse.image;

  if (!init.value) {
    category.image = ref(filename);
    init.value = true;
  } else {
    category.image.value = filename;
  }
  console.log(category.image.value);
};

const enabled = ref(true);

watch(enabled, (newValue) => {
  category.status = newValue ? "active" : "inactive";
});
</script>

<style>
.filepond--panel-root {
  background-color: transparent;
  border: 2px solid #2c3340;
}
.filepond--item {
  width: calc(99% - 0.5em);
  height: auto;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>