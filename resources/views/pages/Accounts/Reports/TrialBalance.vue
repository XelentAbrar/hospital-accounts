<template>
    <Head title="Trial Balance" />
    <AppLayout title="Trial Balance">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-6 sm:px-6">
                <h3 class="font-semibold text-4xl text-primary">
                    Trial Balance
                </h3>
            </div>
            <div class="border-t border-gray-100 py-2 px-4">
                <form
                    @submit.prevent="
                        filter.get(route('trial-balance'), filter);
                        isLoading = true;
                    "
                >
                    <div
                        class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4"
                    >
                        <div>
                            <label
                                for="account_id"
                                class="block text-sm font-semibold text-gray-900"
                            >
                                Account
                            </label>
                            <div class="mt-2">
                                <multiselect
                                    v-model="selectedAccount"
                                    :options="accounts"
                                    :searchable="true"
                                    :multiple="false"
                                    :close-on-select="true"
                                    :show-labels="false"
                                    placeholder="Select Account"
                                    :custom-label="accountLabel"
                                    track-by="acc_code"
                                    @update:modelValue="onAccountSelect"
                                />
                            </div>
                        </div>

                        <div>
                            <label
                                for="from_date"
                                class="block text-sm font-semibold text-gray-900"
                            >
                                From Date
                            </label>
                            <div class="mt-2">
                                <input
                                    id="from_date"
                                    name="from_date"
                                    type="text"
                                    ref="from_date"
                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                    v-model="filter.from_date"
                                />
                            </div>
                        </div>
                        <div>
                            <label
                                for="to_date"
                                class="block text-sm font-semibold text-gray-900"
                            >
                                To Date
                            </label>
                            <div class="mt-2">
                                <input
                                    id="to_date"
                                    name="to_date"
                                    type="text"
                                    ref="to_date"
                                    autocomplete="to_date"
                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                    v-model="filter.to_date"
                                />
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 mt-8">
                            <label
                                for="column"
                                class="block text-sm font-semibold text-gray-900 cursor-pointer"
                            >
                                <input
                                    id="column"
                                    name="column"
                                    type="radio"
                                    autocomplete="column"
                                    v-model="filter.column"
                                    value="2"
                                />
                                2 Column
                            </label>
                            <label
                                for="column6"
                                class="block text-sm font-semibold text-gray-900 cursor-pointer"
                            >
                                <input
                                    id="column6"
                                    name="column"
                                    type="radio"
                                    autocomplete="column6"
                                    v-model="filter.column"
                                    value="6"
                                />
                                6 Column
                            </label>
                            <label
                                for="columncurrent"
                                class="block text-sm font-semibold text-gray-900 cursor-pointer"
                            >
                                <input
                                    id="columncurrent"
                                    name="column"
                                    type="radio"
                                    autocomplete="columncurrent"
                                    v-model="filter.column"
                                    value="current"
                                />
                                Current
                            </label>
                        </div>
                        <div>
                            <input
                                class="rounded px-1 py-1"
                                type="checkbox"
                                id="sortByAccCode"
                                v-model="sortByAccCode"
                                @change="updateSortQuery"
                            />
                            <label for="sort_by_code" class="pt-2">
                                Sort by Account Code</label
                            >
                        </div>
                        <div>
                            <button
                                type="submit"
                                class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                                :disabled="isLoading"
                                :class="{ 'opacity-25': isLoading }"
                            >
                                Search
                            </button>
                            <button
                                class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border borderlue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2"
                                @click="print()"
                                v-if="reports.length > 0 && !isLoading"
                            >
                                Print
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="w-full mx-auto">
            <div class="bg-white shadow-sm rounded-lg">
                <div>
                    <div class="mt-2 flow-root" id="printData">
                        <div
                            style="
                                font-family: Arial, Helvetica, sans-serif;
                                padding: 20px;
                                display: flex;
                                flex-direction: column;
                            "
                        >
                            <!-- <div class="print-only">
                            <p style="font-size: 24px;line-height:1.2;font-weight: 600;text-align: center;margin: 0">Chiniot General Hospital</p>
                            <p style="font-size: 14px;line-height:1.2;font-weight: 400;text-align: center;margin: 0" v-if="projectType === 'hms'">Nawaz Town Sarghoda Road, Faisalabad</p>
                            <p style="font-size: 14px;line-height:1.2;font-weight: 400;text-align: center;margin: 0" v-else>209 Jinnah Colony , Faisalabad</p>
                            <div>
                                <p style="font-size: 22px;line-height:1.2;font-weight: 500;text-align: center;margin: 0;margin-bottom: 10px;">Trial Balance</p>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin: 6px auto;">
                                <div style="flex: 1; display: flex; align-items: center;">
                                    <h4 style="margin: 0; font-family: sans-serif; width: 100px;">From</h4>
                                    <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4>
                                    <p style="margin: 0; margin-left: 2px; text-align: left; font-weight: 500; white-space: nowrap;">
                                        {{ new Date(from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                    </p>
                                </div>
                                <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
                                    <h4 style="margin: 0; font-family: sans-serif; width: 100px;">To</h4>
                                    <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4>
                                    <p style="margin: 0; margin-left: 2px; text-align: left; white-space: nowrap;">
                                        {{ new Date(to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                    </p>
                                </div>
                                <div style="flex: 1; display: flex; align-items: center; justify-content: flex-end;">
                                    <h4 style="margin: 0; font-family: sans-serif; width: 100px;">Printed On</h4>
                                    <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4>
                                    <p style="margin: 0; margin-left: 2px; text-align: left; font-weight: 500; white-space: nowrap;">
                                        {{ new Date().toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                    </p>
                                </div>
                            </div>

                        </div> -->
                            <div class="print-only">
                                <div
                                    style="
                                        display: flex;
                                        align-items: center;
                                        justify-content: space-between;
                                        width: 100%;
                                    "
                                >
                                    <div
                                        style="
                                            display: flex;
                                            align-items: center;
                                        "
                                    >
                                        <!-- <h4 style="margin: 0; font-family: sans-serif; width: 100px; font-size: small;">Printed On</h4>
                                <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4> -->
                                        <p
                                            style="
                                                margin: 0;
                                                margin-left: 2px;
                                                text-align: left;
                                                font-size: 7px;
                                                white-space: nowrap;
                                            "
                                        >
                                            {{
                                                new Date()
                                                    .toLocaleDateString("en-GB")
                                                    .replace(/\//g, "-")
                                            }}
                                        </p>
                                        <div class="print-page-number">
                                            <span class="page-number"></span>
                                        </div>
                                    </div>
                                    <div
                                        style="
                                            position: absolute;
                                            left: 50%;
                                            transform: translateX(-50%);
                                            text-align: center;
                                        "
                                    >
                                        <p
                                            style="
                                                font-size: 24px;
                                                line-height: 1.2;
                                                font-weight: 600;
                                                margin: 0;
                                            "
                                        >
                                            Chiniot General Hospital
                                        </p>
                                        <p
                                            style="
                                                font-size: 16px;
                                                line-height: 1.2;
                                                font-weight: 400;
                                                margin: 0;
                                            "
                                            v-if="projectType === 'hms'"
                                        >
                                            Nawaz Town Sarghoda Road, Faisalabad
                                        </p>
                                        <p
                                            style="
                                                font-size: 16px;
                                                line-height: 1.2;
                                                font-weight: 400;
                                                margin: 0;
                                            "
                                            v-else
                                        >
                                            209 Jinnah Colony, Faisalabad
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <p
                                        style="
                                            font-size: 20px;
                                            line-height: 3.2;
                                            font-weight: 500;
                                            text-align: center;
                                            margin: 0;
                                        "
                                    >
                                        Trial Balance
                                    </p>
                                </div>

                                <div
                                    style="
                                        border: 1px solid #d1d5db;
                                        padding: 4px;
                                        border-radius: 4px;
                                        box-shadow: 2px 2px 6px
                                            rgba(0, 0, 0, 0.1);
                                        width: 100%;
                                        max-width: 230px;
                                        margin: 1px auto;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        text-align: center;
                                    "
                                >
                                    <div
                                        style="
                                            display: flex;
                                            justify-content: center;
                                            align-items: center;
                                            gap: 6px;
                                        "
                                    >
                                        <!-- From Date (Only if column is not '2') -->
                                        <div
                                            v-if="filter?.column !== '2'"
                                            style="
                                                display: flex;
                                                align-items: center;
                                            "
                                        >
                                            <h4
                                                style="
                                                    margin: 0;
                                                    font-family: sans-serif;
                                                    font-size: 12px;
                                                    font-weight: 400;
                                                "
                                            >
                                                From:
                                                <!-- {{ from_date ? new Date(from_date).toLocaleDateString("en-GB").replace(/\//g, "-") : "No Date Selected" }} -->
                                                  {{filter?.from_date}}
                                            </h4>
                                        </div>

                                        <!-- To Date -->
                                        <div
                                            style="
                                                display: flex;
                                                align-items: center;
                                            "
                                        >
                                            <h4
                                                style="
                                                    margin: 0;
                                                    font-family: sans-serif;
                                                    font-size: 12px;
                                                    font-weight: 400;
                                                "
                                            >
                                                <span
                                                    v-if="
                                                        filter?.column !== '2'
                                                    "
                                                >
                                                    To:
                                                    <!-- {{
                                                    to_date
                                                        ? parseDate(to_date)
                                                            .toLocaleDateString("en-GB")
                                                            .replace(/\//g, "-")
                                                        : new Date()
                                                            .toLocaleDateString("en-GB")
                                                            .replace(/\//g, "-")
                                                }} -->
                                                            {{ filter?.to_date }}
                                                </span>
                                                <span
                                                    v-if="filter?.column == '2'"
                                                >
                                                    As On:
                                                    <!-- {{
                                                        to_date
                                                            ? parseDate(to_date)
                                                                .toLocaleDateString("en-GB")
                                                                .replace(/\//g, "-")
                                                            : new Date()
                                                                .toLocaleDateString("en-GB")
                                                                .replace(/\//g, "-")
                                                    }} -->{{ filter?.to_date }}
                                                </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- start report data -->
                            <div class="">
                                <div
                                    class="border border-gray-400  mb-4"
                                >
                                    <table
                                        class="border border-gray-400 min-w-full border-separate border-spacing-0 divide-y divide-gray-300 overflow-auto"
                                    >
                                        <thead>
                                            <tr
                                                class="divide-x divide-gray-300"
                                            >
                                                <th
                                                    scope="col"
                                                    class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                                                >
                                                    A/C Description
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                                                    v-if="filter.column == '6'"
                                                >
                                                    Opening Debit.
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                                                    v-if="filter.column == '6'"
                                                >
                                                    Opening Credit.
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                                                    v-if="
                                                        filter.column == '6' ||
                                                        filter.column ==
                                                            'current'
                                                    "
                                                >
                                                    Current Debit.
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                                                    v-if="
                                                        filter.column == '6' ||
                                                        filter.column ==
                                                            'current'
                                                    "
                                                >
                                                    Current Credit.
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                                                    v-if="
                                                        filter.column !=
                                                        'current'
                                                    "
                                                >
                                                    Closing Debit.
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                                                    v-if="
                                                        filter.column !=
                                                        'current'
                                                    "
                                                >
                                                    Closing Credit.
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                                                    v-if="
                                                        filter.column ==
                                                        'current'
                                                    "
                                                >
                                                    Balance
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template
                                                v-for="(
                                                    report, index
                                                ) in reports"
                                                :key="index"
                                            >
                                                <!-- Control Account Level -->
                                                <tr
                                                    v-if="
                                                        (index === 0 ||
                                                            report.acc_code_level1 !==
                                                                reports[
                                                                    index - 1
                                                                ]
                                                                    .acc_code_level1) && totalAccountCodeLevel(report.acc_code_level1,'closing_credit','acc_code_level1') > 0
                                                    "
                                                >
                                                    <td
                                                        class="border-b border-gray-400 bg-yellow-200 text-lg font-bold text-left p-2"
                                                    >
                                                        {{
                                                            report.acc_desc_level1
                                                        }}
                                                        {{
                                                            report.acc_code_level1
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-yellow-200 font-bold text-right text-lg p-2"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level1,
                                                                    "opening_debit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-yellow-200 font-bold text-lg text-right p-2"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level1,
                                                                    "opening_credit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-yellow-200 font-bold text-lg text-right p-2"
                                                        v-if="
                                                            filter.column ==
                                                                '6' ||
                                                            filter.column ==
                                                                'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level1,
                                                                    "current_debit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-yellow-200 font-bold text-lg text-right p-2"
                                                        v-if="
                                                            filter.column ==
                                                                '6' ||
                                                            filter.column ==
                                                                'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level1,
                                                                    "current_credit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-yellow-200 font-bold text-lg text-right p-2"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level1,
                                                                    "closing_debit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-yellow-200 font-bold text-lg text-right p-2"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level1,
                                                                    "closing_credit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-yellow-200 font-bold text-lg text-right p-2"
                                                        v-if="
                                                            filter.column ==
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                +totalAccountCodeLevel(
                                                                    report.acc_code_level1,
                                                                    "current_debit",
                                                                    "acc_code_level1"
                                                                ) -
                                                                    +totalAccountCodeLevel(
                                                                        report.acc_code_level1,
                                                                        "current_credit",
                                                                        "acc_code_level1"
                                                                    )
                                                            )
                                                        }}
                                                        <span>
                                                            {{
                                                                +totalAccountCodeLevel(
                                                                    report.acc_code_level1,
                                                                    "current_debit",
                                                                    "acc_code_level1"
                                                                ) -
                                                                    +totalAccountCodeLevel(
                                                                        report.acc_code_level1,
                                                                        "current_credit",
                                                                        "acc_code_level1"
                                                                    ) <
                                                                0
                                                                    ? " Cr"
                                                                    : " Dr"
                                                            }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <!-- Sub Control Account Level -->
                                                <!-- <tr v-if="(index === 0 || report.acc_code_level2 !== reports[index - 1].acc_code_level2)  &&

                                                ((filter.column == '6' && (totalAccountCodeLevel(report.acc_code_level4, 'opening_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'opening_credit', 'acc_code_level4') != 0 || totalAccountCodeLevel(report.acc_code_level4, 'current_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'current_credit', 'acc_code_level4') != 0  || totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))

                                                ||

                                                (filter.column == '2' && (totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0)))">
                                                <td colspan="7" class="border border-gray-400 border-gray-400 font-semibold text-left pl-4">
                                                {{ report.acc_desc_level2 }}
                                                </td>
                                            </tr> -->
                                                <!-- Sub Head Account Level -->
                                                <!-- <tr v-if="(index === 0 || report.acc_code_level3 !== reports[index - 1].acc_code_level3)  &&

                                                ((filter.column == '6' && (totalAccountCodeLevel(report.acc_code_level4, 'opening_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'opening_credit', 'acc_code_level4') != 0 || totalAccountCodeLevel(report.acc_code_level4, 'current_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'current_credit', 'acc_code_level4') != 0  || totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))

                                                ||

                                                (filter.column == '2' && (totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0)))">
                                                <td colspan="7" class="border border-gray-400 border-gray-400 font-semibold text-left pl-8">
                                                {{ report.acc_desc_level3 }}
                                                </td>
                                            </tr> -->
                                                <!-- Account Code Level -->
                                                <tr
                                                    v-if="
                                                        (index === 0 ||
                                                            report.acc_code_level4 !==
                                                                reports[
                                                                    index - 1
                                                                ]
                                                                    .acc_code_level4) &&
                                                        ((filter.column ==
                                                            'current' &&
                                                            totalAccountCodeLevel(
                                                                report.acc_code_level4,
                                                                'current_debit',
                                                                'acc_code_level4'
                                                            ) -
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    'current_credit',
                                                                    'acc_code_level4'
                                                                ) !=
                                                                0) ||
                                                            (filter.column ==
                                                                '6' &&
                                                                (totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    'opening_debit',
                                                                    'acc_code_level4'
                                                                ) -
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level4,
                                                                        'opening_credit',
                                                                        'acc_code_level4'
                                                                    ) !=
                                                                    0 ||
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level4,
                                                                        'current_debit',
                                                                        'acc_code_level4'
                                                                    ) -
                                                                        totalAccountCodeLevel(
                                                                            report.acc_code_level4,
                                                                            'current_credit',
                                                                            'acc_code_level4'
                                                                        ) !=
                                                                        0 ||
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level4,
                                                                        'closing_debit',
                                                                        'acc_code_level4'
                                                                    ) -
                                                                        totalAccountCodeLevel(
                                                                            report.acc_code_level4,
                                                                            'closing_credit',
                                                                            'acc_code_level4'
                                                                        ) !=
                                                                        0)) ||
                                                            (filter.column ==
                                                                '2' &&
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    'closing_debit',
                                                                    'acc_code_level4'
                                                                ) -
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level4,
                                                                        'closing_credit',
                                                                        'acc_code_level4'
                                                                    ) !=
                                                                    0))
                                                    "
                                                >
                                                    <td
                                                        class="border-b border-gray-400 bg-blue-200 font-bold text-lg text-left pl-8"
                                                    >
                                                        {{
                                                            report.acc_desc_level4
                                                        }}
                                                        {{
                                                            report.acc_code_level4
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-blue-200 text-right text-lg pr-4 font-bold"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    "opening_debit",
                                                                    "acc_code_level4"
                                                                )
                                                            )
                                                        }}
                                                    </td>

                                                    <td
                                                        class="border-b border-gray-400 bg-blue-200 text-right text-lg pr-4 font-bold"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    "opening_credit",
                                                                    "acc_code_level4"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-blue-200 text-right text-lg pr-4 font-bold"
                                                        v-if="
                                                            filter.column ==
                                                                '6' ||
                                                            filter.column ==
                                                                'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    "current_debit",
                                                                    "acc_code_level4"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-blue-200 text-right text-lg pr-4 font-bold"
                                                        v-if="
                                                            filter.column ==
                                                                '6' ||
                                                            filter.column ==
                                                                'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    "current_credit",
                                                                    "acc_code_level4"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-blue-200 text-right text-lg pr-4 font-bold"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    "closing_debit",
                                                                    "acc_code_level4"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-blue-200 text-right text-lg pr-4 font-bold"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    "closing_credit",
                                                                    "acc_code_level4"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 bg-blue-200 text-right text-lg pr-4 font-bold"
                                                        v-if="
                                                            filter.column ==
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                +totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    "current_debit",
                                                                    "acc_code_level4"
                                                                ) -
                                                                    +totalAccountCodeLevel(
                                                                        report.acc_code_level4,
                                                                        "current_credit",
                                                                        "acc_code_level4"
                                                                    )
                                                            ) +
                                                            (+totalAccountCodeLevel(
                                                                report.acc_code_level4,
                                                                "current_debit",
                                                                "acc_code_level4"
                                                            ) -
                                                                +totalAccountCodeLevel(
                                                                    report.acc_code_level4,
                                                                    "current_credit",
                                                                    "acc_code_level4"
                                                                ) <
                                                            0
                                                                ? " Cr"
                                                                : " Dr")
                                                        }}
                                                    </td>
                                                </tr>
                                                <!-- Chart of Accounts Level -->
                                                <tr
                                                    v-if="
                                                        report.acc_code_level5 &&
                                                        report.balance !== 0 &&
                                                        ((filter.column ==
                                                            'current' &&
                                                            report.current_debit -
                                                                report.current_credit +
                                                                +report.ca_opening !=
                                                                0) ||
                                                            (filter.column ==
                                                                '6' &&
                                                                (report.opening_debit -
                                                                    report.opening_credit +
                                                                    +report.ca_opening !=
                                                                    0 ||
                                                                    report.current_debit -
                                                                        report.current_credit +
                                                                        +report.ca_opening !=
                                                                        0)) ||
                                                            (filter.column ==
                                                                '2' &&
                                                                report.closing_debit -
                                                                    report.closing_credit +
                                                                    +report.ca_opening !=
                                                                    0))
                                                    "
                                                >
                                                    <td
                                                        class="border-b border-gray-400 pl-8"
                                                    >
                                                        {{
                                                            report.acc_code_level5
                                                        }}
                                                        -
                                                        {{
                                                            report.acc_desc_level5
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        {{
                                                            report.opening_debit -
                                                                report.opening_credit +
                                                                +report.ca_opening >
                                                            0
                                                                ? withCommas(
                                                                      parseFloat(
                                                                          report.opening_debit -
                                                                              report.opening_credit +
                                                                              +report.ca_opening
                                                                      )
                                                                  )
                                                                : 0
                                                        }}
                                                    </td>

                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        {{
                                                            report.opening_debit -
                                                                report.opening_credit +
                                                                +report.ca_opening <=
                                                            0
                                                                ? withCommas(
                                                                      -1 *
                                                                          parseFloat(
                                                                              report.opening_debit -
                                                                                  report.opening_credit +
                                                                                  +report.ca_opening
                                                                          )
                                                                  )
                                                                : 0
                                                        }}
                                                    </td>

                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            (filter.column ==
                                                                '6' &&
                                                                projectType ==
                                                                    'hms') ||
                                                            (filter.column ==
                                                                'current' &&
                                                                projectType ==
                                                                    'hms')
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                parseFloat(
                                                                    report.current_debit -
                                                                        report.current_credit >
                                                                        0
                                                                        ? report.current_debit -
                                                                              report.current_credit
                                                                        : 0
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            (filter.column ==
                                                                '6' &&
                                                                projectType ==
                                                                    'jinnah') ||
                                                            (filter.column ==
                                                                'current' &&
                                                                projectType ==
                                                                    'jinnah')
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                parseFloat(
                                                                    report.current_debit ??
                                                                        0
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            (filter.column ==
                                                                '6' &&
                                                                projectType ==
                                                                    'hms') ||
                                                            (filter.column ==
                                                                'current' &&
                                                                projectType ==
                                                                    'hms')
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                parseFloat(
                                                                    report.current_debit -
                                                                        report.current_credit <=
                                                                        0
                                                                        ? -1 *
                                                                              (report.current_debit -
                                                                                  report.current_credit)
                                                                        : 0
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            (filter.column ==
                                                                '6' &&
                                                                projectType ==
                                                                    'jinnah') ||
                                                            (filter.column ==
                                                                'current' &&
                                                                projectType ==
                                                                    'jinnah')
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                parseFloat(
                                                                    report.current_credit ??
                                                                        0
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                parseFloat(
                                                                    report.closing_debit -
                                                                        report.closing_credit +
                                                                        +report.ca_opening >
                                                                        0
                                                                        ? report.closing_debit -
                                                                              report.closing_credit +
                                                                              +report.ca_opening
                                                                        : 0
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                parseFloat(
                                                                    report.closing_debit -
                                                                        report.closing_credit +
                                                                        +report.ca_opening <=
                                                                        0
                                                                        ? -1 *
                                                                              (report.closing_debit -
                                                                                  report.closing_credit +
                                                                                  +report.ca_opening)
                                                                        : 0
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-400 text-right pr-4"
                                                        v-if="
                                                            filter.column ==
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                parseFloat(
                                                                    +report.current_debit -
                                                                        +report.current_credit ??
                                                                        0
                                                                )
                                                            )
                                                        }}
                                                        <span>
                                                            {{
                                                                parseFloat(
                                                                    +report.current_debit -
                                                                        +report.current_credit ??
                                                                        0
                                                                ) < 0
                                                                    ? " Cr"
                                                                    : " Dr"
                                                            }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </template>
                                            <tr class="hover:bg-gray-50">
                                                <td
                                                    class="text-lg font-semibold text-gray-900"
                                                >
                                                    Grand Total
                                                </td>
                                                <td
                                                    class="text-lg text_white text-right bg-gray-800 font-semibold"
                                                    v-if="filter.column == '6'"
                                                >
                                                    {{
                                                        withCommas(
                                                            GrandTotal(
                                                                "opening_debit",
                                                                "acc_code_level1"
                                                            )
                                                        )
                                                    }}
                                                </td>
                                                <td
                                                    class="text-lg text_white text-right bg-gray-800 font-semibold"
                                                    v-if="filter.column == '6'"
                                                >
                                                    {{
                                                        withCommas(
                                                            GrandTotal(
                                                                "opening_credit",
                                                                "acc_code_level1"
                                                            )
                                                        )
                                                    }}
                                                </td>
                                                <td
                                                    class="text-lg text_white text-right bg-gray-800 font-semibold"
                                                    v-if="
                                                        filter.column == '6' ||
                                                        filter.column ==
                                                            'current'
                                                    "
                                                >
                                                    {{
                                                        withCommas(
                                                            GrandTotal(
                                                                "current_debit",
                                                                "acc_code_level1"
                                                            )
                                                        )
                                                    }}
                                                </td>
                                                <td
                                                    class="text-lg text_white text-right bg-gray-800 font-semibold"
                                                    v-if="
                                                        filter.column == '6' ||
                                                        filter.column ==
                                                            'current'
                                                    "
                                                >
                                                    {{
                                                        withCommas(
                                                            GrandTotal(
                                                                "current_credit",
                                                                "acc_code_level1"
                                                            )
                                                        )
                                                    }}
                                                </td>
                                                <td
                                                    class="text-lg text_white text-right bg-gray-800 font-semibold"
                                                    v-if="
                                                        filter.column !=
                                                        'current'
                                                    "
                                                >
                                                    {{
                                                        withCommas(
                                                            GrandTotal(
                                                                "closing_debit",
                                                                "acc_code_level1"
                                                            )
                                                        )
                                                    }}
                                                </td>
                                                <td
                                                    class="text-lg text_white text-right bg-gray-800 font-semibold"
                                                    v-if="
                                                        filter.column !=
                                                        'current'
                                                    "
                                                >
                                                    {{
                                                        withCommas(
                                                            GrandTotal(
                                                                "closing_credit",
                                                                "acc_code_level1"
                                                            )
                                                        )
                                                    }}
                                                </td>
                                                <td
                                                    class="text-lg text_white text-right bg-gray-800 font-semibold"
                                                    v-if="
                                                        filter.column ==
                                                        'current'
                                                    "
                                                ></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex px-4 mb-2">
                                <div class="w-full">
                                    <div
                                        class="rounded-lg overflow-hidden bg-gray-50 shadow-sm ring-1 ring-gray-300"
                                    >
                                        <table
                                            class="min-w-full divide-y divide-gray-300"
                                        >
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="whitespace-nowrap text-left text-lg font-semibold text-gray-900"
                                                    >
                                                        Summary
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-blue-200"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        Opening Debit
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-blue-200"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        Opening Credit
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-green-200"
                                                        v-if="
                                                            filter.column ==
                                                                '6' ||
                                                            filter.column ==
                                                                'current'
                                                        "
                                                    >
                                                        Current Debit
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-green-200"
                                                        v-if="
                                                            filter.column ==
                                                                '6' ||
                                                            filter.column ==
                                                                'current'
                                                        "
                                                    >
                                                        Current Credit
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-yellow-200"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        Closing Debit
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-yellow-200"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        Closing Credit
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-gray-300"
                                            >
                                                <template
                                                    v-for="(
                                                        report, index
                                                    ) in reports"
                                                    :key="index"
                                                >
                                                    <tr
                                                        v-if="
                                                            index === 0 ||
                                                            report.acc_code_level1 !==
                                                                reports[
                                                                    index - 1
                                                                ]
                                                                    .acc_code_level1
                                                        "
                                                        class="hover:bg-gray-50"
                                                    >
                                                        <td
                                                            class="text-lg font-medium text-gray-900"
                                                        >
                                                            Total
                                                            {{
                                                                report.acc_desc_level1
                                                            }}
                                                        </td>
                                                        <td
                                                            class="text-lg text-gray-900 bg-blue-200"
                                                            v-if="
                                                                filter.column ==
                                                                '6'
                                                            "
                                                        >
                                                            {{
                                                                withCommas(
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level1,
                                                                        "opening_debit",
                                                                        "acc_code_level1"
                                                                    )
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="text-lg text-gray-900 bg-blue-200"
                                                            v-if="
                                                                filter.column ==
                                                                '6'
                                                            "
                                                        >
                                                            {{
                                                                withCommas(
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level1,
                                                                        "opening_credit",
                                                                        "acc_code_level1"
                                                                    )
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="text-lg text-gray-900 bg-green-200"
                                                            v-if="
                                                                filter.column ==
                                                                    '6' ||
                                                                filter.column ==
                                                                    'current'
                                                            "
                                                        >
                                                            {{
                                                                withCommas(
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level1,
                                                                        "current_debit",
                                                                        "acc_code_level1"
                                                                    )
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="text-lg text-gray-900 bg-green-200"
                                                            v-if="
                                                                filter.column ==
                                                                    '6' ||
                                                                filter.column ==
                                                                    'current'
                                                            "
                                                        >
                                                            {{
                                                                withCommas(
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level1,
                                                                        "current_credit",
                                                                        "acc_code_level1"
                                                                    )
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="text-lg text-gray-900 bg-yellow-200"
                                                            v-if="
                                                                filter.column !=
                                                                'current'
                                                            "
                                                        >
                                                            {{
                                                                withCommas(
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level1,
                                                                        "closing_debit",
                                                                        "acc_code_level1"
                                                                    )
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="text-lg text-gray-900 bg-yellow-200"
                                                            v-if="
                                                                filter.column !=
                                                                'current'
                                                            "
                                                        >
                                                            {{
                                                                withCommas(
                                                                    totalAccountCodeLevel(
                                                                        report.acc_code_level1,
                                                                        "closing_credit",
                                                                        "acc_code_level1"
                                                                    )
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr class="hover:bg-gray-50">
                                                    <td
                                                        class="text-lg font-semibold text-gray-900"
                                                    >
                                                        Grand Total
                                                    </td>
                                                    <td
                                                        class="text-lg text_white bg-gray-800 font-semibold"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                GrandTotal(
                                                                    "opening_debit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="text-lg text_white bg-gray-800 font-semibold"
                                                        v-if="
                                                            filter.column == '6'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                GrandTotal(
                                                                    "opening_credit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="text-lg text_white bg-gray-800 font-semibold"
                                                        v-if="
                                                            filter.column ==
                                                                '6' ||
                                                            filter.column ==
                                                                'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                GrandTotal(
                                                                    "current_debit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="text-lg text_white bg-gray-800 font-semibold"
                                                        v-if="
                                                            filter.column ==
                                                                '6' ||
                                                            filter.column ==
                                                                'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                GrandTotal(
                                                                    "current_credit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="text-lg text_white bg-gray-800 font-semibold"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                GrandTotal(
                                                                    "closing_debit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="text-lg text_white bg-gray-800 font-semibold"
                                                        v-if="
                                                            filter.column !=
                                                            'current'
                                                        "
                                                    >
                                                        {{
                                                            withCommas(
                                                                GrandTotal(
                                                                    "closing_credit",
                                                                    "acc_code_level1"
                                                                )
                                                            )
                                                        }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- end report date -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted, watch, onUpdated } from "vue";
import dayjs from "dayjs";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";


const from_date = ref(null);
const to_date = ref(null);


const props = defineProps({
    reports: Array,
    from_date: String,
    sort_by_code: Boolean,
    to_date: String,
    cr_sum: String,
    dr_sum: String,
    sort_by_code: String,
    accounts: Array,
    selected_accounts: String,
});

const sortByAccCode = ref(props.sort_by_code === "true");

// const sortedReports = computed(() => {
//   if (!sortByAccCode.value) {
//     return props.reports;
//   }
//   return [...props.reports].sort((a, b) => {
//     return a.acc_code_level4.localeCompare(b.acc_code_level4);
//   });
// });

// const sortReports = () => {
// };
const projectType = import.meta.env.VITE_PROJECT_TYPE;

const defaultFromDate =
    dayjs().month() < 6
        ? dayjs()
              .year(dayjs().year() - 1)
              .month(6)
              .date(1)
              .format("DD-MM-YYYY")
        : dayjs().year(dayjs().year()).month(6).date(1).format("DD-MM-YYYY");

const filter = useForm({
    from_date: props?.from_date || defaultFromDate,
    to_date:
        props?.to_date ||
        new Date().toLocaleDateString("en-GB").replace(/\//g, "-"),
    sortByAccCode: props.sort_by_code === "true",
    selected_accounts: props?.selected_accounts || null,
    column: "2",
});

const updateSortQuery = () => {
    filter.sortByAccCode = sortByAccCode.value;
    filter.get(route("trial-balance"), {
        preserveState: true,
        replace: true,
    });
};
const selectedAccount = ref(null);
const isLoading = ref(false);
const onAccountSelect = (selectedAccount) => {
    filter.selected_accounts = selectedAccount?.acc_code ?? null;
};
if (props?.selected_accounts) {
    const code = props.accounts.find(
        (c) => c.acc_code === props?.selected_accounts
    );
    if (code) {
        selectedAccount.value = code;
    }
}

const withCommas = (value) => {
    if (value == null || value == "") {
        return "0";
    }
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

const formatWithCommas = (value) => {
    if (value == null || value === "") {
        return "0";
    }
    const number = value;
    const formattedNumber = number
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return number < 0
        ? `(${formattedNumber.replace("-", "")})`
        : formattedNumber;
};

const totalAssets = computed(() => {
    return props.reports
        .filter((r) => r.acc_code_level1.startsWith("10"))
        .reduce((sum, report) => sum + parseFloat(report.balance || 0), 0);
});

const totalLiabilities = computed(() => {
    return props.reports
        .filter((r) => r.acc_code_level1.startsWith("11"))
        .reduce((sum, report) => sum + parseFloat(report.balance || 0), 0);
});

const GrandTotal = (type, levelType) => {
    let reports = props.reports;
    let sum = 0;
    reports.map((res, index) => {
        if (
            index === 0 ||
            res.acc_code_level1 !== reports[index - 1].acc_code_level1
        ) {
            sum =
                +sum +
                +totalAccountCodeLevel(res.acc_code_level1, type, levelType);
        }
    });
    return sum;
};
const totalAccountCodeLevel = (level4Code, type, levelType) => {
    let reports = props.reports.filter((r) => r[levelType] === level4Code);
    let sum = 0;
    reports.map((res, index) => {
        if (type == "opening_debit") {
            sum =
                +sum +
                +(res.opening_debit - res.opening_credit + +res.ca_opening > 0
                    ? res.opening_debit - res.opening_credit + +res.ca_opening
                    : 0);
        } else if (type == "opening_credit") {
            sum =
                +sum +
                +(res.opening_debit - res.opening_credit + +res.ca_opening <= 0
                    ? res.opening_debit - res.opening_credit + +res.ca_opening
                    : 0);
        } else if (type == "current_debit") {
            sum =
                +sum +
                +(res.current_debit - res.current_credit > 0
                    ? res.current_debit - res.current_credit
                    : 0);
        } else if (type == "current_credit") {
            sum =
                +sum +
                +(res.current_debit - res.current_credit <= 0
                    ? res.current_debit - res.current_credit
                    : 0);
        } else if (type == "closing_debit") {
            sum =
                +sum +
                +(res.closing_debit - res.closing_credit + +res.ca_opening > 0
                    ? res.closing_debit - res.closing_credit + +res.ca_opening
                    : 0);
        } else if (type == "closing_credit") {
            sum =
                +sum +
                +(res.closing_debit - res.closing_credit + +res.ca_opening <= 0
                    ? res.closing_debit - res.closing_credit + +res.ca_opening
                    : 0);
        }
    });
    if (sum < 0) {
        sum = -1 * sum;
    }
    return parseFloat(sum);
    // .reduce((sum, report) => sum + parseFloat(report[type] || 0), 0);
};

const print = () => {
    var header_str =
        "<html><head><title>" + document.title + "</title></head><body>";
    var footer_str = "</body></html>";
    var new_str = document.getElementById("printData").innerHTML;
    var old_str = document.body.innerHTML;
    document.body.innerHTML = header_str + new_str + footer_str;
    window.print();

    return false;
};

const handleKeydown = (event) => {
    if (event.ctrlKey && event.key === "p") {
        event.preventDefault();
        window.location.reload();
    }
};
const accountLabel = (account) => {
    return `${account.acc_desc} (${account.acc_code})`;
};

const getFinancialYearStart = () => {
  const today = new Date();
  const currentYear = today.getFullYear();
  const financialYearStart = new Date(currentYear, 6, 1); // July 1st
  // If today is before July 1st, set financial year to last year's July 1st
  if (today.getMonth() < 6) {
    financialYearStart.setFullYear(currentYear - 1);
  }
  return financialYearStart;
};
const parseDate = (dateString) => {
            if (!dateString) return new Date();
            const [day, month, year] = dateString.split("-");
            return new Date(`${year}-${month}-${day}`);
        };
onMounted(() => {

    const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y", // Format: DD-MM-YYYY
        allowInput: true, // Allows manual typing
        clickOpens: true, // Ensures the picker still works
        defaultDate: defaultDate || new Date(), // Use provided default date or current date
    });

    flatpickr(to_date.value, flatpickrOptions(filter.to_date));
    flatpickr(from_date.value, flatpickrOptions(filter.from_date));

    window.addEventListener("keydown", handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeydown);
});
</script>
<script>
import Multiselect from "vue-multiselect";
export default {
    components: {
        Multiselect,
        AppLayout,
        Head,
        InertiaLink,
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style>
.print-only {
    display: none;
}
table {
    width: 100%;
    height: auto;
    border-collapse: collapse;
}

table th,
table tfoot td {
    padding: 12px 14px;
    font-size: 16px;
}
table td {
    padding: 10px 12px;
    font-size: 15px;
}
@media only screen and (min-width: 1600px) {
    table th,
    table tfoot td {
        padding: 14px 16px;
        font-size: 18px;
    }
}

.text_white {
    color: #fff;
}
@media print {
    .print-only {
        display: block;
    }
    @page {
        size: A4;
        margin: 55px 0px 40px 0px;

        font-family: "Fira Mono";
        font-size: 12px;
        sheet-size: A4;

        @top-right {
            content: "Page " counter(page) " of " counter(pages);
            margin-right: 20px;
            margin-top: 40px;
            padding-top: 20px !important;
            padding-bottom: 10px !important;
        }
    }

    table th {
        margin-top: 37px !important;
        padding: 8px 4px;
        font-size: 12px !important;
        line-height: 10px !important;
    }
    table tfoot td {
        padding: 6px;
        font-size: 12px !important;
        line-height: 14px !important;
    }
    table td {
        padding: 4px 4px 3px;
        font-size: 11px !important;
        line-height: 11px !important;
    }
    .text_white {
        color: #000 !important;
    }
}
</style>
