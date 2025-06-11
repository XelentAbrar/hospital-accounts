<template>
  <Head title="Create Voucher" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Voucher Audit
          </h1>
            <form  @submit.prevent>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 py-6">
                  <!-- <div>
                    <label
                      for="voucher_no"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Voucher number
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="name"
                        name="voucher_no"
                        type="text"
                        autocomplete="voucher_no"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        disabled="true"
                        :value="form.voucher_no"
                        tabindex="-1"
                      />
                    <InputError
                      v-if="form?.errors?.voucher_no"
                      :message="form?.errors?.voucher_no"
                    />
                  </div> -->
                  <div>
                    <label
                      for="voucher_type_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Voucher type
                      <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedVoucherTypeId"
                        :options="voucher_types"
                        @select="onVoucherTypeSelect"
                        label="voucher_name"
                        track-by="id"
                        placeholder="Select voucher type"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form?.errors?.voucher_type_id"
                      :message="form?.errors?.voucher_type_id"
                    />
                  </div>
                  <div>
                    <label
                      for="voucher_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Voucher date
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="voucher_date"
                        name="voucher_date"
                        type="date"
                        autocomplete="voucher_date"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.voucher_date"
                      />
                    <InputError
                      v-if="form?.errors?.voucher_date"
                      :message="form?.errors?.voucher_date"
                    />
                  </div>
                  <div>
                    <label
                      for="narration"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Narration
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="name"
                        name="narration"
                        type="text"
                        autocomplete="narration"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.narration"
                      />
                    <InputError
                      v-if="form?.errors?.narration"
                      :message="form?.errors?.narration"
                    />
                  </div>
                </div>
                <!--Voucher Detail-->
                <div class="divide-y divide-gray-200 pt-4 pb-4">
                <div class="border shadow-md rounded-lg border-gray-200 px-4 py-6 mb-4 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">Voucher detail</h3>
                  <InputError
                    v-if="form.errors?.[`voucher_audit_details`]"
                    :message="form.errors?.[`voucher_audit_details`]"
                  />
                  <div class="border rounded mb-2">
                    <div class="flex divide-x border-b" >
                      <div class="min-w-0 w-48 p-1 flex items-center justify-center">
                        <label for="account" class="text-center font-medium">Account</label>
                      </div>
                      <div class="min-w-0 w-48 p-1 flex items-center justify-center" v-if="form.voucher_audit_details.some(voucher_detail => voucher_detail.transaction_type?.name)"                        >
                        <span :set="show_service = 1"></span>
                        <label for="account" class="text-center font-medium">Service/Doctor</label>
                      </div>
                      <div class="min-w-0 p-1 flex items-center justify-center w-32">
                        <label for="dr" class="text-center font-medium">DR</label>
                      </div>
                      <div class="min-w-0 p-1 flex items-center justify-center w-32">
                        <label for="cr" class="text-center font-medium">CR</label>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="transaction_type" class="text-center font-medium">Transaction type</label>
                      </div>
                      <div class="min-w-0 w-44 p-1 flex items-center justify-center">
                        <label for="transaction_no" class="text-center font-medium">Transaction #</label>
                      </div>
                      <div class="min-w-0 w-48 p-1 flex items-center justify-center">
                        <label for="remarks" class="text-center font-medium">Remarks</label>
                      </div>
                      <div class="flex items-center p-1 w-[49px]"></div>
                    </div>
                  <div
                    v-for="(voucher_detail, index) in form.voucher_audit_details"
                    :key="index"
                  >
                  <div class="flex divide-x border-b">
                        <div class="min-w-0 w-48 p-1 flex items-center justify-center">
                          <div class="w-full">
                        <label for="card-expiration-date" class="sr-only"
                          >Account</label
                        >
                        <multiselect
                          v-model="voucher_detail['chart_of_account']"
                          :options="chart_of_accounts"
                          label="acc_desc"
                          track-by="id"
                          placeholder="Select account"
                        ></multiselect>
                        <InputError
                          v-if="
                            form.errors?.[
                              `voucher_audit_details.${index}.chart_of_account.id`
                            ]
                          "
                          :message="
                            form.errors?.[
                              `voucher_audit_details.${index}.chart_of_account.id`
                            ]
                          "
                        />
                      </div>
                      </div>

                      <div class="min-w-0 w-64 p-1 flex items-center justify-center" v-show="show_service == 1">
                            <div class="w-full">
                            <label for="service-employee" class="sr-only">Service/Doctor Name </label>
                            <input
                                :value="voucher_detail['transaction_type'] ? voucher_detail['transaction_type']['name'] : null"
                                type="text"
                                readonly
                                class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                placeholder="Service/Doctor Name"
                            />
                            </div>
                        </div>
                      <div class="min-w-0 w-32 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">DR</label>
                        <input
                          v-model="voucher_detail['dr']"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="DR"
                          :disabled="
                            voucher_detail['cr'] != null &&
                            voucher_detail['cr'] != '' &&
                            voucher_detail['cr'] != 0
                          "
                          :tabindex="
                            voucher_detail['cr'] != null &&
                            voucher_detail['cr'] != '' &&
                            voucher_detail['cr'] != 0
                              ? -1
                              : ''
                          "
                        />
                        <InputError
                          v-if="form.errors?.[`voucher_audit_details.${index}.dr`]"
                          :message="
                            form.errors?.[`voucher_audit_details.${index}.dr`]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-32 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">CR</label>
                        <input
                          v-model="voucher_detail['cr']"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="CR"
                          :disabled="
                            voucher_detail['dr'] != null &&
                            voucher_detail['dr'] != '' &&
                            voucher_detail['dr'] != 0
                          "
                          :tabindex="
                            voucher_detail['dr'] != null &&
                            voucher_detail['dr'] != '' &&
                            voucher_detail['dr'] != 0
                              ? -1
                              : ''
                          "
                        />
                        <InputError
                          v-if="form.errors?.[`voucher_audit_details.${index}.cr`]"
                          :message="
                            form.errors?.[`voucher_audit_details.${index}.cr`]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-expiration-date" class="sr-only"
                          >Transaction type</label
                        >
                        <multiselect
                          v-model="voucher_detail['transaction_type']"
                          :options="transaction_types"
                          label="label"
                          track-by="label"
                          placeholder="Select transaction type"
                        ></multiselect>
                        <InputError
                          v-if="
                            form.errors?.[
                              `voucher_audit_details.${index}.transaction_type`
                            ]
                          "
                          :message="
                            form.errors?.[
                              `voucher_audit_details.${index}.transaction_type`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-44 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only"
                          >Transaction #</label
                        >
                        <input
                          v-model="voucher_detail['transaction_no']"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Transaction #"
                        />
                        <InputError
                          v-if="
                            form.errors?.[
                              `voucher_audit_details.${index}.transaction_no`
                            ]
                          "
                          :message="
                            form.errors?.[
                              `voucher_audit_details.${index}.transaction_no`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-48 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">Remarks</label>
                        <input
                          v-model="voucher_detail['remarks']"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Remarks"
                        />
                        <InputError
                          v-if="
                            form.errors?.[`voucher_audit_details.${index}.remarks`]
                          "
                          :message="
                            form.errors?.[`voucher_audit_details.${index}.remarks`]
                          "
                        />
                      </div>
                      </div>
                      <div class="flex items-center p-1">
                        <button
                          type="button"
                          @click="removeVoucherDetail(index)"
                          class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                          tabindex="-1"
                        >
                          <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"
                            />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                  </div>
                  <button
                    type="button"
                    @click="addVoucherDetail"
                    class="hidden inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                      />
                    </svg>
                    Add More
                  </button>
                </div>
              </div>

            </form>
            <div class="flex items-center justify-end mb-4">
                <span class="p-4 border rounded w-1/3">
                  DR => {{form.voucher_audit_details.reduce((sum, row) => +sum + +row['dr'], 0)}}
                  CR => {{form.voucher_audit_details.reduce((sum, row) => +sum + +row['cr'], 0)}}
                </span>
              </div>
              <div class="flex items-center justify-end gap-4 py-4">
                <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('vouchers-audit.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  @click.prevent="submitForm()" @keydown.enter.prevent="submitForm()"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                  Save
                </button>
              </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
    <script setup>
  import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink, router } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { ref, defineComponent, computed } from "vue";
import InputError from "../../Components/InputError.vue";
import Swal from 'sweetalert2';

const props = defineProps({
  voucher: Object,
  voucher_types: Array,
  chart_of_accounts: Array,
  voucher_number: String,
});

const form = useForm({
  id: props?.voucher?.id || null,
  voucher_type_id: props?.voucher?.voucher_type_id || null,
  voucher_date: (props?.voucher?.voucher_date ? new Date(props?.voucher?.voucher_date).getFullYear()+'-'+(new Date(props?.voucher?.voucher_date).getMonth() + 1).toString().padStart(2, '0')+'-'+new Date(props?.voucher?.voucher_date).getDate().toString().padStart(2, '0') : null),
  voucher_no: props?.voucher_number || props?.voucher?.voucher_no || null,
  narration: props?.voucher?.narration || null,
  voucher_audit_details: props?.voucher?.voucher_audit_details || [
    {
      id: null,
      chart_of_account: null,
      dr: null,
      cr: null,
      name: null,
      transaction_type: null,
      transaction_no: null,
      remarks: null,
    },
    {
      id: null,
      chart_of_account: null,
      dr: null,
      cr: null,
      name: null,
      transaction_type: null,
      transaction_no: null,
      remarks: null,
    },
  ],
});

const addVoucherDetail = () => {
  form.voucher_audit_details.push({
    id: null,
    chart_of_account: null,
    dr: null,
    cr: null,
    name: null,
    transaction_type: null,
    transaction_no: null,
    remarks: null,
  });
};

const removeVoucherDetail = (index) => {
  if (form.voucher_audit_details.length > 2) {
    form.voucher_audit_details.splice(index, 1);
  }
};
const VoucherNo = ref(props?.voucher?.voucher_no || '');

const searchVoucher = (voucherNo) => {
  if (voucherNo) {
    router.visit(route('voucher-edit', voucherNo));
  }
};

const onSearchClick = () => {
    searchVoucher(VoucherNo.value);
};

const transaction_types = computed(() => {
    return [{ label: "Cheque" }, { label: "Bill no." }, { label: "ipd" }, { label: "lab" }, { label: "opd" }, { label: "expense" },{ label: "donor_fund" },{ label: "donation" }];
});

const show_service = ref(0);
const selectedVoucherTypeId = ref(null);
if (props?.voucher) {
  const group = props.voucher_types.find(
    (c) => c.id == props.voucher.voucher_type_id
  );
  if (group) {
    selectedVoucherTypeId.value = group;
  }
}
else if(new URLSearchParams(window.location.search).get("vt") && new URLSearchParams(window.location.search).get("vt") != ''){
  const group = props.voucher_types.find(
    (c) => c.id == new URLSearchParams(window.location.search).get("vt")
  );
  if (group) {
    selectedVoucherTypeId.value = group;
    form.voucher_type_id = new URLSearchParams(window.location.search).get("vt");
  }
}
else{
  router.visit(route('vouchers-audit.create'));
}

const onVoucherTypeSelect = async (selectedVoucherTypeId) => {
  form.voucher_type_id = selectedVoucherTypeId.id;
};

const submitForm = () => {
  Swal.fire({
    title: 'Are you sure?',
    text: 'Do you want to save this audit voucher?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, save it!'
  }).then((result) => {
    if (!result.isConfirmed) return;

    if (form?.id) {
      form.put(route('vouchers-audit.update', { id: form.id }), {
        preserveScroll: true,
        onSuccess: () => {
          Inertia.reload();
        }
      });
    } else {
      form.post(route('vouchers-audit.store'), {
        preserveScroll: true,
        onSuccess: () => {
          Inertia.reload();
        }
      });
    }
  });
};

// const submitForm = () => {
//   if(form?.id){
//     form.put(route('vouchers-audit.update', { id: form.id }), {
//       preserveScroll: true,
//         onSuccess: (res) => {
//           Inertia.reload();
//         }
//     })
//   }
//   else{
//     form.post(route('vouchers-audit.store'), {
//         form,
//         preserveScroll: true,
//         onSuccess: (res) => {
//           Inertia.reload();
//         }
//       })
//   }
// };


defineComponent({
  components: {
    Multiselect,
  },
});
</script>
    <style src="vue-multiselect/dist/vue-multiselect.css"></style>
