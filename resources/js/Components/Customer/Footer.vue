<script setup>


</script>

<template>
    <q-footer :class="$q.dark.isActive ? 'bg-black text-white' : 'bg-white text-black'">
        <div class="row q-mt-md q-mx-xl">
            <div class="col-6">
                <q-avatar class="q-mr-md">
                    <img :src="`https://cdn.quasar.dev/logo-v2/svg/logo-mono-${$q.dark.isActive ? 'white' : 'black'}.svg`">
                </q-avatar>
                <span class="text-h6">Taytayan Camp Hub</span>

                <p class="q-mt-md">Unit 3308, High St South Corp Plaza Tower 2 <br>
                26th St and 11th Ave, Bonifacio Global City <br>
                Taguig 1634 Philippines</p>
       
                
            </div>
            <div class="col-6">
                <p>Find us online</p>
                <q-btn-group flat>
                    <q-btn icon="facebook"/>
                    <q-btn icon="facebook"/>
                    <q-btn icon="facebook"/>
                    <q-btn icon="facebook"/>
                </q-btn-group>
            </div>
        </div>
        <p class="text-center">© 2024 Taytayan Camp Hub. All rights reserved.</p>
    </q-footer>
</template>