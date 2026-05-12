<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    dashboard,
    login,
} from '@/routes';

const courses = [
    {
        title: 'Laravel Masterclass',
        progress: 82,
    },
    {
        title: 'Vue 3 Fundamentals',
        progress: 61,
    },
    {
        title: 'Leadership Training',
        progress: 38,
    },
];
</script>

<template>
    <Head title="Learning Management System">
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
                                LMS Platform
                            </span>

                            <h1
                                class="mt-6 text-4xl font-bold tracking-tight lg:text-5xl dark:text-[#EDEDEC]"
                            >
                                Learning Management System
                            </h1>

                            <p
                                class="mt-4 max-w-xl text-base leading-7 text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Deliver training, manage learning paths,
                                track progress, and develop employee skills.
                            </p>

                            <div class="mt-8 flex gap-4">
                                <button
                                    class="rounded-sm border border-black bg-[#F53003] px-6 py-3 text-sm font-medium text-white hover:bg-[#d92800]"
                                >
                                    Create Course
                                </button>

                                <button
                                    class="rounded-sm border border-[#19140035] px-6 py-3 text-sm font-medium text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC]"
                                >
                                    View Analytics
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
                                <path
                                    d="M40 60L100 30L160 60L100 90L40 60Z"
                                    fill="currentColor"
                                />

                                <path
                                    d="M60 90V125C60 145 78 160 100 160C122 160 140 145 140 125V90"
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

                <!-- Courses -->
                <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="course in courses"
                        :key="course.title"
                        class="rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.08)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed14]"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-xl font-semibold dark:text-[#EDEDEC]">
                                    {{ course.title }}
                                </h2>

                                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Professional development course.
                                </p>
                            </div>

                            <span
                                class="rounded-full bg-[#F53003]/10 px-3 py-1 text-sm font-medium text-[#F53003]"
                            >
                                Active
                            </span>
                        </div>

                        <div class="mt-6">
                            <div class="mb-2 flex justify-between text-sm">
                                <span>Progress</span>
                                <span>{{ course.progress }}%</span>
                            </div>

                            <div class="h-3 rounded-full bg-gray-100 dark:bg-[#222]">
                                <div
                                    class="h-3 rounded-full bg-[#F53003]"
                                    :style="{ width: `${course.progress}%` }"
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