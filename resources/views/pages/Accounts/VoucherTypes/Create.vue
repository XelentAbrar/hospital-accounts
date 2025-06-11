<template>
  <Head title="Create Voucher Type" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Voucher Type
          </h1>
            <form
              @submit.prevent="
                form?.id
                  ? form.put(route('voucher-types.update', { id: form.id }))
                  : form.post(route('voucher-types.store'), form)
              "
            >
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                  <div>
                    <label
                      for="voucher_name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Name
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="name"
                        name="voucher_name"
                        type="text"
                        autocomplete="voucher_name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.voucher_name"
                      />
                    <InputError
                      v-if="form?.errors?.voucher_name"
                      :message="form?.errors?.voucher_name"
                    />
                  </div>
                  <div class="sm:col-span-2">
                    <label
                      for="voucher_abrv"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Abberivation
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="name"
                        name="voucher_abrv"
                        type="text"
                        autocomplete="voucher_abrv"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.voucher_abrv"
                      />
                    <InputError
                      v-if="form?.errors?.voucher_abrv"
                      :message="form?.errors?.voucher_abrv"
                    />
                  </div>
                </div>
                <div class="flex items-center justify-end gap-4 pb-4">
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('voucher-types.index')"
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
  </AppLayout>
</template>
  <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  voucher_type: Object,
});

const form = useForm({
  id: props?.voucher_type?.id || null,
  voucher_name: props?.voucher_type?.voucher_name || null,
  voucher_abrv: props?.voucher_type?.voucher_abrv || null,
});
</script>