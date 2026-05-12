<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    dashboard,
    login,
} from '@/routes';

const employees = [
    {
        name: 'Emily Watson',
        role: 'HR Manager',
        department: 'Human Resources',
    },
    {
        name: 'Michael Turner',
        role: 'Software Engineer',
        department: 'Engineering',
    },
    {
        name: 'Olivia Green',
        role: 'Marketing Lead',
        department: 'Marketing',
    },
];
</script>

<template>
    <Head title="HR Portal">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <header class="mb-6 w-full max-w-7xl text-sm">
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
                                HR Platform
                            </span>

                            <h1
                                class="mt-6 text-4xl font-bold tracking-tight lg:text-5xl dark:text-[#EDEDEC]"
                            >
                                Human Resources Management
                            </h1>

                            <p
                                class="mt-4 max-w-xl text-base leading-7 text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Manage employees, recruitment, attendance,
                                leave requests, and company culture from a
                                single HR portal.
                            </p>

                            <div class="mt-8 flex gap-4">
                                <button
                                    class="rounded-sm border border-black bg-[#F53003] px-6 py-3 text-sm font-medium text-white hover:bg-[#d92800]"
                                >
                                    Add Employee
                                </button>

                                <button
                                    class="rounded-sm border border-[#19140035] px-6 py-3 text-sm font-medium text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC]"
                                >
                                    View Attendance
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
                                    cy="60"
                                    r="24"
                                    fill="currentColor"
                                />

                                <circle
                                    cx="60"
                                    cy="130"
                                    r="18"
                                    fill="currentColor"
                                    opacity="0.7"
                                />

                                <circle
                                    cx="140"
                                    cy="130"
                                    r="18"
                                    fill="currentColor"
                                    opacity="0.7"
                                />

                                <path
                                    d="M40 170C40 145 55 132 80 132H120C145 132 160 145 160 170"
                                    stroke="currentColor"
                                    stroke-width="10"
                                    stroke-linecap="round"
                                />
                            </svg>

                            <div
                                class="absolute inset-0 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                            />
                        </div>
                    </div>
                </div>

                <!-- Employee Directory -->
                <div
                    class="mt-8 rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.08)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed14]"
                >
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-2xl font-semibold dark:text-[#EDEDEC]">
                            Employee Directory
                        </h2>

                        <button
                            class="rounded-sm border border-black bg-[#F53003] px-4 py-2 text-sm font-medium text-white"
                        >
                            Invite Employee
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-[#e5e5e5] dark:border-[#2b2b2b]">
                                    <th class="pb-3">Name</th>
                                    <th class="pb-3">Role</th>
                                    <th class="pb-3">Department</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="employee in employees"
                                    :key="employee.name"
                                    class="border-b border-[#f1f1f1] dark:border-[#222]"
                                >
                                    <td class="py-4 font-medium dark:text-[#EDEDEC]">
                                        {{ employee.name }}
                                    </td>

                                    <td>{{ employee.role }}</td>

                                    <td>{{ employee.department }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>