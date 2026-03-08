<template>
    <div style="width:100%">
        <v-breadcrumbs :items="breadcrumbs"></v-breadcrumbs>

        <v-spacer />

        <v-row
            class="ml-4 mr-0"
        >
            <v-col cols="10">
                <h2>Invoices</h2>
            </v-col>
            <v-col cols="2" class="pr-4 text-right pt-4">
                <v-btn
                    prepend-icon="mdi-plus"
                    to="/invoices/new"
                >
                    New
                </v-btn>
            </v-col>
        </v-row>

        <v-container width="100vw" class="ma-2 ml-0" fluid fill-height>
            <v-row>
                <v-col cols="12">
                    <v-data-table-server
                        :headers="headers"
                        :items="serverItems"
                        :items-length="totalItems"
                        :hover="true"
                        class="elevation-1"
                        dense
                        :hide-default-footer="true"
                        @update:options="loadItems"
                        @click:row="onRowClick"
                    >
                        <template v-slot:item.total="{ item }">
                            {{ priceFormat(item.total) }}
                        </template>
                    </v-data-table-server>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'Invoices',
    data: () => {
        return {
            breadcrumbs: [
                {title: 'Home', disabled: false, href: '/'},
                {title: 'Invoices', disabled: true},
            ],
            serverItems: [],
            totalItems: 0,
            countPages: 1,
            loading: false,
            sortBy: [{ key: 'invoice_id', order: 'asc' }],
            p: 1,
            headers: [
                {
                    title: 'Invoice No.',
                    key: 'invoice_id',
                    sortable: true
                }, {
                    title: 'Invoice Date',
                    key: 'invoice_date',
                    sortable: true
                }, {
                    title: 'Invoice Due Date',
                    key: 'due_date',
                    sortable: false
                }, {
                    title: 'Invoice Price',
                    key: 'total',
                    sortable: false
                }
            ]
        }
    },
    methods: {
        loadItems (options) {
            let self = this
            self.loading = true

            const opt = {
                'page': options.page,
                'sortBy': options.sortBy ? options.sortBy[0] : self.sortBy,
                'sortDesc': options.sortDesc ? options.sortDesc[0] : self.sortDesc
            }

            axios.get('/api/invoices', {params: opt})
                .then((response) => {
                    self.serverItems = response.data.data
                    self.totalItems = response.data.total

                    self.countPages = self.totalItems / self.itemsPerPage
                    self.loading = false
                })
        },
        onRowClick (e, row) {
            this.$router.push('/invoices/' + row.item.invoice_id)
        },
        priceFormat: function (value) {
            return new Intl.NumberFormat().format(value)
        }
    }
}
</script>
