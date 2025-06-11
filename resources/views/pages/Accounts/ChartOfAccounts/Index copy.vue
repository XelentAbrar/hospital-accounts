<template>
  <Head title="Chart of Accounts" />
  <AppLayout title="Chart of Accounts">
      <div class="w-full mx-auto pt-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <h1 class="font-semibold text-3xl text-primary">
                  Chart of Accounts
                </h1>
                <div class="mt-2 ml-auto sm:ml-0 sm:mt-0">
              <input
                  id="name"
                  name="name"
                  type="text"
                  autocomplete="name"
                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                  step="0.01"
                  placeholder="Search"
                  :value="filters?.search"
                  @keyup.enter="searchItem($event.target.value, type)"
                />
          </div>
                <inertia-link
                  :href="route('chart-of-accounts.create')"
                  class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                  >Create chart of account</inertia-link
                >
            </div>
            <div class="mt-6 flow-root">
                <div class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                    <thead>
                      <tr class="divide-x divide-gray-200">
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          ID
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Account code
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Account
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Account description
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Actions
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        class="divide-x divide-gray-200"
                        v-for="(
                          chart_of_account, index
                        ) in chart_of_accounts.data"
                        :key="chart_of_account.id"
                        :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                      >
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ chart_of_account.id }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{
                            chart_of_account?.account_code?.acc_desc
                          }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ chart_of_account?.acc_code }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ chart_of_account.acc_desc }}
                        </td>
                        <td class="relative whitespace-nowrap border-b border-gray-200 p-4">
                          <div class="flex items-center justify-center space-x-3" v-if="roles.includes(1)">
                          <InertiaLink
                            :href="
                              route(
                                'chart-of-accounts.edit',
                                chart_of_account.id
                              )
                            "
                            >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 lg:w-6 h-5 lg:h-6 text-primary hover:text-accent"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                />
                              </svg>
                          </InertiaLink>
                          <button
                            @click.prevent="
                              deleteChartOfAccount(chart_of_account.id)
                            "
                            type="button"

                          >
                          <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 lg:w-6 h-5 lg:h-6 text-red-600 hover:text-red-900"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                />
                              </svg>
                          </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="pagination-container py-4 flex justify-between px-4">
                  <div class="pagination-info flex">
                    Page
                    <span class="font-semibold px-2 text-gray-900 report">{{ chart_of_accounts.current_page }}</span>
                    of
                    <span class="font-semibold px-2 text-gray-900 report">{{ chart_of_accounts.last_page }}</span>
                    <div>
                      <p class="pagination-info">
                        Showing
                        <span class="font-semibold px-1  text-gray-900 report">{{ chart_of_accounts.from }}</span>
                        to
                        <span class="font-semibold px-1  text-gray-900 report">{{ chart_of_accounts.to }}</span>
                        of
                        <span class="font-semibold px-1  text-gray-900 report">{{ chart_of_accounts.total }}</span>
                        entries
                      </p>
                    </div>
                  </div>
                  <div class="pagination-links">
                    <template v-if="chart_of_accounts.links">
                      <template v-for="(link, index) in chart_of_accounts.links" :key="index">
                        <InertiaLink
                          v-if="link.url"
                          :href="link.url"
                          v-html="link.label"
                          :class="['pagination-box', { 'pagination-box-current': link.active }]"
                        ></InertiaLink>
                      </template>
                    </template>
                  </div>
                </div>
              </div>
        </div>
      </div>

      <ConfirmationModal :show="displayDeleteModal != null" @close="displayDeleteModal = null">
            <template #title>
                Merge Account
            </template>

            <template #content>
                <div>
                  <label
                    for="coa4_id"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Merge With Account
                    <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-1">
                    <multiselect
                      v-model="selectedCoaId"
                      :options="all_chart_of_accounts"
                      @update:modelValue="onAccountCodeSelect"
                      label="acc_desc"
                      class="z-50"
                      track-by="id"
                      placeholder="Select account code"
                    ></multiselect>
                  </div>
                  <InputError
                    v-if="error"
                    :message="error"
                  />
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="displayDeleteModal = null">
                    Cancel
                </SecondaryButton>

                <button class="block rounded bg-red-700 w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer ml-2" @click="updateAccount('delete')">Merge & Delete</button>

                <button class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer ml-2" @click="updateAccount('merge')">Only Merge Account</button>

            </template>
        </ConfirmationModal>

  </AppLayout>
</template>

      <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, usePage ,router} from "@inertiajs/vue3";
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { Inertia } from "@inertiajs/inertia";
import InputError from '@/Components/InputError.vue';
import { ref } from "vue";
import Multiselect from "vue-multiselect";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const displayDeleteModal = ref(null);
const coa_id = ref(null);
const delete_id = ref(null);
const selectedCoaId = ref(null);
const error = ref(null);

const deleteChartOfAccount = (id) => {
  error.value = null;
  delete_id.value = id;
  displayDeleteModal.value = 1;
};

const onAccountCodeSelect = async (selectedCoaId) => {
  coa_id.value = selectedCoaId.id;
};

const updateAccount = async (type) => {
  if(coa_id.value == '' || coa_id.value == null){
    error.value = 'Merge Account is required!';
    return;
  }
  return Inertia.post(route("chart-of-accounts-delete", {id:delete_id.value, type:type, coa_id:coa_id.value})).then(res => {
    displayDeleteModal.value = null;
  });
};
const searchItem = async (val) => {
  try {
    router.visit(
        route( "chart-of-accounts.index" , {
            search: val,
        }),
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
  } catch (error) {
    console.error("Error fetching cities:", error);
  }
};

const page = usePage()
const roles = ref([]);
if(page.props.auth.user?.user_role){
    page.props.auth.user?.user_role.map(user_role => {
        roles.value.push(user_role?.role_id);
    });
    console.log('roles', roles.value);
}

</script>
      <script>
export default {
  props: {
    chart_of_accounts: Array,
    all_chart_of_accounts: Array,
    filters: Array,
  },
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
<style scoped>
.pagination-box {
  padding: 10px 16px;
  border: 1px solid #ccc;
  border-radius: 0.25rem;
  margin: 0 0.25rem;
  text-decoration: none;
}
.pagination-box-current {
  background-color: #6D4C41;
  color: #fff;
}
.pagination-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1rem;
}
.pagination-info {
  display: flex;
  align-items: center;
}
.pagination-links {
  display: flex;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
