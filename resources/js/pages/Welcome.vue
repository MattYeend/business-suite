<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    dashboard,
    login
} from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <header
            class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
        >
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main
                class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row"
            >
                <div
                    class="flex-1 rounded-br-lg rounded-bl-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:rounded-tl-lg lg:rounded-br-none lg:p-20 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                >
                    <h1 class="mb-1 font-medium">Welcome to Your Workspace</h1>
                    <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                        Your all in one business management platform. <br />Access the tools you need to succeed.
                    </p>
                    <ul class="mb-4 flex flex-col lg:mb-6">
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-1/2 before:bottom-0 before:left-[0.4rem] before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span
                                class="relative bg-white py-1 dark:bg-[#161615]"
                            >
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]"
                                    />
                                </span>
                            </span>
                            <span>
                                Manage customer relationships with
                                <a
                                    href="/crm"
                                    class="ml-1 inline-flex items-center space-x-1 font-medium text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]"
                                >
                                    <span>CRM Dashboard</span>
                                    <svg
                                        width="10"
                                        height="11"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-2.5 w-2.5"
                                    >
                                        <path
                                            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                            stroke="currentColor"
                                            stroke-linecap="square"
                                        />
                                    </svg>
                                </a>
                            </span>
                        </li>
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-0 before:bottom-0 before:left-[0.4rem] before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span
                                class="relative bg-white py-1 dark:bg-[#161615]"
                            >
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]"
                                    />
                                </span>
                            </span>
                            <span>
                                Streamline operations through
                                <a
                                    href="/erp"
                                    class="ml-1 inline-flex items-center space-x-1 font-medium text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]"
                                >
                                    <span>ERP Module</span>
                                    <svg
                                        width="10"
                                        height="11"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-2.5 w-2.5"
                                    >
                                        <path
                                            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                            stroke="currentColor"
                                            stroke-linecap="square"
                                        />
                                    </svg>
                                </a>
                            </span>
                        </li>
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-0 before:bottom-0 before:left-[0.4rem] before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span
                                class="relative bg-white py-1 dark:bg-[#161615]"
                            >
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]"
                                    />
                                </span>
                            </span>
                            <span>
                                Empower your team with
                                <a
                                    href="/hr"
                                    class="ml-1 inline-flex items-center space-x-1 font-medium text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]"
                                >
                                    <span>HR Portal</span>
                                    <svg
                                        width="10"
                                        height="11"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-2.5 w-2.5"
                                    >
                                        <path
                                            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                            stroke="currentColor"
                                            stroke-linecap="square"
                                        />
                                    </svg>
                                </a>
                            </span>
                        </li>
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-0 before:bottom-1/2 before:left-[0.4rem] before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span
                                class="relative bg-white py-1 dark:bg-[#161615]"
                            >
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]"
                                    />
                                </span>
                            </span>
                            <span>
                                Develop skills using
                                <a
                                    href="/lms"
                                    class="ml-1 inline-flex items-center space-x-1 font-medium text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]"
                                >
                                    <span>Learning Management</span>
                                    <svg
                                        width="10"
                                        height="11"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-2.5 w-2.5"
                                    >
                                        <path
                                            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                            stroke="currentColor"
                                            stroke-linecap="square"
                                        />
                                    </svg>
                                </a>
                            </span>
                        </li>
                    </ul>
                    <ul class="flex gap-3 text-sm leading-normal">
                        <li>
                            <a
                                href="/dashboard"
                                class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                            >
                                Access Dashboard
                            </a>
                        </li>
                    </ul>
                </div>
                <div
                    class="relative -mb-px aspect-[335/364] w-full shrink-0 overflow-hidden rounded-t-lg bg-[#fff2f2] lg:mb-0 lg:-ml-px lg:aspect-auto lg:w-[438px] lg:rounded-t-none lg:rounded-r-lg dark:bg-[#1D0002]"
                >
                    <!-- Business System Logo -->
                    <svg
                        class="w-full max-w-[420px] text-[#F53003] opacity-100 transition-all duration-750 dark:text-[#F61500] starting:opacity-0 motion-safe:starting:translate-y-6"
                        viewBox="0 0 520 140"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <!-- Centered Group -->
                        <g transform="translate(70,34)">
                            
                            <!-- Logo Icon -->
                            <g transform="translate(0,0)">
                                <rect
                                    x="0"
                                    y="0"
                                    width="72"
                                    height="72"
                                    rx="18"
                                    fill="currentColor"
                                    opacity="0.12"
                                />

                                <rect
                                    x="14"
                                    y="14"
                                    width="18"
                                    height="18"
                                    rx="4"
                                    fill="currentColor"
                                />

                                <rect
                                    x="40"
                                    y="14"
                                    width="18"
                                    height="18"
                                    rx="4"
                                    fill="currentColor"
                                    opacity="0.7"
                                />

                                <rect
                                    x="14"
                                    y="40"
                                    width="18"
                                    height="18"
                                    rx="4"
                                    fill="currentColor"
                                    opacity="0.7"
                                />

                                <rect
                                    x="40"
                                    y="40"
                                    width="18"
                                    height="18"
                                    rx="4"
                                    fill="currentColor"
                                />
                            </g>

                            <!-- Text Content -->
                            <g transform="translate(100,10)">
                                <!-- Brand -->
                                <text
                                    x="0"
                                    y="30"
                                    font-family="Inter, sans-serif"
                                    font-size="40"
                                    font-weight="700"
                                    letter-spacing="-1"
                                    fill="currentColor"
                                >
                                    Business Suite
                                </text>

                                <!-- Tagline -->
                                <text
                                    x="2"
                                    y="58"
                                    font-family="Inter, sans-serif"
                                    font-size="13"
                                    font-weight="500"
                                    letter-spacing="2"
                                    fill="currentColor"
                                    opacity="0.55"
                                >
                                    CRM • ERP • HR • LMS
                                </text>
                            </g>
                        </g>
                    </svg>

                    <!-- Business Graphics: Interconnected Nodes representing integrated systems -->
                    <svg
                        class="relative -mt-[6.6rem] -ml-8 w-[438px] max-w-none [--stroke-color:#1B1B18] lg:ml-0 dark:[--stroke-color:#FF750F]"
                        viewBox="0 0 440 392"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <!-- Layer 1: Base structure -->
                        <g
                            class="text-[#1B1B18] opacity-100 mix-blend-darken transition-all delay-300 duration-750 dark:text-black dark:mix-blend-normal starting:opacity-0"
                        >
                            <!-- Central Hub -->
                            <circle cx="220" cy="250" r="45" fill="currentColor" opacity="0.15"/>
                            <circle cx="220" cy="250" r="45" stroke="var(--stroke-color)" stroke-width="2.4" fill="none"/>
                            
                            <!-- CRM Node (top left) -->
                            <circle cx="120" cy="150" r="35" fill="currentColor" opacity="0.15"/>
                            <circle cx="120" cy="150" r="35" stroke="var(--stroke-color)" stroke-width="2.4" fill="none"/>
                            <line x1="148" y1="172" x2="192" y2="228" stroke="var(--stroke-color)" stroke-width="2.4"/>
                            
                            <!-- ERP Node (top right) -->
                            <circle cx="320" cy="150" r="35" fill="currentColor" opacity="0.15"/>
                            <circle cx="320" cy="150" r="35" stroke="var(--stroke-color)" stroke-width="2.4" fill="none"/>
                            <line x1="292" y1="172" x2="248" y2="228" stroke="var(--stroke-color)" stroke-width="2.4"/>
                            
                            <!-- HR Node (bottom left) -->
                            <circle cx="120" cy="350" r="35" fill="currentColor" opacity="0.15"/>
                            <circle cx="120" cy="350" r="35" stroke="var(--stroke-color)" stroke-width="2.4" fill="none"/>
                            <line x1="148" y1="328" x2="192" y2="272" stroke="var(--stroke-color)" stroke-width="2.4"/>
                            
                            <!-- LMS Node (bottom right) -->
                            <circle cx="320" cy="350" r="35" fill="currentColor" opacity="0.15"/>
                            <circle cx="320" cy="350" r="35" stroke="var(--stroke-color)" stroke-width="2.4" fill="none"/>
                            <line x1="292" y1="328" x2="248" y2="272" stroke="var(--stroke-color)" stroke-width="2.4"/>
                        </g>

                        <!-- Layer 2: Pink accent -->
                        <g
                            class="text-[#F3BEC7] opacity-100 transition-all delay-400 duration-750 dark:text-[#4B0600] starting:opacity-0 motion-safe:starting:-translate-x-[13px]"
                        >
                            <circle cx="233" cy="250" r="45" fill="currentColor" opacity="0.12"/>
                            <circle cx="133" cy="150" r="35" fill="currentColor" opacity="0.12"/>
                            <circle cx="333" cy="150" r="35" fill="currentColor" opacity="0.12"/>
                            <circle cx="133" cy="350" r="35" fill="currentColor" opacity="0.12"/>
                            <circle cx="333" cy="350" r="35" fill="currentColor" opacity="0.12"/>
                        </g>

                        <!-- Layer 3: Yellow accent -->
                        <g
                            class="text-[#F8B803] opacity-100 mix-blend-color transition-all delay-400 duration-750 dark:text-[#391800] dark:mix-blend-hard-light starting:opacity-0 motion-safe:starting:-translate-x-[26px]"
                        >
                            <circle cx="246" cy="250" r="45" fill="currentColor" opacity="0.1"/>
                            <circle cx="146" cy="150" r="35" fill="currentColor" opacity="0.1"/>
                            <circle cx="346" cy="150" r="35" fill="currentColor" opacity="0.1"/>
                            <circle cx="146" cy="350" r="35" fill="currentColor" opacity="0.1"/>
                            <circle cx="346" cy="350" r="35" fill="currentColor" opacity="0.1"/>
                        </g>

                        <!-- Layer 4: Data flow indicators -->
                        <g
                            class="text-[#F3BEC7] opacity-100 mix-blend-multiply transition-all delay-500 duration-750 dark:text-[#733000] dark:mix-blend-normal starting:opacity-0 motion-safe:starting:-translate-x-[39px]"
                        >
                            <!-- Small dots along connection lines -->
                            <circle cx="170" cy="190" r="6" fill="currentColor"/>
                            <circle cx="270" cy="190" r="6" fill="currentColor"/>
                            <circle cx="170" cy="310" r="6" fill="currentColor"/>
                            <circle cx="270" cy="310" r="6" fill="currentColor"/>
                        </g>

                        <!-- Layer 5: Highlight layer -->
                        <g
                            class="text-[#F3BEC7] opacity-100 mix-blend-hard-light transition-all delay-400 duration-750 dark:text-[#4B0600] starting:opacity-0 motion-safe:starting:-translate-x-[51px]"
                        >
                            <circle cx="259" cy="250" r="45" fill="currentColor" opacity="0.08"/>
                            <circle cx="159" cy="150" r="35" fill="currentColor" opacity="0.08"/>
                            <circle cx="359" cy="150" r="35" fill="currentColor" opacity="0.08"/>
                            <circle cx="159" cy="350" r="35" fill="currentColor" opacity="0.08"/>
                            <circle cx="359" cy="350" r="35" fill="currentColor" opacity="0.08"/>
                        </g>
                    </svg>
                    <div
                        class="absolute inset-0 rounded-t-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:rounded-t-none lg:rounded-r-lg dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                    ></div>
                </div>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
