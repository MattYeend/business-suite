<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    dashboard,
    login,
} from '@/routes';

const stats = [
    { label: 'Active Customers', value: '1,284' },
    { label: 'Deals Pipeline', value: '£482k' },
    { label: 'Open Tickets', value: '38' },
    { label: 'Conversion Rate', value: '24%' },
];

const customers = [
    {
        name: 'Acme Solutions',
        contact: 'Sarah Mitchell',
        email: 'sarah@acme.com',
        status: 'Active',
    },
    {
        name: 'Vertex Industries',
        contact: 'James Carter',
        email: 'james@vertex.com',
        status: 'Pending',
    },
    {
        name: 'Northwind Group',
        contact: 'Emma Lewis',
        email: 'emma@northwind.com',
        status: 'Active',
    },
];
</script>

<template>
    <Head title="CRM Dashboard">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <header
            class="mb-6 w-full max-w-7xl text-sm"
        >
            <nav class="flex items-center justify-between">
                <Link
                    href="/"
                    class="font-semibold text-[#F53003]"
                >
                    Business Suite
                </Link>

                <div class="flex items-center gap-4">
                    <Link
                        :href="dashboard()"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Dashboard
                    </Link>

                    <Link
                        :href="login()"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                </div>
            </nav>
        </header>

        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main class="w-full max-w-7xl">
                <!-- Hero -->
                <div
                    class="overflow-hidden rounded-lg bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                >
                    <div class="grid lg:grid-cols-2">
                        <!-- Left -->
                        <div class="p-8 lg:p-12">
                            <span
                                class="inline-flex rounded-full bg-[#F53003]/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#F53003]"
                            >
                                CRM Platform
                            </span>

                            <h1
                                class="mt-6 text-4xl font-bold tracking-tight lg:text-5xl dark:text-[#EDEDEC]"
                            >
                                Manage Customers & Sales
                            </h1>

                            <p
                                class="mt-4 max-w-xl text-base leading-7 text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Track leads, monitor pipelines, manage support,
                                and build stronger customer relationships from
                                one central platform.
                            </p>

                            <div class="mt-8 flex gap-4">
                                <button
                                    class="rounded-sm border border-black bg-[#F53003] px-6 py-3 text-sm font-medium text-white hover:bg-[#d92800]"
                                >
                                    Add Customer
                                </button>

                                <button
                                    class="rounded-sm border border-[#19140035] px-6 py-3 text-sm font-medium text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC]"
                                >
                                    View Reports
                                </button>
                            </div>
                        </div>

                        <!-- Right -->
                        <div
                            class="relative flex items-center justify-center overflow-hidden bg-[#fff2f2] dark:bg-[#1D0002]"
                        >
                            <svg
                                class="h-[340px] w-[340px] text-[#F53003]"
                                viewBox="0 0 200 200"
                                fill="none"
                            >
                                <circle
                                    cx="100"
                                    cy="100"
                                    r="80"
                                    stroke="currentColor"
                                    stroke-width="10"
                                    opacity="0.15"
                                />

                                <circle
                                    cx="100"
                                    cy="100"
                                    r="45"
                                    fill="currentColor"
                                    opacity="0.12"
                                />

                                <circle
                                    cx="60"
                                    cy="70"
                                    r="12"
                                    fill="currentColor"
                                />

                                <circle
                                    cx="140"
                                    cy="70"
                                    r="12"
                                    fill="currentColor"
                                />

                                <circle
                                    cx="100"
                                    cy="140"
                                    r="12"
                                    fill="currentColor"
                                />

                                <line
                                    x1="60"
                                    y1="70"
                                    x2="100"
                                    y2="140"
                                    stroke="currentColor"
                                    stroke-width="6"
                                />

                                <line
                                    x1="140"
                                    y1="70"
                                    x2="100"
                                    y2="140"
                                    stroke="currentColor"
                                    stroke-width="6"
                                />
                            </svg>

                            <div
                                class="absolute inset-0 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                            />
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.08)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed14]"
                    >
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            {{ stat.label }}
                        </p>

                        <h2 class="mt-3 text-3xl font-bold dark:text-[#EDEDEC]">
                            {{ stat.value }}
                        </h2>
                    </div>
                </div>

                <!-- Customers -->
                <div
                    class="mt-8 rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.08)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed14]"
                >
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-2xl font-semibold dark:text-[#EDEDEC]">
                            Customer Directory
                        </h2>

                        <input
                            type="text"
                            placeholder="Search customers..."
                            class="rounded-sm border border-[#19140035] bg-transparent px-4 py-2 text-sm dark:border-[#3E3E3A] dark:text-[#EDEDEC]"
                        />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-[#e5e5e5] dark:border-[#2b2b2b]">
                                    <th class="pb-3">Company</th>
                                    <th class="pb-3">Contact</th>
                                    <th class="pb-3">Email</th>
                                    <th class="pb-3">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="customer in customers"
                                    :key="customer.email"
                                    class="border-b border-[#f1f1f1] dark:border-[#222]"
                                >
                                    <td class="py-4 font-medium dark:text-[#EDEDEC]">
                                        {{ customer.name }}
                                    </td>

                                    <td>{{ customer.contact }}</td>

                                    <td>{{ customer.email }}</td>

                                    <td>
                                        <span
                                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                                        >
                                            {{ customer.status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pipeline -->
                <div
                    class="mt-8 rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.08)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed14]"
                >
                    <h2 class="mb-6 text-2xl font-semibold dark:text-[#EDEDEC]">
                        Sales Pipeline
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <div class="mb-2 flex justify-between text-sm">
                                <span>New Leads</span>
                                <span>70%</span>
                            </div>

                            <div class="h-3 rounded-full bg-gray-100 dark:bg-[#222]">
                                <div
                                    class="h-3 w-[70%] rounded-full bg-[#F53003]"
                                />
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex justify-between text-sm">
                                <span>Negotiation</span>
                                <span>45%</span>
                            </div>

                            <div class="h-3 rounded-full bg-gray-100 dark:bg-[#222]">
                                <div
                                    class="h-3 w-[45%] rounded-full bg-blue-500"
                                />
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex justify-between text-sm">
                                <span>Closed Deals</span>
                                <span>30%</span>
                            </div>

                            <div class="h-3 rounded-full bg-gray-100 dark:bg-[#222]">
                                <div
                                    class="h-3 w-[30%] rounded-full bg-green-500"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>