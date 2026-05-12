<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    dashboard,
    login,
} from '@/routes';

const modules = [
    {
        title: 'Inventory',
        description: 'Track stock levels and warehouse activity.',
    },
    {
        title: 'Purchasing',
        description: 'Manage suppliers and purchase orders.',
    },
    {
        title: 'Finance',
        description: 'Monitor revenue, invoices, and expenses.',
    },
    {
        title: 'Operations',
        description: 'Oversee workflows and manufacturing.',
    },
];
</script>

<template>
    <Head title="ERP Management">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <header class="mb-6 w-full max-w-7xl text-sm">
            <nav class="flex items-center justify-between">
                <Link href="/" class="font-semibold text-[#F53003]">
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
                <div
                    class="overflow-hidden rounded-lg bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                >
                    <div class="grid lg:grid-cols-2">
                        <div class="p-8 lg:p-12">
                            <span
                                class="inline-flex rounded-full bg-[#F53003]/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#F53003]"
                            >
                                ERP Platform
                            </span>

                            <h1 class="mt-6 text-4xl font-bold tracking-tight lg:text-5xl dark:text-[#EDEDEC]">
                                Enterprise Resource Planning
                            </h1>

                            <p class="mt-4 max-w-xl text-base leading-7 text-[#706f6c] dark:text-[#A1A09A]">
                                Centralise operations, inventory, purchasing,
                                finance, and reporting in one integrated ERP system.
                            </p>

                            <div class="mt-8 flex gap-4">
                                <button
                                    class="rounded-sm border border-black bg-[#F53003] px-6 py-3 text-sm font-medium text-white"
                                >
                                    Generate Report
                                </button>

                                <button
                                    class="rounded-sm border border-[#19140035] px-6 py-3 text-sm font-medium dark:border-[#3E3E3A] dark:text-[#EDEDEC]"
                                >
                                    Manage Inventory
                                </button>
                            </div>
                        </div>

                        <div
                            class="relative flex items-center justify-center overflow-hidden bg-[#fff2f2] dark:bg-[#1D0002]"
                        >
                            <svg
                                class="h-[340px] w-[340px] text-[#F53003]"
                                viewBox="0 0 200 200"
                                fill="none"
                            >
                                <rect
                                    x="40"
                                    y="40"
                                    width="120"
                                    height="120"
                                    rx="20"
                                    stroke="currentColor"
                                    stroke-width="8"
                                    opacity="0.2"
                                />

                                <rect x="60" y="60" width="30" height="30" rx="6" fill="currentColor" />
                                <rect x="110" y="60" width="30" height="30" rx="6" fill="currentColor" opacity="0.7" />
                                <rect x="60" y="110" width="30" height="30" rx="6" fill="currentColor" opacity="0.7" />
                                <rect x="110" y="110" width="30" height="30" rx="6" fill="currentColor" />
                            </svg>

                            <div
                                class="absolute inset-0 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <div
                        v-for="module in modules"
                        :key="module.title"
                        class="rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.08)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed14]"
                    >
                        <h2 class="text-xl font-semibold dark:text-[#EDEDEC]">
                            {{ module.title }}
                        </h2>

                        <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            {{ module.description }}
                        </p>
                    </div>
                </div>
            </main>
        </div>

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>