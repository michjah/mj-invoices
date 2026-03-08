<script setup>
import { ref } from 'vue';

const rail = ref(false);

</script>
<template>
    <v-app>
        <v-main
            density="compact"
            class="fill-height"
        >
            <v-app-bar
                title="MJ Invoice app"
            >
                <template v-slot:prepend>
                    <v-app-bar-nav-icon
                        @click="rail = !rail"
                    ></v-app-bar-nav-icon>
                </template>
            </v-app-bar>

            <div class="body">
                <v-navigation-drawer
                    dense
                    permanent
                    :rail="rail"
                >
                    <v-list>
                        <v-list-item
                            title="Dashboard"
                            :to="{name: 'Home'}"
                        ></v-list-item>
                        <v-divider></v-divider>
                        <v-list-item
                            title="Contacts"
                            :to="{name: 'Contacts'}"
                            prepend-icon="mdi-account-multiple"
                        ></v-list-item>
                        <v-list-item
                            title="Invoices"
                            :to="{name: 'Invoices'}"
                            prepend-icon="mdi-folder"
                        ></v-list-item>
                    </v-list>
                </v-navigation-drawer>
                <v-layout class="ma-4 ml-0">
                    <router-view />
                </v-layout>
            </div>
        </v-main>
    </v-app>
</template>

<style scoped>
html {
    background-color: black;
}
</style>
