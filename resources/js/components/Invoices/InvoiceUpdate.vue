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
                    </v-toolbar>
                </v-col>
            </v-row>

            <v-spacer />

            <v-container
                fluid
                class="ma-0 pa-0"
            >
                <v-form id="invoice-form">
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
                                            <v-text-field
                                                density="compact"
                                                v-model="invoice.order_id"
                                                label="Order Number"
                                                width="200px"
                                            />
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
                                        <v-col cols="8" dense>
                                            <v-select
                                                v-model="invoice.supplier_id"
                                                :items="contacts"
                                                item-title="name"
                                                item-value="id"
                                                label="Search contact"
                                                hide-no-data
                                                clearable
                                                @update:modelValue="onSupplierChange"
                                            />
                                        </v-col>
                                    </v-row>

                                    <div v-if="selectedSupplierData">
                                        <v-row dense>
                                            <v-col cols="8" dense>
                                                {{ selectedSupplierData.name }}
                                            </v-col>
                                        </v-row>
                                        <v-row dense>
                                            <v-col cols="8" dense>
                                                {{ selectedSupplierData.address_street }}
                                            </v-col>
                                        </v-row>
                                        <v-row dense>
                                            <v-col cols="8" dense>
                                                {{ selectedSupplierData.address_zipcode }} {{ selectedSupplierData.address_city }}
                                            </v-col>
                                        </v-row>
                                    </div>
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
                                        <v-col cols="8" dense>
                                            <v-select
                                                v-model="invoice.customer_id"
                                                :items="contacts"
                                                item-title="name"
                                                item-value="id"
                                                label="Search contact"
                                                hide-no-data
                                                clearable
                                                @update:modelValue="onCustomerChange"
                                            />
                                        </v-col>
                                    </v-row>

                                    <div v-if="selectedCustomerData">
                                        <v-row dense>
                                            <v-col cols="8" dense>
                                                {{ selectedCustomerData.name }}
                                            </v-col>
                                        </v-row>
                                        <v-row dense>
                                            <v-col cols="8" dense>
                                                {{ selectedCustomerData.address_street }}
                                            </v-col>
                                        </v-row>
                                        <v-row dense>
                                            <v-col cols="8" dense>
                                                {{ selectedCustomerData.address_zipcode }} {{ selectedCustomerData.address_city }}
                                            </v-col>
                                        </v-row>
                                    </div>
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
                                            <v-date-input
                                                density="compact"
                                                v-model="invoice.invoice_date"
                                                label="Invoice Date"
                                                input-format="yyyy-MM-dd"
                                            />
                                        </v-col>
                                    </v-row>
                                    <v-row dense>
                                        <v-col cols="4" dense>
                                            Due Date
                                        </v-col>
                                        <v-col cols="8" dense>
                                            <v-date-input
                                                density="compact"
                                                v-model="invoice.due_date"
                                                label="Due Date"
                                                input-format="yyyy-MM-dd"
                                            />
                                        </v-col>
                                    </v-row>
                                    <v-row dense>
                                        <v-col cols="4" dense>
                                            DUZP Date
                                        </v-col>
                                        <v-col cols="8" dense>
                                            <v-date-input
                                                density="compact"
                                                v-model="invoice.duzp_date"
                                                label="DUZP Date"
                                                input-format="yyyy-MM-dd"
                                            />
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
                                                    <v-textarea
                                                        density="compact"
                                                        v-model="invoice.note"
                                                    />
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

                    <v-row dense>
                        <v-col cols="12">
                            <table-item
                                :items="invoice.items"
                                :default-currency="defaultCurrency"
                                :default-vat="defaultVat"
                                :currencies="currencies"
                                :taxes="taxes"
                                :compute-totals="computeTotals"
                                :update="true"
                            />
                        </v-col>
                    </v-row>
                </v-form>
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
import axios from "axios";

export default {
    name: 'invoiceUpdate',
    components: {TableItem, VDateInput},
    data: () => {
        return {
            loading: false,
            breadcrumbs: [
                {title: 'Home', disabled: false, href: '/'},
                {title: 'Invoices', disabled: false, href: '/invoices'},
            ],
            currencies: [],
            defaultCurrency: null,
            defaultVat: null,
            taxes: [],
            invoice_id: null,
            invoiceTitle: null,
            invoice: {
                invoice_id: null,
                invoice_date: null,
                due_date: null,
                items: [],
            },
            contacts: [],
            fullContacts: [],
            selectedCustomerData: {
                id: null,
                name: null,
                address_street: null,
                address_zipcode: null,
                address_city: null,
                address_country: null,
                phone: null,
                email: null,
                website: null,
                tax_number: null,
                bank_account: null,
                bank_name: null,
            },
            selectedSupplierData: {
                id: null,
                name: null,
                address_street: null,
                address_zipcode: null,
                address_city: null,
                address_country: null,
                phone: null,
                email: null,
                website: null,
                tax_number: null,
                bank_account: null,
                bank_name: null,
            },
        }
    },
    mounted() {
        let self = this
        this.invoice_id = this.$route.params.id
        this.breadcrumbs.push({ title: 'Invoice ' + this.invoice_id, disabled: false, href: '/invoices/' + this.invoice_id })
        this.breadcrumbs.push({ title: 'Update', disabled: true })
        this.loadInvoice()
        this.searchContact()

        axios.get('/api/collect').then((response) => {
            self.defaultCurrency = response.data.defaultCurrency
            self.currencies = response.data.currencies
            self.taxes = response.data.taxes
        })

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
                            self.onSupplierChange(self.invoice.supplier_id)
                            self.onCustomerChange(self.invoice.customer_id)
                            self.loading = false
                        }
                    })
            }, 200)
        },
        updateWebTitle(invoiceId, status) {
            return 'Invoice ' + invoiceId
        },
        searchContact() {
            let self = this
            axios.get('/api/contacts')
                .then((response) => {
                    response.data.data.forEach(contact => {
                        let name = contact.name + ' (' + contact.ic + ')'
                        self.contacts.push({id: contact.id, name: name})
                        self.fullContacts.push(contact)
                    })
                })
        },
        onSupplierChange(supplierId) {
            this.fullContacts.forEach(contact => {
                if (contact.id === supplierId) {
                    this.selectedSupplierData = contact
                }
            })
        },
        onCustomerChange(customerId) {
            this.fullContacts.forEach(contact => {
                if (contact.id === customerId) {
                    this.selectedCustomerData = contact
                }
            })
        },
        computeTotals() {
            this.useVat(this.invoice.items)
        },
        toCents(value) {
            return Math.round(value * 100)
        },
        fromCents(value) {
            return value / 100
        },
        useVat(items) {
            let totalNet = 0
            let totalVat = 0
            let totalGross = 0

            items.forEach(item => {
                const priceCents = this.toCents(item.price)
                const qty = item.amount
                const rate = item.vat

                let net = 0
                let vat = 0
                let gross = 0

                if (this.includedVat) {
                    console.log('with VAT')
                    // with VAT
                    gross = priceCents * qty
                    net = Math.round(gross / (1 + rate / 100))
                    vat = gross - net
                } else {
                    console.log('without VAT')
                    // without VAT
                    net = priceCents * qty
                    vat = Math.round(net * rate / 100)
                    gross = net + vat
                }

                totalNet += net
                totalVat += vat
                totalGross += gross
            })

            this.invoice.total_price = totalNet
            this.invoice.total_price_vat = totalVat
            this.invoice.total_price_without_vat = totalGross

            return {
                totalNet: this.fromCents(totalNet),
                totalVat: this.fromCents(totalVat),
                totalGross: this.fromCents(totalGross)
            }
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
