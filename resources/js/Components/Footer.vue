
<script setup>
import { Link } from '@inertiajs/vue3'

</script>

<template>
    <div class="bg-dark q-mt-xl " style="color: white;">
        <div class="q-pa-md row justify-between items-center footer">
            <!-- Left Section: Company Info -->
            <div class="col-auto">
                <div class="flex items-center">
                    <q-avatar>
                        <q-img
                            src="logo.png"
                            alt="Logo"
                            contain
                            class="q-mb-sm"
                        />
                    </q-avatar>
                    <div class="text-h6 q-ml-md">Taytayan Camp Hub</div>
                </div>
                <div class="q-my-sm">
                    <div>RJC San Vicente Olango Island</div>
                    <div>09267495567</div>
                    <div>taytayancamp@email.com</div>
                </div>
                
            </div>

            <!-- Right Section: Links -->
            <div class="col-auto">
                <div class="q-mt-md">
                    <Link :href="route('home')">Home</Link>
                    <Link :href="route('home')" class="q-mx-lg">About Us</Link>
                    <Link :href="route('home')">Contact Us</Link>
                </div>
                <div>
                    <div class="text-weight-bold text-subtitle1">Follow Us</div>
                    <q-btn round>
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 16 16" id="facebook">
                            <path fill="#1976D2"
                                d="M14 0H2C.897 0 0 .897 0 2v12c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2V2c0-1.103-.897-2-2-2z">
                            </path>
                            <path fill="#FAFAFA" fill-rule="evenodd"
                                d="M13.5 8H11V6c0-.552.448-.5 1-.5h1V3h-2a3 3 0 0 0-3 3v2H6v2.5h2V16h3v-5.5h1.5l1-2.5z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </q-btn>
                    <q-btn round class="q-mx-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28.87 28.87" id="instagram">
                            <defs>
                                <linearGradient id="linear-gradient" x1="-1.84" x2="32.16" y1="30.47" y2="-3.03"
                                    gradientUnits="userSpaceOnUse">
                                    <stop offset="0" stop-color="#fed576"></stop>
                                    <stop offset=".26" stop-color="#f47133"></stop>
                                    <stop offset=".61" stop-color="#bc3081"></stop>
                                    <stop offset="1" stop-color="#4c63d2"></stop>
                                </linearGradient>
                            </defs>
                            <g id="Layer_2">
                                <g id="Layer_1-2">
                                    <rect width="28.87" height="28.87" rx="6.48" ry="6.48"
                                        style="fill:url(#linear-gradient)"></rect>
                                    <g id="_Group_">
                                        <path id="_Compound_Path_"
                                            d="M10 5h9c.2.1.5.1.7.2a4.78 4.78 0 0 1 3.8 3.3 8 8 0 0 1 .3 1.5v8.8a6.94 6.94 0 0 1-1.2 3.1 5.51 5.51 0 0 1-4.5 1.9h-7.5a5.49 5.49 0 0 1-3.7-1.2A5.51 5.51 0 0 1 5 18.14v-7a7.57 7.57 0 0 1 .1-1.5 4.9 4.9 0 0 1 3.8-4.3zm-3.1 9.5v3.9a3.42 3.42 0 0 0 3.7 3.7q3.9.15 7.8 0c2.3 0 3.6-1.4 3.7-3.7q.15-3.9 0-7.8a3.52 3.52 0 0 0-3.7-3.7q-3.9-.15-7.8 0a3.42 3.42 0 0 0-3.7 3.7z"
                                            style="fill:#fff"></path>
                                        <path id="_Compound_Path_2"
                                            d="M9.64 14.54a4.91 4.91 0 0 1 4.9-4.9 5 5 0 0 1 4.9 4.9 4.91 4.91 0 0 1-4.9 4.9 5 5 0 0 1-4.9-4.9zm4.9-3.1a3.05 3.05 0 1 0 3 3 3 3 0 0 0-3-3z"
                                            style="fill:#fff"></path>
                                        <path id="_Path_"
                                            d="M18.34 9.44a1.16 1.16 0 0 1 1.2-1.2 1.29 1.29 0 0 1 1.2 1.2 1.2 1.2 0 0 1-2.4 0z"
                                            style="fill:#fff"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </q-btn>
                    <q-btn round>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" id="twitter">
                                <path fill="#050505" fill-rule="evenodd"
                                    d="m60,12c0-4.42-3.58-8-8-8H12C7.58,4,4,7.58,4,12v40c0,4.42,3.58,8,8,8h40c4.42,0,8-3.58,8-8V12h0Z">
                                </path>
                                <path fill="#fff"
                                    d="m15.07,48.28h4l10.68-12.14,9.29,12.12h10.86l-14.25-18.84,12.06-13.7h-4l-9.91,11.26-8.5-11.25h-11.21l13.79,17.99-12.82,14.57Zm5.11-29.56h3.64l20.06,26.54h-3.35l-20.34-26.54Z">
                                </path>
                            </svg>
                    </q-btn>
                </div>
            </div>
        </div>
        <div class="text-body2 text-center q-pb-md">© {{ new Date().getFullYear() }} Taytayan Camp. All rights reserved.</div>
    </div>
</template>

<style scoped>
.q-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    /* Optional: Adds subtle border at the top of the footer */
}

a {
    text-decoration: none;
    color: white;
}

.footer {
    max-width: 1280px;
    margin: auto;
    z-index: 0;
}
</style>
