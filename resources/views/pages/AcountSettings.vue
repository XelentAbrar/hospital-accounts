<template>
    <Head title="Create Account Setting" />
    <AppLayout title="Account Settings">
       <div class="p-6">
          <h1 class="text-2xl font-semibold mb-6">Account Settings</h1>
          <form @submit.prevent="submit">
             <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div v-for="field in fields" :key="field" class="flex flex-col">
                   <label :for="field" class="bg-primary text-center text-white px-2 py-2 rounded-md font-medium mb-2">
                      {{ field }}
                   </label>
                   <vue-multiselect
                      v-model="form[field]"
                      :options="accountCodes"
                      label="name"
                      track-by="name"
                      placeholder="Select Account Code"
                      :multiple="false" disabled
                      class="border border-gray-300 rounded-md p-2"
                   ></vue-multiselect>
                </div>
             </div>
            <div class="flex items-end justify-end hidden">
              <button type="submit" class="mt-6 bg-primary text-white py-2 px-6 rounded-md hover:bg-primary focus:outline-none">
                Save
              </button>
            </div>
            </form>
       </div>
    </AppLayout>
 </template>

 <script>
 import { useForm } from '@inertiajs/inertia-vue3';
 import { ref, watchEffect } from 'vue';
 import VueMultiselect from 'vue-multiselect';
 import AppLayout from "@/Layouts/AppLayout.vue";

 export default {
   components: { VueMultiselect, AppLayout },
   props: {
     settings: Object,
     accountCodes: Array,
     field: String,

   },
   setup(props) {
       const form = useForm({
           Advance: props.settings.Advance || null,
           Cash_In_Hand: props.settings.Cash_In_Hand || null,
           Donation: props.settings.Donation || null,
           LAB_RECEIPTS: props.settings.LAB_RECEIPTS || null,
           MISC_RECEIPTS: props.settings.MISC_RECEIPTS || null,
           ZF_Patients: props.settings.ZF_Patients || null,
           DISCOUNT: props.settings.DISCOUNT || null,
     });


     const fields = ['Advance', 'Cash_In_Hand','Donation', 'LAB_RECEIPTS', 'MISC_RECEIPTS', 'ZF_Patients','DISCOUNT'];
     watchEffect(() => {
  if (props.settings && props.accountCodes) {
    fields.forEach((field) => {
      const settingValue = props.settings[field];
      if (settingValue) {
        const selectedAccount = props.accountCodes.find(
          (account) => account.name === settingValue
        );
        form[field] = selectedAccount || null;
      }
    });
  }
});

     const submit = () => {
       form.post(route('account.settings.store'));
     };

     return { form, submit, fields };
   },

 };

 </script>

 <style src="vue-multiselect/dist/vue-multiselect.css"></style>
