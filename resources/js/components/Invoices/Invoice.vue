<template>
    <div style="width:100%">
        <v-breadcrumbs :items="breadcrumbs" />

        <v-spacer />

        <v-container
            fluid
            v-if="!loading"
        >
            <v-row
                dense
            >
                <v-col cols="12">
                    <v-toolbar
                        density="comfortable"
                        color="transparent"
                    >
                        <template v-slot:prepend>
                            <h2 class="pl-2">{{ invoiceTitle }}</h2>
                        </template>

                        <template v-if="$vuetify.display.smAndUp">
                            <v-divider
                                class="mx-3 align-self-center"
                                length="24"
                                thickness="2"
                                vertical
                            ></v-divider>

                            <v-tooltip
                                text="Update"
                                location="bottom"
                            >
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        icon="mdi-pencil"
                                        :href="`/invoices/${invoice.invoice_id}/edit`"
                                        :disabled="invoice.status !== 'DRAFT'"
                                    />
                                </template>
                            </v-tooltip>

                            <v-tooltip
                                text="Preview PDF"
                                location="bottom"
                            >
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        icon="mdi-eye"
                                        :href="`/invoices/${invoice.invoice_id}/preview`"
                                    />
                                </template>
                            </v-tooltip>

                            <v-tooltip
                                text="Export to PDF"
                                location="bottom"
                            >
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        icon="mdi-file-pdf-box"
                                        :href="`/invoices/${invoice.invoice_id}/pdf`"
                                    />
                                </template>
                            </v-tooltip>
                        </template>
                    </v-toolbar>
                </v-col>
            </v-row>

            <v-spacer />

            <v-container
                fluid
                class="ma-0 pa-0"
            >
                <v-row dense>
                    <v-col cols="12">
                        <v-card class="mb-2">
                            <v-card-item>
                                <v-row dense>
                                    <v-col cols="2" dense>
                                        Variable Number
                                    </v-col>
                                    <v-col cols="10" dense>
                                        {{ invoice.invoice_id }}
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="2" dense>
                                        Order Number
                                    </v-col>
                                    <v-col cols="10" dense>
                                        {{ invoice.order_id || '-'}}
                                    </v-col>
                                </v-row>
                            </v-card-item>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row dense>
                    <v-col cols="6" rounded="lg">
                        <v-card class="mb-2" v-if="invoice.supplier">
                            <v-card-item>
                                <v-row dense class="mb-4">
                                    <v-col cols="12" dense>
                                        <h4>Supplier</h4>
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="4" dense>
                                        {{ invoice.supplier.name }}
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4" dense>
                                        {{ invoice.supplier.address_street }}
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4" dense>
                                        {{ invoice.supplier.address_zipcode }} {{ invoice.supplier.address_city }}
                                    </v-col>
                                </v-row>
                            </v-card-item>
                        </v-card>
                    </v-col>
                    <v-col cols="6" rounded="lg">
                        <v-card class="mb-2" v-if="invoice.customer">
                            <v-card-item>
                                <v-row dense class="mb-4">
                                    <v-col cols="12" dense>
                                        <h4>Customer</h4>
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="4" dense>
                                        {{ invoice.customer.name }}
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4" dense>
                                        {{ invoice.customer.address_street }}
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4" dense>
                                        {{ invoice.customer.address_zipcode }} {{ invoice.customer.address_city }}
                                    </v-col>
                                </v-row>
                            </v-card-item>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row dense>
                    <v-col cols="6">
                        <v-card>
                            <v-card-item>
                                <v-row dense>
                                    <v-col cols="4" dense>
                                        Invoice Date
                                    </v-col>
                                    <v-col cols="8" dense>
                                        {{ invoice.invoice_date }}
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4" dense>
                                        Due Date
                                    </v-col>
                                    <v-col cols="8" dense>
                                        {{ invoice.due_date }}
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4" dense>
                                        DUZP Date
                                    </v-col>
                                    <v-col cols="8" dense>
                                        {{ invoice.duzp_date }}
                                    </v-col>
                                </v-row>
                            </v-card-item>
                        </v-card>
                    </v-col>
                    <v-col cols="6">
                        <v-row>
                            <v-col cols="12">
                                <v-card class="mb-2">
                                    <v-card-item>
                                        <v-row dense>
                                            <v-col cols="12" dense>Note</v-col>
                                        </v-row>
                                        <v-row dense>
                                            <v-col cols="12" dense>
                                                <p>{{ invoice.note || '-' }}</p>
                                            </v-col>
                                        </v-row>
                                    </v-card-item>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        Items
                    </v-col>
                </v-row>

                <v-row>
                    <v-data-table
                        :items="invoice.items"
                        :headers="[
                            { title: 'Text', key: 'text', sortable: false },
                            { title: 'Amount', key: 'amount', sortable: false },
                            { title: 'Price', key: 'price', sortable: false },
                            { title: 'TAX', key: 'tax', sortable: false },
                            { title: 'VAT', key: 'vat', sortable: false },
                        ]"
                        hide-default-footer
                        dense
                    >
                    </v-data-table>
                </v-row>
            </v-container>
        </v-container>
        <v-container
            v-else
            fluid
            class="text-center"
        >
            <v-skeleton-loader type="article" text="Loading ..." />
        </v-container>
    </div>
</template>

<script>
import {VDateInput} from "vuetify/labs/VDateInput";
import TableItem from "../Universal/tableItem.vue";

export default {
    name: 'invoice',
    components: {TableItem, VDateInput},
    data: () => {
        return {
            loading: false,
            breadcrumbs: [
                {title: 'Home', disabled: false, href: '/'},
                {title: 'Invoices', disabled: false, href: '/invoices'},
            ],
            invoice_id: null,
            invoiceTitle: null,
            invoice: {
                invoice_id: null,
                invoice_date: null,
                due_date: null,
                items: [],
            }
        }
    },
    mounted() {
        this.invoice_id = this.$route.params.id
        this.breadcrumbs.push({title: 'Invoice ' + this.invoice_id, disabled: true})
        this.loadInvoice()
    },
    methods: {
        loadInvoice() {
            let self = this

            self.loading = true
            setTimeout(() => {
                axios.get('/api/invoices/id/' + this.invoice_id)
                    .then((response) => {
                        if (response.data) {
                            self.invoice = response.data
                            self.breadcrumbs[2].title = self.updateWebTitle(self.invoice.invoice_id, self.invoice.status)
                            self.invoiceTitle = self.updateWebTitle(self.invoice.invoice_id, self.invoice.status)
                            self.loading = false
                        }
                    })
            }, 200)
        },
        updateWebTitle(invoiceId, status) {
            return 'Invoice ' + invoiceId + ' [' + status + ']'
        }
    }
}
</script>

<style>
#actions {
    display: flex;
    justify-content: flex-end;
}

#actions > a {
    margin-left: 10px;
}
</style>
