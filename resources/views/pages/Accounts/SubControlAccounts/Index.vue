<template>
  <Head title="Sub Control Accounts" />
  <AppLayout title="Sub Control Accounts">
      <div class="w-full mx-auto pt-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <h1 class="font-semibold text-3xl text-primary">
                  Sub Control Accounts
                </h1>
                <inertia-link
                  :href="route('sub-control-accounts.create')"
                  class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                  >Create sub control account</inertia-link
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
                          Control account
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
                          sub_control_account, index
                        ) in sub_control_accounts.data"
                        :key="sub_control_account.id"
                        :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                      >
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ sub_control_account.id }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ sub_control_account?.control_account?.acc_desc }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ sub_control_account?.acc_code }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ sub_control_account.acc_desc }}
                        </td>
                        <td class="relative whitespace-nowrap border-b border-gray-200 p-4">
                          <div class="flex items-center justify-center space-x-3" v-if="roles.includes(1)">
                          <InertiaLink
                            :href="
                              route(
                                'sub-control-accounts.edit',
                                sub_control_account.id
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
                              deleteSubControlAccount(sub_control_account.id)
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
        <span class="font-semibold px-2 text-gray-900 report">{{ sub_control_accounts.current_page }}</span>
        of
        <span class="font-semibold px-2 text-gray-900 report">{{ sub_control_accounts.last_page }}</span>
        <div>
          <p class="pagination-info">
            Showing
            <span class="font-semibold px-1  text-gray-900 report">{{ sub_control_accounts.from }}</span>
            to
            <span class="font-semibold px-1  text-gray-900 report">{{ sub_control_accounts.to }}</span>
            of
            <span class="font-semibold px-1  text-gray-900 report">{{ sub_control_accounts.total }}</span>
            entries
          </p>
        </div>
      </div>

      <div class="pagination-links">
        <template v-if="sub_control_accounts.links">
          <template v-for="(link, index) in sub_control_accounts.links" :key="index">
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
  </AppLayout>
</template>

      <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from 'sweetalert2';

const deleteSubControlAccount = (id) => {
//   if (confirm("Are you sure you want to delete this sub_control_account?")) {
//     return Inertia.delete(route("sub-control-accounts.destroy", id));
//   }
Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route("sub-control-accounts.destroy", id));
        }
    });
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
    sub_control_accounts: Array,
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
