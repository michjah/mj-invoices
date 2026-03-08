<template>
    <div>
        <v-breadcrumbs :items="breadcrumbs"></v-breadcrumbs>
        <h2>This is the Home Page</h2>
        <v-btn>asdfjx</v-btn>
        <router-link :to="{name: 'Invoices'}">Link</router-link>

        <v-container fluid fill-height>
            <v-card width="100vw">
                <MjTable
                    url="/invoices"
                    :headers="headers"
                ></MjTable>
            </v-card>
        </v-container>
    </div>
</template>

<script>
import axios from "axios";
import MjTable from "../Universal/table.vue"

export default {
    name: 'Home',
    components: {MjTable},
    data: () => ({
        invoices: [],
        invoicesLength: 0,
        headers: [
            {
                title: 'Id',
                key: 'id'
            }, {
                title: 'Name',
                key: 'name'
            }
        ],
        itemsPerPage: 10,
        breadcrumbs: [
            {title: 'Home', disabled: true, href: '/'},
        ]
    }),
    created() {
    },
    computed: {
    },
    methods: {
        getInvoices() {
            console.log('Fetching invoices')
            const self = this

/*
            let request = axios.get('/invoices')

            request.then(response => {
                self.invoices = response.data
                self.invoicesLength = response.data.length
            })
*/
        }
    }
}
</script>

