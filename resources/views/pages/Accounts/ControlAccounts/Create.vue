<template>
  <Head title="Create Control Account" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Control Account
          </h1>
            <form
              @submit.prevent="
                form?.id
                  ? form.put(route('control-accounts.update', { id: form.id }))
                  : form.post(route('control-accounts.store'), form)
              "
            >
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                  <div class="sm:col-span-2">
                    <label
                      for="acc_desc"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Account description
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <input
                        id="name"
                        name="acc_desc"
                        type="text"
                        autocomplete="acc_desc"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.acc_desc"
                      />
                    </div>
                    <InputError
                      v-if="form?.errors?.acc_desc"
                      :message="form?.errors?.acc_desc"
                    />
                  </div>
                </div>
                <div class="flex items-center justify-end gap-4 py-4">
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('control-accounts.index')"
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
  control_account: Object,
});

const form = useForm({
  id: props?.control_account?.id || null,
  acc_desc: props?.control_account?.acc_desc || null,
});
</script>