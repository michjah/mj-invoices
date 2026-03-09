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
                        <template v-slot:item.total_price="{ item }">
                            {{ priceFormat(item.total_price) }}
                        </template>

                        <template v-slot:item.status="{ item }">
                            <div>
                                <v-chip
                                    :color="item.status === 'DRAFT' ? 'red' : 'green'"
                                    :text="item.status"
                                    class="text-uppercase"
                                    size="small"
                                    label
                                ></v-chip>
                            </div>
                        </template>

                        <template v-slot:item.invoice_date="{ item }">
                            {{ dateFormat(item.invoice_date) }}
                        </template>

                        <template v-slot:item.due_date="{ item }">
                            {{ dateFormat(item.due_date) }}
                        </template>

                        <template v-slot:item.action="{ item }">
                            <v-tooltip
                                text="Preview PDF"
                                location="bottom"
                            >
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        icon="mdi-eye"
                                        @click.stop="previewInvoice(item.invoice_id)"
                                        v-if="item.status === 'DRAFT' || item.status === 'GENERATED'"
                                    />
                                </template>
                            </v-tooltip>

                            <v-tooltip
                                text="Confirm Invoice"
                                location="bottom"
                            >
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        icon="mdi-check-decagram-outline"
                                        @click.stop="showConfirmInvoiceDialog( item.invoice_id)"
                                        v-if="item.status === 'DRAFT'"
                                    />
                                </template>
                            </v-tooltip>
                        </template>
                    </v-data-table-server>
                </v-col>
            </v-row>
        </v-container>
        <v-dialog v-model="showDialog" max-width="800px">
            <v-card>
                <v-card-title>Preview PDF</v-card-title>
                <v-card-text style="height: 600px; padding: 0;">
                    <iframe
                        v-if="pdfUrl"
                        :src="pdfUrl"
                        width="100%"
                        height="100%"
                        style="border: none;"
                    ></iframe>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="showDialog = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showConfirmInvoice" max-width="800px">
            <v-card>
                <v-card-title>Confirm Invoice</v-card-title>
                <v-card-text>
                    Are you sure you want to confirm this invoice?
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" text @click="showConfirmInvoice = false">Cancel</v-btn>
                    <v-btn color="green darken-1" text @click="generateInvoice(); showConfirmInvoice = false">Confirm</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
            showDialog: false,
            pdfUrl: null,
            showConfirmInvoice: false,
            headers: [
                {
                    title: 'Invoice No.',
                    key: 'invoice_id',
                    sortable: true
                }, {
                    title: 'Invoice Date',
                    key: 'invoice_date',
                    dataType: 'date',
                    sortable: true,
                }, {
                    title: 'Invoice Due Date',
                    key: 'due_date',
                    dataType: 'date',
                    sortable: false,
                }, {
                    title: 'Invoice Price',
                    key: 'total_price',
                    dataType: 'float',
                    sortable: false,
                }, {
                    title: 'Invoice Status',
                    key: 'status',
                    dataType: 'string',
                    sortable: false,
                }, {
                }, {
                    title: 'Actions',
                    key: 'action',
                    sortable: false,
                    align: 'end',
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
            return new Intl.NumberFormat('cs-CZ', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value)
        },
        dateFormat: function (dateString) {
            return new Intl.DateTimeFormat('cs-CZ', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).format(new Date(dateString))
        },
        previewInvoice(invoiceId) {
            this.showDialog = true
            this.pdfUrl = '/api/invoices/id/' + invoiceId + '/preview-pdf'
        },
        showInvoice(invoiceId) {
            window.open('/api/invoices/id/' + invoiceId + '/show-pdf', '_blank')
        },
        confirmInvoice(invoiceId) {
            this.showConfirmInvoice = true
        },
        showConfirmInvoiceDialog(invoiceId) {
            this.showConfirmInvoice = true
            this.confirmInvoiceId = invoiceId
        },
        generateInvoice(invoiceId) {
            axios.post('/api/invoices/id/' + invoiceId + '/confirm-invoice')
                .then((response) => {
                    console.log('confirm')
                })
        },
    }
}
</script>
